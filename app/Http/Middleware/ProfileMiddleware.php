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
    public function handle($request, Closure $next)
    {
        $roles  = array_slice(func_get_args(), 2);

        if(! in_array($request->user()->roles->pluck('name')[0], $roles))
        {
            /*
             if($request->user()->can('edit user')){
                return $next($request);
            }
            */

            if( Auth::user()->id != $request->route('users') )
            {
                if($request->user()->can('edit user')){
                    return $next($request);
                }
                else
                {
                    Session::flash('msgerror', '401 - Unauthorized Access!');
                    Session::flash('alert-class', 'alert-danger');
                    return Redirect::to('home');
                }
            }
        }
        return $next($request);
    }
}
