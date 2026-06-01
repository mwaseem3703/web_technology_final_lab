<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access request context.');
        }

        return $next($request);
    }
}