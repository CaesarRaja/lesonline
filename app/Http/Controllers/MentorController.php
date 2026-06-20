<?php

namespace App\Http\Controllers;

use App\Models\CourseBundle;
use App\Models\Material;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    public function dashboard()
    {
        $mentor = auth()->user()->mentor;
        $mentor->load('schedules', 'transactions.student', 'transactions.schedule', 'bundles', 'withdrawals');

        $totalEarnings = $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->sum('total_harga');
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

    public function exportPdf()
    {
        $mentor = auth()->user()->mentor;
        $transactions = $mentor->transactions()
            ->with('student', 'schedule')
            ->where('status_pembayaran', 'success')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('mentor.report-pdf', compact('transactions', 'mentor'));
        return $pdf->download('laporan-pendapatan.pdf');
    }

    public function updateSchedule(Request $request)
    {
        $validated = $request->validate([
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
        ]);

        $mentor = auth()->user()->mentor;
        Schedule::create([
            'mentor_id' => $mentor->id,
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'status' => 'available',
        ]);

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

    // Course Bundles
    public function storeBundle(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah_sesi' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        auth()->user()->mentor->bundles()->create($validated);
        return back()->with('success', 'Paket belajar berhasil dibuat!');
    }

    public function deleteBundle(CourseBundle $bundle)
    {
        if ($bundle->mentor_id !== auth()->user()->mentor->id) abort(403);
        $bundle->delete();
        return back()->with('success', 'Paket belajar dihapus.');
    }

    // Material Upload
    public function uploadMaterial(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'transaction_id' => 'nullable|exists:transactions,id',
        ]);

        $path = $request->file('file')->store('public/materials');
        $mentor = auth()->user()->mentor;

        Material::create([
            'mentor_id' => $mentor->id,
            'transaction_id' => $validated['transaction_id'] ?? null,
            'judul' => $validated['judul'],
            'file_path' => $path,
            'tipe' => $request->file('file')->extension(),
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
            ->sum('total_harga');
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
