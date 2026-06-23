<?php

use App\Models\Transaction;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('transactions:cleanup-pending', function () {
    $cutoff = now()->subMinutes(30);

    $transactions = Transaction::with('schedule')
        ->where('status_pembayaran', 'pending')
        ->where('created_at', '<=', $cutoff)
        ->get();

    $count = 0;

    foreach ($transactions as $transaction) {
        if ($transaction->schedule) {
            $transaction->schedule->update(['status' => 'available']);
        }
        $transaction->delete();
        $count++;
    }

    $this->info("Deleted {$count} pending transaction(s) older than 30 minutes.");
})->purpose('Delete pending transactions older than 30 minutes and release schedules');

Schedule::command('transactions:cleanup-pending')->everyMinute();
