<?php

namespace App\Actions;

use App\Models\Schedule;
use App\Models\Transaction;

class RescheduleScheduleAction
{
    public function execute(Transaction $transaction, int $newScheduleId): Transaction
    {
        if ($transaction->status_pembayaran !== 'success') {
            throw new \DomainException('Hanya transaksi berhasil yang dapat di-reschedule.');
        }

        if ($transaction->schedule && $transaction->schedule->waktu_mulai->diffInHours(now()) < 24) {
            throw new \DomainException('Reschedule hanya bisa dilakukan minimal 24 jam sebelum kelas dimulai.');
        }

        $newSchedule = Schedule::findOrFail($newScheduleId);

        if ($newSchedule->status !== 'available') {
            throw new \DomainException('Jadwal baru tidak tersedia.');
        }

        if ($newSchedule->mentor_id !== $transaction->mentor_id) {
            throw new \DomainException('Jadwal baru harus dengan mentor yang sama.');
        }

        $oldSchedule = $transaction->schedule;
        if ($oldSchedule) {
            $oldSchedule->update(['status' => 'available']);
        }

        $newSchedule->update(['status' => 'booked']);
        $transaction->update(['schedule_id' => $newSchedule->id]);

        return $transaction;
    }
}
