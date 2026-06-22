<?php

namespace App\Http\Controllers;


use App\Models\Dispute;
use App\Models\PlatformFee;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalMentors = User::where('role', 'mentor')->count();
        $pendingVerifications = User::where('role', 'mentor')->where('verification_status', 'pending')->count();
        $totalTransactions = Transaction::where('status_pembayaran', 'success')->count();
        $totalRevenue = Transaction::where('status_pembayaran', 'success')->sum('total_harga');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $openDisputes = Dispute::where('status', 'open')->count();
        $recentTransactions = Transaction::with('student', 'mentor.user', 'schedule')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalStudents', 'totalMentors', 'pendingVerifications',
            'totalTransactions', 'totalRevenue', 'pendingWithdrawals', 'openDisputes',
            'recentTransactions'
        ));
    }

    public function users()
    {
        $users = User::with('mentor')->latest()->paginate(10);
        $trashedCount = User::onlyTrashed()->count();
        return view('admin.users', compact('users', 'trashedCount'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:student,mentor,admin',
            'verification_status' => 'nullable|in:pending,verified,rejected',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['verification_status'] ??= 'pending';

        $user = User::create($validated);

        if ($user->role === 'mentor') {
            $user->mentor()->create([
                'keahlian' => $request->keahlian ?? '',
                'tarif_per_jam' => $request->tarif_per_jam ?? 0,
                'bio' => $request->bio ?? '',
                'link_meeting' => $request->link_meeting ?? '',
            ]);
        }

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1 && $request->role !== 'admin') {
            return back()->with('error', 'Tidak dapat mengubah role admin terakhir.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:student,mentor,admin',
            'verification_status' => 'nullable|in:pending,verified,rejected',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($user->role === 'mentor') {
            if ($user->mentor) {
                $user->mentor()->update([
                    'keahlian' => $request->keahlian ?? '',
                    'tarif_per_jam' => $request->tarif_per_jam ?? 0,
                    'bio' => $request->bio ?? '',
                    'link_meeting' => $request->link_meeting ?? '',
                ]);
            } else {
                $user->mentor()->create([
                    'keahlian' => $request->keahlian ?? '',
                    'tarif_per_jam' => $request->tarif_per_jam ?? 0,
                    'bio' => $request->bio ?? '',
                    'link_meeting' => $request->link_meeting ?? '',
                ]);
            }
        }

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        if ($user->isMentor() && $user->mentor) {
            $user->mentor()->delete();
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    // KYC Verification
    public function verifications()
    {
        $mentors = User::where('role', 'mentor')
            ->whereIn('verification_status', ['pending', 'verified', 'rejected'])
            ->with('mentor')
            ->latest()
            ->get();
        return view('admin.verifications', compact('mentors'));
    }

    public function verifyMentor(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,rejected',
            'alasan' => 'nullable|string|max:500',
        ]);

        $user->update(['verification_status' => $validated['status']]);
        return back()->with('success', 'Status verifikasi mentor berhasil diperbarui.');
    }

    // Platform Fee
    public function fees()
    {
        $fee = PlatformFee::getActive();
        return view('admin.fees', compact('fee'));
    }

    public function updateFees(Request $request)
    {
        $validated = $request->validate([
            'persentase' => 'required|numeric|min:0|max:100',
            'nominal_tetap' => 'required|numeric|min:0',
        ]);

        PlatformFee::where('is_active', true)->update(['is_active' => false]);
        PlatformFee::create(array_merge($validated, ['is_active' => true]));

        return back()->with('success', 'Konfigurasi komisi platform berhasil diperbarui.');
    }

    // Withdrawals Management
    public function withdrawals()
    {
        $withdrawals = Withdrawal::with('mentor.user')->latest()->get();
        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function resolveWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'alasan_penolakan' => 'nullable|required_if:status,rejected|string|max:500',
        ]);

        $withdrawal->update($validated);
        return back()->with('success', 'Status penarikan dana berhasil diperbarui.');
    }

    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Disputes
    public function disputes()
    {
        $disputes = Dispute::with('transaction.mentor.user', 'student', 'resolver')->latest()->get();
        return view('admin.disputes', compact('disputes'));
    }

    public function resolveDispute(Request $request, Dispute $dispute)
    {
        $validated = $request->validate([
            'status' => 'required|in:resolved,rejected',
            'catatan_resolusi' => 'nullable|string|max:1000',
        ]);

        $dispute->update([
            'status' => $validated['status'],
            'catatan_resolusi' => $validated['catatan_resolusi'],
            'resolved_by' => auth()->id(),
        ]);

        if ($validated['status'] === 'resolved') {
            $transaction = $dispute->transaction;
            $transaction->update(['refund_status' => 'refunded']);

            if ($transaction->midtrans_transaction_id) {
                try {
                    MidtransTransaction::refund($transaction->midtrans_order_id);
                } catch (\Exception $e) {
                    \Log::warning('Midtrans refund failed for ' . $transaction->midtrans_order_id . ': ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Sengketa berhasil diresolusi.');
    }

    // Reviews Moderation
    public function reviews()
    {
        $reviews = Review::with('transaction.mentor.user', 'transaction.student')->latest()->get();
        return view('admin.reviews', compact('reviews'));
    }

    public function deleteReview(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }

    public function exportTransactionsPdf(Request $request)
    {
        $query = Transaction::with('student', 'mentor.user', 'schedule')
            ->where('status_pembayaran', 'success');

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->get();
        $totalRevenue = $transactions->sum('total_harga');

        $pdf = Pdf::loadView('admin.report-pdf', compact('transactions', 'totalRevenue'));
        return $pdf->download('laporan-keuangan-platform.pdf');
    }

    public function testMidtrans()
    {
        $serverKey = config('services.midtrans.server_key');
        $clientKey = config('services.midtrans.client_key');
        $isProduction = config('services.midtrans.is_production');
        $mode = $isProduction ? 'PRODUCTION' : 'SANDBOX';

        $errors = [];
        $snapToken = null;

        if (!$serverKey) {
            $errors[] = 'MIDTRANS_SERVER_KEY tidak terisi di .env';
        }
        if (!$clientKey) {
            $errors[] = 'MIDTRANS_CLIENT_KEY tidak terisi di .env';
        }

        if ($serverKey && $clientKey) {
            try {
                $snapToken = Snap::getSnapToken([
                    'transaction_details' => [
                        'order_id' => 'TEST-' . time(),
                        'gross_amount' => 10000,
                    ],
                    'customer_details' => [
                        'first_name' => 'Test',
                        'email' => 'test@test.com',
                    ],
                ]);
            } catch (\Exception $e) {
                $errors[] = 'Midtrans API Error: ' . $e->getMessage();
            }
        }

        return view('admin.test-midtrans', compact(
            'serverKey', 'clientKey', 'isProduction', 'mode',
            'errors', 'snapToken'
        ));
    }
}
