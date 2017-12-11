@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{URL::asset('css/taskDetail.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h2>{{$project->name}}</h2>
                <p class="text-right" style="color: #ff0000;">External Deadline:  &nbsp; {{\Carbon\Carbon::parse($project->external_deadline)->format('d F, Y -- H : i: s')}}</p>

                <p class="text-right">Internal Deadline: &nbsp; {{\Carbon\Carbon::parse($project->internal_deadline)->format('d F, Y -- H : i: s')}}</p>
                <div class="content" style="margin-top: 40px;">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Team lead</th>
                                    <th>Developer</th>
                                    @hasrole(['developer', 'teamlead', 'engineer', 'admin'])
                                    <th>Actual Hours</th>
                                    @endrole
                                    <th>Productive Hours</th>
                                    <th>Export</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($hours as $hour)
                                    <tr>
                                        <td>{{$hour['month']}}</td>
                                        <td>{!! $project->teamlead !!}</td>
                                        <td>{!! $project->developers !!}</td>
                                        @hasrole(['developer', 'teamlead', 'engineer', 'admin'])
                                        <td><a href="javascript:void(0)" onclick="getHoursDetail( '{{$hour['month']}}', '{{$hour['year']}}' )" data-toggle="modal" data-target="#myModal2">{{$hour['consumed_hours']}}</a> </td>
                                        @endrole
                                        <td><a href="javascript:void(0)" onclick="getHoursDetail( '{{$hour['month']}}', '{{$hour['year']}}' )" data-toggle="modal" data-target="#myModal2">{{$hour['estimated_hours']}}</a></td>
                                        <td><a href="{{ URL::to('/downloadExcel_hour_by_months/'.$project->id.'/'.$hour['year'].'-'.$hour['month']) }}" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @hasrole(['admin','sales','teamlead'])
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 resourceSheet">
                            <form class="form-inline" action="{{ URL::to('/downloadExcel_hour_by_filter/'.$project->id) }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label for="Form">From Date:</label>
                                    <input type="date" class="form-control" id="from" name="from">
                                </div>
                                <div class="form-group">
                                    <label for="To">To Date:</label>
                                    <input type="date" class="form-control" id="to" name="to">
                                </div>
                                <div class="form-group">
                                    <label for="Resource">Resource:</label>
                                    <select class="form-control" id="export_resource" name="resource">
                                    <option value="">Select a Resource</option>
                                    @foreach($users as $user)
                                        <option value="{{$user['id']}}">{{$user["name"]}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-default" onclick="check_inputs_on_submit()">Export</button>
                            </form>
                            @if(Session::has('export_msg'))
                            <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
                                {{ Session::get('export_msg') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endrole

                    <div class="row">
                        <div class="col-md-12">
                            <!-- Trigger the modal with a button -->
                            <div class="text-center" style="margin-top: 50px;">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Add Hours</button>
                                <a href="{{ URL::to('/downloadExcel_project_by_months/'.$project->id.'/xlsx') }}" class="btn btn-primary">Export Project Details</a>
                                <a href="/projects" class="btn btn-default">Go Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--////////////////////////////////////////////////////////////////////////////////////////////////-->
            <!--Main Page Content Area-->
            @hasrole(['admin','pm'])
{{--
            <div class="taskDetailContentBox col-md-10 col-md-offset-1" style="margin-top: 20px">

                <!--Main Page Content Area Filter Dropdowns-->
                <div class="contentBoxFilters">
                    <div class="btn-group taskDetailFilter">
                        <button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Project Name...<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group taskDetailFilter">
                        <button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Type: All<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group taskDetailFilter">
                        <button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Status:All<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group taskDetailFilter">
                        <button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Assignee:All<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="taskDetailInput">
                        <input type="text" class="form-control" placeholder="Username">
                    </div>
                    <div class="btn-group taskDetailFilter">
                        <button type="button" class="btn btn-default dropdown-toggle taskDetailFilterButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            More<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                </div>


                <div class="mainTaskDetail">

                    <!--Content Area Sidebar Showing All Tasks-->
                    <div class="taskListSideBox">
                        <div class="allTasks">
                            <div class="allTaskHeader">

                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 1 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 2 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 2 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 2 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 2 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 2 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 2 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 2 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 2 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                            <div class="taskList">
                                <ol class="eachTask">
                                    <li>
                                        <a href="#">
                                            <div class="taskKey">Task 2 key</div>
                                            <div class="taskName">Task Name</div>
                                        </a>
                                    </li>
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
                                <div class="taskProjectNameAndKey"><a href="#">Project Name</a> / <a href="#">Task Key</a></div>
                                <div class="taskDetailBoxHeading">Task Name</div>
                            </div>
                            <!--Task Detail Header Buttons-->
                            <div class="taskDetailBoxHeaderButtons">
                                <button class="btn btn-default btn-sm" type="submit"><span class="fa fa-pencil-square-o"></span>Edit</button>
                                <button class="btn btn-default btn-sm" type="submit"><span class="fa fa-comment"></span>Comment</button>
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
                                                        <span class="detail">New Feature</span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Status:</span>
                                                        <span class="detail"></span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Priority:</span>
                                                        <span class="detail"></span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Fix Versions:</span>
                                                        <span class="detail"></span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Components:</span>
                                                        <span class="detail"></span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="itemDetail">
                                                        <span class="detailType">Tags:</span>
                                                        <span class="detail"></span>
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
                                                        <textarea class="form-control" rows="3" name="description" id="description"></textarea>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

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
                                                <div class="profilerName"><span class="fa fa-user"></span>Name</div>
                                            </div>
                                            <div class="profile">
                                                <div class="profileType">Reporter</div>
                                                <div class="profilerName"><span class="fa fa-user"></span>Name</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rightBoxPeopleBox">
                                        <!-- Main Content Right Box Dates -->
                                        <div class="rightBoxDatesBoxTitle">Dates</div>
                                        <div class="rightBoxDatesBoxContent">
                                            <div class="dueDate">
                                                <div class="profileType">Due:</div>
                                                <div class="profilerName">dd/mm/yy</div>
                                            </div>
                                            <div class="createdAt">
                                                <div class="profileType">Created:</div>
                                                <div class="profilerName">dd/mm/yy</div>
                                            </div>
                                            <div class="createdAt">
                                                <div class="profileType">Updated:</div>
                                                <div class="profilerName">dd/mm/yy</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
--}}
            @endrole
            <!--////////////////////////////////////////////////////////////////////////////////////////////////-->

        </div>
    </div>

    <!-- MyModal Starts Here -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- MyModal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Enter Hours</h4>
                </div>
                <form action="/hour" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" name="date" class="form-control" value="{{$currentDate}}" id="date">
                        </div>
                        <div class="form-group">
                            <label for="consumed_hours">Actual Hours:</label>
                            <input type="text" name="consumed_hours" class="form-control" id="consumed_hours">
                        </div>
                        <div class="form-group">
                            <label for="estimated_hours">Productive Hours:</label>
                            <input type="text" name="estimated_hours" class="form-control" id="estimated_hours">
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
                        <div class="form-group">
                            <label for="details">Task Details:</label>
                            <textarea name="details" class="form-control" rows="5" id="details"></textarea>
                        </div>
                        <input type="hidden" name="project_id" value="{{$project->id}}">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!--MyModel Ends Here-->

    <script>
        function getHoursDetail(month, year){
            $.ajax({
                type:'GET',
                url:'/hour/{{$project->id}}/'+year+'-'+month,
                success: function(response){
                    $("#myModal2 .modal-body").html(response.html);
                }
            });
        }
        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    </script>

    <!-- Modal 2-->
    <div id="myModal2" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Hours Details</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection