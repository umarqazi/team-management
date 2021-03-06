<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        // dd($user);
        $projects = array();
        


        if($user->hasRole(['developer', 'teamlead', 'engineer','frontend']))
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
        if($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend']))
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
                'consumed_hours'      => $hour->sum('consumed_hours'),
                'estimated_hours'  => $hour->sum('estimated_hours')
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
        $currentDate=Carbon::now()->format('Y-m-d');
    	return view('project.view', compact('project', 'hours', 'users', 'currentDate'));
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
                    'Hours'             => $hour->sum('estimated_hours')
                    );
            } else{
                $hours[]    = array(
                    'Month'             => Carbon::parse($hour[0]['created_at'])->format('F - Y'),
                    'Teamlead'          => $project->teamlead,
                    'Developer'         => $project->developers,
                    'Actual hours'      => $hour->sum('consumed_hours'),
                    'Productive hours'  => $hour->sum('estimated_hours')
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
            'external_deadline' => 'after:internal_deadline',
            'key' => 'required|unique:projects',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/projects/create')
                ->withErrors($validator)
                ->withInput();
        }

        else {
            $project = new Project;
            $project->name = $request->name;
            $project->technology = json_encode($request->technology);
            $project->description = $request->description;
            $project->status = $request->status;
            $project->internal_deadline = date('Y-m-d H:i:s', strtotime($request->internal_deadline));
            $project->external_deadline = date('Y-m-d H:i:s', strtotime($request->external_deadline));
            $project->key = $request->key;

            $project->save();

            if (!empty($request->teamlead)) {

                if (is_array($request->teamlead)) {
                    foreach ($request->teamlead as $teamlead) {
                        $project->users()->attach($teamlead);
//                  dd(User::where('id',$teamlead)->pluck('email')->first());
                    }
                } else {
                    $project->users()->attach($request->teamlead);
                }
            }
            if (!empty($request->developer)) {
                foreach ($request->developer as $developer) {
                    $project->users()->attach($developer);
                }
            }

            // Email Notification

            Mail::send('mail.project', array(
                'projectName' => $request->name,
                'teamleadName' => $request->teamlead,
                'description' => $request->description,
                'int_deadline' => date('Y-m-d H:i:s', strtotime($request->internal_deadline)),
                'ext_deadline' => date('Y-m-d H:i:s', strtotime($request->external_deadline))), function ($message) {
                $message->to('umarfarooq.hcp@gmail.com', 'Techverx Management');
                $message->from('umarfarooq1857@gmail.com');

                $message->subject('Project Notification via Email');
            });


            // redirect
            Session::flash('message', 'Successfully Created A Project ' . $request->name);
            Session::flash('alert-class', 'alert-success');
            return redirect('/projects');
        }
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
            'status'     => 'required',
            'key' => 'required',
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
        $project->key = $request->key;
        $project->update();
        $project->users()->detach();
        if( ! empty($request->teamlead) )
        {
            foreach($request->teamlead as $teamlead)
            {
                $project->users()->attach($teamlead);
            }
        }
        if( ! empty($request->developer) )
        {
            foreach($request->developer as $developer)
            {
                $project->users()->attach($developer);
            }
        }

        // redirect
        Session::flash('message', 'Successfully Updated A Project '.$request->name);
        Session::flash('alert-class', 'alert-success');
        return redirect('/projects');
    }

    public function destroy($id)
    {   
        $project    = Project::find($id);
        $projectName = $project->name;
        $project->delete();

        Session::flash('message', 'Successfully Deleted A Project '.$projectName);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/projects');
    }

    //Other Functions for Main Project View and Other Views BY UMAR FAROOQ

    public function getMainView()
    {
        return view('tasks.mainProjectView');
    }

    public function getDetailView()
    {
        return view('tasks.taskDetail');
    }
}
