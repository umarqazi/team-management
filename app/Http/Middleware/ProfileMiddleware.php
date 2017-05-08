<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if( ! $request->user()->hasRole($role) )
        {
            if( Auth::user()->id != $request->route('users') )
            {
                Session::flash('msgerror', '401 - Unauthorized Access!');
                Session::flash('alert-class', 'alert-danger');
                return Redirect::to('home');
            }
        }
        return $next($request);
    }
}
