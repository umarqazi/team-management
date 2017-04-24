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

    public function getHours(Request $request ,$id, $proj_month)
    {
//        if($request->ajax()) {
//            return response()->json(array('success' => true, 'response' => $proj_month));
//        }


        $hours 			= array();
        $project    = Project::find($id);
        $year_month 	= Carbon::parse($proj_month)->format("Y-m");
        $hrs_details 	= $project->hours()->whereBetween("created_at", [Carbon::parse($year_month)->startOfMonth(), Carbon::parse($year_month)->endOfMonth()])->get();

        $hours_details  = array();
        foreach($hrs_details as $hr_detail)
        {
            $hours_details[]  = array(
                'label' => Carbon::parse($hr_detail->created_at)->format("Y, m, d"),
                'y' => $hr_detail->productive_hours
            );
        }

//        return response()->json(array('success' => true, 'response' => $hours));
        echo json_encode($hours_details, JSON_NUMERIC_CHECK);

//        return view('project.view', compact('project', 'hours', 'users'));
    }
}
