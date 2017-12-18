@extends('home')
@section('admin')
    <div class="container pageIdentifier" data-project-id="home">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="chartContainerGeneral" style="height: 300px; width: 100%;">
                                </div>
                                <div class="pagination pull-right">
                                    {{ $projects->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 30px; margin-bottom: 30px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <form class="form-group form-horizontal">
                                        <div class="col-md-4">
                                            <label>Select Project</label>
                                            <select id="project-charts" class="form-control">
                                                @foreach($allProjects as $project)
                                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Select Resource</label>
                                            <select id="project-resource" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                        <label>Month: </label>
                                        <input type="month" id="proj_month" class="form-control" name="proj_month" value={{\Carbon\Carbon::today()->format('Y-m')}}>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="chartContainerResources" style="height: 300px; width: 100%; margin-top: 20px;">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="chartContainerMonthly" style="height: 300px; width: 100%;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                @if(Auth::user()->can('create project'))
                    <div class="row">
                        <div class="col-md-2 col-md-offset-10">
                            <div class="text-right" style="margin:20px;">
                                <a href="/projects/create" class="btn btn-primary">Create Project</a>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">Active Projects</div>
                    <div class="panel-body">
                        <div class="content" style="margin-top: 20px;">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Technology</th>
                                        <th>Team lead</th>
                                        <th>Developer</th>
                                        <th>View</th>
                                        @if(Auth::user()->can('edit project'))
                                            <th>Edit</th>
                                        @endif
                                        @if(Auth::user()->can('delete project'))
                                            <th>Delete</th>
                                        @endif
                                        <th>View</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>{{$project->name}}</td>
                                            <td>
                                                @if(is_array(json_decode($project->technology)))
                                                    {{ @implode(", ", json_decode($project->technology)) }}
                                                @else
                                                    {{ $project->technology }}
                                                @endif
                                            </td>
                                            <td>{!! $project->teamlead !!}</td>
                                            <td>{!! $project->developers !!}</td>

                                            <td><a href="/projects/{{$project->id}}"> <span class="glyphicon glyphicon-eye-open"></span> </a>  </td>
                                            @if(Auth::user()->can('edit project'))
                                                <td><a href="/projects/{{$project->id}}/edit"> <span class="glyphicon glyphicon-edit"></span></a></td>
                                            @endif
                                            @if(Auth::user()->can('delete project'))
                                                <td>
                                                    {{--<button class="no_button" data-toggle="modal" data-target="#deleteProjectModal" ><i class="glyphicon glyphicon-trash"></i> </button>--}}
                                                    {{ Form::open(array('url' => '/projects/' . $project->id)) }}
                                                    {{ Form::hidden('_method', 'DELETE') }}
                                                    <button type="submit" class="no_button"><i class="glyphicon glyphicon-trash"></i> </button>
                                                    {{ Form::close() }}
                                                </td>
                                            @endif
                                            <td><a href="/tasks/specific/{{$project->id}}" style="text-decoration: none;color: #FFFFFF;"><button class="btn btn-sm btn-success">View Tasks</button></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="paginator" class="text-center">
                                {{ $projects->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('charts')
   @endsection
