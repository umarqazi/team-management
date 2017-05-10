<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use App\Hour;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function getHours(Request $request ,$id, $proj_month, $resource = 0)
    {
        $project    = Project::find($id);
        $year_month 	= Carbon::parse($proj_month)->format("Y-m");
        $project_hours = array();
        $resource_hours = array();

        if($request->ajax() && ! empty($resource))
        {
            $project_hours = Hour::select(array(DB::Raw('project_id'), DB::Raw('sum(productive_hours) as productive_hours'), DB::Raw('sum(actual_hours) as actual_hours'), DB::Raw('DATE(created_at) as day')))->where('project_id', $id)->where('user_id',$resource)->whereBetween('created_at', [Carbon::parse($year_month)->startOfMonth(), Carbon::parse($year_month)->endOfMonth()])->groupBy('day')->get();
            $resources   = $project->hours()->selectRaw('sum(actual_hours) as actual_hours, sum(productive_hours) as productive_hours ,user_id')->where('user_id', $resource)->groupBy('user_id')->get();

        }
        elseif( $resource == 0 )
        {
            $project_hours = Hour::select(array(DB::Raw('project_id'), DB::Raw('sum(productive_hours) as productive_hours'), DB::Raw('sum(actual_hours) as actual_hours'), DB::Raw('DATE(created_at) as day')))->where('project_id', $id)->whereBetween('created_at', [Carbon::parse($year_month)->startOfMonth(), Carbon::parse($year_month)->endOfMonth()])->groupBy('day')->get();

            $resources   = $project->hours()->selectRaw('sum(actual_hours) as actual_hours, sum(productive_hours) as productive_hours ,user_id')->whereIn('user_id', $project->users->pluck('id')->toArray())->groupBy('user_id')->get();
        }

        foreach($resources as $pr_resource)
        {
            //We need to add below whereBetween for the above recieved $proj_month
            $resource_hours[] = array('user' => User::find($pr_resource->user_id),'actual_hours' => $pr_resource->actual_hours, 'productive_hours' => $pr_resource->productive_hours );
        }

          $hours_details = array(0 => array(), 1 => array());

        foreach($project_hours as $project_hour)
        {
            $hours_details[0][] = array(
               'label' => $project_hour->day,
               'y' => $project_hour->productive_hours
           );

            $hours_details[1][] = array(
                'label' => $project_hour->day,
                'y' => $project_hour->actual_hours
            );
        }
//        return response()->json(array('success' => true, 'response' => $hours));
        echo json_encode(array('hours' => $hours_details, 'resources' => $resource_hours),JSON_NUMERIC_CHECK);

        }


    public function getUserHours(Request $request,$id,$resource_id)
    {
        $project    = Project::find($id);
        $resource_hours = $project->hours()->where("user_id",$resource_id)->get();
        echo json_encode($resource_hours ,JSON_NUMERIC_CHECK);
    }
}
