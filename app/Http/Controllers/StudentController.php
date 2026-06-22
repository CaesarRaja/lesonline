<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Dispute;
use App\Models\Material;
use App\Models\Mentor;
use App\Models\MentorFavorite;
use App\Models\Schedule;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;

class StudentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function dashboard()
    {
        $user = auth()->user();
        $transactions = Transaction::with('mentor.user', 'schedule')
            ->where('student_id', $user->id)
            ->latest()
            ->get();
        $totalJam = $transactions->where('status_pembayaran', 'success')->count() * 1;
        $kelasMendatang = $transactions->where('status_pembayaran', 'success')
            ->filter(fn($t) => $t->schedule && $t->schedule->waktu_mulai > now());
        $favorites = MentorFavorite::with('mentor.user')
            ->where('student_id', $user->id)
            ->get();
        return view('student.dashboard', compact('transactions', 'totalJam', 'kelasMendatang', 'favorites'));
    }

    public function bookSchedule(Schedule $schedule)
    {
        if ($schedule->status !== 'available') {
            return back()->with('error', 'Jadwal sudah dipesan.');
        }

        $mentor = $schedule->mentor;
        $user = auth()->user();

        $transaction = Transaction::create([
            'student_id' => $user->id,
            'mentor_id' => $mentor->id,
            'schedule_id' => $schedule->id,
            'total_harga' => $mentor->tarif_per_jam,
            'status_pembayaran' => 'pending',
        ]);

        $orderId = 'BIMBELEDU-' . $transaction->id . '-' . time();
        $transaction->update(['midtrans_order_id' => $orderId]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $mentor->tarif_per_jam,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $mentor->id,
                    'price' => (int) $mentor->tarif_per_jam,
                    'quantity' => 1,
                    'name' => 'Sesi Belajar dengan ' . $mentor->user->name,
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('student.payment', compact('snapToken', 'transaction'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function paymentSuccess(Request $request)
    {
        $transaction = Transaction::where('midtrans_order_id', $request->order_id)->first();
        if ($transaction) {
            $fee = \App\Models\PlatformFee::getActive();
            $jumlahDibayar = $transaction->total_harga;
            $potongan = $fee ? $fee->calculate($jumlahDibayar) : 0;
            $transaction->update([
                'status_pembayaran' => 'success',
                'jumlah_dibayar' => $jumlahDibayar - $potongan,
                'midtrans_transaction_id' => $request->transaction_id,
                'midtrans_response' => $request->all(),
            ]);
            $transaction->schedule->update(['status' => 'booked']);
        }
        return redirect()->route('student.dashboard')->with('success', 'Pembayaran berhasil!');
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50',
            'transaction_id' => 'required|exists:transactions,id',
        ]);

        $coupon = Coupon::where('kode', $validated['kode'])->first();
        $transaction = Transaction::findOrFail($validated['transaction_id']);

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'Kode promo tidak valid atau sudah habis.');
        }

        $jumlahDibayar = $coupon->applyTo($transaction->total_harga);
        $transaction->update([
            'coupon_id' => $coupon->id,
            'jumlah_dibayar' => $jumlahDibayar,
        ]);
        $coupon->increment('terpakai');

        return back()->with('success', 'Kode promo berhasil diterapkan! Potongan: Rp ' . number_format($transaction->total_harga - $jumlahDibayar, 0, ',', '.'));
    }

    public function requestCancel(Transaction $transaction)
    {
        if ($transaction->student_id !== auth()->id()) {
            abort(403);
        }
        if ($transaction->schedule && $transaction->schedule->waktu_mulai->diffInHours(now()) < 24) {
            return back()->with('error', 'Pembatalan hanya bisa dilakukan minimal 24 jam sebelum kelas dimulai.');
        }
        $transaction->update([
            'alasan_pembatalan' => request('alasan'),
            'cancelled_at' => now(),
            'refund_status' => 'pending',
        ]);
        return back()->with('success', 'Pengajuan pembatalan telah dikirim.');
    }

    public function payments()
    {
        $transactions = Transaction::with('mentor.user', 'schedule', 'coupon', 'dispute')
            ->where('student_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('student.payments', compact('transactions'));
    }

    public function materials()
    {
        $transactions = Transaction::with('materials')
            ->where('student_id', auth()->id())
            ->where('status_pembayaran', 'success')
            ->has('materials')
            ->get();
        $generalMaterials = Material::whereNull('transaction_id')->with('mentor.user')->get();
        return view('student.materials', compact('transactions', 'generalMaterials'));
    }

    public function toggleFavorite(Mentor $mentor)
    {
        $user = auth()->user();
        $fav = MentorFavorite::where('student_id', $user->id)
            ->where('mentor_id', $mentor->id)
            ->first();

        if ($fav) {
            $fav->delete();
            return back()->with('success', 'Mentor dihapus dari favorit.');
        }
        MentorFavorite::create([
            'student_id' => $user->id,
            'mentor_id' => $mentor->id,
        ]);
        return back()->with('success', 'Mentor ditambahkan ke favorit!');
    }

    public function storeReview(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        $transaction->review()->create($validated);

        $avgRating = \DB::table('reviews')
            ->join('transactions', 'reviews.transaction_id', '=', 'transactions.id')
            ->where('transactions.mentor_id', $transaction->mentor_id)
            ->where('transactions.status_pembayaran', 'success')
            ->avg('reviews.rating');

        $transaction->mentor()->update(['rating_rata_rata' => $avgRating ?? 0]);

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }

    public function rescheduleSchedule(Request $request, Transaction $transaction)
    {
        if ($transaction->student_id !== auth()->id()) {
            abort(403);
        }
        if ($transaction->status_pembayaran !== 'success') {
            return back()->with('error', 'Hanya transaksi berhasil yang dapat di-reschedule.');
        }
        if ($transaction->schedule && $transaction->schedule->waktu_mulai->diffInHours(now()) < 24) {
            return back()->with('error', 'Reschedule hanya bisa dilakukan minimal 24 jam sebelum kelas dimulai.');
        }

        $validated = $request->validate([
            'new_schedule_id' => 'required|exists:schedules,id',
        ]);

        $newSchedule = Schedule::findOrFail($validated['new_schedule_id']);
        if ($newSchedule->status !== 'available') {
            return back()->with('error', 'Jadwal baru tidak tersedia.');
        }
        if ($newSchedule->mentor_id !== $transaction->mentor_id) {
            return back()->with('error', 'Jadwal baru harus dengan mentor yang sama.');
        }

        $oldSchedule = $transaction->schedule;
        if ($oldSchedule) {
            $oldSchedule->update(['status' => 'available']);
        }

        $newSchedule->update(['status' => 'booked']);
        $transaction->update(['schedule_id' => $newSchedule->id]);

        return back()->with('success', 'Jadwal berhasil diubah!');
    }

    public function storeDispute(Request $request, Transaction $transaction)
    {
        if ($transaction->student_id !== auth()->id()) {
            abort(403);
        }
        if ($transaction->status_pembayaran !== 'success') {
            return back()->with('error', 'Hanya transaksi berhasil yang dapat di-dispute.');
        }
        if ($transaction->dispute) {
            return back()->with('error', 'Sengketa sudah diajukan untuk transaksi ini.');
        }

        $validated = $request->validate([
            'alasan' => 'required|string|max:1000',
        ]);

        Dispute::create([
            'transaction_id' => $transaction->id,
            'student_id' => auth()->id(),
            'alasan' => $validated['alasan'],
            'status' => 'open',
        ]);

        return back()->with('success', 'Sengketa berhasil diajukan!');
    }
}
