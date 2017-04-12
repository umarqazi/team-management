<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user   = Auth::user();

        if($user->hasRole(['developer', 'teamlead', 'engineer']))
        {
            $projects   = $user->projects()->paginate(10);
        }
        else
        {
            $projects   = Project::paginate(10);
        }

        /*if($user->hasRole(['developer', 'teamlead', 'engineer']))
        {
            $projects   = $user->projects;
        }
        else
        {
            $projects   = Project::all();
        }*/

        $view   = View::make('home');
        if($user->hasRole(['developer', 'teamlead', 'engineer']))
        {
            $view->nest('dashboard', 'dashboard.engineers', compact('projects'));
        }
        elseif($user->hasRole('admin'))
        {
            $view->nest('dashboard', 'dashboard.admin', compact('projects'));
        }
        else
        {
            $view->nest('dashboard', 'dashboard.sales', compact('projects'));
        }

        return $view;
    }
}
