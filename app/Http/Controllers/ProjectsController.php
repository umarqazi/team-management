<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \App\Project;
use \App\Hour;


class ProjectsController extends Controller
{
    public function index()
    {
    	$projects = Project::all();

        // $hours = Hour::with('project')->get();
        // echo "<pre>";
        // echo "<br>";
        // print_r($hours);
        // echo "</pre>";
        // die();

        // foreach ($results as $table2record) {
        //   echo $table2record->id; //access table2 data
        //   echo $table2record->project->booktitle; //access table1 data
        // }



        // foreach ($projects as $project) {
        //     echo $hours->id;
        //     echo $hours->productive_hours;
        // }
        // die();
        
        // echo "<pre>";
        // echo "<br>";
        // print_r($hours);
        // echo "</pre>";

        // die();
        // Hour::where('project_id', $projects);

    	$user = "engineering_admin";
    	// $user = "CEO";

    	if($user == "engineering_admin")
    	{
    		return view('project.eng_view', compact('projects')) ;
    	}
    	else
    	{
    		// die("here");
    		return view('project.index', compact('projects')) ;
    	}   	
    }

    public function project_view(project $project)
    {
        $hour = hour::where('project_id', $project->id)->first();
    	 return view('project.project_view', compact('project','hour'));
    }

    public function create()
    {
    	return view("project.create");
    }

    public function store(Request $request)
    {
    	// echo "<pre>";
    	// echo "<br>";
    	// print_r($request->name);
    	// echo "</pre>";

    	// die();


		$project = new Project; 
		$project->name = $request->name;
		$project->technology = $request->technology;
		$project->teamlead = $request->teamlead;
		$project->developer = $request->developer;
		$project->description = $request->description;
		$project->save();
		return redirect('projects'); 		
    }

    // public function edit(project $project)
    // {
    //     return view('project.edit_project', compact('project'));
    // }


    //    public function update( Request $request , category $category)
    // {
    //     $this->validate($request, [
    //         'name' => 'bail|required|max:255',
    //     ]); 

    //      $category->update($request->all());
    //      return redirect('/admin/categories');
    // }


    // public function destroy(project $project)
    // {   
    //      $project->delete();       
    //      return redirect('projects/index');
    // }

}
