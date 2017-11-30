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
    public function handle($request, Closure $next)
    {
        $roles  = array_slice(func_get_args(), 2);

        if( in_array($request->user()->roles->pluck('name')[0], $roles) ){
            return $next($request);
        }

        else{
            switch ($request->method())
            {
                case 'GET':
                    if($request->user()->can('create user'))
                    {
                        return $next($request);
                    }
                    else
                    {
                        Session::flash('msgerror', '401 - Unauthorized Access!');
                        Session::flash('alert-class', 'alert-danger');
                        return Redirect::to('home');
                    }
                    break;

                case 'POST':
                    if($request->user()->can('create user'))
                    {
                        return $next($request);
                    }
                    else
                    {
                        Session::flash('msgerror', '401 - Unauthorized Access!');
                        Session::flash('alert-class', 'alert-danger');
                        return Redirect::to('home');
                    }
                    break;

                case 'DELETE':
                    if($request->user()->can('delete user'))
                    {
                        return $next($request);
                    }
                    else
                    {
                        Session::flash('msgerror', '401 - Unauthorized Access!');
                        Session::flash('alert-class', 'alert-danger');
                        return Redirect::to('home');
                    }
                    break;

                default:
                    break;
            }

            Session::flash('msgerror', '403 - Access Forbidden!');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('home');
        }
    }
}
