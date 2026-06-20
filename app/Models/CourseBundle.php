<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseBundle extends Model
{
    protected $fillable = ['mentor_id', 'nama', 'deskripsi', 'jumlah_sesi', 'harga'];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}
