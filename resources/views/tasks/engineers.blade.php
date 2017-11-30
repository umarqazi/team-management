@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{URL::asset('css/taskDetail.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/bootstrap-select.min.css')}}">
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md-12 taskContainer">

                <!--Main Page Sidebar-->
                <div class="taskDetailSidebar">
                    <div id="sidebarHeading">FILTERS</div>
                    <button src="#" id="newFilterButton">New Filter</button>
                    <ul>
                        <li><a href="#">Find Filter</a></li>
                        <hr>
                        <li><a href="#">All Issues</a></li>
                    </ul>
                </div>

                <!--Main Page Content Area-->
                <div class="taskDetailContentBox">
                    <div class="contentBoxHeader">
                       @if(!empty($Project)) <h2>{{$Project->name}}</h2> @endif
                    </div>

                    <!--Main Page Content Area Filter Dropdowns-->
                    <div class="contentBoxFilters">

                        <!--Projects Filter Dropdown-->
                        <div class="btn-group taskDetailFilter">
                            <select class="selectpicker liveSearch" id="project_select"  data-live-search="true">
                                <option value="null" selected="selected" >Select Project</option>
                                 @if($projects->count())
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}" data-tokens="{{$project->name}}" @if(!empty($Project->id) && $project->id == $Project->id) {{"selected"}} @endif><a href="#">{{$project->name}}</a></option>
                                    @endforeach
                                 @endif
                            </select>

                            {{--@foreach($projects as $project)--}}
                            {{--<li><a href="#">{{$project->name}}</a></li>--}}
                            {{--@endforeach--}}
                        </div>

                        <!--Task Type Filter Dropdown-->
                        <div class="btn-group taskDetailFilter">
                            <button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Type: <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="task_type">
                                <li class="all"><a>All</a></li>
                                <li class="new-feature"><a>New Feature</a></li>
                                <li class="bug"><a>Bug</a></li>
                                <li class="improvement"><a>Improvement</a></li>
                                <li class="task"><a>Task</a></li>
                            </ul>
                        </div>

                        <!--Task Component Filter Dropdown-->
                        <div class="btn-group taskDetailFilter">
                            <button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Component: <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="task_component">
                                <li id="all"><a>All</a></li>
                                <li id="web"><a>Web</a></li>
                                <li id="android"><a>Android</a></li>
                                <li id="ios"><a>IOS</a></li>
                            </ul>
                        </div>

                        <!--Task Priority Filter Dropdown-->
                        <div class="btn-group taskDetailFilter">
                            <button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Priority: <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="task_priority">
                                <li id="all"><a>All</a></li>
                                <li id="blocker"><a>Blocker</a></li>
                                <li id="critical"><a>Critical</a></li>
                                <li id="major"><a>Major</a></li>
                                <li id="minor"><a>Minor</a></li>
                                <li id="trivial"><a>Trivial</a></li>
                            </ul>
                        </div>

                        <!--Task Workflow Filter Dropdown-->
                        <div class="btn-group taskDetailFilter">
                            <button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Workflow: <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="task_workflow">
                                <li id="all"><a>All</a></li>
                                <li id="todo"><a>Todo</a></li>
                                <li id="in-progress"><a>In Progress</a></li>
                                <li id="in-qa"><a>In QA</a></li>
                                <li id="completed"><a>Completed</a></li>
                            </ul>
                        </div>

                        <!--Users Filter Dropdown-->
                        <div class="btn-group taskDetailFilter">
                            {{--<button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                            {{--Assignee:All<span class="caret"></span>--}}
                            {{--</button>--}}
                            {{--<ul class="dropdown-menu taskFilter">--}}
                            {{--@foreach($users as $user)--}}
                            {{--<li><a href="#">{{$user->name}}</a></li>--}}
                            {{--@endforeach--}}
                            {{--</ul>--}}

                            {{--<select class="selectpicker liveSearch" id="task_assignee" data-live-search="true">
                                <option value="all">All</option>
                                @foreach($users as $user)
                                    <option data-tokens="{{$user->name}}" value="{{strtolower(str_replace(' ', '-', $user->name))}}"><a>{{$user->name}}</a></option>
                                @endforeach
                            </select>--}}
                        </div>

                        <!--Tag Filter-->
                        {{--<div class="taskDetailInput">
                            <input type="text" class="form-control" id="task_tag" placeholder="Search By Tags">
                        </div>--}}
                    </div>

                    <div class="mainTaskDetail">

                        <!--Content Area Sidebar Showing All Tasks-->
                        <div class="taskListSideBox">
                            <!--Tab Code-->
                            <div>
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs taskViewSidebarTabs" role="tablist">
                                    <li role="presentation" class="active" ><a href="#tasks" aria-controls="tasks" role="tab"data-toggle="tab">Tasks @if(!empty($tasks)) <span class="tasksCount">{{count($tasks)}}</span> @endif</a></li>
                                    <li role="presentation"><a href="#bugs" aria-controls="bugs" role="tab" data-toggle="tab">Bugs @if(!empty($tasks)) <span class="bugsCount">{{0}}</span> @endif</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <!--Tasks Content-->

                                    <div role="tabpanel" class="allTasks tab-pane fade in active" id="tasks">
                                        {{--<div class="allTaskHeader">Tasks</div>--}}
                                        <div class="taskList">
                                            <ol class="eachTask" >
                                                @if(! empty($tasks))
                                                    @foreach($tasks as $t)
                                                        <li class="@if($t->duedate < Carbon\Carbon::today() && $t->workflow != 'Completed') delayed @elseif(strtotime($t->duedate .' -1 day') >= Carbon\Carbon::now()) aboutToDeliver @endif {{strtolower(str_replace(' ','-', $t->types))}} {{strtolower(str_replace(' ','-', $t->component))}} {{strtolower(str_replace(' ','-', $t->priority))}} {{strtolower(str_replace(' ','-', $t->workflow))}} @foreach($t->users as $user) {{strtolower(str_replace(' ','-', $user->name))}} @endforeach {{strtolower(str_replace(' ','-', $t->tags))}}">
                                                            <a href="/tasks/{{$t->id}}">
                                                                <div class="taskKey">{{$t->key}}</div>
                                                                <div class="taskName">{{str_limit($t->name, 15)}}</div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <li> No Tasks Available </li>
                                                @endif
                                            </ol>
                                        </div>
                                    </div>

                                    <div role="tabpanel" class="allBugs tab-pane" id="bugs">
                                        <div class="bugList">
                                            <ul class="eachBug">
                                                <li><div id="BugTitle">No Bugs Available</div></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!--Tab Code-->
                        </div>

                        <!--Content Box Task Detail Box Showing Detail of the Task-->
                        <div class="taskDetailBox">

                            <!--Task Detail Header-->
                            <div class="taskDetailBoxHeader">

                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="taskDetailBoxHeading">
                                    <div class="taskProjectNameAndKey"><a href="#"> @if($task != null) {{$task->project->name}} @endif </a> / <a href="#"> @if($task != null) {{$task->key}} @endif </a></div>
                                    <div class="taskDetailBoxHeading">@if($task != null) {{$task->name}} @endif </div>
                                </div>

                                <!--Task Detail Header Buttons-->
                                <div class="taskDetailBoxHeaderButtons">
                                    <!--Button Only Appears if the Authenticated User Has Permission to Edit-->
                                    @if(auth()->user()->can('edit task'))
                                        <a class="editTask" @if($task != null) href="{{ url('/tasks/'.$task->id.'/edit') }}" @endif><button class="btn btn-default btn-sm" type="button" style="cursor: pointer"><span class="fa fa-pencil-square-o"></span> Edit</button></a>
                                    @endif

                                <!--Button Only Appears if the Authenticated User Has Permission to Delete-->
                                    @if(auth()->user()->can('delete task'))
                                        @if($task != null)
                                            <div class="deleteTask" style="display: inline-block">
                                                {{ Form::open(array('url' => '/tasks/' . $task->id)) }}
                                                {{ Form::hidden('_method', 'DELETE') }}
                                                <button class="btn btn-default btn-sm" type="submit" style="cursor: pointer"><span class="fa fa-trash"></span> Delete</button>
                                                {{ Form::close() }}
                                            </div>
                                        @else
                                            <button class="btn btn-default btn-sm" type="submit" style="cursor: pointer"><span class="fa fa-trash"></span> Delete</button>
                                        @endif
                                    @endif

                                    <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#addHourModal"><span class="fa fa-clock-o"></span> Add Hour</button>
                                    <button class="btn btn-default btn-sm" type="submit"><span class="fa fa-comment"></span> Comment</button>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                        <button type="button" class="btn btn-default">Assign</button>
                                        <button type="button" class="btn btn-default">Reopen</button>
                                        <button type="button" class="btn btn-default">Change Request</button>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                More <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if(auth()->user()->can('create subtask'))
                                                    <li><a href="#" data-toggle="modal" data-target="#SubtaskModal" data-backdrop="static" data-keyboard="false">Create Sub Task</a></li>
                                                @endif
                                                <li><a href="#" data-toggle="modal" data-target="#DeveloperEstimationModal" data-backdrop="static" data-keyboard="false">Add Estimation</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    {{--<div class="btn-group btn-group-sm" role="group" aria-label="...">
                                        <button type="button" class="btn btn-default">Backlog</button>
                                        <button type="button" class="btn btn-default">Selected for Development</button>
                                        <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Workflow<span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            ...
                                        </ul>
                                    </div>--}}
                                </div>

                                <!-- Main Content Box with Both Left And Right Boxes-->
                                <div class="mainTaskDetailBox">

                                    <!-- Main Content Left Box -->
                                    <div class="leftBox col-md-8">

                                        <!-- Main Content Left Box For Details-->
                                        <div class="leftBoxDetailBox">
                                            <div class="leftBoxDetailBoxTitle">Details</div>
                                            <div class="leftBoxDetailBoxContent">
                                                <ul class="leftBoxDetailBoxContentList">
                                                    <li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Type:</span>
                                                            <span class="detail"> @if($task != null) {{$task->types}} @endif </span>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Workflow:</span>
                                                            <span class="detail"> @if($task != null) {{$task->workflow}} @endif </span>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Priority:</span>
                                                            <span class="detail"> @if($task != null) {{$task->priority}} @endif </span>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Fix Versions:</span>
                                                            <span class="detail"> @if($task != null) ---- @endif </span>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Components:</span>
                                                            <span class="detail"> @if($task != null) {{$task->component}} @endif </span>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Tags:</span>
                                                            <span class="detail"> @if($task != null) {{$task->tags}} @endif </span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Main Content Left Box For Subtasks-->
                                        <!--ADD Condition here if subtasks are not Empty-->
                                        <div class="leftBoxSubtasksBox">
                                            <div class="leftBoxSubtasksBoxTitle">Subtasks</div>
                                            <div class="leftBoxSubtasksBoxContent table-responsive">
                                                @if(! empty($task->subtasks))
                                                    <table class="table table-stripped table-condensed">
                                                        <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>View</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody class="taskViewSubtasks">
                                                        @foreach($task->subtasks as $subtask)
                                                            <tr>
                                                                <td>{{str_limit($subtask->name, 40)}}</td>
                                                                <td><a class="btn btn-primary btn-sm" href="/subtasks/{{$subtask->id}}">View</a></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <h4> No Subtask has been created yet</h4>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Main Content Left Box For Description-->
                                        <div class="leftBoxDescriptionBox">
                                            <div class="leftBoxDescriptionBoxTitle">Description</div>
                                            <div class="leftBoxDescriptionBoxContent">
                                                <form>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <textarea class="form-control" rows="3" name="description" id="description"> @if($task != null) {{$task->description}} @endif </textarea>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Main Content Left Box For Time Estimation Starts -->
                                        <div class="leftBoxDetailBox">
                                            <div class="leftBoxDetailBoxTitle">Time Estimation</div>
                                            <div class="leftBoxDetailBoxContent">
                                                <ul class="leftBoxDetailBoxContentList">
                                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['pm','admin']))
                                                        <li class="item">
                                                            <div class="itemDetail">
                                                                <span class="detailType">Total Estimated Hours:</span>
                                                                <span class="detail"> @if(! empty($task->hours[0])) {{$task->hours[0]->estimated_hours}} @else 0 @endif Hours</span>
                                                            </div>
                                                        </li>
                                                    @endif
                                                    <li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Dev's Estimated Hours:</span>
                                                            <span class="detail"> @if(! empty($task->hours[0])) {{$task->hours[0]->internal_hours}} @else 0 @endif Hours</span>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Total Consumed Hours:</span>
                                                            <span class="detail"> @if(! empty($task->hours[0])) {{$task->hours->where('subtask_id',0)->sum('consumed_hours')}} @else 0 @endif Hours</span>
                                                        </div>
                                                    </li>
                                                    </li><li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Total Remaining Hours:</span>
                                                            <span class="detail"> @if(! empty($task->hours[0])) {{abs($task->hours[0]->internal_hours - $task->hours->where('subtask_id',0)->sum('consumed_hours'))}} @else 0 @endif Hours</span>@if(! empty($task->hours[0]) && $task->hours->where('subtask_id',0)->sum('consumed_hours') > $task->hours[0]->internal_hours) <span class="overdueEstimation">Overdue</span>@endif
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- Main Content Left Box For Time Estimation Ends -->

                                        <!-- Main Content Left Box For Activity-->
                                        <div class="leftBoxActivityBox">
                                            <!-- Main Content Left Box Activity -->
                                            <div class="leftBoxActivityBoxTitle">Activity</div>
                                            <div class="leftBoxActivityBoxContent">
                                                <div>

                                                    <!-- Nav tabs -->
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li role="presentation" class="active"><a href="#all" aria-controls="all" role="tab" data-toggle="tab">All</a></li>
                                                        <li role="presentation"><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comments</a></li>
                                                        <li role="presentation"><a href="#worklog" aria-controls="worklog" role="tab" data-toggle="tab">Work Log</a></li>
                                                        <li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">History</a></li>
                                                        <li role="presentation"><a href="#activity" aria-controls="activity" role="tab" data-toggle="tab">Activity</a></li>
                                                    </ul>

                                                    <!-- Tab panes -->
                                                    <div class="tab-content">
                                                        <div role="tabpanel" class="tab-pane active" id="all">All</div>
                                                        <div role="tabpanel" class="tab-pane" id="comments">Comments</div>
                                                        <div role="tabpanel" class="tab-pane" id="worklog">Worklog</div>
                                                        <div role="tabpanel" class="tab-pane" id="history">History</div>
                                                        <div role="tabpanel" class="tab-pane" id="activity">Activity</div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Main Content Right Box -->
                                    <div class="rightBox col-md-4">
                                        <div class="rightBoxPeopleBox">
                                            <!-- Main Content Right Box People -->
                                            <div class="rightBoxPeopleBoxTitle">People</div>
                                            <div class="rightBoxPeopleBoxContent">
                                                <div class="profile">
                                                    <div class="profileType">Assignee</div>
                                                    @if($task != null)
                                                        @foreach($task->users as $user)
                                                            <div class="profilerName">
                                                                <span class="fa fa-user"></span> {{$user->name}}
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="profilerName"><span class="fa fa-user"></span></div>
                                                    @endif
                                                </div>

                                                <div class="profile">
                                                    <div class="profileType">Reporter</div>
                                                    <div class="profilerName"><span class="fa fa-user"></span> @if($task != null) {{\App\User::where('id',$task->reporter)->pluck('name')->first()}} @endif </div>
                                                </div>

                                                <div class="profile">
                                                    <div class="profileType">Follower</div>
                                                    <div class="profilerName"><span class="fa fa-user"></span> @if($task != null) {{\App\User::where('id',$task->follower)->pluck('name')->first()}} @endif </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="rightBoxPeopleBox">
                                            <!-- Main Content Right Box Dates -->
                                            <div class="rightBoxDatesBoxTitle">Dates</div>
                                            <div class="rightBoxDatesBoxContent">
                                                <div class="dueDate">
                                                    <div class="profileType">Due:</div>
                                                    <div class="profilerName">@if($task != null) {{$task->duedate}} @endif </div>
                                                </div>
                                                <div class="createdAt">
                                                    <div class="profileType">Created:</div>
                                                    <div class="profilerName"> @if($task != null) {{$task->created_at->format('d-m-Y')}} @endif </div>
                                                </div>
                                                <div class="createdAt">
                                                    <div class="profileType">Updated:</div>
                                                    <div class="profilerName"> @if($task != null) {{$task->updated_at->format('d-m-Y')}} @endif </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--<!--Task Model Starts Here-->--}}
                                {{--<div id="taskModal" class="modal fade" role="dialog">--}}
                                {{--<div class="modal-dialog modal-lg">--}}

                                {{--<!-- TaskModal content-->--}}
                                {{--<div class="modal-content">--}}
                                {{--<div class="modal-header">--}}
                                {{--<div class="btn-group" id="ConfigureFields">--}}
                                {{--<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                                {{--<span class="fa fa-cog"></span>Configure Fields <span class="caret"></span>--}}
                                {{--</button>--}}

                                {{--<!--Configure Fields Dropdown-->--}}
                                {{--<ul class="dropdown-menu">--}}
                                {{--<div id="dropdownHeader"><strong>Show Fields:</strong> All | Custom</div>--}}
                                {{--<hr>--}}
                                {{--<div class="configurableFields">--}}
                                {{--<label class="taskFields"><input type="checkbox" id="assignee" onchange="stateChanged(this.id)">Assignee</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="attachment" onchange="stateChanged(this.id)">Attachment</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="component" onchange="stateChanged(this.id)">Component/s</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="description" onchange="stateChanged(this.id)">Description</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="duetime" onchange="stateChanged(this.id)">Due Time</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="effort" onchange="stateChanged(this.id)">Effort</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="environment" onchange="stateChanged(this.id)">Environment</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="epicLink" onchange="stateChanged(this.id)">Epic Link</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="fixVersion" onchange="stateChanged(this.id)">Fix Version/s</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="tags" onchange="stateChanged(this.id)">Tags</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="percentDone" onchange="stateChanged(this.id)">Percent Done</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="priority" onchange="stateChanged(this.id)">Priority</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="reporter" onchange="stateChanged(this.id)">Reporter</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="follower" onchange="stateChanged(this.id)">Follower</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="sprint" onchange="stateChanged(this.id)">Sprint</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="timeTracking" onchange="stateChanged(this.id)">Time Tracking</label>--}}
                                {{--<label class="taskFields"><input type="checkbox" id="units" onchange="stateChanged(this.id)">Units</label>--}}
                                {{--</div>--}}
                                {{--</ul>--}}
                                {{--</div>--}}
                                {{--<h3 class="modal-title">Create Task</h3>--}}
                                {{--</div>--}}
                                {{--<div class="modal-body">--}}
                                {{--<form class="form-horizontal taskForm" action="" method="POST">--}}
                                {{--<div class="form-group projectName">--}}
                                {{--<label for="" class="col-sm-2 control-label">Project Name<span class="mendatoryFields">*</span></label>--}}
                                {{--<div class="col-sm-4">--}}
                                {{--<select class="form-control" name="project_name">--}}
                                {{--<option id="" value="null">Select A Project</option>--}}
                                {{--<option>Actionable Insight</option>--}}
                                {{--<option>Actionable Insight Web</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group taskType">--}}
                                {{--<label class="col-sm-2 control-label">Task Type<span class="mendatoryFields">*</span></label>--}}
                                {{--<div class="col-sm-4">--}}
                                {{--<select class="form-control" name="task_type">--}}
                                {{--<option id="" value="null">Select A Proper Type</option>--}}
                                {{--<option>New Feature</option>--}}
                                {{--<option>Bug</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<hr>--}}
                                {{--<div class="form-group taskName">--}}
                                {{--<label for="task_name" class="col-sm-2 control-label">Task Name<span class="mendatoryFields">*</span></label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<input type="text" name="task_name" class="form-control" id="task_name">--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group component" hidden>--}}
                                {{--<label for="task_component" class="col-sm-2 control-label">Component/s</label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<select class="form-control" id="task_component" name="task_component">--}}
                                {{--<option id="" value="null">Select A Component</option>--}}
                                {{--<option>Web</option>--}}
                                {{--<option>Android</option>--}}
                                {{--<option>IOS</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group priority" hidden>--}}
                                {{--<label for="task_priority" class="col-sm-2 control-label">Priority</label>--}}
                                {{--<div class="col-sm-4">--}}
                                {{--<select class="form-control" id="task_priority" name="task_priority">--}}
                                {{--<option>Blocker</option>--}}
                                {{--<option>Critical</option>--}}
                                {{--<option>Major</option>--}}
                                {{--<option>Minor</option>--}}
                                {{--<option>Trivial</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group taskDuedate">--}}
                                {{--<label for="task_duedate" class="col-sm-2 control-label">Due Date<span class="mendatoryFields">*</span></label>--}}
                                {{--<div class="col-sm-3">--}}
                                {{--<input type="date" name="task_duedate" class="form-control" id="task_duedate">--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group assignee" hidden>--}}
                                {{--<label for="task_assignee" class="col-sm-2 control-label">Assignee</label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<select class="form-control" id="task_assignee" name="task_assignee">--}}
                                {{--<option>Mustafa Rizvi</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group follower" hidden>--}}
                                {{--<label for="task_follower" class="col-sm-2 control-label">Follower</label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<select class="form-control" id="task_follower" name="task_follower">--}}
                                {{--<option>Mustafa Rizvi</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group effort" hidden>--}}
                                {{--<label for="task_effort" class="col-sm-2 control-label">Effort</label>--}}
                                {{--<div class="col-sm-2">--}}
                                {{--<select class="form-control" id="task_effort" >--}}
                                {{--<option>None</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group reporter" hidden>--}}
                                {{--<label for="task_reporter" class="col-sm-2 control-label">Reporter<span class="mendatoryFields">*</span></label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<select class="form-control" id="task_reporter" name="task_reporter" >--}}
                                {{--<option>Mustafa Rizvi</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group environment" hidden>--}}
                                {{--<label for="task_environment" class="col-sm-2 control-label">Task Environment</label>--}}
                                {{--<div class="col-sm-10">--}}
                                {{--<textarea name="task_environment" class="form-control" rows="5" id="task_environment" ></textarea>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group description" hidden>--}}
                                {{--<label for="task_description" class="col-sm-2 control-label">Task Description</label>--}}
                                {{--<div class="col-sm-10">--}}
                                {{--<textarea name="task_description" class="form-control" rows="5" id="task_description" ></textarea>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group timeTracking" hidden>--}}
                                {{--<label for="task_originalEstimate" class="col-sm-2 control-label">Original Estimate</label>--}}
                                {{--<div class="col-sm-3">--}}
                                {{--<input type="text" name="task_originalEstimate" class="form-control" id="task_originalEstimate" >--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group timeTracking" hidden>--}}
                                {{--<label for="task_remainingEstimate" class="col-sm-2 control-label">Remaining Estimate</label>--}}
                                {{--<div class="col-sm-3">--}}
                                {{--<input type="text" name="task_remainingEstimate" class="form-control" id="task_remainingEstimate" >--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group attachment" hidden>--}}
                                {{--<label for="task_file" class="col-sm-2 control-label">Select File/s</label>--}}
                                {{--<div class="col-sm-4" style="border: none">--}}
                                {{--<input type="file" name="task_file" id="task_file" >--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group tags" hidden>--}}
                                {{--<label for="task_tags" class="col-sm-2 control-label">Tags</label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<input type="text" name="task_tags" class="form-control" id="task_tags" >--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group epicLink" hidden>--}}
                                {{--<label for="task_epicLink" class="col-sm-2 control-label">Epic Links</label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<select class="form-control" id="task_epicLink" >--}}
                                {{--<option selected>Select Link</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group sprint" hidden>--}}
                                {{--<label for="task_sprint" class="col-sm-2 control-label">Sprint</label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<select class="form-control" id="task_sprint" >--}}
                                {{--<option selected>Select Sprint</option>--}}
                                {{--<option>Mustafa Rizvi</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group fixVersion" hidden>--}}
                                {{--<label for="task_version" class="col-sm-2 control-label">Fix Version/s</label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<select class="form-control" id="task_version" >--}}
                                {{--<option selected>Select Version</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group units" hidden>--}}
                                {{--<label for="task_units" class="col-sm-2 control-label">Units</label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<input type="text" name="task_units" class="form-control" id="task_units" >--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group percentDone" hidden>--}}
                                {{--<label for="percentDone" class="col-sm-2 control-label">Percent Done </label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<input type="text" name="percentDone" class="form-control" id="percentDone" >--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group duetime" hidden>--}}
                                {{--<label for="due_time" class="col-sm-2 control-label">Due Time</label>--}}
                                {{--<div class="col-sm-8">--}}
                                {{--<input type="text" name="due_time" class="form-control" id="due_time" >--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<input type="hidden" name="project_id">--}}
                                {{--</form>--}}
                                {{--</div>--}}
                                {{--<div class="modal-footer">--}}
                                {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                                {{--<input type="checkbox" id="createAnother">Create Another--}}

                                {{--<button type="submit" class="btn btn-primary" id="createTaskButton">Create</button>--}}
                                {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<!--Task Model Ends Here-->--}}

                            <!--Create Sub Task Model Starts Here-->

                                <div id="SubtaskModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                        <!-- TaskModal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="btn-group" id="ConfigureFields">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="fa fa-cog"></span>Configure Fields <span class="caret"></span>
                                                    </button>

                                                    <!--Configure Fields Dropdown-->
                                                    <ul class="dropdown-menu">
                                                        <div id="dropdownHeader"><strong>Show Fields:</strong> All | Custom</div>
                                                        <hr>
                                                        <div class="configurableFields">
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-assignee" onchange="fieldStateChanged(this.id)">Assignee</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-attachment" onchange="fieldStateChanged(this.id)">Attachment</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-description" onchange="fieldStateChanged(this.id)">Description</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-duetime" onchange="fieldStateChanged(this.id)">Due Time</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-effort" onchange="fieldStateChanged(this.id)">Effort</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-environment" onchange="fieldStateChanged(this.id)">Environment</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-epicLink" onchange="fieldStateChanged(this.id)">Epic Link</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-tags" onchange="fieldStateChanged(this.id)">Tags</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-percentDone" onchange="fieldStateChanged(this.id)">Percent Done</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-priority" onchange="fieldStateChanged(this.id)">Priority</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-reporter" onchange="fieldStateChanged(this.id)">Reporter</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-follower" onchange="fieldStateChanged(this.id)">Follower</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-timeTracking" onchange="fieldStateChanged(this.id)">Time Tracking</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-units" onchange="fieldStateChanged(this.id)">Units</label>
                                                            <label class="taskFields"><input type="checkbox" id="subtask-modal-workflow" onchange="fieldStateChanged(this.id)">Workflow</label>
                                                        </div>
                                                    </ul>
                                                </div>
                                                <h3 class="modal-title">Create Subtask</h3>
                                            </div>

                                            <form class="form-horizontal subtaskForm" method="POST" action="">
                                                <div class="modal-body">
                                                    <div class="form-group projectName">
                                                        <label for="" class="col-sm-2 control-label">Task Name<span class="mendatoryFields">*</span></label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" id="project_name" name="project_name" style="overflow-y: scroll">
                                                                <option id="" value="null">Select A Task</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{--<div class="form-group subtaskType">
                                                        <label class="col-sm-2 control-label">Subtask Type<span class="mendatoryFields">*</span></label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" name="subtask_type">
                                                                <option value="null">Select A Proper Type</option>
                                                                <option value="New Feature">New Feature</option>
                                                                <option value="Bug">Bug</option>
                                                                <option value="Improvement">Improvement</option>
                                                                <option value="Task">Task</option>
                                                            </select>
                                                        </div>
                                                    </div>--}}

                                                    <hr>
                                                    <div class="form-group subtaskName">
                                                        <label for="subtask_name" class="col-sm-2 control-label">Task Name<span class="mendatoryFields">*</span></label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="subtask_name" class="form-control" id="subtask_name">
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-priority" hidden>
                                                        <label for="subtask_priority" class="col-sm-2 control-label">Priority</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" id="subtask_priority" name="subtask_priority">
                                                                <option value="Blocker">Blocker</option>
                                                                <option value="Critical">Critical</option>
                                                                <option value="Major">Major</option>
                                                                <option value="Minor">Minor</option>
                                                                <option value="Trivial">Trivial</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class='col-sm-12 subtaskDuedate'>
                                                        <div class="form-group">
                                                            <label for="subtask_duedate" class="col-sm-2 control-label">Due Date & Time:<span class="mendatoryFields">*</span></label>
                                                            <div class='input-group date col-xs-3' id='subtaskModalDueDate'>
                                                                <input type='text' name="subtask_duedate" class="form-control" id="subtask_duedate" />
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $('#subtaskModalDueDate').datetimepicker();
                                                        });
                                                    </script>

                                                    <div class="form-group subtask-modal-assignee" hidden>
                                                        <label for="subtask_assignee" class="col-sm-2 control-label">Assignee</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control selectpicker" id="subtask_assignee" name="subtask_assignee">
                                                                <option value="null" disabled>Select An Assignee</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-follower" hidden>
                                                        <label for="subtask_follower" class="col-sm-2 control-label">Follower</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" id="subtask_follower" name="subtask_follower">
                                                                <option value="null">Select A Follower</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-effort" hidden>
                                                        <label for="subtask_effort" class="col-sm-2 control-label">Effort</label>
                                                        <div class="col-sm-2">
                                                            <select class="form-control" id="subtask_effort" >
                                                                <option>None</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-reporter" hidden>
                                                        <label for="subtask_reporter" class="col-sm-2 control-label">Reporter<span class="mendatoryFields">*</span></label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" id="subtask_reporter" name="subtask_reporter" >
                                                                <option value="null">Select A Reporter</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-environment" hidden>
                                                        <label for="subtask_environment" class="col-sm-2 control-label">Task Environment</label>
                                                        <div class="col-sm-8">
                                                            <textarea name="subtask_environment" class="form-control" rows="5" id="subtask_environment" ></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-description" hidden>
                                                        <label for="subtask_description" class="col-sm-2 control-label">Task Description</label>
                                                        <div class="col-sm-8">
                                                            <textarea name="subtask_description" class="form-control" rows="5" id="subtask_description" ></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-timeTracking" hidden>
                                                        <label for="subtask_originalEstimate" class="col-sm-2 control-label">Original Estimate</label>
                                                        <div class="col-sm-3">
                                                            <input type="number" name="subtask_originalEstimate" class="form-control" id="subtask_originalEstimate" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-timeTracking" hidden>
                                                        <label for="subtask_remainingEstimate" class="col-sm-2 control-label">Remaining Estimate</label>
                                                        <div class="col-sm-3">
                                                            <input type="number" name="subtask_remainingEstimate" class="form-control" id="subtask_remainingEstimate" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-attachment" hidden>
                                                        <label for="subtask_file" class="col-sm-2 control-label">Select File/s</label>
                                                        <div class="col-sm-4" style="border: none">
                                                            <input type="file" name="subtask_file" id="subtask_file" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-tags" hidden>
                                                        <label for="subtask_tags" class="col-sm-2 control-label">Tags</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="subtask_tags" class="form-control" id="subtask_tags" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-workflow" hidden>
                                                        <label for="subtask_workflow" class="col-xs-2 control-label">Workflow</label>
                                                        <div class="col-xs-8">
                                                            <select class="form-control" id="subtask_workflow" name="subtask_workflow">
                                                                <option value="Todo">Todo</option>
                                                                <option value="In Progress">In Progress</option>
                                                                <option value="In QA">In QA</option>
                                                                <option value="Completed">Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-epicLink" hidden>
                                                        <label for="subtask_epicLink" class="col-sm-2 control-label">Epic Links</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" id="subtask_epicLink" >
                                                                <option selected>Select Link</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-sprint" hidden>
                                                        <label for="subtask_sprint" class="col-sm-2 control-label">Sprint</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" id="subtask_sprint" >
                                                                <option selected>Select Sprint</option>
                                                                <option>Mustafa Rizvi</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-fixVersion" hidden>
                                                        <label for="subtask_version" class="col-sm-2 control-label">Fix Version/s</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" id="subtask_version" >
                                                                <option selected>Select Version</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-units" hidden>
                                                        <label for="subtask_units" class="col-sm-2 control-label">Units</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="subtask_units" class="form-control" id="subtask_units" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-percentDone" hidden>
                                                        <label for="subTaskPercentDone" class="col-sm-2 control-label">Percent Done </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="subTaskPercentDone" class="form-control" id="subTaskPercentDone" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group subtask-modal-duetime" hidden>
                                                        <label for="due_time" class="col-sm-2 control-label">Due Time</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="due_time" class="form-control" id="due_time" >
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="task_id">
                                                </div>

                                                <div class="modal-footer myFooter">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="checkbox" id="createAnother">Create Another
                                                    <button type="submit" class="btn btn-primary" id="createSubtaskButton">Create</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- Task Modal Content Ends -->
                                    </div>
                                </div>

                                <!--Create Sub Task Model Ends Here-->

                                {{--Add Hour Modal Starts--}}

                                <div class="modal fade" id="addHourModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Add Hour</h4>
                                            </div>

                                            <form method="post" action="/task_consumed_hours">
                                                <div class="modal-body">
                                                    {{--<div class="form-group">
                                                        <label for="date">Date:</label>
                                                        <input type="date" name="date" class="form-control" value="" id="date">
                                                    </div>--}}

                                                    <div class='col-sm-12'>
                                                        <div class="form-group">
                                                            <label>Date:</label>
                                                            <div class='input-group date' id='taskAddHourModalDate'>
                                                                <input type='text' name="date" class="form-control" value="" id="date" />
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $('#taskAddHourModalDate').datetimepicker();
                                                        });
                                                    </script>

                                                    <div class="form-group">
                                                        <label for="consumed_hours">Consumed Hours:</label>
                                                        <input type="text" name="consumed_hours" class="form-control" id="consumed_hours">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="resource">Resource:</label>
                                                        @hasrole('developer')
                                                        <input type="hidden" name="resource" value="{{ Auth::user()->id }}">
                                                        <select class="form-control" name="resource" disabled="disabled">
                                                            <option value="{{ Auth::user()->id }}" selected = "selected">{{ Auth::user()->name }}</option>
                                                        </select>
                                                        @else
                                                            <select class="form-control" id="resource" name="resource">
                                                                @foreach($users as $user)
                                                                    <option value="{{$user['id']}}">{{$user["name"]}}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>

                                                    @if(! empty($Project))<input type="hidden" name="project_id" value="{{$Project->id}}">@endif
                                                    @if(! empty($task))<input type="hidden" name="task_id" value="{{$task->id}}">@endif
                                                    @if(! empty($task->hours[0])) <input type="hidden" name="task_internal_hours" value="{{$task->hours[0]->internal_hours}}">@endif
                                                    @if(! empty($task->hours[0])) <input type="hidden" name="task_estimated_hours" value="{{$task->hours[0]->estimated_hours}}">@endif
                                                </div>

                                                <div class="modal-footer">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Add Hours</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{--Add Hour Modal Ends--}}

                                {{--Add Estimated Hour By Developer Starts --}}
                                <div class="modal fade" id="DeveloperEstimationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Add Estimation</h4>
                                            </div>

                                            <form method="post" action="/developerTaskEstimation">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="dev_estimated_hours">Estimated Hours:</label>
                                                        <input type="text" name="dev_estimated_hours" class="form-control" id="dev_estimated_hours">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="task_id" value="{{$task->id}}">

                                                <div class="modal-footer">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Add Hours</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{--Add Estimated Hour By Developer Ends--}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script src="{{URL::asset('js/bootstrap-select.min.js')}}"></script>
    <script src="{{URL::asset('js/taskFilter.js')}}"></script>
@endsection
<script>
    $('.selectpicker').selectpicker();
    document.getElementById('date').valueAsDate = new Date();
</script>
@endsection