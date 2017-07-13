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
        $userId=$request->user()->id;
        $projectId=$request->projects;
        if($projectId)
        {
            $getRole=Role::findByName('admin')->users;
            $role='user';
            foreach($getRole as $role)
            {
                if($role['id']==$userId)
                {
                    $role='admin';
                    break;
                }
            }
            if($role!='admin')
            {
                $checkProject=DB::table('project_user')->where('project_id', $projectId)->where('user_id', $userId)->first();
                if(!$checkProject)
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
