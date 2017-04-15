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

        foreach ($projects as $project) {
            $teamleads   = array();
            foreach ($project->teamlead as $teamlead) {
                $teamleads[]  = $teamlead->name;
            }
            $project->teamlead  = implode('<br />', $teamleads);

            $developers = array();
            foreach ($project->developers as $developer) {
                $developers[]    = $developer->name;
            }
            $project->developers    = implode('<br />', $developers);
        }

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
