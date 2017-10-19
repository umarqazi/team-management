<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
class ProjectMiddleware
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
        $roles = array_slice(func_get_args(),2);
        if(in_array($request->user()->roles->pluck('name')[0],$roles)){
            return $next($request);
        }
//        // if user clicked on the projects menu item in navigation Bar

        else{
            if($request->route()->uri()=='projects'){
                return $next($request);
            }
            if(in_array($request->projects, $request->user()->projects->pluck('id')->toArray())){
                return $next($request);
            }
            else
            {
                Session::flash('msgerror', '401 - Unauthorized Access!');
                Session::flash('alert-class', 'alert-danger');
                return Redirect::to('home');
            }

//            dump('Else Check');
//            $userId=$request->user()->id;
//            dump($userId);
//            //  Purpose of following Line ??
//            $projectId=$request->projects;
//            dump($projectId);
//            if($projectId)
//            {
//                dump('Else Check -> if');
//                $getRole=Role::findByName('admin')->users;
//                $role='user';
//
//                foreach($getRole as $role)
//                {
//                    if($role['id']==$userId)
//                    {
//                        dump('Else Check -> if -> foreach -> if');
//                        $role='admin';
//                        break;
//                    }
//                }
//
//                if($role!='admin')
//                {
//                    dump('Else Check -> if -> if');
//                    $checkProject=DB::table('project_user')->where('project_id', $projectId)->where('user_id', $userId)->first();
//                    if(!$checkProject)
//                    {
//                        dump('Else Check -> if -> if -> if');
//                        Session::flash('msgerror', '401 - Unauthorized Access!');
//                        Session::flash('alert-class', 'alert-danger');
//                        dump('Else Check -> if -> if -> if -> End');
//                        return Redirect::to('home');
//                    }
//                }
//            }
        }
    }
}
