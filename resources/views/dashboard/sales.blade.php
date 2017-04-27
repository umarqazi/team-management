@extends('home')

@section('sales')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div id="chartContainer2" style="height: 300px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="chartContainerGeneral" style="height: 300px; width: 100%;">
                                </div>
                                <div class="pagination pull-right">
                                    {{ $projects->links() }}
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
    <script type="text/javascript">

//        General Chart

                window.onload = function () {
                    var chart = new CanvasJS.Chart("chartContainerGeneral", {
                        theme: "theme3",//theme1
                        title:{
                            text: "Projects Overview - General"
                        },
                        animationEnabled: false,   // change to true
                        axisY:{
                            title:"Hours",
                        },
                        data: [
                            {
                                // Change type to "bar", "area", "spline", "pie",etc.
                                type: "column",
                                dataPoints: {!! json_encode($datapoints[0], JSON_NUMERIC_CHECK) !!}
                            }
                        ]
                    });
                    chart.render();

                }
    </script>
@endsection
