<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Project;

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

        $projects   = array();

        if($user->hasRole(['developer', 'teamlead']))
        {
            $projects   = $user->projects;
        }
        else
        {
            $projects   = Project::all();
        }

        $view   = View::make('home');
        if($user->hasRole(['developer', 'teamlead']))
        {
            $view->nest('dashboard', 'dashboard.engineers', compact('projects'));
        }
        else
        {
            $view->nest('dashboard', 'dashboard.sales', compact('projects'));
        }

        return $view;
    }
}
