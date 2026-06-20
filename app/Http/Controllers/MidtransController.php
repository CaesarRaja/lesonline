<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::where('midtrans_order_id', $request->order_id)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->update([
            'status_pembayaran' => $request->transaction_status === 'capture' || $request->transaction_status === 'settlement'
                ? 'success' : ($request->transaction_status === 'deny' ? 'failed' : 'pending'),
            'midtrans_transaction_id' => $request->transaction_id,
            'midtrans_response' => $request->all(),
        ]);

        if ($transaction->status_pembayaran === 'success') {
            $transaction->schedule->update(['status' => 'booked']);
        }

        return response()->json(['message' => 'OK']);
    }
}
