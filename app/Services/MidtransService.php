<?php

namespace App\Services;

use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function generateSnapToken(array $params): string
    {
        return Snap::getSnapToken($params);
    }

    public function buildTransactionParams(Transaction $transaction): array
    {
        $mentor = $transaction->mentor;
        $student = $transaction->student;

        return [
            'transaction_details' => [
                'order_id' => $transaction->midtrans_order_id,
                'gross_amount' => (int) $transaction->total_harga,
            ],
            'customer_details' => [
                'first_name' => $student->name,
                'email' => $student->email,
            ],
            'item_details' => [
                [
                    'id' => $mentor->id,
                    'price' => (int) $transaction->total_harga,
                    'quantity' => 1,
                    'name' => 'Sesi Belajar dengan '.$mentor->user->name,
                ],
            ],
        ];
    }

    public function generateOrderId(Transaction $transaction): string
    {
        return 'BIMBELEDU-'.$transaction->id.'-'.time();
    }

    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        return $hashed === $signatureKey;
    }
}
