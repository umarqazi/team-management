@extends('home')
@section('sales')
    <div class="container">
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
                        <div class="row" style="margin-top: 30px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <form class="form-group form-horizontal">
                                        <div class="col-md-4">
                                            <label>Select Project</label>
                                            <select id="project-charts" class="form-control">
                                                @foreach($projects as $project)
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
                <div class="panel panel-default">
                    <div class="panel-heading">Projects</div>
                    <div class="panel-body">
                        <div class="content" style="margin-top: 20px;">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Technology</th>
                                    <th>Team lead</th>
                                    <th>Developer</th>
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
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
