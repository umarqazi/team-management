<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
// use DB;
use App\Http\Requests;
use \App\Project;
use \App\Hour;
use Carbon\Carbon;
// use Session;

class ProjectsController extends Controller
{
    public function index()
    {
    	$projects = Project::paginate(8);

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

    public function show(Project $project)
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
          $this->validate($request, [
           'name' => 'required|unique:projects|max:255',
           'technology' => 'required',
           'teamlead' => 'required',
           'developer' => 'required',           
       ]);


		$project = new Project; 
		$project->name = $request->name;
		$project->technology = $request->technology;
		$project->teamlead = $request->teamlead;
		$project->developer = $request->developer;
		$project->description = $request->description;
		$project->save();
		return redirect('/projects'); 		
    }

    public function edit(Project $project)
    {
       return view('project.edit', compact('project'));
    }

    public function update( Request $request , Project $project)
    {
        $this->validate($request, [
           'name' => 'required|unique:projects|max:255',
           'technology' => 'required',
           'teamlead' => 'required',
           'developer' => 'required',           
       ]);

         $project->update($request->all());
         return redirect('/projects');
    }

    public function destroy(Project $project)
    {   
         $project->delete();       
         return redirect('/projects');
    }

}
