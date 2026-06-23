<?php

namespace App\Actions;

use App\Models\Coupon;
use App\Models\Transaction;

class ApplyCouponAction
{
    public function execute(string $kode, int $transactionId): Transaction
    {
        $coupon = Coupon::where('kode', $kode)->firstOrFail();
        $transaction = Transaction::findOrFail($transactionId);

        if (! $coupon->isValid()) {
            throw new \DomainException('Kode promo tidak valid atau sudah habis.');
        }

        $jumlahDibayar = $coupon->applyTo($transaction->total_harga);
        $transaction->update([
            'coupon_id' => $coupon->id,
            'jumlah_dibayar' => $jumlahDibayar,
        ]);
        $coupon->increment('terpakai');

        return $transaction;
    }
}
