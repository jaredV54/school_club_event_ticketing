<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Convert roles to lowercase for comparison
        $allowedRoles = array_map('strtolower', $roles);
        $userRole = strtolower($user->role);

        // Check if user's role is in the allowed roles or contains the role (for officer variants)
        $hasAccess = in_array($userRole, $allowedRoles);
        if (!$hasAccess) {
            foreach ($allowedRoles as $role) {
                if (str_contains($userRole, $role) || str_contains($role, $userRole)) {
                    $hasAccess = true;
                    break;
                }
            }
        }

        if ($hasAccess) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
