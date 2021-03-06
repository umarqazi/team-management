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
        if ($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend'])) {
            $projects = $user->projects()->where('status', 1)->orderBy('created_at','desc')->paginate(10);
        } else {
            $projects = Project::where('status', 1)->orderBy('created_at','desc')->paginate(10);
            $allProjects = Project::where('status', 1)->orderBy('created_at','desc')->get();
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

            $consumed_hours = $project->hours()->sum('consumed_hours');
            if ( empty($consumed_hours)) {
                $consumed_hours = 0;
            }
            $estimated_hours = $project->hours()->sum('estimated_hours');
            if ( empty($estimated_hours)) {
                $estimated_hours = 0;
            }
            $datapoints[0][] = array("y"=> $estimated_hours, "label" => ucwords($project->name));
            if( !$user->hasRole('sales'))
            {
                $datapoints[1][] = array("y"=> $consumed_hours, "label" => ucwords($project->name) );
            }
        }
        $view   = View::make('home');
        if($user->hasRole(['developer', 'teamlead', 'engineer','pm', 'frontend']))
        {
            $view->nest('dashboard', 'dashboard.engineers', compact('projects','datapoints'));
        }
        elseif($user->hasRole('admin'))
        {
            $view->nest('dashboard', 'dashboard.admin', compact('projects','datapoints', 'allProjects'));
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
            $project_hours = Hour::select(array(DB::Raw('project_id'), DB::Raw('sum(estimated_hours) as estimated_hours'), DB::Raw('sum(consumed_hours) as consumed_hours'), DB::Raw('DATE(created_at) as day')))->where('project_id', $id)->where('user_id',$resource)->whereBetween('created_at', [Carbon::parse($year_month)->startOfMonth(), Carbon::parse($year_month)->endOfMonth()])->groupBy('day')->get();
            $resources   = $project->hours()->selectRaw('sum(consumed_hours) as consumed_hours, sum(estimated_hours) as estimated_hours ,user_id')->where('user_id', $resource)->groupBy('user_id')->get();

        }
        elseif( $resource == 0 )
        {
            $project_hours = Hour::select(array(DB::Raw('project_id'), DB::Raw('sum(estimated_hours) as estimated_hours'), DB::Raw('sum(consumed_hours) as consumed_hours'), DB::Raw('DATE(created_at) as day')))->where('project_id', $id)->whereBetween('created_at', [Carbon::parse($year_month)->startOfMonth(), Carbon::parse($year_month)->endOfMonth()])->groupBy('day')->get();

            $resources   = $project->hours()->selectRaw('sum(consumed_hours) as consumed_hours, sum(estimated_hours) as estimated_hours ,user_id')->whereIn('user_id', $project->users->pluck('id')->toArray())->groupBy('user_id')->get();
        }

        foreach($resources as $pr_resource)
        {
            //We need to add below whereBetween for the above recieved $proj_month
            $resource_hours[] = array('user' => User::find($pr_resource->user_id),'consumed_hours' => $pr_resource->consumed_hours, 'estimated_hours' => $pr_resource->estimated_hours );
        }

          $hours_details = array(0 => array(), 1 => array());

        foreach($project_hours as $project_hour)
        {
            $hours_details[0][] = array(
               'label' => $project_hour->day,
               'y' => $project_hour->estimated_hours
           );

            $hours_details[1][] = array(
                'label' => $project_hour->day,
                'y' => $project_hour->consumed_hours
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
