<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasBdeFeatures
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('has_bde_features') || !session()->get('has_bde_features')) {
            // Redirect or abort if the session does not contain the feature access
            return redirect()->route('emp/dashboard')->with('error', 'You do not have access to this feature.');
        }
        return $next($request);
    }
}
