<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsManager
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
        # 1st Step: Verify if user is logged in
        if (!Auth::check()) return redirect(route('login'));
        # 2nd Step: Check if user is admin or not
        if (Auth::user()->user_role != 'manager') return redirect(route('login'));
        # If everything went perfect
        return $next($request);
    }
}
