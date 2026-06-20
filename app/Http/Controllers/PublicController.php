<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\MentorFavorite;
use App\Models\Schedule;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function landing()
    {
        $mentorCount = Mentor::count();
        return view('landing', compact('mentorCount'));
    }

    public function mentors(Request $request)
    {
        $query = Mentor::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('keahlian', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $mentors = $query->get();
        return view('mentors.index', compact('mentors'));
    }

    public function mentorDetail(Mentor $mentor)
    {
        $mentor->load('user', 'schedules');
        $schedules = $mentor->schedules()->where('status', 'available')->get();
        $isFavorited = auth()->check() && auth()->user()->isStudent()
            ? MentorFavorite::where('student_id', auth()->id())->where('mentor_id', $mentor->id)->exists()
            : false;
        return view('mentors.detail', compact('mentor', 'schedules', 'isFavorited'));
    }
}
