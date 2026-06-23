<?php

namespace App\Services;

use App\Models\Mentor;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function updateMentorAverageRating(Mentor $mentor): float
    {
        $avgRating = (float) DB::table('reviews')
            ->join('transactions', 'reviews.transaction_id', '=', 'transactions.id')
            ->where('transactions.mentor_id', $mentor->id)
            ->where('transactions.status_pembayaran', 'success')
            ->avg('reviews.rating');

        $mentor->update(['rating_rata_rata' => $avgRating]);

        return $avgRating;
    }
}
