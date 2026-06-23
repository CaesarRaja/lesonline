<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'role', 'verification_status'];

    protected $hidden = ['password', 'remember_token'];

    public function mentor(): HasOne
    {
        return $this->hasOne(Mentor::class);
    }

    public function studentTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'student_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(MentorFavorite::class, 'student_id');
    }

    public function isStudent(): bool
    {
        return $this->role === UserRole::Student->value;
    }

    public function isMentor(): bool
    {
        return $this->role === UserRole::Mentor->value;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin->value;
    }

    public function scopeMentors(Builder $query): Builder
    {
        return $query->where('role', UserRole::Mentor->value);
    }

    public function scopeStudents(Builder $query): Builder
    {
        return $query->where('role', UserRole::Student->value);
    }

    public function scopePendingVerification(Builder $query): Builder
    {
        return $query->where('verification_status', VerificationStatus::Pending->value);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
