<?php

namespace App\Services;

use App\Models\Mentor;
use Illuminate\Support\Facades\DB;

class MentorFinanceService
{
    public function getTotalEarnings(Mentor $mentor): float
    {
        return (float) $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->sum(DB::raw('COALESCE(jumlah_dibayar, total_harga)'));
    }

    public function getTotalWithdrawn(Mentor $mentor): float
    {
        return (float) $mentor->withdrawals()
            ->where('status', 'approved')
            ->sum('jumlah');
    }

    public function getBalance(Mentor $mentor): float
    {
        return $this->getTotalEarnings($mentor) - $this->getTotalWithdrawn($mentor);
    }

    public function getActiveStudentsCount(Mentor $mentor): int
    {
        return $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->distinct('student_id')
            ->count('student_id');
    }

    public function getTotalSessions(Mentor $mentor): int
    {
        return $mentor->transactions()
            ->where('status_pembayaran', 'success')
            ->count();
    }
}
