<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'mentor_id', 'waktu_mulai', 'waktu_selesai', 'status', 'alasan_pembatalan', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
