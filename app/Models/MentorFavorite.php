<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorFavorite extends Model
{
    protected $fillable = ['student_id', 'mentor_id'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}
