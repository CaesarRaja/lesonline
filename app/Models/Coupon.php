<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = ['kode', 'tipe', 'nilai', 'kuota', 'terpakai', 'expired_at'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function isValid(): bool
    {
        return $this->terpakai < $this->kuota && now()->lt($this->expired_at);
    }

    public function applyTo(float $amount): float
    {
        if ($this->tipe === 'percent') {
            return max(0, $amount - ($amount * $this->nilai / 100));
        }

        return max(0, $amount - $this->nilai);
    }

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
        ];
    }
}
