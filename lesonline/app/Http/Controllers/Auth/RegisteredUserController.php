<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,mentor'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'verification_status' => $request->role === UserRole::Mentor->value
                ? VerificationStatus::Pending->value
                : VerificationStatus::Verified->value,
        ]);

        if ($request->role === UserRole::Mentor->value) {
            $user->mentor()->create([
                'bio' => 'Saya siap mengajar!',
                'tarif_per_jam' => 100000,
                'link_meeting' => 'https://meet.google.com/abc-defg-hij',
                'keahlian' => 'Umum',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        $dashboardRoute = match ($user->role) {
            UserRole::Admin->value => 'admin.dashboard',
            UserRole::Mentor->value => 'mentor.dashboard',
            default => 'student.dashboard',
        };

        return redirect(route($dashboardRoute, absolute: false));
    }
}
