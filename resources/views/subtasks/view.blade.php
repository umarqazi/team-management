@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{URL::asset('css/subtaskDetail.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/bootstrap-select.min.css')}}">
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md-12 subtaskContainer">

                <!--Main Page Sidebar-->
                <div class="subtaskDetailSidebar col-md-2">
                    <div id="sidebarHeading">{{ucwords($subtask->task()->pluck('name')->first())}}</div>
                    {{--<button src="#" id="newFilterButton">New Filter</button>--}}
                    <ul class="list">
                        <li><a href="#">Find Filter</a></li>
                        <hr>
                        <li><a href="#">All Issues</a></li>
                    </ul>
                </div>

                <!--Main Page Content Area-->
                <div class="subtaskDetailContentBox col-md-10">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="contentBoxHeader">
                        <div class="contentBoxSubheader">
                            <span class="contentBoxSubheaderTaskName"><a href="/tasks/{{$subtask->task()->pluck('id')->first()}}">{{$subtask->task()->pluck('name')->first()}}</a></span>/
                            <span class="contentBoxSubheaderTaskKey"><a href="/tasks/{{$subtask->task()->pluck('id')->first()}}">{{$subtask->task()->pluck('key')->first()}}</a></span>
                        </div>
                        <div class="contentBoxHeading">{{ucwords($subtask->name)}}</div>
                    </div>

                    <div class="mainSubtaskDetail col-md-12">

                        <!--Content Box Sub Task Detail Box Showing Detail of the Sub Task-->
                        <div class="subtaskDetailBox">

                            <!--Task Detail Header Buttons-->
                            <div class="subtaskDetailBoxHeaderButtons">
                                @if(auth()->user()->can('edit task'))
                                    <a class="editSubtask" data-toggle="modal" data-target="#SubtaskEditModal" data-backdrop="static" data-keyboard="false"><button class="btn btn-default" id="editSubtaskButton" type="button" style="cursor: pointer"><span class="fa fa-pencil-square-o"></span> Edit</button></a>
                                @endif

                                @if(auth()->user()->can('delete task'))
                                    <div class="deleteSubtask" style="display: inline-block">
                                        {{ Form::open(array('url' => '/subtasks/' . $subtask->id)) }}
                                        {{ Form::hidden('_method', 'DELETE') }}
                                        <button class="btn btn-default" type="submit" style="cursor: pointer"><span class="fa fa-trash"></span> Delete</button>
                                        {{ Form::close() }}
                                    </div>
                                @endif

                                <button class="btn btn-default" data-toggle="modal" data-target="#hourModal"><span class="fa fa-clock-o"></span> Add Hour</button>
                                <button class="btn btn-default subtaskDetailBoxCommentButton" type="submit"><span class="fa fa-comment"></span> Comment</button>
                                <button class="btn btn-default subtaskDetailBoxAssignButton" type="button">Assign</button>

                                <div class="btn-group" role="group">
                                    <button class="btn btn-default dropdown-toggle subtaskDetailBoxAdminButton" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Status <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu subtaskStatus">
                                        <li value="Todo"><a>Todo</a></li>
                                        <li value="In Progress"><a>In Progress</a></li>
                                        <li value="In QA"><a>In QA</a></li>
                                        <li value="Completed"><a>Completed</a></li>
                                    </ul>

                                    <script>
                                        $(function () {
                                            $('.subtaskStatus li').click(function () {
                                                console.log($(this).attr('value'));
                                                $.ajax({
                                                    url: '/subtask_status',
                                                    type:'GET',
                                                    data:{subtask_id: '<?=$subtask->id ?>',value: $(this).attr('value')},
                                                    dataType: 'json',
                                                    success: function (data) {
                                                        if(data) {
                                                            alert('Status Successfully Updated');
                                                            location.reload();
                                                        }
                                                    }
                                                });
                                            });
                                        })
                                    </script>
                                </div>
                                <button class="btn btn-default" type="button">Reopen</button>
                                <button class="btn btn-default" type="button">Change Request</button>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-default dropdown-toggle subtaskDetailBoxAdminButton" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        More <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" data-toggle="modal" data-target="#DeveloperSubtaskEstimationModal" data-backdrop="static" data-keyboard="false">Add Estimation</a></li>
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
                                                        <span class="detailType">Workflow:</span>
                                                        <span class="detail"> {{$subtask->Workflow}}</span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Priority:</span>
                                                        <span class="detail"> {{$subtask->priority}}</span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Tags:</span>
                                                        <span class="detail"> {{$subtask->tags}} </span>
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
                                                    <textarea class="form-control" rows="5" name="description" id="description"> {{$subtask->description}}</textarea>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Main Content Left Box For Time Estimation Starts -->
                                    <div class="leftBoxTimeEstimationBox">
                                        <div class="leftBoxDetailBoxTitle">Time Estimation</div>
                                        <div class="leftBoxDetailBoxContent">
                                            <ul class="leftBoxDetailBoxContentList">
                                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['pm','admin']))
                                                    <li class="item">
                                                        <div class="itemDetail">
                                                            <span class="detailType">Total Estimated Hours:</span>
                                                            <span class="detail">@if(! empty($subtask->hours->pluck('estimated_hours')->first())) {{$subtask->hours->pluck('estimated_hours')->first()}} @else 0 @endif Hours</span>
                                                        </div>
                                                    </li>
                                                @endif
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Developer's Estimated Hours:</span>
                                                        <span class="detail">@if(! empty($subtask->hours->pluck('internal_hours')->first())) {{$subtask->hours->pluck('internal_hours')->first()}} @else 0 @endif Hours</span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Total Consumed Hours:</span>
                                                        <span class="detail">@if(! empty($subtask->hours)) {{$subtask->hours->sum('consumed_hours')}} @else 0 @endif Hours</span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Total Remaining Hours:</span>
                                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['pm','admin']))
                                                        <span class="detail">@if(! empty($subtask->hours->pluck('estimated_hours')->first())) {{abs($subtask->hours->pluck('estimated_hours')->first() - $subtask->hours->sum('consumed_hours'))}} @else 0 @endif Hours</span>@if($subtask->hours->sum('consumed_hours') > $subtask->hours->pluck('estimated_hours')->first()) <span class="overdueEstimation">Overdue</span>@endif
                                                        @else
                                                        <span class="detail">@if(! empty($subtask->hours->pluck('internal_hours')->first())) {{abs($subtask->hours->pluck('internal_hours')->first() - $subtask->hours->sum('consumed_hours'))}} @else {{$subtask->hours->sum('consumed_hours')}} @endif Hours</span>@if($subtask->hours->sum('consumed_hours') > $subtask->hours->pluck('internal_hours')->first()) <span class="overdueDevEstimation">Overdue</span>@endif
                                                        @endif
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
                                                <div class="profilerName"><span class="fa fa-user"></span>
                                                    {{\App\User::where('id',$subtask->user_id)->pluck('name')->first()}}
                                                </div>
                                            </div>

                                            <div class="profile">
                                                <div class="profileType">Reporter</div>
                                                <div class="profilerName"><span class="fa fa-user"></span>
                                                    {{\App\User::where('id',$subtask->reporter)->pluck('name')->first()}}
                                                </div>
                                            </div>

                                            <div class="profile">
                                                <div class="profileType">Follower</div>
                                                <div class="profilerName"><span class="fa fa-user"></span>
                                                    {{\App\User::where('id',$subtask->follower)->pluck('name')->first()}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rightBoxDateBox">
                                        <!-- Main Content Right Box Dates -->
                                        <div class="rightBoxDatesBoxTitle">Dates</div>
                                        <div class="rightBoxDatesBoxContent">
                                            <div class="dueDate">
                                                <div class="profileType">Due:</div>
                                                <div class="profilerName">{{date('d-m-Y h:i A',$subtask->duedate)}} </div>
                                            </div>
                                            <div class="createdAt">
                                                <div class="profileType">Created:</div>
                                                <div class="profilerName">{{$subtask->created_at->format('d-m-Y')}} </div>
                                            </div>
                                            <div class="createdAt">
                                                <div class="profileType">Updated:</div>
                                                <div class="profilerName">{{$subtask->updated_at->format('d-m-Y')}} </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--Edit Subtask Modal Starts--}}

                            <div id="SubtaskEditModal" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">

                                    <!--Edit Subtask Modal content-->
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
                                            <h3 class="modal-title">Edit Subtask</h3>
                                        </div>

                                        <form class="form-horizontal subtaskForm"  role="form" action="/subtasks/{{$subtask->id }}" method="POST">

                                            <input type="hidden" name="_method" value="PUT">

                                            <div class="modal-body">
                                                <div class="form-group taskName">
                                                    <label for="" class="col-sm-2 control-label">Task Name<span class="mendatoryFields">*</span></label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="task_name" name="task_name" style="overflow-y: scroll">
                                                            @foreach($tasks as $task)
                                                                <option value="{{$task->id}}" {{($task->id == $subtask->task()->pluck('id')->first())? 'selected' : ''}}>{{ucwords($task->name)}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="form-group subtaskName">
                                                    <label for="subtask_name" class="col-sm-2 control-label">Subtask Name<span class="mendatoryFields">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="subtask_name" class="form-control" id="subtask_name" value="{{$subtask->name}}">
                                                    </div>
                                                </div>

                                                <div class="form-group subtask-modal-priority" hidden>
                                                    <label for="subtask_priority" class="col-sm-2 control-label">Priority</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="subtask_priority" name="subtask_priority">
                                                            <option value="Blocker" @if($subtask->priority == 'Blocker') selected @endif>Blocker</option>
                                                            <option value="Critical" @if($subtask->priority == 'Critical') selected @endif>Critical</option>
                                                            <option value="Major" @if($subtask->priority == 'Major') selected @endif>Major</option>
                                                            <option value="Minor" @if($subtask->priority == 'Minor') selected @endif>Minor</option>
                                                            <option value="Trivial" @if($subtask->priority == 'Trivial') selected @endif>Trivial</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class='col-sm-12 subtaskDuedate'>
                                                    <div class="form-group">
                                                        <label for="subtask_duedate" class="col-sm-2 control-label">Due Date & Time:<span class="mendatoryFields">*</span></label>
                                                        <div class='input-group date col-xs-3' id='subtaskEditModalDueDate'>
                                                            <input type='text' name="subtask_duedate" class="form-control" id="subtask_duedate" value="{{$subtask->duedate}}" />
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script type="text/javascript">
                                                    $(function () {
                                                        $('#subtaskEditModalDueDate').datetimepicker();
                                                        $('#subtask_duedate').val(" {{date('m/d/Y h:i A',$subtask->duedate)}} ");
                                                    });
                                                </script>

                                                <div class="form-group subtask-modal-assignee" hidden>
                                                    <label for="subtask_assignee" class="col-sm-2 control-label">Assignee</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control selectpicker" id="subtask_assignee" name="subtask_assignee">
                                                            @foreach($users as $assignee)
                                                                <option value="{{$assignee->id}}" @if($assignee->id == $subtask->user_id) @endif>{{$assignee->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group subtask-modal-follower" hidden>
                                                    <label for="subtask_follower" class="col-sm-2 control-label">Follower</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" id="subtask_follower" name="subtask_follower">
                                                            <option value="{{ $subtask->follower}}">{{\App\User::where('id',$subtask->follower)->pluck('name')->first()}}</option>
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
                                                            <option value="{{$subtask->reporter}}">{{\App\User::where('id',$subtask->reporter)->pluck('name')->first()}}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group subtask-modal-environment" hidden>
                                                    <label for="subtask_environment" class="col-sm-2 control-label">Subtask Environment</label>
                                                    <div class="col-sm-10">
                                                        <textarea name="subtask_environment" class="form-control" rows="5" id="subtask_environment" ></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group subtask-modal-description" hidden>
                                                    <label for="subtask_description" class="col-sm-2 control-label">Subtask Description</label>
                                                    <div class="col-sm-10">
                                                        <textarea name="subtask_description" class="form-control" rows="5" id="subtask_description" >{{$subtask->description}}</textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group subtask-modal-timeTracking" hidden>
                                                    <label for="subtask_originalEstimate" class="col-sm-2 control-label">Original Estimate</label>
                                                    <div class="col-sm-3">
                                                        <input type="number" name="subtask_originalEstimate" class="form-control" id="subtask_originalEstimate" value="{{$subtask->hours->pluck('estimated_hours')->first()}}">
                                                    </div>
                                                </div>

                                                <div class="form-group subtask-modal-timeTracking" hidden>
                                                    <label for="subtask_remainingEstimate" class="col-sm-2 control-label">Remaining Estimate</label>
                                                    <div class="col-sm-3">
                                                        <input type="number" name="subtask_remainingEstimate" class="form-control" id="subtask_remainingEstimate" value="{{$subtask->hours->pluck('estimated_hours')->first() - (empty($subtask->hours->pluck('consumed_hours')->first())? 0: $subtask->hours->pluck('consumed_hours')->first())}}">
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
                                                        <input type="text" name="subtask_tags" class="form-control" id="subtask_tags" value="{{$subtask->tags}}">
                                                    </div>
                                                </div>

                                                <div class="form-group subtask-modal-workflow" hidden>
                                                    <label for="subtask_workflow" class="col-xs-2 control-label">Workflow</label>
                                                    <div class="col-xs-8">
                                                        <select class="form-control" id="subtask_workflow" name="subtask_workflow" value="{{$subtask->Workflow}}">
                                                            <option value="Todo" @if($subtask->Workflow == 'Todo') selected @endif>Todo</option>
                                                            <option value="In Progress" @if($subtask->Workflow == 'In Progress') selected @endif>In Progress</option>
                                                            <option value="In QA" @if($subtask->Workflow == 'In QA') selected @endif>In QA</option>
                                                            <option value="Completed" @if($subtask->Workflow == 'Completed') selected @endif>Completed</option>
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

                                                <input type="hidden" name="project_id" value="{{$projectId}}">
                                            </div>

                                            <div class="modal-footer myFooter">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="checkbox" id="createAnother">Create Another
                                                <button type="submit" class="btn btn-success" id="updateSubtaskButton">Update</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!--Sub Task Modal Content Ends -->
                                </div>
                            </div>

                            {{--Edit Subtask Modal Ends--}}

                                {{--Add Hour Modal Starts--}}

                                <div class="modal fade" id="hourModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Add Hour</h4>
                                            </div>

                                            <form method="post" action="/subtask_consumed_hours">
                                                <div class="modal-body">

                                                    <div class='col-sm-12'>
                                                        <div class="form-group">
                                                            <label>Date:</label>
                                                            <div class='input-group date' id='subtaskAddHourModalDate'>
                                                                <input type='text' name="date" class="form-control" value="" id="date" />
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $('#subtaskAddHourModalDate').datetimepicker();
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

                                                    @if(! empty($projectId))<input type="hidden" name="project_id" value="{{$projectId}}">@endif
                                                    @if(! empty($task))<input type="hidden" name="task_id" value="{{$task->id}}">@endif
                                                    @if(! empty($subtask))<input type="hidden" name="subtask_id" value="{{$subtask->id}}">@endif
                                                    @if(! empty($subtask->hours[0])) <input type="hidden" name="subtask_internal_hours" value="{{$subtask->hours[0]->internal_hours}}">@endif
                                                    @if(! empty($subtask->hours[0])) <input type="hidden" name="subtask_estimated_hours" value="{{$subtask->hours[0]->estimated_hours}}">@endif

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
                                <div class="modal fade" id="DeveloperSubtaskEstimationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Add Estimation</h4>
                                            </div>

                                            <form method="post" action="/developerSubtaskEstimation">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="dev_estimated_hours">Estimated Hours:</label>
                                                        <input type="text" name="dev_estimated_hours" class="form-control" id="dev_estimated_hours">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="task_id" value="{{$task->id}}">
                                                <input type="hidden" name="subtask_id" value="{{$subtask->id}}">

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

@section('scripts')
    <script src="{{URL::asset('js/bootstrap-select.min.js')}}"></script>
    <script src="{{URL::asset('js/taskFilter.js')}}"></script>
    <script src="{{URL::asset('js/main.js')}}"></script>
@endsection

{{--<script>--}}
{{----}}
{{--</script>--}}

<script>
    $('.selectpicker').selectpicker();
</script>
@endsection