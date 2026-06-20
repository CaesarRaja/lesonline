<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['mentor_id', 'transaction_id', 'judul', 'file_path', 'tipe'];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
