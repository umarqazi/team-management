@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h2>{{$project->name}}</h2>
                <p class="text-right" style="color: #ff0000;">External Deadline:  &nbsp; {{\Carbon\Carbon::parse($project->external_deadline)->format('d F, Y -- H : i: s')}}</p>

                <p class="text-right">Internal Deadline: &nbsp; {{\Carbon\Carbon::parse($project->internal_deadline)->format('d F, Y -- H : i: s')}}</p>
                <div class="content" style="margin-top: 40px;">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Month</th>
                            <th>Team lead</th>
                            <th>Developer</th>
                            @hasrole(['developer', 'teamlead', 'engineer', 'admin'])
                            <th>Actual Hours</th>
                            @endrole
                            <th>Productive Hours</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($hours as $hour)
                            <tr>
                                <td>{{$hour['month']}}</td>
                                <td>{!! $project->teamlead !!}</td>
                                <td>{!! $project->developers !!}</td>
                                @hasrole(['developer', 'teamlead', 'engineer', 'admin'])
                                <td><a href="javascript:void(0)" onclick="getHoursDetail()" data-toggle="modal" data-target="#myModal2">{{$hour['actual_hours']}}</a> </td>
                                @endrole
                                <td><a href="javascript:void(0)" onclick="getHoursDetail()" data-toggle="modal" data-target="#myModal2">{{$hour['productive_hours']}}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- Trigger the modal with a button -->
                    <div class="text-center" style="margin-top: 50px;">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add Hours
                        </button>
                        <a href="/projects" class="btn btn-default">Go Back</a>
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
                            <label for="actual_hours">Actual Hours:</label>
                            <input type="text" name="actual_hours" class="form-control" id="actual_hours">
                        </div>
                        <div class="form-group">
                            <label for="productive_hours">Productive Hours:</label>
                            <input type="text" name="productive_hours" class="form-control" id="productive_hours">
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
        function getHoursDetail(){
            $.ajax({
                type:'GET',
                url:'/hour/{{$project->id}}',
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