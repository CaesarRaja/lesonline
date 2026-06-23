<?php

namespace App\Models;

use App\Enums\ScheduleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Schedule extends Model
{
    protected $fillable = [
        'mentor_id', 'waktu_mulai', 'waktu_selesai', 'status', 'alasan_pembatalan', 'cancelled_at',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', ScheduleStatus::Available->value);
    }

    public function scopeBooked(Builder $query): Builder
    {
        return $query->where('status', ScheduleStatus::Booked->value);
    }

    protected function casts(): array
    {
        return [
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }
}
