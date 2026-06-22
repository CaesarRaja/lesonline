<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'student_id', 'mentor_id', 'schedule_id', 'total_harga', 'coupon_id', 'jumlah_dibayar',
        'status_pembayaran', 'refund_status', 'midtrans_order_id', 'midtrans_transaction_id',
        'midtrans_response', 'cancelled_at', 'alasan_pembatalan',
    ];

    protected function casts(): array
    {
        return [
            'midtrans_response' => 'array',
            'total_harga' => 'decimal:2',
            'jumlah_dibayar' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
