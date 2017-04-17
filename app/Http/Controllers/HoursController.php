<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Project;
use App\Hour;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

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
    	// print_r($hour->id);
    	// echo("</pre>");
    	// die();
		return redirect('/projects');
    }

	public function show(Project $project, $month_year)
	{
		$strg_break = explode("_", $month_year);
		$month = intval(date('m', strtotime($strg_break[0])));
		$year = $strg_break[1];
		$users = $project->users;
		$hrs_details = $project->hours()->whereBetween("created_at", [Carbon::parse($year."-".$month)->startOfMonth(), Carbon::parse($year."-".$month)->endOfMonth()])->get();
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
}
