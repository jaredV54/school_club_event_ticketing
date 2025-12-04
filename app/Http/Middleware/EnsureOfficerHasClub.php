<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOfficerHasClub
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If user is an officer, ensure they have a club assigned
        if ($user && $user->role === 'officer' && !$user->club_id) {
            abort(403, 'You must be assigned to a club to access this resource. Please contact an administrator.');
        }

        return $next($request);
    }
}