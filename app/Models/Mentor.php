<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'tarif_per_jam', 'link_meeting', 'rating_rata_rata', 'keahlian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function bundles()
    {
        return $this->hasMany(CourseBundle::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function favoritedBy()
    {
        return $this->hasMany(MentorFavorite::class);
    }
}
