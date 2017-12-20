<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Techverx</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{URL::asset('css/task_modal.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/theme.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/toastr.min.css')}}">
    @yield('styles')
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
        .no_button {
            background: none;
            border: none;
            color: #337ab9;
            padding: 0px !important;
        }
        .no_button:hover {
            color: #23527c !important;
            text-decoration: underline;
            font-weight: 400 !important;
        }
        .link{
            color: #337ab7 !important;
        }
        .link:hover{
            color: #23527c !important;
        }
        .canvasjs-chart-credit {
            display: none;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="{{URL::asset('js/toastr.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

    @yield('scripts')
</head>
<body id="app-layout">
<nav class="navbar navbar-default navbar-static-top menuBar">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand">
                Techverx
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                    <li><a href="{{ url('/home') }}">Home</a></li>
                @else
                    <li><a href="{{ url('/home') }}">Home</a></li>
                    <li><a href="{{ url('/projects') }}">Projects</a></li>
                    <li><a href="{{url('/tasks')}}">Task View</a></li>
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    {{--@hasrole(['pm', 'admin'])--}}
                    {{--Create New Task Model--}}
                    @if(auth()->user()->can('create task'))
                        <li><a href="#" id="createTask" data-toggle="modal" data-target="#appTaskModal" data-backdrop="static" data-keyboard="false">Create Task</a></li>
                        {{--<li><a href="{{url('/tasks/create')}}" id="createTask">Add Task</a></li>--}}
                    @endif

                    {{--@endrole--}}

                    @if(auth()->user()->can('create user'))
                        <li><a href="{{ url('/users') }}">Users</a></li>
                    @endif

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/users/'.Auth::user()->id.'/edit') }}"><i class="fa fa-btn fa-user"></i>Profile</a></li>
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

{{--<!--Task Model Starts Here-->--}}

<div id="appTaskModal" class="modal fade" role="dialog">
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
                            <label class="taskFields"><input type="checkbox" id="modal-assignee" onchange="fieldStateChanged(this.id)">Assignee</label>
                            <label class="taskFields"><input type="checkbox" id="modal-attachment" onchange="fieldStateChanged(this.id)">Attachment</label>
                            <label class="taskFields"><input type="checkbox" id="modal-component" onchange="fieldStateChanged(this.id)">Component/s</label>
                            <label class="taskFields"><input type="checkbox" id="modal-description" onchange="fieldStateChanged(this.id)">Description</label>
                            <label class="taskFields"><input type="checkbox" id="modal-duetime" onchange="fieldStateChanged(this.id)">Due Time</label>
                            <label class="taskFields"><input type="checkbox" id="modal-effort" onchange="fieldStateChanged(this.id)">Effort</label>
                            <label class="taskFields"><input type="checkbox" id="modal-environment" onchange="fieldStateChanged(this.id)">Environment</label>
                            <label class="taskFields"><input type="checkbox" id="modal-epicLink" onchange="fieldStateChanged(this.id)">Epic Link</label>
                            <label class="taskFields"><input type="checkbox" id="modal-fixVersion" onchange="fieldStateChanged(this.id)">Fix Version/s</label>
                            <label class="taskFields"><input type="checkbox" id="modal-tags" onchange="fieldStateChanged(this.id)">Tags</label>
                            <label class="taskFields"><input type="checkbox" id="modal-percentDone" onchange="fieldStateChanged(this.id)">Percent Done</label>
                            <label class="taskFields"><input type="checkbox" id="modal-priority" onchange="fieldStateChanged(this.id)">Priority</label>
                            <label class="taskFields"><input type="checkbox" id="modal-reporter" onchange="fieldStateChanged(this.id)">Reporter</label>
                            <label class="taskFields"><input type="checkbox" id="modal-follower" onchange="fieldStateChanged(this.id)">Follower</label>
                            <label class="taskFields"><input type="checkbox" id="modal-sprint" onchange="fieldStateChanged(this.id)">Sprint</label>
                            <label class="taskFields"><input type="checkbox" id="modal-timeTracking" onchange="fieldStateChanged(this.id)">Remaining Estimate</label>
                            <label class="taskFields"><input type="checkbox" id="modal-units" onchange="fieldStateChanged(this.id)">Units</label>
                            <label class="taskFields"><input type="checkbox" id="modal-workflow" onchange="fieldStateChanged(this.id)">Workflow</label>
                        </div>
                    </ul>
                </div>
                <h3 class="modal-title">Create Task</h3>
            </div>

                <form class="form-horizontal taskForm"method="POST" action="/tasks">
                    <div class="modal-body">

                        <div class="form-group projectName">
                            <label for="" class="col-sm-2 control-label">Project Name<span class="mendatoryFields">*</span></label>
                            <div class="col-sm-4">
                                <select class="form-control" id="project_name" name="project_name" value="{{old('project_name')}}" style="overflow-y: scroll">
                                </select>
                            </div>
                        </div>

                        <div class="form-group taskType">
                            <label class="col-sm-2 control-label">Task Type<span class="mendatoryFields">*</span></label>
                            <div class="col-sm-4">
                                <select class="form-control" name="task_type" value="{{old('task_type')}}">
                                    <option value="" selected>Select Task Type</option>
                                    <option value="New Feature">New Feature</option>
                                    <option value="Bug">Bug</option>
                                    <option value="Improvement">Improvement</option>
                                    <option value="Task">Task</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group taskName">
                            <label for="task_name" class="col-sm-2 control-label">Task Name<span class="mendatoryFields">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="task_name" class="form-control" id="task_name" value="{{old('task_name')}}">
                            </div>
                        </div>

                        <div class="form-group modal-component" hidden>
                            <label for="task_component" class="col-sm-2 control-label">Component/s</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="task_component" name="task_component" value="{{old('task_component')}}">
                                    <option value="" selected>Select A Component</option>
                                    <option value="Web">Web</option>
                                    <option value="Android">Android</option>
                                    <option value="IOS">IOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group modal-priority" hidden>
                            <label for="task_priority" class="col-sm-2 control-label">Priority</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="task_priority" name="task_priority" value="{{old('task_priority')}}">
                                    <option value="">Select A Priority</option>
                                    <option value="Blocker">Blocker</option>
                                    <option value="Critical">Critical</option>
                                    <option value="Major">Major</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Trivial">Trivial</option>
                                </select>
                            </div>
                        </div>

                        <div class='col-sm-12 taskDuedate'>
                            <div class="form-group">
                                <label for="task_duedate" class="col-sm-2 control-label">Due Date & Time:<span class="mendatoryFields">*</span></label>
                                <div class='input-group date col-xs-4' id='taskModalDueDate'>
                                    <input type='text' name="task_duedate" class="form-control" id="task_duedate" value="{{old('task_duedate')}}" />
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            $(function () {
                                var dateToday  = new Date();
                                $('#taskModalDueDate').datetimepicker({
                                    minDate: dateToday
                                });
                            });
                        </script>

                        <div class="form-group modal-assignee" hidden>
                            <label for="task_assignee" class="col-sm-2 control-label">Assignee</label>
                            <div class="col-sm-8">
                                <select class="form-control selectpicker" id="task_assignee" name="task_assignee[]" multiple>
                                    <option value="" disabled>Select An Assignee</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group modal-follower" hidden>
                            <label for="task_follower" class="col-sm-2 control-label">Follower</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="task_follower" name="task_follower" value="{{old('task_follower')}}">
                                    <option value="">Select A Follower</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group modal-effort" hidden>
                            <label for="task_effort" class="col-sm-2 control-label">Effort</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="task_effort">
                                    <option>None</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group modal-reporter" hidden>
                            <label for="task_reporter" class="col-sm-2 control-label">Reporter</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="task_reporter" name="task_reporter" value="{{old('task_reporter')}}" >
                                    <option value="">Select A Reporter</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group modal-environment" hidden>
                            <label for="task_environment" class="col-sm-2 control-label">Task Environment</label>
                            <div class="col-sm-8">
                                <textarea name="task_environment" class="form-control" rows="5" id="task_environment" ></textarea>
                            </div>
                        </div>

                        <div class="form-group modal-description" hidden>
                            <label for="task_description" class="col-sm-2 control-label">Task Description</label>
                            <div class="col-sm-8">
                                <textarea name="task_description" class="form-control" rows="5" id="task_description" >{{old('task_description')}}"</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="task_originalEstimate" class="col-sm-2 control-label">Original Estimate<span class="mendatoryFields">*</span></label>
                            <div class="col-sm-3">
                                <input type="number" name="task_originalEstimate" class="form-control hourEstimation" id="task_originalEstimate" value="{{old('task_originalEstimate')}}" min="0">
                            </div>
                        </div>

                        <div class="form-group modal-timeTracking" hidden>
                            <label for="task_remainingEstimate" class="col-sm-2 control-label">Remaining Estimate</label>
                            <div class="col-sm-3">
                                <input type="number" name="task_remainingEstimate" class="form-control" id="task_remainingEstimate" value="{{old('task_remainingEstimate')}}" >
                            </div>
                        </div>

                        <div class="form-group modal-attachment" hidden>
                            <label for="task_file" class="col-sm-2 control-label">Select File/s</label>
                            <div class="col-sm-4" style="border: none">
                                <input type="file" name="task_file" id="task_file" >
                            </div>
                        </div>

                        <div class="form-group modal-tags" hidden>
                            <label for="task_tags" class="col-sm-2 control-label">Tags</label>
                            <div class="col-sm-8">
                                <input type="text" name="task_tags" class="form-control" id="task_tags" value="{{old('task_tags')}}">
                            </div>
                        </div>

                        <div class="form-group modal-workflow" hidden>
                            <label for="task_workflow" class="col-xs-2 control-label">Workflow</label>
                            <div class="col-xs-8">
                                <select class="form-control" id="task_workflow" name="task_workflow" value="{{old('task_workflow')}}">
                                    <option value="">Select Workflow</option>
                                    <option value="Todo">Todo</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="In QA">In QA</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group modal-epicLink" hidden>
                            <label for="task_epicLink" class="col-sm-2 control-label">Epic Links</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="task_epicLink" >
                                    <option selected>Select Link</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group modal-sprint" hidden>
                            <label for="task_sprint" class="col-sm-2 control-label">Sprint</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="task_sprint" >
                                    <option selected>Select Sprint</option>
                                    <option>Mustafa Rizvi</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group modal-fixVersion" hidden>
                            <label for="task_version" class="col-sm-2 control-label">Fix Version/s</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="task_version" >
                                    <option selected>Select Version</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group modal-units" hidden>
                            <label for="task_units" class="col-sm-2 control-label">Units</label>
                            <div class="col-sm-8">
                                <input type="text" name="task_units" class="form-control" id="task_units" >
                            </div>
                        </div>

                        <div class="form-group modal-percentDone" hidden>
                            <label for="percentDone" class="col-sm-2 control-label">Percent Done </label>
                            <div class="col-sm-8">
                                <input type="text" name="percentDone" class="form-control" id="percentDone" >
                            </div>
                        </div>

                        <div class="form-group modal-duetime" hidden>
                            <label for="due_time" class="col-sm-2 control-label">Due Time</label>
                            <div class="col-sm-8">
                                <input type="text" name="due_time" class="form-control" id="due_time" >
                            </div>
                        </div>

                        <input type="hidden" name="project_id">
                    </div>
                        <div class="modal-footer myFooter">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="checkbox" id="createAnother">Create Another

                            <button type="submit" class="btn btn-primary" id="createTaskButton">Create</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                </form>
        </div>
        <!-- Task Modal Content Ends -->
    </div>
</div>
{{--<!--Task Model Ends Here-->--}}

@if(Session::has('msgerror'))
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
                    {{ Session::get('msgerror') }}
                </div>
            </div>
        </div>
    </div>
@endif

@yield('content')

<footer style="margin-top: 50px; height: 100px;"></footer>


<!-- JavaScripts -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="{{URL::asset('js/pageIdentifier.js')}}"></script>
<script src="{{URL::asset('js/bootstrap-select.min.js')}}"></script>
<script src="{{URL::asset('js/taskFilter.js')}}"></script>
<script src="{{URL::asset('js/moment.js')}}"></script>
<script src="{{URL::asset('js/main.js')}}"></script>
<script src="{{URL::asset('js/bootstrap-datetimepicker.js')}}"></script>
<!--Initializing Select Picker-->
<script>
    $('.selectpicker').selectpicker();
</script>

<script>

    var technologies    = [{id: "PHP", text: 'PHP'}, {id: "Ruby on Rails", text: 'Ruby on Rails'}, {id: "Laravel", text: 'Laravel'}, {id: "Codeigniter", text: 'Codeigniter'}, {id: "iOS", text: 'iOS'}, {id: "Android", text: 'Android'}, {id: "Node.JS", text: "Node.JS"}, {id: "React.JS", text: "React.JS"}, {id: "Angular.JS", text: "Angular.JS"}, {id: "Wordpress", text: "Wordpress"}, {id: "Magento", text: "Magento"}];
    var AllProjects = ['Actionable Insights Web','ActionableInsights','Amazing you cleaning','Caribbean Charter','Cocomo Realty','Crisis Hub'];
    $(document).ready(function(){
        $('select.form-control[name^="technology"]').select2({
            placeholder: "Select Technologies",
            data: technologies
        });
    });

    $(document).ready(function(){
        $('#project-charts').select2({
            theme: "classic"
        });
        $('#project-resource').select2({
            theme: "classic"
        });
    });
    $(document).ready(function(){
        $('select.form-control[name^="teamlead"]').select2({
            placeholder: "Select Teamlead(s)"
        });
    });

    $(document).ready(function(){
        $('select.form-control[name^="developer"]').select2({
            placeholder: "Select Developer(s)"
        });
    });

    $(document).ready(function() {
        $('#myTable').dataTable({
            "lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
    });
    $(document).ready(function(){
        $('#myTable2').dataTable({
            "lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
    });
    $(document).on("change","#td_select select",function(){
      $("option[value=" + this.value + "]", this)
      .attr("selected", true).siblings()
      .removeAttr("selected")
    });
    function showform(elem) {
        var id = $(elem).attr("id");
        var res = id.split("_");
        id = res[2];
        document.getElementById("tr_hours_"+id).classList.add("hidden");
        document.getElementById("tr_hours_form_"+id+"_1").classList.remove("hidden");
        document.getElementById("tr_hours_form_"+id+"_2").classList.remove("hidden");
    }
    function delete_hour(elem) {
        var id = $(elem).attr("id");
        var res = id.split("_");
        id = res[2];
        var $_token = "{{ csrf_token() }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $_token
            }
        });
        $.ajax({
            url : "/hour/delete/"+id,
            type: 'POST',
            cache: false,
            success: function(response){
                $('#tr_hours_'+id).addClass("hidden");
            }
        });
    }
    function submitform(elem) {
        var id = $(elem).attr("id");
        var res = id.split("_");
        id = res[2];
        var created_at          = $('input[name=created_at_'+id+']').val();
        var consumed_hours        = $('input[name=actual-hours_'+id+']').val();
        var estimated_hours    = $('input[name=productive-hours_'+id+']').val();
        var user_id             = $('select[name=resource_'+id+']').val();
        var details             = $('input[name=details_'+id+']').val();
        var $_token             = "{{ csrf_token() }}";
        var data                = { consumed_hours    : consumed_hours,
                                    estimated_hours: estimated_hours,
                                    resource        : user_id,
                                    details         : details,
                                    created_at      : created_at
                                   };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $_token
            }
        });
        $.ajax({
            url : "/hour/update/"+id,
            type: 'POST',
            data: data,
            cache: false,
            success: function(response){
                var d = new Date(response.hours.created_at);
                var months_name = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                $("#td_consumed_hours_"+id).html(response.hours.consumed_hours);
                $("#td_estimated_hours_"+id).html(response.hours.estimated_hours);
                $("#td_user_id_"+id).html(response.hours.user_name);
                $("#td_details_"+id).html(response.hours.details);
                $("#td_created_at_"+id).html(response.hours.createDate);
                $('#tr_hours_'+id).removeClass("hidden");
                $('#tr_hours_form_'+id+'_1').addClass("hidden");
                $('#tr_hours_form_'+id+'_2').addClass("hidden");
            }
        });
    }


    function generateKeySuggestions() {
        // Code To Generate Key Suggestions For Project Starts
        var num = Math.floor(Math.random() * 90000) + 10000;
        var alphaNumericKey = key + num;
        document.getElementById('ProjectKeySugg1').innerHTML = alphaNumericKey;
        // Code To Generate Key Suggestions For Project Ends
    }

    function generateKeyForAllProjects(){
        var projectArray = [];
        var len = AllProjects.length;
        for (var i=0;i<len; i++){

            if (hasWhiteSpace(AllProjects[i]))
            {
                var matches = AllProjects[i].match(/\b(\w)/g);
                projectArray[i] = matches.join('').toUpperCase();
            }
            else {
                projectArray[i] = AllProjects[i];
            }
        }
    }

    function hasWhiteSpace(string) {
        if(/\s/g.test(string))
        {
         return true;
        }
        return false;
    }

    // Function To Show add Task Modal-Form-Input-Fields on Input Check Starts
    function fieldStateChanged(id){
        if ($('#'+id).is(":checked")){
            $("."+id).show();
        }

        else{
            $("."+id).hide();
        }
    }
    // Function To Show add Task Modal-Form-Input-Fields on Input Check Ends

    $('.dropdown-menu input, .dropdown-menu label').click(function(e) {
        e.stopPropagation();
    });

    $(document).ready(function() {
        $('#inactive_projects').DataTable();
    } );
    $(document).ready(function() {
        $('#active_projects').DataTable();
    } );
</script>
</body>
</html>
