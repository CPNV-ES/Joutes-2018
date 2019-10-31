<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class DevLogin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('USER_ID', false))
        {
            $user = User::find(env('USER_ID'));
            if ($user)
                if (Auth::attempt(['username' => $user->first_name, 'password' => 'Pa$$w0rd']))
                    return $next($request);
                else
                    \Redirect::guest('login');
            else
                return redirect('http://intranet.cpnv.ch/connexion');
        } else error_log("Prod");
        return $next($request);
    }
}
