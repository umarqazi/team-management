@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{URL::asset('css/task_modal.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/bootstrap-select.min.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="ErrorMsg">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            @if(Session::has('message'))
                <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
                    {{ Session::get('message') }}
                </div>
            @endif

        <div class="TaskForm">
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
                                <label class="taskFields"><input type="checkbox" id="assignee" onchange="stateChanged(this.id)">Assignee</label>
                                <label class="taskFields"><input type="checkbox" id="attachment" onchange="stateChanged(this.id)">Attachment</label>
                                <label class="taskFields"><input type="checkbox" id="component" onchange="stateChanged(this.id)">Component/s</label>
                                <label class="taskFields"><input type="checkbox" id="description" onchange="stateChanged(this.id)">Description</label>
                                <label class="taskFields"><input type="checkbox" id="duetime" onchange="stateChanged(this.id)">Due Time</label>
                                <label class="taskFields"><input type="checkbox" id="effort" onchange="stateChanged(this.id)">Effort</label>
                                <label class="taskFields"><input type="checkbox" id="environment" onchange="stateChanged(this.id)">Environment</label>
                                <label class="taskFields"><input type="checkbox" id="epicLink" onchange="stateChanged(this.id)">Epic Link</label>
                                <label class="taskFields"><input type="checkbox" id="fixVersion" onchange="stateChanged(this.id)">Fix Version/s</label>
                                <label class="taskFields"><input type="checkbox" id="tags" onchange="stateChanged(this.id)">Tags</label>
                                <label class="taskFields"><input type="checkbox" id="percentDone" onchange="stateChanged(this.id)">Percent Done</label>
                                <label class="taskFields"><input type="checkbox" id="priority" onchange="stateChanged(this.id)">Priority</label>
                                <label class="taskFields"><input type="checkbox" id="reporter" onchange="stateChanged(this.id)">Reporter</label>
                                <label class="taskFields"><input type="checkbox" id="follower" onchange="stateChanged(this.id)">Follower</label>
                                <label class="taskFields"><input type="checkbox" id="sprint" onchange="stateChanged(this.id)">Sprint</label>
                                <label class="taskFields"><input type="checkbox" id="timeTracking" onchange="stateChanged(this.id)">Time Tracking</label>
                                <label class="taskFields"><input type="checkbox" id="units" onchange="stateChanged(this.id)">Units</label>
                                <label class="taskFields"><input type="checkbox" id="workflow" onchange="stateChanged(this.id)">Workflow</label>
                            </div>
                        </ul>
                            </div>
                            <h3 class="modal-title">Create Task</h3>
                </div>

                <div class="task-body">
                    <form class="form-horizontal taskForm" method="POST" action="/tasks">
                        <div class="form-group projectName">
                            <label class="col-xs-12 control-label">Project Name<span class="mendatoryFields">*</span></label>
                            <div class="col-xs-12">
                                <select class="form-control" name="project_name" id="project_name" style="overflow-y: scroll">
                                    <option id="" value="null">Select A Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group taskType">
                            <label class="col-xs-12 control-label">Task Type<span class="mendatoryFields">*</span></label>
                            <div class="col-xs-12">
                                <select class="form-control" name="task_type">
                                    <option value="null">Select A Proper Type</option>
                                    <option value="New Feature">New Feature</option>
                                    <option value="Bug">Bug</option>
                                    <option value="Improvement">Improvement</option>
                                    <option value="Task">Task</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group taskName">
                            <label for="task_name" class="col-xs-12 control-label">Task Name<span class="mendatoryFields">*</span></label>
                            <div class="col-xs-12">
                                <input type="text" name="task_name" class="form-control" id="task_name">
                            </div>
                        </div>

                        <div class="form-group component" hidden>
                            <label for="task_component" class="col-xs-12 control-label">Component/s</label>
                            <div class="col-xs-12">
                                <select class="form-control" id="task_component" name="task_component">
                                    <option value="null">Select A Component</option>
                                    <option value="Web">Web</option>
                                    <option value="Android">Android</option>
                                    <option value="IOS">IOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group priority" hidden>
                            <label for="task_priority" class="col-xs-12 control-label">Priority</label>
                            <div class="col-xs-12">
                                <select class="form-control" id="task_priority" name="task_priority">
                                    <option value="Blocker">Blocker</option>
                                    <option value="Critical">Critical</option>
                                    <option value="Major">Major</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Trivial">Trivial</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group taskDuedate">
                            <label for="task_duedate" class="col-xs-12 control-label">Due Date<span class="mendatoryFields">*</span></label>
                            <div class="col-sm-3">
                                <input type="date" name="task_duedate" class="form-control" id="task_duedate">
                                <small style="color: #737373"> Format: mm/dd/yyyy</small>
                            </div>
                        </div>

                        <div class="form-group assignee" hidden>
                            <label for="task_assignee" class="col-xs-12 control-label">Assignee</label>
                            <div class="col-xs-6">
                                <select class="form-control selectpicker" id="task_assignee" name="task_assignee[]" multiple>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group follower" hidden>
                            <label for="task_follower" class="col-xs-12 control-label">Follower</label>
                            <div class="col-xs-6">
                                <select class="form-control selectpicker" id="task_follower" name="task_follower">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group effort" hidden>
                            <label for="task_effort" class="col-xs-12 control-label">Effort</label>
                            <div class="col-xs-12">
                                <select class="form-control" id="task_effort" name="task_effort">
                                    <option>None</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group reporter" hidden>
                            <label for="task_reporter" class="col-xs-12 control-label">Reporter<span class="mendatoryFields">*</span></label>
                            <div class="col-xs-6">
                                <select class="form-control" id="task_reporter" name="task_reporter" >
                                    @foreach($reporters as $reporter)
                                        <option value="{{$reporter->id}}">{{$reporter->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group environment" hidden>
                            <label for="task_environment" class="col-xs-12 control-label">Task Environment</label>
                            <div class="col-xs-12">
                                <textarea name="task_environment" class="form-control" rows="5" id="task_environment" name="task_environment" ></textarea>
                            </div>
                        </div>

                        <div class="form-group description" hidden>
                            <label for="task_description" class="col-xs-12 control-label">Task Description</label>
                            <div class="col-xs-12">
                                <textarea name="task_description" class="form-control" rows="5" id="task_description" name="task_description" ></textarea>
                            </div>
                        </div>

                        <div class="form-group timeTracking" hidden>
                            <label for="task_originalEstimate" class="col-xs-12 control-label">Original Estimated Hours</label>
                            <div class="col-sm-3">
                                <input type="number" name="task_originalEstimate" class="form-control" id="task_originalEstimate" name="task_originalEstimate">
                            </div>
                        </div>

                        <div class="form-group timeTracking" hidden>
                            <label for="task_remainingEstimate" class="col-xs-12 control-label">Remaining Estimated Hours</label>
                            <div class="col-sm-3">
                                <input type="number" name="task_remainingEstimate" class="form-control" id="task_remainingEstimate" name="task_remainingEstimate" >
                            </div>
                        </div>

                        <div class="form-group attachment" hidden>
                            <label for="task_file" class="col-xs-12 control-label">Select File/s</label>
                            <div class="col-xs-12" style="border: none">
                                <input type="file" name="task_file" id="task_file" >
                            </div>
                        </div>

                        <div class="form-group tags" hidden>
                            <label for="task_tags" class="col-xs-12 control-label">Tags</label>
                            <div class="col-xs-12">
                                <input type="text" name="task_tags" class="form-control" id="task_tags" >
                            </div>
                        </div>

                        <div class="form-group workflow" hidden>
                            <label for="task_workflow" class="col-xs-12 control-label">Workflow</label>
                            <div class="col-xs-12">
                                <select class="form-control" id="task_workflow" name="task_workflow">
                                    <option value="Todo">Todo</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="In QA">In QA</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group epicLink" hidden>
                            <label for="task_epicLink" class="col-xs-12 control-label">Epic Links</label>
                            <div class="col-xs-12">
                                <select class="form-control" id="task_epicLink" name="task_epicLink">
                                    <option value="null">Select Link</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group sprint" hidden>
                            <label for="task_sprint" class="col-xs-12 control-label">Sprint</label>
                            <div class="col-xs-12">
                                <select class="form-control" id="task_sprint" name="task_sprint">
                                    <option value="null">Select Sprint</option>
                                    <option>Mustafa Rizvi</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group fixVersion" hidden>
                            <label for="task_version" class="col-xs-12 control-label">Fix Version/s</label>
                            <div class="col-xs-12">
                                <select class="form-control" id="task_version" name="task_version">
                                    <option value="null">Select Version</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group units" hidden>
                            <label for="task_units" class="col-xs-12 control-label">Units</label>
                            <div class="col-xs-12">
                                <input type="text" name="task_units" class="form-control" id="task_units" >
                            </div>
                        </div>

                        <div class="form-group percentDone" hidden>
                            <label for="percentDone" class="col-xs-12 control-label">Percent Done </label>
                            <div class="col-xs-12">
                                <input type="text" name="percentDone" class="form-control" id="percentDone" >
                            </div>
                        </div>

                        <div class="form-group duetime" hidden>
                            <label for="due_time" class="col-xs-12 control-label">Due Time</label>
                            <div class="col-xs-12">
                                <input type="text" name="due_time" class="form-control" id="due_time" >
                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-primary" id="createTaskButton">Create Task</button>
                            <button type="button" class="btn btn-default" href="#">Back</button>
                        </div>
                    </form>
                </div>
            </div>
                    <!-- Task Modal Content Ends -->
        </div>
        </div>
    </div>

    @section('scripts')
        <script src="{{URL::asset('js/bootstrap-select.min.js')}}"></script>
        <script src="{{URL::asset('js/taskFilter.js')}}"></script>
    @endsection

    <script>
        // Function To Show add Task Modal-Form-Input-Fields on Input Check Starts
        function stateChanged(id){

            if ($('#'+id).is(":checked")){
                $("."+id).show();
            }

            else{
                $("."+id).hide();
            }
        }
        // Function To Show add Task Modal-Form-Input-Fields on Input Check Ends
    </script>

    <script>
        $('.selectpicker').selectpicker();
    </script>

@endsection
