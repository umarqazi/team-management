<?php

namespace App\Http\Controllers;

use App\Hour;
use App\Http\Middleware\TaskMiddleware;
use App\Project;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user   = Auth::user();
        $users = array();
        $Project = array();
        $projects = array();
        $tasks = array();
        $task = array();

        if($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend']))
        {
            $projects = !empty($user->projects) ?$user->projects :array();
            $users = User::role(['teamlead','developer'])->get();
            $task = $user->tasks()->orderBy('created_at','desc')->first();

            if (!empty($task)){
                $Project = $task->project;
                $tasks = $user->tasks()->where('project_id', $Project->id)->get();
                $assignee = $task->users->pluck('id','name');
                $hours = $task->hours;
            }
        }
        else
        {
            $projects = Project::all();
            $users = User::role(['teamlead','developer'])->get();
            $task = Task::orderBy('created_at','desc')->first();

            if (!empty($task))
            {
                $Project = $task->project;
                $tasks = $Project->tasks;
//                dd($task->hours);
                $assignee = $task->users->pluck('id','name');
                $hours = $task->hours;
            }
        }

        $view   = View::make('tasks.index');

        if($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend']))
        {
            $view->nest('tasks', 'tasks.engineers', compact('projects','users','Project','tasks','task'));
        }
        else
        {
            $view->nest('tasks', 'tasks.admin', compact('projects','users','Project','tasks','task'));
        }

        return $view;

//        return view('tasks.admin', compact('projects','users','Project','tasks','task'));
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
            'task_duedate' =>'required',
            'task_assignee' =>'array',
            'task_follower' => 'integer',
            'task_reporter' => 'integer',
            'task_description' => 'string',
            'task_originalEstimate' => 'required|integer|min:1',
            'task_remainingEstimate' => 'integer|min:1',
            'task_tags' => 'alpha_num',
            'task_workflow' => 'string',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('/tasks/specific/'.$request->project_name)
                ->withErrors($validator);
        }
        else {

            $task = new Task();
            $task->project_id = $request->project_name;
            $task->types = $request->task_type;
            $task->name = $request->task_name;
            $task->component = $request->task_component;
            $task->priority = $request->task_priority;
            $task->duedate = strtotime($request->task_duedate);
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
            Session::flash('message', 'Successfully Created A Task '.$request->task_name);
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('/tasks/specific/'.$request->project_name);
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
        $user   = Auth::user();

        if($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend']))
        {
            $projects = $user->projects;
            $users = User::role(['teamlead','developer'])->get();
            $task = $user->tasks()->find($id);
            $Project = $task->project;
            $tasks = $user->tasks()->where('project_id', $Project->id)->get();
            $assignee = $task->users->pluck('id','name');
            $hours = $task->hours;
        }
        else
        {
        // Data To Populate View i-e Projects Filter, Users Filter etc
            $projects = Project::all();
            $users = User::role(['teamlead','developer'])->get();

            // Other Specific Data
            $task = Task::find($id);
            $Project = $task->project;
            $tasks = $Project->tasks;
            $assignee = $task->users->pluck('id','name');
            $hours = $task->hours;
        }

        $view   = View::make('tasks.index');

        if($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend']))
        {
            $view->nest('tasks', 'tasks.view', compact('projects','users','Project','tasks','task'));
        }
        else
        {
            $view->nest('tasks', 'tasks.view', compact('projects','users','Project','tasks','task'));
        }

        return $view;
    }

    // Function To Fetch Specific Project and its Tasks on Project Filter in Task Views
    public function showProjectSpecific($pid)
    {
        // String To Int Conversion
        $pid = (int)$pid;

        $users = array();
        $Project = array();
        $projects = array();
        $tasks = array();
        $task = array();

        $hours[] = [];

        $user   = Auth::user();

        if($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend']))
        {
            $projects = !empty($user->projects) ?$user->projects :array();
            $users = User::all();
            $Project = $user->projects()->find($pid);
            $tasks = $user->tasks()->where('project_id', $Project->id)->get();
        }
        else
        {
            $projects = Project::all();
            $users = User::all();
            $Project = Project::find($pid);
            $tasks = $Project->tasks;
        }

        // Check Condition Changed
        if (empty($tasks)) {

            $view   = View::make('tasks.index');

            if($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend']))
            {
                $view->nest('tasks', 'tasks.engineers', compact('projects','users','Project','tasks','task'));
            }
            else
            {
                $view->nest('tasks', 'tasks.admin', compact('projects','users','Project','tasks','task'));
            }

            return $view;
        }

        else
        {
            $task = $Project->tasks()->orderBy('created_at', 'desc')->first();

            $view   = View::make('tasks.index');

            if($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend']))
            {
                $view->nest('tasks', 'tasks.engineers', compact('projects','users','Project','tasks','task'));
            }
            else
            {
                $view->nest('tasks', 'tasks.admin', compact('projects','users','Project','tasks','task'));
            }

            return $view;

            //$task = $project->tasks()->with('users')->orderBy('created_at','desc')->first();
            //$hours = $task->hours;
            //return view('tasks.admin', compact('projects','users','Project','tasks','task'));
        }
    }


    public function showProjectUsers($ProjectID)
    {
        $users = Project::find($ProjectID)->users;
//        dd($users);
        $html = '';
        foreach ($users as $user)
        {
            $html.="<option value='$user->id'>$user->name</option>";
        }
        echo $html;
    }

    public function showProject($PID = null)
    {
        $user = Auth::user();

        if ($user->hasRole(['developer','teamlead'])){
            $projects = $user->projects()->where('status', 1)->get();
        }

        elseif ($user->hasRole(['admin','pm'])){
            $projects = Project::where('status', 1)->get();
        }

        else{
            $projects = Project::where('status', 1)->get();
        }

        $users = User::role(['teamlead','developer'])->get();
        $reporters = User::role(['pm','admin'])->get();

        $project_html = '';
        $user_html = '';
        $reporter_html = '';

        foreach ($projects as $project) {
            $project_html .="<option value='$project->id' ". ((!empty($_GET['projectId']) and $_GET['projectId'] == $project->id)? 'selected': '').">$project->name</option>";
        }

        foreach ($users as $user) {
            $user_html .="<option value='$user->id'>$user->name</option>";
        }

        foreach ($reporters as $reporter) {
            $reporter_html .="<option value='$reporter->id'>$reporter->name</option>";
        }
        $data = array(
            'project_options' => $project_html,
            'user_options' => $user_html,
            'reporter_options' => $reporter_html
        );
        echo json_encode($data);
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

        $reporters = User::role(['pm','admin'])->get();

        $task = Task::where('id',$id)->first();

        $users = $task->project->users;

//      dd($task->users->toArray());
        $taskProject = $task->project;

        $taskUser = $task->users->pluck('id');

        if ($_GET['isAjax'])
        {
            $project_html = '';
            $user_html = '';
            $reporter_html = '';

            foreach ($projects as $project) {
                $project_html .="<option value='$project->id' ".(($taskProject->id == $project->id)? 'selected': '').">$project->name</option>";
            }

            /*echo($task->users->pluck('id'));
            die();*/

            foreach ($users as $user) {
                $user_html .="<option value='$user->id'>$user->name</option>";
            }

            /*echo($user_html);
            die();*/

            foreach ($reporters as $reporter) {
                $reporter_html .="<option value='$reporter->id' ".(($task->reporter == $reporter->id)? 'selected': '').">$reporter->name</option>";
            }

            $task_user = array();
            foreach ($task->users as $user) {
                $task_user = array_merge($task_user, array($user->pluck('id')));
            }

//            $task_user = $task->users->pluck('id')->first();

            /*echo ($reporter_html);
            die();*/

            $data = array(
                'project_options' => $project_html,
                'user_options' => $user_html,
                'reporter_options' => $reporter_html,
                'task_name' => $task->name,
                'task_description' => $task->description,
                'task_percentDone' => $task->percentDone,
                'task_duedate' => date('m/d/Y h:i A',$task->duedate),
                'task_estimated_hours' => $task->hours()->where('subtask_id',null)->pluck('estimated_hours'),
                'task_remaining_hours' => $task->hours()->where('subtask_id',null)->pluck('estimated_hours'),
                'task_tags' => $task->tags,
                'task_type' => $task->types,
                'task_priority' => $task->priority,
                'task_workflow' => $task->workflow,
                'task_component' => $task->component,
                'task_follower' => $task->follower,
                'task_assignee' => $taskUser,
            );
            echo json_encode($data);
        } else
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
            'task_duedate' =>'required',
            'task_assignee' =>'array',
            'task_follower' => 'integer',
            'task_reporter' => 'integer',
            'task_description' => 'string',
            'task_originalEstimate' => 'required|integer|min:1',
            'task_remainingEstimate' => 'integer|min:1',
            'task_tags' => 'alpha_num',
            'task_workflow' => 'string',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('/tasks/'.$id)
                ->withErrors($validator);
        }
        else {

            $task = Task::find($id);
            $task->project_id = $request->project_name;
            $task->types = $request->task_type;
            $task->name = $request->task_name;
            $task->component = $request->task_component;
            $task->priority = $request->task_priority;
            $task->duedate = strtotime($request->task_duedate);
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

            /*To update Hours Estimate in Hours Table*/

            if (! empty($request->task_originalEstimate)) {

                if (! empty($task->hours->where('subtask_id', null)->first())) {
                    $hour = $task->hours->where('subtask_id', null)->first();
                    $hour->task_id = $id;
                    $hour->project_id = $request->project_name;
                    $hour->estimated_hours = $request->task_originalEstimate;
                    $hour->update();
                }
                /* If hours are not previously entered */
                else {
                    $hour = new Hour();
                    $hour->task_id = $id;
                    $hour->project_id = $request->project_name;
                    $hour->estimated_hours = $request->task_originalEstimate;
                    $hour->save();
                }
            }

            // redirect
            Session::flash('message', 'Successfully Updated A Task '.$request->task_name);
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('tasks/'.$id);
        }
    }

    public function updateStatus(){

        $task = Task::find($_GET['task_id']);
        $task->workflow = $_GET['value'];
        $task->update();

        // redirect
        Session::flash('message', 'Successfully Updated Task Status');
        Session::flash('alert-class', 'alert-success');

        echo true;
    }

    // Function To Add Task Reopen and Change Request.
    public function reopenAndChangeRequest(Request $request){
        
        $rules = array(
            'description' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Reopen And Change Request
        if ($validator->fails()) {
            return Redirect::to('/tasks/'.$request->task_id)
                ->withErrors($validator);
        }
        else {
            $task = Task::find($request->task_id);
            $task->workflow = 'Todo';
            if (empty($task->description)){
                $task->description = $request->request_type."\n".$request->description;
            }
            else{
                $task->description .= "\n\n".$request->request_type."\n".$request->description;
            }
            $task->update();

            // redirect
            if ($request->request_type == "Reopen Request"){
                Session::flash('message', 'Task Reopen Request Successfully Submitted!');
            }
            else{
                Session::flash('message', 'Task Change Request Successfully Submitted!');
            }
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('tasks/'.$request->task_id);
        }
    }

    // Task Assign to Users through admin And View.blade.php
    public function assignTask(){
        echo $_GET['UID'];
        echo $_GET['TID'];

        $task = Task::find($_GET['TID']);
        $task->users()->attach($_GET['UID']);
        $userName = User::find($_GET['UID'])->pluck('name');

        // redirect
        Session::flash('message', 'Successfully Assigned Task To '.$userName);
        Session::flash('alert-class', 'alert-success');

        echo true;
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
        $taskName = $task->name;

        $task->delete();

        Session::flash('message', 'Successfully Deleted A Task '.$taskName);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/tasks/specific/'.$task->project()->pluck('id')->first());
    }
}
