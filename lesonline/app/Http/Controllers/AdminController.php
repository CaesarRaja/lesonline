<?php

namespace App\Http\Controllers;

use App\Enums\VerificationStatus;
use App\Enums\WithdrawalStatus;
use App\Http\Requests\ResolveWithdrawalRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePlatformFeeRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\VerifyMentorRequest;
use App\Models\PlatformFee;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalUsers = User::count();
        $totalStudents = User::students()->count();
        $totalMentors = User::mentors()->count();
        $pendingVerifications = User::pendingVerification()->mentors()->count();
        $totalTransactions = Transaction::success()->count();
        $totalRevenue = Transaction::success()->sum('total_harga');
        $totalKomisi = Transaction::success()
            ->whereNotNull('jumlah_dibayar')
            ->sum(\DB::raw('total_harga - jumlah_dibayar'));
        $pendingWithdrawals = Withdrawal::where('status', WithdrawalStatus::Pending->value)->count();
        $recentTransactions = Transaction::with('student', 'mentor.user', 'schedule')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalStudents', 'totalMentors', 'pendingVerifications',
            'totalTransactions', 'totalRevenue', 'totalKomisi', 'pendingWithdrawals',
            'recentTransactions',
        ));
    }

    public function users(): View
    {
        $users = User::with('mentor')->latest()->paginate(10);
        $trashedCount = User::onlyTrashed()->count();

        return view('admin.users', compact('users', 'trashedCount'));
    }

    public function storeUser(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $validated['verification_status'] ??= VerificationStatus::Pending->value;

        $user = User::create($validated);

        if ($user->isMentor()) {
            $user->mentor()->create([
                'keahlian' => $request->keahlian ?? '',
                'tarif_per_jam' => $request->tarif_per_jam ?? 0,
                'bio' => $request->bio ?? '',
                'link_meeting' => $request->link_meeting ?? '',
            ]);
        }

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1 && $request->role !== 'admin') {
            return back()->with('error', 'Tidak dapat mengubah role admin terakhir.');
        }

        $validated = $request->validated();

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($user->isMentor()) {
            $mentorData = [
                'keahlian' => $request->keahlian ?? '',
                'tarif_per_jam' => $request->tarif_per_jam ?? 0,
                'bio' => $request->bio ?? '',
                'link_meeting' => $request->link_meeting ?? '',
            ];

            if ($user->mentor) {
                $user->mentor()->update($mentorData);
            } else {
                $user->mentor()->create($mentorData);
            }
        }

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if (! Gate::allows('delete', $user)) {
            return back()->with('error', 'Tidak dapat menghapus akun ini.');
        }

        if ($user->isMentor() && $user->mentor) {
            $user->mentor()->delete();
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function verifications(): View
    {
        $mentors = User::mentors()
            ->whereIn('verification_status', [
                VerificationStatus::Pending->value,
                VerificationStatus::Verified->value,
                VerificationStatus::Rejected->value,
            ])
            ->with('mentor')
            ->latest()
            ->get();

        return view('admin.verifications', compact('mentors'));
    }

    public function verifyMentor(VerifyMentorRequest $request, User $user): RedirectResponse
    {
        $user->update(['verification_status' => $request->validated('status')]);

        return back()->with('success', 'Status verifikasi mentor berhasil diperbarui.');
    }

    public function fees(): View
    {
        $fee = PlatformFee::getActive();

        return view('admin.fees', compact('fee'));
    }

    public function updateFees(UpdatePlatformFeeRequest $request): RedirectResponse
    {
        PlatformFee::where('is_active', true)->update(['is_active' => false]);

        PlatformFee::create(array_merge($request->validated(), ['is_active' => true]));

        return back()->with('success', 'Konfigurasi komisi platform berhasil diperbarui.');
    }

    public function withdrawals(): View
    {
        $withdrawals = Withdrawal::with('mentor.user')->latest()->get();

        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function resolveWithdrawal(ResolveWithdrawalRequest $request, Withdrawal $withdrawal): RedirectResponse
    {
        $withdrawal->update($request->validated());

        return back()->with('success', 'Status penarikan dana berhasil diperbarui.');
    }

    public function reviews(): View
    {
        $reviews = Review::with('transaction.mentor.user', 'transaction.student')->latest()->get();

        return view('admin.reviews', compact('reviews'));
    }

    public function deleteReview(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }

    public function exportTransactionsPdf(Request $request): Response
    {
        $query = Transaction::with('student', 'mentor.user', 'schedule')
            ->success();

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
}
