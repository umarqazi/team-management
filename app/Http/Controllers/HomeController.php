<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use App\Hour;
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
        $datapoints = array( 0 => array(), 1 => array());
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
            $datapoints[0][] = array("y"=> $productive_hours, "label" => ucwords($project->name));
            if( !$user->hasRole('sales'))
            {
                $datapoints[1][] = array("y"=> $actual_hours, "label" => ucwords($project->name) );
            }
        }
        $view   = View::make('home');
        if($user->hasRole(['developer', 'teamlead', 'engineer']))
        {
            $view->nest('dashboard', 'dashboard.engineers', compact('projects','datapoints'));
        }
        elseif($user->hasRole('admin'))
        {
            $view->nest('dashboard', 'dashboard.admin', compact('projects','datapoints'));
        }
        else
        {
            $view->nest('dashboard', 'dashboard.sales', compact('projects','datapoints'));
        }

        return $view;
    }

    public function getHours(Request $request ,$id)
    {
//        if($request->ajax()) {
//            return response()->json(array('success' => true, 'response' => $id));
//        }

        $project    = Project::find($id);
        $hours = array();

        foreach ($project->hours->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('m');
        }) as $hour) {
            $hours[]    = array(
                'month'             => Carbon::parse($hour[0]['created_at'])->format('F'),
                'year'              => Carbon::parse($hour[0]['created_at'])->format('Y'),
                'actual_hours'      => $hour->sum('actual_hours'),
                'productive_hours'  => $hour->sum('productive_hours')
            );
        }
        return response()->json(array('success' => true, 'response' => $hours));

//        return view('project.view', compact('project', 'hours', 'users'));
    }

}
