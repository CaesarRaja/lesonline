<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['kode', 'tipe', 'nilai', 'kuota', 'terpakai', 'expired_at'];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
        ];
    }

    public function isValid()
    {
        return $this->terpakai < $this->kuota && now()->lt($this->expired_at);
    }

    public function applyTo($amount)
    {
        if ($this->tipe === 'percent') {
            return max(0, $amount - ($amount * $this->nilai / 100));
        }
        return max(0, $amount - $this->nilai);
    }
}
