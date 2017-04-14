<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Project;
use App\Hour;
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
    	$hour->save();
		return redirect('/projects');
    }

	public function show(Project $project)
	{
		$hrs_details = $project->hours()->get();
		$returnHTML = View::make('hours._index',compact('hrs_details'))->render();
		return response()->json(array('success' => true, 'html'=>$returnHTML));

	}
	public function update($id)
	{
	    // $hour = Hour::findOrFail($id);
	    // $hour->actual_hours     = Input::get('actual_hours');
	    // $hour->productive_hours = Input::get('productive_hours');
	    // $hour->details          = Input::get('details');
	    // $hour->save();
	    return response()->json(array('success' => true));
	}
}
