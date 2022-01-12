<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsClient
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
        if (Auth::user()->user_role != 'client') return redirect(route('login'));
        # 3rd step: Checks if the client is logging in first time or not
        if (empty(Auth::user()->client->last_logged_in)) return redirect(route('first.login.client'));
        # 4th step: Checks if the client is active and has paid the charges
        if (Auth::user()->client->active == 0) return redirect(route('approval.wait'));
        if (Auth::user()->client->subscription[0]->next_payment_date < Carbon::now()) return redirect(route('approval.unpaid'));
        # If everything went perfect
        return $next($request);
    }
}
