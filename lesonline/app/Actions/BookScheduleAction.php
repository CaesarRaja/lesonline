<?php

namespace App\Actions;

use App\Models\Schedule;
use App\Models\Transaction;
use App\Services\MidtransService;

class BookScheduleAction
{
    public function __construct(
        private MidtransService $midtransService,
    ) {}

    public function execute(Schedule $schedule, int $userId): array
    {
        $mentor = $schedule->mentor;

        $transaction = Transaction::create([
            'student_id' => $userId,
            'mentor_id' => $mentor->id,
            'schedule_id' => $schedule->id,
            'total_harga' => $mentor->tarif_per_jam,
            'status_pembayaran' => 'pending',
        ]);

        $orderId = $this->midtransService->generateOrderId($transaction);
        $transaction->update(['midtrans_order_id' => $orderId]);

        $params = $this->midtransService->buildTransactionParams($transaction);
        $snapToken = $this->midtransService->generateSnapToken($params);

        return ['snapToken' => $snapToken, 'transaction' => $transaction];
    }
}
