@extends('layouts.app')

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
                                    <th>Import</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($hours as $hour)
                                    <tr>
                                        <td>{{$hour['month']}}</td>
                                        <td>{!! $project->teamlead !!}</td>
                                        <td>{!! $project->developers !!}</td>
                                        @hasrole(['developer', 'teamlead', 'engineer', 'admin'])
                                        <td><a href="javascript:void(0)" onclick="getHoursDetail( '{{$hour['month']}}', '{{$hour['year']}}' )" data-toggle="modal" data-target="#myModal2">{{$hour['actual_hours']}}</a> </td>
                                        @endrole
                                        <td><a href="javascript:void(0)" onclick="getHoursDetail( '{{$hour['month']}}', '{{$hour['year']}}' )" data-toggle="modal" data-target="#myModal2">{{$hour['productive_hours']}}</a></td>
                                        <td><a href="{{ URL::to('/downloadExcel_hour_by_months/'.$project->id.'/'.$hour['year'].'-'.$hour['month']) }}" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @hasrole(['admin','sales','teamlead'])
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
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
                                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add Hours
                                </button>
                                <a href="{{ URL::to('/downloadExcel_project_by_months/'.$project->id.'/xlsx') }}" class="btn btn-primary">Export Project Details</a>
                                <a href="/projects" class="btn btn-default">Go Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Enter Hours</h4>
                </div>
                <form action="/hour" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" name="date" class="form-control" id="date">
                        </div>
                        <div class="form-group">
                            <label for="actual_hours">Actual Hours:</label>
                            <input type="text" name="actual_hours" class="form-control" id="actual_hours">
                        </div>
                        <div class="form-group">
                            <label for="productive_hours">Productive Hours:</label>
                            <input type="text" name="productive_hours" class="form-control" id="productive_hours">
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
    <script>
        function getHoursDetail(month, year){
            console.log(month);
            $.ajax({
                type:'GET',
                url:'/hour/{{$project->id}}/'+year+'-'+month,
                success: function(response){
                    $("#myModal2 .modal-body").html(response.html);
                }
            });
        }
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