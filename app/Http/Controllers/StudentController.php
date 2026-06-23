<?php

namespace App\Http\Controllers;

use App\Actions\ApplyCouponAction;
use App\Actions\BookScheduleAction;
use App\Actions\CancelTransactionAction;
use App\Actions\RescheduleScheduleAction;
use App\Enums\ScheduleStatus;
use App\Enums\TransactionStatus;
use App\Enums\UserRole;
use App\Http\Requests\ApplyCouponRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Material;
use App\Models\Mentor;
use App\Models\MentorFavorite;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Services\ChatService;
use App\Services\MidtransService;
use App\Services\ReviewService;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    public function __construct(
        private TransactionService $transactionService,
        private MidtransService $midtransService,
        private ReviewService $reviewService,
        private BookScheduleAction $bookScheduleAction,
        private ApplyCouponAction $applyCouponAction,
        private CancelTransactionAction $cancelTransactionAction,
        private RescheduleScheduleAction $rescheduleScheduleAction,
    ) {}

    public function dashboard(): View
    {
        $this->transactionService->cleanupPendingTransactions();

        $user = auth()->user();
        $transactions = Transaction::with('mentor.user', 'schedule')
            ->where('student_id', $user->id)
            ->latest()
            ->get();
        $totalJam = $transactions->where('status_pembayaran', 'success')->count() * 1;
        $kelasMendatang = $transactions->where('status_pembayaran', 'success')
            ->filter(fn ($t) => $t->schedule && $t->schedule->waktu_mulai > now());
        $favorites = MentorFavorite::with('mentor.user')
            ->where('student_id', $user->id)
            ->get();

        return view('student.dashboard', compact('transactions', 'totalJam', 'kelasMendatang', 'favorites'));
    }

    public function bookSchedule(Schedule $schedule): View|RedirectResponse
    {
        if ($schedule->status !== ScheduleStatus::Available->value) {
            return back()->with('error', 'Jadwal sudah dipesan.');
        }

        try {
            $result = $this->bookScheduleAction->execute($schedule, auth()->id());

            return view('student.payment', [
                'snapToken' => $result['snapToken'],
                'transaction' => $result['transaction'],
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: '.$e->getMessage());
        }
    }

    public function paymentSuccess(Request $request): RedirectResponse
    {
        $transaction = Transaction::where('midtrans_order_id', $request->order_id)->first();

        if ($transaction) {
            $jumlahDibayar = $transaction->total_harga;
            $netAmount = $this->transactionService->calculateNetAmount($jumlahDibayar);

            $transaction->update([
                'status_pembayaran' => TransactionStatus::Success->value,
                'jumlah_dibayar' => $netAmount,
                'midtrans_transaction_id' => $request->transaction_id,
                'midtrans_response' => $request->all(),
            ]);

            $transaction->schedule->update(['status' => ScheduleStatus::Booked->value]);
        }

        return redirect()->route('student.dashboard')->with('success', 'Pembayaran berhasil!');
    }

    public function applyCoupon(ApplyCouponRequest $request): RedirectResponse
    {
        try {
            $transaction = $this->applyCouponAction->execute(
                $request->validated('kode'),
                $request->validated('transaction_id'),
            );

            $diskon = $transaction->total_harga - $transaction->jumlah_dibayar;

            return back()->with(
                'success',
                'Kode promo berhasil diterapkan! Potongan: Rp '.number_format($diskon, 0, ',', '.'),
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function requestCancel(Request $request, Transaction $transaction): RedirectResponse
    {
        Gate::authorize('cancel', $transaction);

        try {
            $this->cancelTransactionAction->execute($transaction, $request->alasan);

            return back()->with('success', 'Pengajuan pembatalan telah dikirim.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function payments(): View
    {
        $this->transactionService->cleanupPendingTransactions();

        $transactions = $this->transactionService->getUserTransactions(auth()->id(), perPage: 10);

        return view('student.payments', compact('transactions'));
    }

    public function payPending(Transaction $transaction): View|RedirectResponse
    {
        Gate::authorize('pay', $transaction);

        if ($transaction->status_pembayaran !== TransactionStatus::Pending->value) {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        try {
            $params = $this->midtransService->buildTransactionParams($transaction);
            $snapToken = $this->midtransService->generateSnapToken($params);

            return view('student.payment', compact('snapToken', 'transaction'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: '.$e->getMessage());
        }
    }

    public function materials(): View
    {
        $transactions = Transaction::with('materials')
            ->where('student_id', auth()->id())
            ->success()
            ->has('materials')
            ->get();
        $generalMaterials = Material::whereNull('transaction_id')->with('mentor.user')->get();

        return view('student.materials', compact('transactions', 'generalMaterials'));
    }

    public function downloadMaterial(Material $material): RedirectResponse|BinaryFileResponse|StreamedResponse
    {
        Gate::authorize('download', $material);

        if (! Storage::disk('public')->exists($material->file_path)) {
            abort(404);
        }

        $filename = $material->judul.'.'.$material->tipe;

        return Storage::disk('public')->download($material->file_path, $filename);
    }

    public function toggleFavorite(Mentor $mentor): RedirectResponse
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

    public function storeReview(StoreReviewRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction->review()->create($request->validated());

        $this->reviewService->updateMentorAverageRating($transaction->mentor);

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }

    public function rescheduleSchedule(Request $request, Transaction $transaction): RedirectResponse
    {
        Gate::authorize('reschedule', $transaction);

        try {
            $this->rescheduleScheduleAction->execute(
                $transaction,
                $request->input('new_schedule_id'),
            );

            return back()->with('success', 'Jadwal berhasil diubah!');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function chat(): View
    {
        $mentors = app(ChatService::class)
            ->getConversations(auth()->user(), UserRole::Mentor);

        return view('student.chat', compact('mentors'));
    }
}
