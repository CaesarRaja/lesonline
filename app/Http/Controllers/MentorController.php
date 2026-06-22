<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Message;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{
    public function dashboard()
    {
        $mentor = auth()->user()->mentor;
        $mentor->load('schedules', 'transactions.student', 'transactions.schedule', 'withdrawals');

        $totalEarnings = $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->sum(DB::raw('COALESCE(jumlah_dibayar, total_harga)'));
        $activeStudents = $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->distinct('student_id')
            ->count('student_id');
        $totalSessions = $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->count();
        $upcomingSchedules = $mentor->schedules()
            ->where('status', 'booked')
            ->where('waktu_mulai', '>', now())
            ->with('transaction.student')
            ->get();
        $recentBookings = $mentor->transactions()
            ->with('student', 'schedule')
            ->latest()
            ->take(5)
            ->get();

        // Analytics data
        $ratingData = $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->whereHas('review')
            ->with('review')
            ->get()
            ->groupBy(fn($t) => $t->created_at->format('M Y'));

        return view('mentor.dashboard', compact(
            'mentor', 'totalEarnings', 'activeStudents',
            'totalSessions', 'upcomingSchedules', 'recentBookings', 'ratingData'
        ));
    }

    public function chat()
    {
        $mentor = auth()->user()->mentor;

        $students = User::whereIn('id', function ($q) use ($mentor) {
            $q->select('sender_id')->from('messages')
                ->where('receiver_id', auth()->id())
                ->union(
                    DB::table('messages')->select('receiver_id')
                        ->where('sender_id', auth()->id())
                );
        })
        ->where('role', 'student')
        ->select('id', 'name')
        ->get()
        ->map(function ($student) {
            $lastMsg = Message::where(function ($q) use ($student) {
                $q->where('sender_id', auth()->id())->where('receiver_id', $student->id);
            })->orWhere(function ($q) use ($student) {
                $q->where('sender_id', $student->id)->where('receiver_id', auth()->id());
            })->latest()->first();

            $unread = Message::where('sender_id', $student->id)
                ->where('receiver_id', auth()->id())
                ->where('dibaca', false)
                ->count();

            return (object) [
                'id' => $student->id,
                'name' => $student->name,
                'last_message' => $lastMsg?->isi,
                'last_time' => $lastMsg?->created_at,
                'unread' => $unread,
            ];
        })
        ->sortByDesc(fn($s) => $s->last_time)
        ->values();

        return view('mentor.chat', compact('mentor', 'students'));
    }

    public function exportPdf()
    {
        $mentor = auth()->user()->mentor;
        $transactions = $mentor->transactions()
            ->with('student', 'schedule')
            ->where('status_pembayaran', 'success')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('mentor.report-pdf', compact('transactions', 'mentor'));
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-pendapatan.pdf"',
        ]);
    }

    public function schedules()
    {
        $mentor = auth()->user()->mentor;
        $allSchedules = $mentor->schedules()->with('transaction.student')->latest()->get();
        $upcomingSchedules = $allSchedules->where('status', 'booked')->where('waktu_mulai', '>', now());
        return view('mentor.schedules', compact('mentor', 'allSchedules', 'upcomingSchedules'));
    }

    public function withdrawals()
    {
        $mentor = auth()->user()->mentor;
        $totalEarnings = $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->sum(DB::raw('COALESCE(jumlah_dibayar, total_harga)'));
        $totalWithdrawn = $mentor->withdrawals()
            ->where('status', 'approved')
            ->sum('jumlah');
        $saldo = $totalEarnings - $totalWithdrawn;
        $withdrawals = $mentor->withdrawals()->latest()->get();
        return view('mentor.withdrawals', compact('mentor', 'totalEarnings', 'saldo', 'withdrawals'));
    }

    public function updateSchedule(Request $request)
    {
        $validated = $request->validate([
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
        ]);

        $mentor = auth()->user()->mentor;

        if (!$mentor) {
            return back()->with('error', 'Data mentor tidak ditemukan.');
        }

        try {
            Schedule::create([
                'mentor_id' => $mentor->id,
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'status' => 'available',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage());
        }

        return back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function toggleException(Request $request, Schedule $schedule)
    {
        if ($schedule->mentor_id !== auth()->user()->mentor->id) {
            abort(403);
        }
        $schedule->update(['status' => $schedule->status === 'available' ? 'booked' : 'available']);
        return back()->with('success', 'Status jadwal diubah.');
    }

    // Material Upload
    public function uploadMaterial(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'transaction_id' => 'nullable|exists:transactions,id',
        ]);

        $path = $request->file('file')->store('materials', 'public');
        $mentor = auth()->user()->mentor;

        Material::create([
            'mentor_id' => $mentor->id,
            'transaction_id' => $validated['transaction_id'] ?? null,
            'judul' => $validated['judul'],
            'file_path' => $path,
            'tipe' => $request->file('file')->getClientOriginalExtension(),
        ]);

        return back()->with('success', 'Materi berhasil diunggah!');
    }

    public function materials()
    {
        $mentor = auth()->user()->mentor;
        $materials = $mentor->materials()->with('transaction.student')->latest()->get();
        $transactions = $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->with('student')
            ->get();
        return view('mentor.materials', compact('materials', 'transactions'));
    }

    public function downloadMaterial(Material $material)
    {
        if ($material->mentor_id !== auth()->user()->mentor->id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($material->file_path)) {
            abort(404);
        }

        $filename = $material->judul . '.' . $material->tipe;
        return Storage::disk('public')->download($material->file_path, $filename);
    }

    public function updateMaterial(Request $request, Material $material)
    {
        if ($material->mentor_id !== auth()->user()->mentor->id) {
            abort(403);
        }

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

    public function deleteMaterial(Material $material)
    {
        if ($material->mentor_id !== auth()->user()->mentor->id) {
            abort(403);
        }

        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return back()->with('success', 'Materi berhasil dihapus!');
    }

    // Withdrawal
    public function requestWithdrawal(Request $request)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:50000',
            'bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:255',
        ]);

        $mentor = auth()->user()->mentor;
        $totalEarnings = $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->sum(DB::raw('COALESCE(jumlah_dibayar, total_harga)'));
        $totalWithdrawn = $mentor->withdrawals()
            ->where('status', 'approved')
            ->sum('jumlah');
        $saldo = $totalEarnings - $totalWithdrawn;

        if ($validated['jumlah'] > $saldo) {
            return back()->with('error', 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($saldo, 0, ',', '.'));
        }

        $mentor->withdrawals()->create($validated);
        return back()->with('success', 'Pengajuan penarikan dana berhasil dikirim!');
    }
}
