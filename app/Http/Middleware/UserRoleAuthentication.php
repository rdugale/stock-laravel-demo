<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRoleAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::user() && Auth::user()->role == 'admin') {
            return $next($request);
        }

        if (!Auth::user() ) {
            return redirect('/login');
        }

        return redirect('/')->with("error", "You Don't Have Permission to Access Add Stock Page");

        // old code :  return $next($request);
    }
}
