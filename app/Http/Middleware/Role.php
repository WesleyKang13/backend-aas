<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {

        if(!Auth::check()){


        $request->session()->flush();
        // Regenerate session id
        $request->session()->regenerate();
            return redirect('/login')->withError('You must login first');
        }

        if(Auth::user()->role == null){


        $request->session()->flush();
        // Regenerate session id
        $request->session()->regenerate();
            return redirect('login')->withError('Access Denied!');
        }

        if(Auth::user()->role !== $role){


        $request->session()->flush();
        // Regenerate session id
        $request->session()->regenerate();
            return redirect('/login')->withError('Access Denied!');
        }


        return $next($request);
    }
}
