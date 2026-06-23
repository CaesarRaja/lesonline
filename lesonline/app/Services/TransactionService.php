<?php

namespace App\Services;

use App\Models\PlatformFee;
use App\Models\Transaction;

class TransactionService
{
    public function cleanupPendingTransactions(): void
    {
        Transaction::where('status_pembayaran', 'pending')
            ->where('created_at', '<=', now()->subMinutes(30))
            ->each(function ($transaction) {
                $transaction->schedule?->update(['status' => 'available']);
                $transaction->delete();
            });
    }

    public function calculateNetAmount(float $totalHarga): float
    {
        $fee = PlatformFee::getActive();
        $potongan = $fee ? $fee->calculate($totalHarga) : 0;

        return $totalHarga - $potongan;
    }

    public function getUserTransactions(int $userId, ?int $perPage = null)
    {
        $query = Transaction::with('mentor.user', 'schedule', 'coupon')
            ->where('student_id', $userId)
            ->latest();

        return $perPage ? $query->paginate($perPage) : $query->get();
    }
}
