<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployeeOrHr
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        // Check if the user is not authenticated
        if (!$user) {
            return redirect()->route('emp/login');
        }

        // Redirect authenticated users to the dashboard
        if (Auth::guard('web')->check()) {
            // Allow HR users to access additional features
            if ($user->role == 'hr' || $user->role == 'employee') {
                return $next($request);
            }

            // For other roles, redirect to the dashboard with an error message
            return redirect()->route('emp/dashboard')->with('error', 'You do not have permission to access this area.');
        }
        return $next($request);
    }
}
    