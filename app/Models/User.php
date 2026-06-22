<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'role', 'verification_status'];

    protected $hidden = ['password', 'remember_token'];

    public function mentor()
    {
        return $this->hasOne(Mentor::class);
    }

    public function studentTransactions()
    {
        return $this->hasMany(Transaction::class, 'student_id');
    }

    public function favorites()
    {
        return $this->hasMany(MentorFavorite::class, 'student_id');
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isMentor()
    {
        return $this->role === 'mentor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
