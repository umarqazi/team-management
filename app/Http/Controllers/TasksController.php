<?php

namespace App\Http\Controllers;

use App\Hour;
use App\Http\Middleware\TaskMiddleware;
use App\Project;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\View;
use App\Http\Requests;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $projects = Project::all();
        $users = User::all();
        $task = Task::orderBy('created_at','desc')->first();
        $Project = $task->project;
        $tasks = $Project->tasks;
        $assignee = $task->users->pluck('id','name');
        $hours = $task->hours;

        return view('tasks.taskDetail', compact('projects','users','Project','tasks','task'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::all();
        $users = User::role(['teamlead','developer'])->get();
        $reporters = User::role(['pm','admin'])->get();

        return view('tasks.create', compact('projects','users', 'reporters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'project_name' => 'required|integer',
            'task_type' => 'required|string',
            'task_name' => 'required',
            'task_component' =>'alpha',
            'task_priority' =>'alpha',
            'task_duedate' =>'required|date',
            'task_assignee' =>'array',
            'task_follower' => 'integer',
            'task_reporter' => 'integer',
            'task_description' => 'string',
            'task_originalEstimate' => 'integer|min:1',
            'task_remainingEstimate' => 'integer|min:1',
            'task_tags' => 'alpha_num',
            'task_workflow' => 'string',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('tasks/create')
                ->withErrors($validator);
        }
        else {
            $task = new Task();
            $task->project_id = $request->project_name;
            $task->types = $request->task_type;
            $task->name = $request->task_name;
            $task->component = $request->task_component;
            $task->priority = $request->task_priority;
            $task->duedate = $request->task_duedate;
            $task->follower = $request->task_follower;
            $task->reporter = $request->task_reporter;
            $task->description = $request->task_description;
            $task->tags = $request->task_tags;
            $task->workflow = $request->task_workflow;

            $project = Project::where('id',$request->project_name)->first();

            // Add Task key Based on Project Key + incrementing Number

            // Get All the Tasks of Specific Project
            $ProjectTasks = $project->tasks()->get();
            // Incrementing Task-Key Value By One.

            if (collect($ProjectTasks)->isEmpty())
            {
                $num = 1;
            }
            else{
                // Get Last Task of Project
                $TaskKey = collect($ProjectTasks)->last()->key;
                $number = explode('-',$TaskKey,2);
                $num = (int)$number[1];
                $num += 1;
            }

            $taskKey = $project->key.'-'.$num;
            $task->key = $taskKey;
            $task->save();

            /*Assignee is User-> task_user*/
            /*IF Multiple Task Assignee*/
            if (is_array($request->task_assignee))
            {
                foreach ($request->task_assignee as $assignee){
                    $task->users()->attach($assignee);
                }
            }

            /*IF Single Task Assignee*/
            else{
                $task->users()->attach($request->task_assignee);
            }

            /*To Add Hours Estimate in Hours Table*/
            if (! empty($request->task_originalEstimate))
            {
                $hour = new Hour();
                $hour->task_id = Task::orderBy('created_at','desc')->pluck('id')->first();
                $hour->project_id = $request->project_name;
                $hour->estimated_hours = $request->task_originalEstimate;
                $hour->save();
            }


            // redirect
            Session::flash('message', 'Successfully created Task!');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('tasks/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Data To Populate View i-e Projects Filter, Users Filter etc
        $projects = Project::all();
        $users = User::all();

        //Other Specific Data
        $task = Task::find($id);
        $Project = $task->project;
        $tasks = $Project->tasks;
        $assignee = $task->users->pluck('id','name');
        $hours = $task->hours;

        return view('tasks.view', compact('projects','users','Project','tasks','task'));
    }

    public function showProjectSpecific($pid)
    {
        $tasks[] = [];
        $task[] = [];
        $hours[] = [];

        // String To Int Conversion
        $pid = (int)$pid;

        $projects = Project::all();
        $users = User::all();
        $Project = Project::find($pid);
        $tasks = $Project->tasks;

        if ($tasks == null) {

            return view('tasks.taskDetail', compact('projects','users','Project','tasks','task'));
        }

        else {
            $task = $Project->tasks()->orderBy('created_at', 'desc')->first();
//            $task = $project->tasks()->with('users')->orderBy('created_at','desc')->first();
            //$hours = $task->hours;

            return view('tasks.taskDetail', compact('projects','users','Project','tasks','task'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $projects = Project::all();
        $users = User::role(['teamlead','developer'])->get();
        $reporters = User::role(['pm','admin'])->get();

        $task = Task::where('id',$id)->first();

//        dd($task->users->toArray());
        $taskProject = $task->project->first();
        $taskUser = $task->users->pluck('id','name');

        return view('tasks.edit', compact('projects','users', 'reporters','task','taskProject','taskUser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'project_name' => 'required',
            'task_type' => 'required|string',
            'task_name' => 'required',
            'task_component' =>'alpha',
            'task_priority' =>'alpha',
            'task_duedate' =>'required|date',
            'task_assignee' =>'array',
            'task_follower' => 'integer',
            'task_reporter' => 'integer',
            'task_description' => 'string',
            'task_originalEstimate' => 'integer|min:1',
            'task_remainingEstimate' => 'integer|min:1',
            'task_tags' => 'alpha_num',
            'task_workflow' => 'string',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('/tasks/'. $id.'/edit')
                ->withErrors($validator);
        }
        else {

            $task = Task::find($id);
            $task->project_id = $request->project_name;
            $task->types = $request->task_type;
            $task->name = $request->task_name;
            $task->component = $request->task_component;
            $task->priority = $request->task_priority;
            $task->duedate = $request->task_duedate;
            $task->follower = $request->task_follower;
            $task->reporter = $request->task_reporter;
            $task->description = $request->task_description;
            $task->tags = $request->task_tags;
            $task->workflow = $request->task_workflow;

            $task->update();

            // First Detach Already Existing User
            $task->users()->detach();

            /*Attach Task To User-> task_user*/

            /*IF Multiple Task Assignee*/
            if (is_array($request->task_assignee)) {
                foreach ($request->task_assignee as $assignee) {
                    $task->users()->attach($assignee);
                }
            }

            /*IF Single Task Assignee*/
            else {
                $task->users()->attach($request->task_assignee);
            }

            /*To Add Hours Estimate in Hours Table*/

            if (! empty($task->hours[0]))
            {
                $hour = $task->hours[0];
                //$hour->task_id = Task::orderBy('created_at', 'desc')->pluck('id')->first();
                $hour->task_id = $id;
                $hour->project_id = $request->project_name;
                $hour->estimated_hours = $request->task_originalEstimate;
                $hour->update();
            }
            /* If hours are not previously entered */
            elseif (! empty($request->task_originalEstimate))
            {
                $hour = new Hour();
                $hour->task_id = $id;
                $hour->project_id = $request->project_name;
                $hour->estimated_hours = $request->task_originalEstimate;
                $hour->save();
            }


            // redirect
            Session::flash('message', 'Successfully Updated Task!');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('tasks/'.$id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id= (int)$id;
        $task = Task::find($id);
        $task->delete();

        Session::flash('message', 'Successfully deleted the Task!');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/tasks');
    }
}
