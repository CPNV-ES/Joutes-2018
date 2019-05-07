<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class CheckIsAdmin
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
        
        if(!Auth::check()){
            return redirect(route('events.index'));
        }else if(Auth::user()->role != 'administrator'){
            return redirect(route('events.index'));
        }

        return $next($request);
    }
}
