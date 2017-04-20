<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Project;
use App\Hour;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Session;
use Excel;

class HoursController extends Controller
{
    public function store(Request $request)
    {
    	$hour = new Hour;
    	$hour->project_id = $request->project_id;
    	$hour->actual_hours = $request->actual_hours;
    	$hour->productive_hours = $request->productive_hours;
		$hour->details = $request->details;
		$hour->user_id = $request->resource;
		if(empty($request->date)){
			$hour->created_at = Carbon::now()->format('Y-m-d H:i:s');
		}else{
			$hour->created_at = $request->date;
		}
    	$hour->save();
    	// echo("<pre>");
    	// print_r($request);
    	// echo("</pre>");
    	// die();
		return redirect('/projects');
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
	    $hour->actual_hours     = $request->actual_hours;
	    $hour->productive_hours = $request->productive_hours;
	    $hour->details          = $request->details;
	    $hour->user_id 			= $request->resource;
	    $hour->update();
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
        $year_month = Carbon::parse($year_month)->format("Y-m");
        // echo("<pre>");
        // print_r($year_month);
        // die();
        $user   = Auth::user();
		$users = $project->users;
		$hours = array();
		$hrs_details = $project->hours()->whereBetween("created_at", [Carbon::parse($year_month)->startOfMonth(), Carbon::parse($year_month)->endOfMonth()])->get();

        foreach ($hrs_details as $hr) {
        	$user_id = $hr->user_id;
        	if($user->hasRole(['sales']))
            {
	            $hours[]    = array(
	                'Date'				=> $hr->created_at->format('d-M'),
	                'Actual hours'      => $hr->actual_hours,
	                'Productive hours'  => $hr->productive_hours,
	                'Developer'			=> $user_id,
	                'Details'			=> $hr->details
	                );
	        } else{
	        	$hours[]    = array(
	                'Date'				=> $hr->created_at->format('d-M'),
	                'Hours'  => $hr->productive_hours,
	                'Developer'			=> $user_id,
	                'Details'			=> $hr->details
	                );
	        }
        }
        return Excel::create('hours', function($excel) use ($hours) {
            $excel->sheet('mySheet', function($sheet) use ($hours)
            {
                $sheet->fromArray($hours);
            });
        })->download('xlsx');
    }
    public function downloadExcelfilter(Request $request, Project $project)
    {
        $user   	= Auth::user();
		$users 		= $project->users;
		$hours 		= array();
		$resource 	= $request->resource;
		$from 		= $request->from;
		$to			= $request->to;
		if(!empty($to) && !empty($from) && !empty($resource)){
			$hrs_details = $project->hours()->whereBetween("created_at", [Carbon::parse($to)->format("Y-m-d"), Carbon::parse($from)->format("Y-m-d")])->where("user_id", $resource)->get();
		}
		elseif(!empty($to) && !empty($from) && empty($resource)){
			$hrs_details = $project->hours()->whereBetween("created_at", [Carbon::parse($to)->format("Y-m-d"), Carbon::parse($from)->format("Y-m-d")])->get();
		}
		elseif(empty($request->to) && empty($request->from) && !empty($request->resource)){
			$hrs_details = $project->hours()->where("user_id", $resource)->get();
		}
		else{
			Session::flash('export_msg', 'All fields empty!');
        	Session::flash('alert-class', 'alert-danger');
        	return Redirect::to('/projects/'.$project['id']);
		}

        foreach ($hrs_details as $hr) {
        	$user_id = $hr->user_id;
        	if($user->hasRole(['sales']))
            {
	            $hours[]    = array(
	                'Date'				=> $hr->created_at->format('d-M'),
	                'Actual hours'      => $hr->actual_hours,
	                'Productive hours'  => $hr->productive_hours,
	                'Developer'			=> $user_id,
	                'Details'			=> $hr->details
	                );
	        } else{
	        	$hours[]    = array(
	                'Date'				=> $hr->created_at->format('d-M'),
	                'Hours'  => $hr->productive_hours,
	                'Developer'			=> $user_id,
	                'Details'			=> $hr->details
	                );
	        }
        }
        return Excel::create('hours', function($excel) use ($hours) {
            $excel->sheet('mySheet', function($sheet) use ($hours)
            {
                $sheet->fromArray($hours);
            });
        })->download('xlsx');
    }
}
