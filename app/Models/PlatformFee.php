<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformFee extends Model
{
    protected $fillable = ['persentase', 'nominal_tetap', 'is_active'];

    public static function getActive(): ?self
    {
        return self::where('is_active', true)->first();
    }

    public function calculate(float $amount): float
    {
        return ($amount * $this->persentase / 100) + $this->nominal_tetap;
    }
}
