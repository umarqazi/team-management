<?php

namespace App\Http\Controllers;

use App\User;
use App\Hour;
use App\Project;
use App\Subtask;
use App\Task;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Http\Middleware\TaskMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\View;

class SubtasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('Subtask Index Function');
        $view = View::make('subtasks.view');
        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks = Task::all();
        $users = User::role(['teamlead','developer'])->get();
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
            'task_name' => 'required|integer',
            'subtask_name' => 'required',
            'subtask_priority' =>'alpha',
            'subtask_duedate' =>'required',
            'subtask_assignee' =>'integer',
            'subtask_follower' => 'integer',
            'subtask_reporter' => 'integer',
            'subtask_description' => 'string',
            'subtask_originalEstimate' => 'required|integer|min:1',
            'subtask_remainingEstimate' => 'integer|min:1',
            'subtask_tags' => 'alpha_num',
            'subtask_workflow' => 'string',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('/tasks/'.$request->task_name)
                ->withErrors($validator);
        }
        else {
            $subtask = new Subtask();
            $subtask->task_id = $request->task_name;
            $subtask->name = $request->subtask_name;
            $subtask->priority = $request->subtask_priority;
            $subtask->duedate = strtotime($request->subtask_duedate);
            $subtask->user_id = $request->subtask_assignee;
            $subtask->follower = $request->subtask_follower;
            $subtask->reporter = $request->subtask_reporter;
            $subtask->description = $request->subtask_description;
            $subtask->tags = $request->subtask_tags;
            $subtask->workflow = $request->subtask_workflow;
            $subtask->save();

            /*To Add Hours Estimate in Hours Table*/
            if (! empty($request->subtask_originalEstimate))
            {
                $hour = new Hour();
                $hour->task_id = $request->task_name;
                $hour->subtask_id = Subtask::orderBy('created_at','desc')->pluck('id')->first();
                $hour->project_id = $request->project_id;
                $hour->estimated_hours = $request->subtask_originalEstimate;
                $hour->save();
            }

            // redirect
            Session::flash('message', 'Successfully Created A Subtask. '.$request->subtask_name);
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('/tasks/'.$request->task_name);
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
            $tasks = Task::all();
            $projects = $user->projects;

//            $subtask = $user->subtasks()->find($id);
            $subtask = Subtask::find($id);
            $task = $subtask->task;
            $projectId = $task->project_id;
            $users = $task->users;
//            $tasks = $user->tasks()->where('project_id', $Project->id)->get();
            $assignee = $task->users->pluck('id','name');
            $hours = $task->hours;
        }
        else
        {
            // Data To Populate View i-e Projects Filter, Users Filter etc
            $tasks = Task::all();

            // Other Specific Data
            $subtask = Subtask::find($id);
            $task = $subtask->task;
            $projectId = $task->project_id;
            $users = $task->users;
            $assignee = $task->users->pluck('id','name');
            $hours = $task->hours;
        }

        $view   = View::make('subtasks.view', compact('subtask','users','tasks', 'projectId'));
        return $view;

        /*if($user->hasRole(['developer', 'teamlead', 'engineer', 'frontend']))
        {
            $view->nest('tasks', 'tasks.view', compact('projects','Project','tasks','task'));
        }
        else
        {
            $view->nest('tasks', 'tasks.view', compact('projects','users','Project','tasks','task'));
        }*/
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
        
        $tasks = Task::all();

        $reporters = User::role(['pm','admin'])->get();

        $subtask = Subtask::where('id',$id)->first();

        $users = $subtask->task->users;

//      dd($task->users->toArray());
        $sub_task = $subtask->task;

        //$subtaskUser = $subtask->user_id;

        if ($_GET['isAjax'])
        {
            $task_html = '';
            $user_html = '';
            $reporter_html = '';

            foreach ($tasks as $task) {
                $task_html .="<option value='$task->id' ".(($sub_task->id == $task->id)? 'selected': '').">$task->name</option>";
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
                'task_options' => $task_html,
                'user_options' => $user_html,
                'reporter_options' => $reporter_html,
                'subtask_name' => $subtask->name,
                'subtask_description' => $subtask->description,
                'subtask_percentDone' => $subtask->percentDone,
                'subtask_duedate' => $subtask->duedate,
                'subtask_estimated_hours' => $subtask->hours->pluck('estimated_hours'),
                'subtask_remaining_hours' => $subtask->hours->pluck('estimated_hours'),
                'subtask_tags' => $subtask->tags,
                'subtask_type' => $subtask->types,
                'subtask_priority' => $subtask->priority,
                'subtask_workflow' => $subtask->workflow,
                'subtask_follower' => $subtask->follower,
                'subtask_assignee' => $subtask->user_id,
            );
            echo json_encode($data);
        }
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
            'task_name' => 'required|integer',
            'subtask_name' => 'required',
            'subtask_priority' =>'alpha',
            'subtask_duedate' =>'required',
            'subtask_assignee' =>'integer',
            'subtask_follower' => 'integer',
            'subtask_reporter' => 'integer',
            'subtask_description' => 'string',
            'subtask_originalEstimate' => 'required|integer|min:1',
            'subtask_remainingEstimate' => 'integer|min:1',
            'subtask_tags' => 'alpha_num',
            'subtask_workflow' => 'string',
        );
        $validator = Validator::make(Input::all(), $rules);

        // Process the Task Creation
        if ($validator->fails()) {
            return Redirect::to('/subtasks/'.$id)
                ->withErrors($validator);
        }
        else {
            $subtask = Subtask::find($id);
            $subtask->task_id = $request->task_name;
            $subtask->name = $request->subtask_name;
            $subtask->priority = $request->subtask_priority;
            $subtask->duedate = strtotime($request->subtask_duedate);
            $subtask->user_id = $request->subtask_assignee;
            $subtask->follower = $request->subtask_follower;
            $subtask->reporter = $request->subtask_reporter;
            $subtask->description = $request->subtask_description;
            $subtask->tags = $request->subtask_tags;
            $subtask->Workflow = $request->subtask_workflow;
            $subtask->update();

            /*To Add Hours Estimate in Hours Table*/
            if (!empty($request->subtask_originalEstimate)) {

                if (! empty($subtask->hours->first())){

                    $hour = $subtask->hours->first();
                    $hour->task_id = $request->task_name;
                    $hour->subtask_id = $id;
                    $hour->project_id = $request->project_id;
                    $hour->estimated_hours = $request->subtask_originalEstimate;
                    $hour->update();
                }

                else{
                    $hour = new Hour();
                    $hour->task_id = $request->task_name;
                    $hour->subtask_id = $id;
                    $hour->project_id = $request->project_id;
                    $hour->estimated_hours = $request->subtask_originalEstimate;
                    $hour->save();
                }
            }

            // redirect
            Session::flash('message', 'Successfully Updated Subtask. '.$request->subtask_name);
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('/subtasks/'.$id);
        }
    }

    public function updateStatus(){

        $subtask = Subtask::find($_GET['subtask_id']);
        $subtask->Workflow = $_GET['value'];
        $subtask->update();

        // redirect
        Session::flash('message', 'Successfully Updated Subtask Status.');
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
            return Redirect::to('/subtasks/'.$request->subtask_id)
                ->withErrors($validator);
        }
        else {
            $subtask = Subtask::find($request->subtask_id);
            $subtask->Workflow = 'Todo';
            if (empty($subtask->description)){
                $subtask->description = $request->request_type.":\n".$request->description;
            }
            else{
                $subtask->description .= "\n\n".$request->request_type.":\n".$request->description;
            }
            $subtask->update();

            // redirect
            if ($request->request_type == "Reopen Request"){
                Session::flash('message', 'Subtask Reopen Request Successfully Submitted!');
            }
            else{
                Session::flash('message', 'Subtask Change Request Successfully Submitted!');
            }
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('subtasks/'.$request->subtask_id);
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
        $subtask = Subtask::find($id);
        $subtaskName = $subtask->name;
        $subtask->delete();

        Session::flash('message', 'Successfully Deleted A Subtask. '.$subtaskName);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/tasks');
    }
}
