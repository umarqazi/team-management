@extends('home')

@section('engineers')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                @role('teamlead')
                <div class="row">
                    <div class="col-md-2 col-md-offset-10">
                        <div class="text-right" style="margin-top:20px;">
                            <a href="/project/create" class="btn btn-primary">Create Project</a>
                        </div>
                    </div>
                </div>
                @endrole
                <div class="panel panel-default">
                    <div class="panel-heading">Projects</div>
                    <div class="panel-body">
                        <div class="content" style="margin-top: 20px;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Technology</th>
                                    <th>Team lead</th>
                                    <th>Developer</th>
                                    <th>View</th>
                                    @role('teamlead')
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    @endrole
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->projects as $project)
                                    <tr>
                                        <td>{{$project->name}}</td>
                                        <td>{{$project->technology}}</td>
                                        @foreach($project->teamlead as $teamlead)
                                            <td>{{$teamlead->name}}</td>
                                        @endforeach
                                        @foreach($project->developers as $developer)
                                            <td>{{$developer->name}}</td>
                                        @endforeach

                                        <td><a href="/project/{{$project->id}}"> <span class="glyphicon glyphicon-eye-open"></span> </a>  </td>
                                        @role('teamlead')
                                        <td><a href="/project/{{$project->id}}/edit"> <span class="glyphicon glyphicon-edit"></span></a></td>
                                        <td>
                                            {{ Form::open(array('url' => '/projects/' . $project->id)) }}
                                            {{ Form::hidden('_method', 'DELETE') }}
                                            <button type="submit" class="btn btn-warning"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                            {{ Form::close() }}
                                        </td>
                                        @endrole
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div id="paginator" class="text-center">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
