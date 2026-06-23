<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $fillable = [
        'student_id', 'mentor_id', 'schedule_id', 'total_harga', 'coupon_id', 'jumlah_dibayar',
        'status_pembayaran', 'refund_status', 'midtrans_order_id', 'midtrans_transaction_id',
        'midtrans_response', 'cancelled_at', 'alasan_pembatalan',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function scopeSuccess(Builder $query): Builder
    {
        return $query->where('status_pembayaran', TransactionStatus::Success->value);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status_pembayaran', TransactionStatus::Pending->value);
    }

    public function scopeRecent(Builder $query, int $limit = 5): Builder
    {
        return $query->latest()->take($limit);
    }

    protected function casts(): array
    {
        return [
            'midtrans_response' => 'array',
            'total_harga' => 'decimal:2',
            'jumlah_dibayar' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }
}
