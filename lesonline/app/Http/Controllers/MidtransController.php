<?php

namespace App\Http\Controllers;

use App\Enums\ScheduleStatus;
use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function __construct(
        private MidtransService $midtransService,
    ) {}

    public function callback(Request $request): JsonResponse
    {
        if (! $this->midtransService->verifySignature(
            $request->order_id,
            $request->status_code,
            $request->gross_amount,
            $request->signature_key,
        )) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::where('midtrans_order_id', $request->order_id)->first();

        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $status = match (true) {
            in_array($request->transaction_status, ['capture', 'settlement']) => TransactionStatus::Success,
            $request->transaction_status === 'deny' => TransactionStatus::Failed,
            default => TransactionStatus::Pending,
        };

        $transaction->update([
            'status_pembayaran' => $status->value,
            'midtrans_transaction_id' => $request->transaction_id,
            'midtrans_response' => $request->all(),
        ]);

        if ($status === TransactionStatus::Success) {
            $transaction->schedule->update(['status' => ScheduleStatus::Booked->value]);
        }

        return response()->json(['message' => 'OK']);
    }
}
