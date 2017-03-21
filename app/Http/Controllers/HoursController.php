<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Hour;

class HoursController extends Controller
{
    public function store(Request $request)
    {
    	// die($request->project_id);
    	$hour = new Hour; 
    	$hour->project_id = $request->project_id;
    	$hour->actual_hours = $request->actual_hours;
    	$hour->productive_hours = $request->productive_hours;
    	$hour->save();
		return redirect('projects'); 
    }
}
