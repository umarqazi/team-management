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
                        <h2>{{$Project->name}}</h2>
                    </div>

                    <!--Main Page Content Area Filter Dropdowns-->
                    <div class="contentBoxFilters">

                        <!--Projects Filter Dropdown-->
                        <div class="btn-group taskDetailFilter">
                                <select class="selectpicker liveSearch" id="project_select"  data-live-search="true">
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}" data-tokens="{{$project->name}}" @if($project->id == $Project->id) {{"selected"}} @endif><a href="#">{{$project->name}}</a></option>
                                    @endforeach
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

                            <select class="selectpicker liveSearch" id="task_assignee" data-live-search="true">
                                <option value="all">All</option>
                                @foreach($users as $user)
                                    <option data-tokens="{{$user->name}}" value="{{strtolower(str_replace(' ', '-', $user->name))}}"><a>{{$user->name}}</a></option>
                                @endforeach
                            </select>
                        </div>

                        <!--Tag Filter-->
                        <div class="taskDetailInput">
                            <input type="text" class="form-control" id="task_tag" placeholder="Search By Tags">
                        </div>
                    </div>

                    <div class="mainTaskDetail">

                        <!--Content Area Sidebar Showing All Tasks-->
                        <div class="taskListSideBox">
                            <div class="allTasks">
                                <div class="allTaskHeader">Tasks</div>
                                <div class="taskList">
                                    <ol class="eachTask" >
                                        @if($tasks != null)
                                            @foreach($tasks as $task)
                                                <li class="{{strtolower(str_replace(' ','-', $task->types))}} {{strtolower(str_replace(' ','-', $task->component))}} {{strtolower(str_replace(' ','-', $task->priority))}} {{strtolower(str_replace(' ','-', $task->workflow))}} @foreach($task->users as $user) {{strtolower(str_replace(' ','-', $user->name))}} @endforeach {{strtolower(str_replace(' ','-', $task->tags))}}">
                                                    <a href="/tasks/{{$task->id}}">
                                                        <div class="taskKey">{{$task->key}}</div>
                                                        <div class="taskName">{{str_limit($task->name, 15)}}</div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ol>
                                </div>
                            </div>

                            <div class="allTaskFooter  clearfix">
                                <span>Footer</span>
                            </div>
                        </div>

                        <!--Content Box Task Detail Box Showing Detail of the Task-->
                        <div class="taskDetailBox">

                            <!--Task Detail Header-->
                            <div class="taskDetailBoxHeader">
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

                                        <button class="btn btn-default btn-sm"><span class="fa fa-clock"></span> Add Hour</button>
                                        <button class="btn btn-default btn-sm" type="submit"><span class="fa fa-comment"></span> Comment</button>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                            <button type="button" class="btn btn-default">Assign</button>
                                            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                More <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                ...
                                            </ul>
                                        </div>

                                        <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                            <button type="button" class="btn btn-default">Backlog</button>
                                            <button type="button" class="btn btn-default">Selected for Development</button>
                                            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Workflow<span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                ...
                                            </ul>
                                        </div>
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
                                                        <li class="item">
                                                            <div class="itemDetail">
                                                                <span class="detailType">Total Estimated Hours:</span>
                                                                <span class="detail"> @if(! empty($task->hours[0])) {{$task->hours[0]->estimated_hours}} @else 0 @endif Hours</span>
                                                            </div>
                                                        </li>
                                                        <li class="item">
                                                            <div class="itemDetail">
                                                                <span class="detailType">Total Remaining Hours:</span>
                                                                <span class="detail"> @if(! empty($task->hours[0])) {{$task->hours[0]->estimated_hours}} @else 0 @endif Hours</span>
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
                                                    Recent Activities Here...
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
    </script>
@endsection