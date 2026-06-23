<?php

namespace App\Actions;

use App\Models\Transaction;

class CancelTransactionAction
{
    public function execute(Transaction $transaction, ?string $alasan): Transaction
    {
        if ($transaction->schedule && $transaction->schedule->waktu_mulai->diffInHours(now()) < 24) {
            throw new \DomainException('Pembatalan hanya bisa dilakukan minimal 24 jam sebelum kelas dimulai.');
        }

        $transaction->update([
            'alasan_pembatalan' => $alasan,
            'cancelled_at' => now(),
            'refund_status' => 'pending',
        ]);

        return $transaction;
    }
}
