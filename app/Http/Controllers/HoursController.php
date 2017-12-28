<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Project;
use App\Hour;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Excel;

class HoursController extends Controller
{
    public function store(Request $request)
    {
    	$hour = new Hour;
    	$hour->project_id   	= $request->project_id;
    	$hour->consumed_hours 	= $request->consumed_hours;
    	$hour->estimated_hours = $request->estimated_hours;
		$hour->details 			= $request->details;
		$hour->user_id 			= $request->resource;
		if(empty($request->date)){
			$hour->created_at = Carbon::now()->format('Y-m-d H:i:s');
		}else{
			$hour->created_at = $request->date;
		}
    	$hour->save();
		return redirect('/projects');
    }

    public function storeDeveloperTaskEstimation(Request $request){

        $rules = array(
            'dev_estimated_hours' => 'required|integer',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('/tasks/'.$request->task_id)
                ->withErrors($validator);
        }
        else {

            $hour = Hour::where([
                ['task_id', $request->task_id],
                ['subtask_id', null]
            ])->first();

            $hour->internal_hours = $request->dev_estimated_hours;
            $hour->update();

            // redirect
            Session::flash('message', 'Task Successfully Estimated!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/tasks/'.$request->task_id);
        }
    }

    public function storeDeveloperSubtaskEstimation(Request $request)
    {
        $rules = array(
            'dev_estimated_hours' => 'required|integer',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('/subtasks/'.$request->subtask_id)
                ->withErrors($validator);
        } else {

            $hour = Hour::where([
                ['subtask_id', $request->subtask_id]
            ])->first();

            $hour->internal_hours = $request->dev_estimated_hours;
            $hour->update();

            // redirect
            Session::flash('message', 'Subtask Successfully Estimated!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/subtasks/'.$request->subtask_id);
        }
    }

    public function storeTaskConsumption(Request $request){

        $rules = array(
            'project_id' => 'required|integer',
            'task_id' => 'required|integer',
            'consumed_hours' => 'required|integer',
            'task_internal_hours' => 'required|integer',
            'task_estimated_hours' => 'required|integer',
            'resource' => 'required|integer',
            'date' => 'date',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('/tasks/' . $request->task_id)
                ->withErrors($validator);
        } else {
            $hour = new Hour;
            $hour->project_id = $request->project_id;
            $hour->task_id = $request->task_id;
            $hour->consumed_hours = $request->consumed_hours;
            $hour->internal_hours = $request->task_internal_hours;
            $hour->estimated_hours = $request->task_estimated_hours;
            $hour->user_id = $request->resource;

            if (empty($request->date)) {
                $hour->created_at = Carbon::now()->format('Y-m-d H:i:s');
            } else {
                $hour->created_at = Carbon::parse($request->date)->format('Y-m-d H:i:s');
            }
            $hour->save();

            // redirect
            Session::flash('message', 'Hours Successfully Added on Task!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/tasks/' . $request->task_id);
        }
    }

    public function storeSubtaskConsumption(Request $request){

        $rules = array(
            'project_id' => 'required|integer',
            'task_id' => 'required|integer',
            'subtask_id' => 'required|integer',
            'consumed_hours' => 'required|integer',
            'subtask_internal_hours' => 'required|integer',
            'subtask_estimated_hours' => 'required|integer',
            'resource' => 'required|integer',
            'date' => 'date',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('/subtasks/'. $request->subtask_id)
                ->withErrors($validator);
        } else {
            $hour = new Hour;
            $hour->project_id = $request->project_id;
            $hour->task_id = $request->task_id;
            $hour->subtask_id = $request->subtask_id;
            $hour->consumed_hours = $request->consumed_hours;
            $hour->internal_hours = $request->subtask_internal_hours;
            $hour->estimated_hours = $request->subtask_estimated_hours;
            $hour->user_id = $request->resource;

            if (empty($request->date)) {
                $hour->created_at = Carbon::now()->format('Y-m-d H:i:s');
            } else {
                $hour->created_at = Carbon::parse($request->date)->format('Y-m-d H:i:s');
            }
            $hour->save();

            // redirect
            Session::flash('message', 'Hours Successfully Added on Subtask!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/subtasks/' . $request->subtask_id);
        }
    }

	public function show(Project $project, $year_month)
	{
		$year_month = Carbon::parse($year_month)->format("Y-m");
		$users = $project->users;
		$hrs_details = $project->hours()->whereBetween("created_at", [Carbon::parse($year_month)->startOfMonth(), Carbon::parse($year_month)->endOfMonth()])->get();
		$returnHTML = View::make('hours._index')->with('hrs_details', $hrs_details)->with('users', $users)->render();
		return response()->json(array('success' => true, 'html'=>$returnHTML));

	}
	public function update(Request $request, $id)
	{
	    //print_r($request);
	    // echo(json_encode(array('id'	=> $request->all())));
	    // die();
	    $hour = Hour::findOrFail($id);
	    $hour->consumed_hours     = $request->consumed_hours;
	    $hour->estimated_hours = $request->estimated_hours;
	    $hour->details          = $request->details;
	    $hour->user_id 			= $request->resource;
		$hour->created_at 		= $request->created_at;
	    $hour->update();
	    $hour->createDate=date('d-M', strtotime($hour->created_at));
	    $project    = Project::find($hour->project_id);
	    $users = $project->users;
	    foreach ($users as $user){
	    	if($user['id'] == $hour->user_id){
	    		$hour->user_name 		= $user['name'];
	    	}
	    }
	    return response()->json(array('success' => true, 'hours' => $hour));
	}
	public function delete($id){
        $hour= Hour::find($id);
        $hour->delete();
        return response()->json(array('success' => true));
        
    }
    public function downloadExcel(Project $project, $year_month)
    {
        $user   		= Auth::user();
        $year_month 	= Carbon::parse($year_month)->format("Y-m");
		$users 			= $project->users;
		$hours 			= array();
		$hrs_details 	= $project->hours()->whereBetween("created_at", [Carbon::parse($year_month)->startOfMonth(), Carbon::parse($year_month)->endOfMonth()])->get();

        foreach ($hrs_details as $hr) {
        	$user_id = $hr->user_id;
        	if($user->hasRole(['admin', 'teamlead']))
            {
	            $hours[]    = array(
	                'Date'				=> $hr->created_at->format('d-M'),
	                'Consumed hours'      => $hr->consumed_hours,
	                'Estimated hours'  => $hr->estimated_hours,
	                'Developer'			=> !empty($hr->user_id) ? $hr->user->name:"N/A",
	                'Details'			=> $hr->details
	                );
	        }elseif($user->hasRole('sales')){
	        	$hours[]    = array(
	                'Date'				=> $hr->created_at->format('d-M'),
	                'Hours'  			=> $hr->estimated_hours,
	                'Developer'			=> !empty($hr->user_id) ? $hr->user->name:"N/A",
	                'Details'			=> $hr->details
	                );
	        }else
	        {
	        	$hours 	= array();
	        }
        }
        return Excel::create('hours '.$year_month, function($excel) use ($hours) {
            $excel->sheet('mySheet', function($sheet) use ($hours)
            {
                $sheet->fromArray($hours);
            });
        })->download('xlsx');
    }
    public function downloadExcelfilter(Request $request, Project $project)
    {
        $user   		= Auth::user();
		$users 			= $project->users;
		$resource		= "";
		if( ! empty($request->resource))
		{
			$resource	= User::find($request->resource)->name;
		}
		$hours 			= array();
		if(!empty($request->to) && !empty($request->from) && !empty($request->resource)){
			$hrs_details = $project->hours()->whereBetween("created_at", [Carbon::parse($request->from." 00:00:00")->format("Y-m-d"), Carbon::parse($request->to." 23:59:59")->format("Y-m-d")])->where("user_id", $request->resource)->get();
			$file_name = "hours-".$project->name."-".Carbon::parse($request->to)->format("d-m")."-".Carbon::parse($request->from)->format("d-m")."-".$resource;
		}
		elseif(!empty($request->to) && !empty($request->from) && empty($request->resource)){
			$hrs_details = $project->hours()->whereBetween("created_at", [Carbon::parse($request->from." 00:00:00")->format("Y-m-d"), Carbon::parse($request->to." 23:59:59")->format("Y-m-d")])->get();
			$file_name = "hours-".$project->name."-".Carbon::parse($request->to)->format("d-m")."-".Carbon::parse($request->from)->format("d-m");
		}
		elseif(empty($request->to) && empty($request->from) && !empty($request->resource)){
			$hrs_details = $project->hours()->where("user_id", $request->resource)->get();
			$file_name = "hours-".$project->name."-".$resource;
		}
		else{
			Session::flash('export_msg', 'All fields empty!');
        	Session::flash('alert-class', 'alert-danger');
        	return Redirect::to('/projects/'.$project['id']);
		}

        foreach ($hrs_details as $hr) {
        	if($user->hasRole(['admin', 'teamlead']))
            {
	            $hours[]    = array(
	                'Date'				=> Carbon::parse($hr->created_at)->format('d-M'),
	                'Actual hours'      => $hr->consumed_hours,
	                'Productive hours'  => $hr->estimated_hours,
	                'Developer'			=> !empty($hr->user_id) ? $hr->user->name:"N/A",
	                'Task'				=> $hr->details
	                );
	        } 
	        elseif($user->hasRole('sales'))
	        {
	        	$hours[]    = array(
	                'Date'				=> Carbon::parse($hr->created_at)->format('d-M'),
	                'Hours'  			=> $hr->estimated_hours,
	                'Developer'			=> !empty($hr->user_id) ? $hr->user->name:"N/A",
	                'Task'				=> $hr->details
	                );
	        }
	        else
	        {
	        	$hours 	= array();
	        }
        }
        return Excel::create($file_name, function($excel) use ($hours, $project) {
            $excel->sheet($project->name, function($sheet) use ($hours)
            {
                $sheet->fromArray($hours);
            });
        })->download('xlsx');
    }
}
