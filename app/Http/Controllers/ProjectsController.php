<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\View;
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
use Session;
use Excel;

class ProjectsController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $projects = array();

        if($user->hasRole(['developer', 'teamlead', 'engineer']))
        {
            $projects   = $user->projects()->get();
            foreach ($projects as $project) {
                $teamleads = array();
                foreach ($project->teamlead as $teamlead) {
                    $teamleads[] = $teamlead->name;
                }
                $project->teamlead = implode('<br />', $teamleads);
                $developers = array();
                foreach ($project->developers as $developer) {
                    $developers[] = $developer->name;
                }
                $project->developers = implode('<br />', $developers);
            }
        }
        else
        {
            $projects['active'] = Project::where('status', 1)->get();
            $projects['inactive'] = Project::where('status', 0)->get();

            foreach ($projects['active'] as $project) {
                $teamleads = array();
                foreach ($project->teamlead as $teamlead) {
                    $teamleads[] = $teamlead->name;
                }
                $project->teamlead = implode('<br />', $teamleads);

                $developers = array();
                foreach ($project->developers as $developer) {
                    $developers[] = $developer->name;
                }
                $project->developers = implode('<br />', $developers);
            }

            foreach ($projects['inactive'] as $project) {
                $teamleads = array();
                foreach ($project->teamlead as $teamlead) {
                    $teamleads[] = $teamlead->name;
                }
                $project->teamlead = implode('<br />', $teamleads);

                $developers = array();
                foreach ($project->developers as $developer) {
                    $developers[] = $developer->name;
                }
                $project->developers = implode('<br />', $developers);
            }
        }

        $view   = View::make('project.index');
        if($user->hasRole(['developer', 'teamlead', 'engineer']))
        {
            $view->nest('project', 'project.engineers', compact('projects'));
        }
        else
        {
            $view->nest('project', 'project.sales', compact('projects'));
        }

        return $view;
    }

    public function show($id)
    {
        $project    = Project::find($id);
        $hours = array();

        foreach ($project->hours->groupBy(function($d) {
             return Carbon::parse($d->created_at)->format('m');
        }) as $hour) {
            $hours[]    = array(
                'month'             => Carbon::parse($hour[0]['created_at'])->format('F'),
                'year'              => Carbon::parse($hour[0]['created_at'])->format('Y'),
                'actual_hours'      => $hour->sum('actual_hours'),
                'productive_hours'  => $hour->sum('productive_hours')
                );
        }
        $teamleads   = array();
        foreach ($project->teamlead as $teamlead) {
            $teamleads[]  = $teamlead->name;
        }
        $project->teamlead  = implode('<br />', $teamleads);

        $developers = array();
        foreach ($project->developers as $developer) {
            $developers[]    = $developer->name;
        }
        $project->developers    = implode('<br />', $developers);

        $users = $project->users;

    	return view('project.view', compact('project', 'hours', 'users'));
    }
    public function downloadExcel($id, $type)
    {
        $project    = Project::find($id);
        $hours = array();
        $user   = Auth::user();
        foreach ($project->teamlead as $teamlead) {
            $teamleads[]  = $teamlead->name;
        }
        if(!empty($teamleads)){
            $project->teamlead  = implode(', ', $teamleads);
        }else{
            $project->teamlead  = "N/A";
        }
        $developers = array();
        foreach ($project->developers as $developer) {
            $developers[]    = $developer->name;
        }
        if(!empty($developers)){
            $project->developers    = implode(', ', $developers);
        }else{
            $project->developers  = "N/A";
        }
        foreach ($project->hours->groupBy(function($d) {
             return Carbon::parse($d->created_at)->format('m');
        }) as $hour) {
            if($user->hasRole(['sales']))
            {
                $hours[]    = array(
                    'Month'             => Carbon::parse($hour[0]['created_at'])->format('F - Y'),
                    'Teamlead'          => $project->teamlead,
                    'Developer'         => $project->developers,
                    'Hours'             => $hour->sum('productive_hours')
                    );
            } else{
                $hours[]    = array(
                    'Month'             => Carbon::parse($hour[0]['created_at'])->format('F - Y'),
                    'Teamlead'          => $project->teamlead,
                    'Developer'         => $project->developers,
                    'Actual hours'      => $hour->sum('actual_hours'),
                    'Productive hours'  => $hour->sum('productive_hours')
                    );
            }
        }
        return Excel::create($project->name, function($excel) use ($hours, $project) {
            $excel->sheet($project->name, function($sheet) use ($hours)
            {
                $sheet->fromArray($hours);
            });
        })->download($type);
    }
    public function create()
    {
        /*$developers = User::whereHas('roles', function($r){
            return $r->whereIn('name', ['developer', 'teamlead']);
        })->get();*/
        $developers = Role::findByName('developer')->users;
        $teamleads  = Role::findByName('teamlead')->users;
        return view("project.create", compact('developers', 'teamleads'));
    }

   
    public function store(Request $request)
    {
        $rules = array(
            'name'       => 'required|unique:projects|max:255',
            'status' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/projects/create')
                ->withErrors($validator)
                ->withInput();
        }

        $project = new Project;
        $project->name = $request->name;
        $project->technology = json_encode($request->technology);
        $project->description = $request->description;
        $project->status = $request->status;
        $project->internal_deadline = $request->internal_deadline;
        $project->external_deadline = $request->external_deadline;

        $project->save();

        if( !empty($request->teamlead) ) {
            $project->users()->attach($request->teamlead);
        }
        if  ( !empty($request->developer) )
        {
            foreach($request->developer as $developer)
            {
                $project->users()->attach($developer);
            }
        }

        return redirect('/projects');
    }

    public function edit(Project $project)
    {
        // $project    = Project::find($project);

        /*$developers = User::whereHas('roles', function($r){
            return $r->whereIn('name', ['developer', 'teamlead']);
        })->get();*/
        $developers = Role::findByName('developer')->users;
        $teamleads  = Role::findByName('teamlead')->users;

        $project->teamlead  = ! empty ($project->teamlead) ? $project->teamlead->pluck('id')->toArray(): array();
        $project->developers = ! empty ($project->developers) ? $project->developers->pluck('id')->toArray(): array();

        return view('project.edit', compact('project','teamleads','developers'));
    }

    public function update( Request $request , $id)
    {
        $rules = array(
            'name'       => 'required|unique:projects,name,'.$id.'|max:255',
            'status'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('/projects/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $project    = Project::find($id);

        $project->name = $request->name;
        $project->technology = json_encode($request->technology);
        $project->description = $request->description;

        $project->status = $request->status;
        $project->internal_deadline = $request->internal_deadline;
        $project->external_deadline = $request->external_deadline;
        $project->update();
        $project->users()->detach();
        if(! empty($request->teamlead) )
        {
            $project->users()->attach($request->teamlead);
        }
        if(! empty($request->developer))
        {
            foreach($request->developer as $developer)
            {
                $project->users()->attach($developer);
            }
        }
        return redirect('/projects');
    }

    public function destroy($id)
    {   
        $project    = Project::find($id);
        $project->delete();

        Session::flash('message', 'Successfully deleted the Project!');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/projects');
    }

}
