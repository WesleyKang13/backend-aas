<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, String $role = null): Response
    {
        // Check if Not Authenticated
        if (!Auth::check()) {
            // Not Authenticated - Redirect
            return redirect('/login')->withError('You must login first!');
        }

        // At this stage we are authenticated - fetch user data
        $user = Auth::user();

        // if($user->role !== 'admin'){
        //     Auth::logout();
        //     request()->session()->flush(); // fliush session
        //     request()->session()->regenerate(); // Regenerate session id

        //     return redirect('/login')->withError('Access Denied!');
        // }

        // Ensure enabled
        if ($user->enabled != 1) {
            // User is disabled - logout and redirect
            Auth::logout();
            request()->session()->flush(); // fliush session
            request()->session()->regenerate(); // Regenerate session id
            return redirect('/login')->withError('Your user account has been disabled!');
        }

        // Expired?
        // if ($user->expires_at != null and $user->expires_at->lt(Carbon::now())) {
        //     // User is expired - logout and redirect
        //     Auth::logout();
        //     request()->session()->flush(); // fliush session
        //     request()->session()->regenerate(); // Regenerate session id
        //     return redirect('/login')->withError('Your user account has expired!');
        // }


        return $next($request);
    }
}
