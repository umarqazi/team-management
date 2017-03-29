<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
// use DB;
use App\Http\Requests;
use \App\Project;
use \App\Hour;
use \App\User;
use Carbon\Carbon;
use Auth;
// use Session;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
       // print_r(Auth::user()->id) ;
       // die();
    	$projects = Project::paginate(8);

        foreach ($projects as $project) {
            $teamleads   = array();
            foreach ($project->teamlead as $teamlead) {
                $teamleads[]  = $teamlead->name;
            }
            $project->teamlead  = implode(", ", $teamleads);

            $developers = array();
            foreach ($project->developers as $developer) {
                $developers[]    = $developer->name;
            }
            $project->developers    = implode(", ", $developers);
        }

        $hours = Hour::all();

//    	$user = "engineering_admin";
//    	 $user = "CEO";

        $user   = Auth::user();

    	if($user->hasRole("teamlead") || $user->hasRole("developer"))
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
        $teamleads   = array();
        foreach ($project->teamlead as $teamlead) {
            $teamleads[]  = $teamlead->name;
        }
        $project->teamlead  = implode(", ", $teamleads);

        $developers = array();
        foreach ($project->developers as $developer) {
            $developers[]    = $developer->name;
        }
        $project->developers    = implode(", ", $developers);

    	return view('project.project_view', compact('project', 'hours'));
    }

    public function create()
    {
        $developers = Role::findByName('developer')->users;
        $teamleads  = Role::findByName('teamlead')->users;
        return view("project.create", compact('developers', 'teamleads'));
    }

   
    public function store(Request $request)
        {
            $rules = array(
                'name'       => 'required|unique:projects|max:255',
                'teamlead'   => 'required',
                'developer'  => 'required'
            );
            $validator = Validator::make(Input::all(), $rules);

            // process the login
            if ($validator->fails()) {
                return Redirect::to('/project/create')
                    ->withErrors($validator)
                    ->withInput();
            }
            $project = new Project;
            $project->name = $request->name;
            $project->technology = $request->technology;
            // $project->teamlead = $request->teamlead;
            // $project->developer = $request->developer;
            $project->description = $request->description;

            $project->save();
            // $project->users()->attach(Auth::user()->id);
            $project->users()->attach($request->teamlead);
            $project->users()->attach($request->developer);
            return redirect('/projects');
    }

    public function edit(Project $project)
    {
        $developers = Role::findByName('developer')->users;
        $teamleads  = Role::findByName('teamlead')->users;

        $project->teamlead  = ! empty ($project->teamlead[0]) ? $project->teamlead[0]: "";
        $project->developer = ! empty ($project->developers[0]) ? $project->developers[0]: "";

        return view('project.edit', compact('project','teamleads','developers'));
    }

    public function update( Request $request , Project $project)
    {
        $rules = array(
            'name'       => 'required|unique:projects,name,'.$project->id.'|max:255',
            'teamlead'   => 'required',
            'developer'  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('/project/'.$project->id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $project->name = $request->name;
        $project->technology = $request->technology;
        $project->description = $request->description;
        $project->update();
        $project->users()->detach();
        $project->users()->attach($request->teamlead);
        $project->users()->attach($request->developer);
        return redirect('/projects');
    }

    public function destroy(Project $project)
    {   
         $project->delete();       
         return redirect('/projects');
    }

}
