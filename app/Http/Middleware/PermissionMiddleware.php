<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Routing\Route;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next, $permission)
    {
        if( ! $request->user()->can($permission)){
            Session::flash('msgerror', '401 - Unauthorized Access!');
            Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('home');
        }
        return $next($request);
    }
}
