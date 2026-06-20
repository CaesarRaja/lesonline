<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
        'transaction_id', 'student_id', 'alasan', 'status', 'resolved_by', 'catatan_resolusi',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
