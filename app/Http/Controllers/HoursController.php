<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Project;
use App\Hour;
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
		if(empty($request->date)){
			$hour->created_at = Carbon::now()->format('Y-m-d H:i:s');
		}else{
			$hour->created_at = $request->date;
		}
    	$hour->save();
		return redirect('/projects');
    }

	public function show(Project $project, $month)
	{
		$hrs = $project->hours()->get();
		/*foreach ($hrs as $hour) {
        	if(Carbon::parse($hour[0]['created_at'])->format('F') == $month)
            
        }*/
		// $returnHTML = View::make('hours._index',compact('hrs_details'))->render();
		return response()->json(array('success' => true, 'hrs'=>$hrs, 'month'=>$month));

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
	    $hour->update();
	    return response()->json(array('success' => true, 'hours' => $hour));
	}
}
