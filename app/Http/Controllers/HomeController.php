<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use App\Http\Requests;
use Carbon\Carbon;
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

        $user = Auth::user();
        if ($user->hasRole(['developer', 'teamlead', 'engineer'])) {
            $projects = $user->projects()->where('status', 1)->orderBy('created_at','desc')->paginate(10);
        } else {
            $projects = Project::where('status', 1)->orderBy('created_at','desc')->paginate(10);
        }
        foreach ($projects as $project) {
            $teamleads = array();
            foreach ($project->teamlead as $teamlead) {
                $teamleads[] = $teamlead->name;
            }
            $project->teamlead = implode('<br />', $teamleads);

            $developers = array();
            foreach ($project->developers as $developer) {
                $developers[] = $developer->name;
            }
            $project->developers = implode('<br />', $developers);

            $actual_hours = $project->hours()->sum('actual_hours');
            if ( empty($actual_hours)) {
                $actual_hours = 0;
            }
            $productive_hours = $project->hours()->sum('productive_hours');
            if ( empty($productive_hours)) {
                $productive_hours = 0;
            }
            $datapoints[] = array("y"=> $actual_hours, "label" => ucwords($project->name) );
            $datapoints2[] = array("y"=> $productive_hours, "label" => ucwords($project->name));
        }

        $view   = View::make('home');
        if($user->hasRole(['developer', 'teamlead', 'engineer']))
        {
            $view->nest('dashboard', 'dashboard.engineers', compact('projects','datapoints','datapoints2'));
        }
        elseif($user->hasRole('admin'))
        {
            $view->nest('dashboard', 'dashboard.admin', compact('projects','datapoints','datapoints2'));
        }
        else
        {
            $view->nest('dashboard', 'dashboard.sales', compact('projects','datapoints2'));
        }

        return $view;
    }
}
