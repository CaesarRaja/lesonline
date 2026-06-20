<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'mentor_id', 'jumlah', 'bank', 'no_rekening', 'atas_nama', 'status', 'alasan_penolakan',
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}
