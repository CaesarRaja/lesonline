<?php

namespace App\Http\Middleware;

use App\Enums\VerificationStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMentorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isMentor()) {
            $status = $user->verification_status ?? VerificationStatus::Pending->value;

            if ($status !== VerificationStatus::Verified->value) {
                return back()->with('error', 'Akun mentor belum terverifikasi. Tidak dapat melanjutkan.');
            }
        }

        return $next($request);
    }
}
