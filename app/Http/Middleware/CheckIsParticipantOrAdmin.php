<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class CheckIsParticipantOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() == null) {
            return redirect(route('events.index'));
        } else {
            $role = Auth::user()->role;
            if (Auth::check()) {
                if ($role == "participant" || $role == "administrator") {
                    return $next($request);
                } else {
                    return redirect(route('events.index'));
                }
            } else {

            }
        }
    }
}
