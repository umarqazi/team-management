<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \App\Project;

class ProjectsController extends Controller
{
    public function index()
    {
    	$projects = Project::all();
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
    	// var_dump($project);
    	 return view('project.project_view', compact('project'));
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
