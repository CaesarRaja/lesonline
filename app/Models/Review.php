<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'transaction_id', 'rating', 'komentar',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
