<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformFee extends Model
{
    protected $fillable = ['persentase', 'nominal_tetap', 'is_active'];

    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    public function calculate($amount)
    {
        return ($amount * $this->persentase / 100) + $this->nominal_tetap;
    }
}
