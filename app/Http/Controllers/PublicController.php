<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\MentorFavorite;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function landing(): View
    {
        $mentorCount = Mentor::count();

        return view('landing', compact('mentorCount'));
    }

    public function mentors(Request $request): View
    {
        $mentors = Mentor::with('user')
            ->search($request->search)
            ->get();

        return view('mentors.index', compact('mentors'));
    }

    public function mentorDetail(Mentor $mentor): View
    {
        $mentor->load('user', 'schedules');
        $schedules = $mentor->schedules()->available()->get();
        $isFavorited = auth()->check() && auth()->user()->isStudent()
            ? MentorFavorite::where('student_id', auth()->id())->where('mentor_id', $mentor->id)->exists()
            : false;

        return view('mentors.detail', compact('mentor', 'schedules', 'isFavorited'));
    }
}
