<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Check if user is authenticated
                $user = Auth::user();

                // Determine redirect URL based on role
                $redirectUrl = match ($user->role) {
                    'admin' => '/admin/dashboard', // Redirect admins to admin dashboard
                    default => '/dashboard',       // Redirect other users (default 'user') to user dashboard
                };

                // Perform the redirect
                return redirect($redirectUrl);
            }
        }

        return $next($request);
    }
}
