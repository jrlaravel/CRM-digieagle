<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckHR
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('has_hr_features') || !session()->get('has_hr_features')) {
            // Redirect or abort if the session does not contain the feature access
            return redirect()->route('emp/dashboard')->with('error', 'You do not have access to this feature.');
        }
        return $next($request);
    }
}
