<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMentorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->isMentor()) {
            $status = $user->verification_status ?? 'pending';
            if ($status !== 'verified') {
                return back()->with('error', 'Akun mentor belum terverifikasi. Tidak dapat melanjutkan.');
            }
        }
        return $next($request);
    }
}
