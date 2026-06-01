<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $now = now();

            if ($user->last_seen_at) {
                $diffInSeconds = $now->diffInSeconds($user->last_seen_at);

                // Ignore inactive intervals longer than 15 minutes to preserve metric integrity
                if ($diffInSeconds < 900) {
                    $user->increment('seconds_spent', $diffInSeconds);
                }
            }

            // Always update last hit timestamp
            $user->update(['last_seen_at' => $now]);
        }

        return $next($request);
    }
}