<?php

namespace App\Http\Controllers;

use App\Actions\UploadMaterialAction;
use App\Enums\ScheduleStatus;
use App\Enums\UserRole;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Requests\UploadMaterialRequest;
use App\Http\Requests\WithdrawalRequest;
use App\Models\Material;
use App\Models\Schedule;
use App\Services\ChatService;
use App\Services\MentorFinanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MentorController extends Controller
{
    public function __construct(
        private MentorFinanceService $financeService,
        private ChatService $chatService,
        private UploadMaterialAction $uploadMaterialAction,
    ) {}

    public function dashboard(): View
    {
        $mentor = auth()->user()->mentor;
        $mentor->load('schedules', 'transactions.student', 'transactions.schedule', 'withdrawals');

        $totalEarnings = $this->financeService->getTotalEarnings($mentor);
        $activeStudents = $this->financeService->getActiveStudentsCount($mentor);
        $totalSessions = $this->financeService->getTotalSessions($mentor);
        $upcomingSchedules = $mentor->schedules()
            ->booked()
            ->where('waktu_mulai', '>', now())
            ->with('transaction.student')
            ->get();
        $recentBookings = $mentor->transactions()
            ->with('student', 'schedule')
            ->latest()
            ->take(5)
            ->get();

        $ratingData = $mentor->transactions()
            ->success()
            ->whereHas('review')
            ->with('review')
            ->get()
            ->groupBy(fn ($t) => $t->created_at->format('M Y'));

        return view('mentor.dashboard', compact(
            'mentor', 'totalEarnings', 'activeStudents',
            'totalSessions', 'upcomingSchedules', 'recentBookings', 'ratingData',
        ));
    }

    public function chat(): View
    {
        $mentor = auth()->user()->mentor;
        $students = $this->chatService->getConversations(auth()->user(), UserRole::Student);

        return view('mentor.chat', compact('mentor', 'students'));
    }

    public function exportPdf(): Response
    {
        $mentor = auth()->user()->mentor;
        $transactions = $mentor->transactions()
            ->with('student', 'schedule')
            ->success()
            ->latest()
            ->get();

        $pdf = Pdf::loadView('mentor.report-pdf', compact('transactions', 'mentor'));

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-pendapatan.pdf"',
        ]);
    }

    public function schedules(): View
    {
        $mentor = auth()->user()->mentor;
        $allSchedules = $mentor->schedules()->with('transaction.student')->latest()->get();
        $upcomingSchedules = $allSchedules->where('status', ScheduleStatus::Booked->value)->where('waktu_mulai', '>', now());

        return view('mentor.schedules', compact('mentor', 'allSchedules', 'upcomingSchedules'));
    }

    public function withdrawals(): View
    {
        $mentor = auth()->user()->mentor;
        $totalEarnings = $this->financeService->getTotalEarnings($mentor);
        $saldo = $this->financeService->getBalance($mentor);
        $withdrawals = $mentor->withdrawals()->latest()->get();

        return view('mentor.withdrawals', compact('mentor', 'totalEarnings', 'saldo', 'withdrawals'));
    }

    public function updateSchedule(UpdateScheduleRequest $request): RedirectResponse
    {
        $mentor = auth()->user()->mentor;

        if (! $mentor) {
            return back()->with('error', 'Data mentor tidak ditemukan.');
        }

        try {
            Schedule::create([
                'mentor_id' => $mentor->id,
                'waktu_mulai' => $request->validated('waktu_mulai'),
                'waktu_selesai' => $request->validated('waktu_selesai'),
                'status' => ScheduleStatus::Available->value,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan jadwal: '.$e->getMessage());
        }

        return back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function toggleException(Request $request, Schedule $schedule): RedirectResponse
    {
        if ($schedule->mentor_id !== auth()->user()->mentor->id) {
            abort(403);
        }

        $schedule->update([
            'status' => $schedule->status === ScheduleStatus::Available->value
                ? ScheduleStatus::Booked->value
                : ScheduleStatus::Available->value,
        ]);

        return back()->with('success', 'Status jadwal diubah.');
    }

    public function uploadMaterial(UploadMaterialRequest $request): RedirectResponse
    {
        $mentor = auth()->user()->mentor;

        $this->uploadMaterialAction->execute(
            $mentor,
            $request->validated(),
            $request->file('file'),
        );

        return back()->with('success', 'Materi berhasil diunggah!');
    }

    public function materials(): View
    {
        $mentor = auth()->user()->mentor;
        $materials = $mentor->materials()->with('transaction.student')->latest()->get();
        $transactions = $mentor->transactions()
            ->success()
            ->with('student')
            ->get();

        return view('mentor.materials', compact('materials', 'transactions'));
    }

    public function downloadMaterial(Material $material): BinaryFileResponse|RedirectResponse
    {
        Gate::authorize('download', $material);

        if (! Storage::disk('public')->exists($material->file_path)) {
            abort(404);
        }

        $filename = $material->judul.'.'.$material->tipe;

        return Storage::disk('public')->download($material->file_path, $filename);
    }

    public function updateMaterial(Request $request, Material $material): RedirectResponse
    {
        Gate::authorize('update', $material);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        $data = ['judul' => $validated['judul']];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($material->file_path);
            $path = $request->file('file')->store('materials', 'public');
            $data['file_path'] = $path;
            $data['tipe'] = $request->file('file')->getClientOriginalExtension();
        }

        $material->update($data);

        return back()->with('success', 'Materi berhasil diperbarui!');
    }

    public function deleteMaterial(Material $material): RedirectResponse
    {
        Gate::authorize('delete', $material);

        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return back()->with('success', 'Materi berhasil dihapus!');
    }

    public function requestWithdrawal(WithdrawalRequest $request): RedirectResponse
    {
        $mentor = auth()->user()->mentor;
        $saldo = $this->financeService->getBalance($mentor);

        if ($request->validated('jumlah') > $saldo) {
            return back()->with(
                'error',
                'Saldo tidak mencukupi. Saldo Anda: Rp '.number_format($saldo, 0, ',', '.'),
            );
        }

        $mentor->withdrawals()->create($request->validated());

        return back()->with('success', 'Pengajuan penarikan dana berhasil dikirim!');
    }
}
