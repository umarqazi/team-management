@extends('home')
@section('admin')
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
                                    <form style="margin-left: 15px;">
                                        <label>Select Project: </label>
                                        <select id="project-charts">
                                            @foreach($projects as $project)
                                                <option value="{{$project->id}}">{{$project->name}}</option>
                                            @endforeach
                                        </select> &nbsp;&nbsp;
                                        {{--<label>Select Resource: </label>--}}
                                        <select id="project-resource">

                                        </select> <br><br>
                                        <label>Month: </label>
                                        <input type="month" id="proj_month" name="proj_month" value={{\Carbon\Carbon::today()->format('Y-m')}}>
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
                                            {{ Form::open(array('url' => '/projects/' . $project->id)) }}
                                            {{ Form::hidden('_method', 'DELETE') }}
                                            <button type="submit" class="no_button"><i class="glyphicon glyphicon-trash"></i> </button>
                                            {{ Form::close() }}
                                        </td>
                                        @endif
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

//  ***************************    General Chart For All Projects  ****************************

        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainerGeneral", {
                theme: "theme3",//theme1
                title:{
                    text: "Projects Overview - General"
                },
                animationEnabled: true,   // change to true
                axisY:{
                    title:"Hours",
                },
                data: [
                    {
                        showInLegend: true,
                        legendText: "Actual Hours",
                        // Change type to "bar", "area", "spline", "pie",etc.
                        type: "column",
                        dataPoints: {!! json_encode($datapoints[1], JSON_NUMERIC_CHECK) !!}
                    },
                    {
                        showInLegend: true,
                        legendText: "Productive Hours",
                        type: "column",
                        dataPoints: {!! json_encode($datapoints[0], JSON_NUMERIC_CHECK) !!}
                    }
                ]
            });
            chart.render();

// ************************ Project Chart Monthly Detailed Hours  ***************************

            var default_loaded = $("#project-charts").val();
            $( "#project-charts" ).ready(function() {
                var default_loaded = $("#project-charts").val();
                var proj_month = $('#proj_month').val();
                var selected_project = $("#project-charts").val();
                $.ajax({
                    type:'GET',
                    url:'/home/'+selected_project+'/'+proj_month,
                    success: function(response){
                        response    = JSON.parse(response);
                        var ProductiveHourdataPoints = [];
                        var ActualHoursDataPoints = [];
                        $(response.hours[0]).each(function(){
                            p = { x: new Date($(this).get(0).label), y: $(this).get(0).y}
                            ProductiveHourdataPoints.push(p);
//                            console.log($(this).get(0).label);
                        });
                        $(response.hours[1]).each(function(){
                            ac = { x: new Date($(this).get(0).label), y: $(this).get(0).y}
                            ActualHoursDataPoints.push(ac);
//                            console.log($(this).get(0).label);
                        });
                        var options = "<option value='0'>Select a Resource</option>";
//                        dataPoint for Resources Pie Chart
                        var dataPoint_actual = [];
                        var dataPoint_prod = [];
                        $(response.resources).each(function(){
//                            console.log(response.resources)
                            a = { y: $(this).get(0).actual_hours, label: $(this).get(0).user['name']}
                            dataPoint_actual.push(a);
                            b = { y: $(this).get(0).productive_hours, label: $(this).get(0).user['name']}
                            dataPoint_prod.push(b);
                           var project_resource_name = $(this).get(0).user['name'];
                            var project_resource_id = $(this).get(0).user['id'];
                            options += "<option value='"+ project_resource_id+"'>" + project_resource_name + "</option>";
                        });
                        $('#project-resource').html(options);
                        var resources = response.resources;

                        var chart = new CanvasJS.Chart("chartContainerMonthly", {
                            theme: "theme2",//theme1
                            title:{
                                text: "Project Hours - Monthly"
                            },
                            animationEnabled: true,   // change to true
                            axisX: {
                                valueFormatString: "DD/MMM"
                            },
                            axisY:{
                                title:"Hours",
                            },
                            data: [
                                {
                                    showInLegend: true,
                                    legendText: "Actual Hours",
                                    // Change type to "bar", "area", "spline", "pie",etc.
                                    type: "line",
                                    dataPoints: ActualHoursDataPoints
                                },
                                {
                                    showInLegend: true,
                                    legendText: "Productive Hours",
                                    // Change type to "bar", "area", "spline", "pie",etc.
                                    type: "line",
                                    dataPoints: ProductiveHourdataPoints
                                }
                            ]
                        });
                        chart.render();

                        //      Resources Chart
                        var chart = new CanvasJS.Chart("chartContainerResources",
                                {
                                    title:{
                                        text: "Resource Hours"
                                    },
                                    legend: {
                                        maxWidth: 350,
                                        itemWidth: 120
                                    },
                                    data: [
                                        {
                                            type: "column",
                                            showInLegend: true,
                                            legendText: "Actual Hours",
                                            dataPoints: dataPoint_actual
                                        },
                                        {
                                            type: "column",
                                            showInLegend: true,
                                            legendText: "Productive Hours",
                                            dataPoints: dataPoint_prod
                                        }
                                    ]
                                });
                        chart.render();
                    }
                });
            });
        } // end of window.onload

// **************************** Ajax Request For Updating Graph **************************

    var selected_project = $("#project-charts").val();
//  OnChange Function
    $( "#project-charts, #proj_month" ).change(function() {
     var selected_project = $("#project-charts").val();
        var proj_month = $('#proj_month').val();
        $.ajax({
        type:'GET',
        url:'/home/'+selected_project+'/'+proj_month,
        success: function(response){
            response    = JSON.parse(response);
            var ProductiveHourdataPoints = [];
            var ActualHoursDataPoints = [];
            $(response.hours[0]).each(function(){
                p = { x: new Date($(this).get(0).label), y: $(this).get(0).y}
                ProductiveHourdataPoints.push(p);
//                console.log($(this).get(0).label);
            });
            $(response.hours[1]).each(function(){
                ac = { x: new Date($(this).get(0).label), y: $(this).get(0).y}
                ActualHoursDataPoints.push(ac);
//                console.log($(this).get(0).label);
            });

            var options = "<option value='0'>Select a Resource</option>";
//            DataPoints For Resources Pie Chart
            var dataPoint_actual = [];
            var dataPoint_prod = [];
            $(response.resources).each(function(){
                a = { y: $(this).get(0).actual_hours, label: $(this).get(0).user['name']}
                 dataPoint_actual.push(a);
                b = { y: $(this).get(0).productive_hours, label: $(this).get(0).user['name']}
                dataPoint_prod.push(b);
                var project_resource_name = $(this).get(0).user['name'];
                var project_resource_id = $(this).get(0).user['id'];
                options += "<option value='"+ project_resource_id+"'>" + project_resource_name + "</option>";
            });
            $('#project-resource').html(options);

            var chart = new CanvasJS.Chart("chartContainerMonthly", {
                theme: "theme2",//theme1
                title:{
                    text: "Project Hours - Monthly"
                },
                animationEnabled: false,
                axisX: {
                    valueFormatString: "DD/MMM"
                },
                axisY:{
                    title:"Hours",
                },
                data: [
                    {
                        showInLegend: true,
                        legendText: "Actual Hours",
                        // Change type to "bar", "area", "spline", "pie",etc.
                        type: "line",
                        dataPoints: ActualHoursDataPoints
                    },
                    {
                        showInLegend: true,
                        legendText: "Productive Hours",
                        // Change type to "bar", "area", "spline", "pie",etc.
                        type: "line",
                        dataPoints: ProductiveHourdataPoints
                    }
                ]
            });
            chart.render();


//            Resources Pie Graph onchange

            var chart = new CanvasJS.Chart("chartContainerResources",
                    {
                        title:{
                            text: "Resource Hours"
                        },
                        legend: {
                            maxWidth: 350,
                            itemWidth: 120
                        },
                        data: [
                            {
                                type: "column",
                                showInLegend: true,
                                legendText: "Actual Hours",
                                dataPoints: dataPoint_actual
                            },
                            {
                                type: "column",
                                showInLegend: true,
                                legendText: "Productive Hours",
                                dataPoints: dataPoint_prod
                            }
                        ]
                    });
            chart.render();
          }
         });
    }); // OnChange function end

// Individual Resource Hours Graph Onchange

    $( "#project-resource").change(function() {
        var resource = $('#project-resource').val();
        var selected_project  = $('#project-charts').val();
        var proj_month        = $('#proj_month').val();

//        console.log(resource);

            $.ajax({
                type: 'GET',
                url: '/home/' + selected_project + '/' + proj_month + '/' + resource,
                success: function (response) {
                    response = JSON.parse(response);

                    var ProductiveHourdataPoints = [];
                    var ActualHoursDataPoints = [];
                    $(response.hours[0]).each(function(){
                        p = { x: new Date($(this).get(0).label), y: $(this).get(0).y}
                        ProductiveHourdataPoints.push(p);
//                console.log($(this).get(0).label);
                    });
                    $(response.hours[1]).each(function(){
                        ac = { x: new Date($(this).get(0).label), y: $(this).get(0).y}
                        ActualHoursDataPoints.push(ac);
//                console.log($(this).get(0).label);
                    });
//            DataPoints For Resources Pie Chart
                    var dataPoint_actual = [];
                    var dataPoint_prod = [];
                    $(response.resources).each(function(){
                        a = { y: $(this).get(0).actual_hours, label: $(this).get(0).user['name']}
                        dataPoint_actual.push(a);
                        b = { y: $(this).get(0).productive_hours, label: $(this).get(0).user['name']}
                        dataPoint_prod.push(b);
                    });

                    //    Selected  Resources Graph

                    var chart = new CanvasJS.Chart("chartContainerMonthly", {
                        theme: "theme2",//theme1
                        title: {
                            text: "Resource Hours - Monthly"
                        },
                        animationEnabled: false,
                        axisX: {
                            valueFormatString: "DD/MMM"
                        },
                        axisY: {
                            title: "Hours",
                        },
                        data: [
                            {
                                showInLegend: true,
                                legendText: "Actual Hours",
                                // Change type to "bar", "area", "spline", "pie",etc.
                                type: "line",
                                dataPoints: ActualHoursDataPoints
                            },
                            {
                                showInLegend: true,
                                legendText: "Productive Hours",
                                // Change type to "bar", "area", "spline", "pie",etc.
                                type: "line",
                                dataPoints: ProductiveHourdataPoints
                            }
                        ]
                    });

                    chart.render();


//            Resources Pie Graph onchange

                    var chart = new CanvasJS.Chart("chartContainerResources",
                            {
                                title:{
                                    text: "Resource Hours"
                                },
                                legend: {
                                    maxWidth: 350,
                                    itemWidth: 120
                                },
                                data: [
                                    {
                                        type: "column",
                                        showInLegend: true,
                                        legendText: "Actual Hours",
                                        dataPoints: dataPoint_actual
                                    },
                                    {
                                        type: "column",
                                        showInLegend: true,
                                        legendText: "Productive Hours",
                                        dataPoints: dataPoint_prod
                                    }
                                ]
                            });
                    chart.render();

                }
            });


    });

//        end ajax request


    </script>
@endsection
