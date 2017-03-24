<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use DB;
use App\Http\Requests;
use \App\Project;
use \App\Hour;
use Carbon\Carbon;


class ProjectsController extends Controller
{
    public function index()
    {
    	$projects = Project::all();

        $hours = Hour::all();

    	$user = "engineering_admin";
    	// $user = "CEO";

    	if($user == "engineering_admin")
    	{
    		return view('project.eng_view', compact('projects')) ;
    	}
    	else
    	{
    		return view('project.index', compact('projects','hours')) ;
    	}   	
    }

    public function project_view(Project $project)
    {
        $hours = array();

        foreach ($project->hours->groupBy(function($d) {
             return Carbon::parse($d->created_at)->format('m');
        }) as $hour) {
            $hours[]    = array(
                'month'             => Carbon::parse($hour[0]['created_at'])->format('F'),
                'actual_hours'      => $hour->sum('actual_hours'),
                'productive_hours'  => $hour->sum('productive_hours')
                );
        }

    	return view('project.project_view', compact('project', 'hours'));
    }

    public function create()
    {
    	return view("project.create");
    }

    public function store(Request $request)
    {
		$project = new Project; 
		$project->name = $request->name;
		$project->technology = $request->technology;
		$project->teamlead = $request->teamlead;
		$project->developer = $request->developer;
		$project->description = $request->description;
		$project->save();
		return redirect('project'); 		
    }

}
