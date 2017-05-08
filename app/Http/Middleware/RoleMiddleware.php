<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Session;

class RoleMiddleware
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
        if( ! $request->user()->hasRole($role) ){
            Session::flash('msgerror', '403 - Access Forbidden!');
            Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('home');
        }
        return $next($request);
    }
}
