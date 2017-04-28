<script type="text/javascript">

    //  ***************************    General Chart For All Projects  ****************************

    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainerGeneral", {
            theme: "theme1",//theme1
            title:{
                text: "Projects Overview - General"
            },
            animationEnabled: true,   // change to true
            axisY:{
                title:"Hours",
            },
            data: [
            @hasrole(['admin','engineer','teamlead'])
             {
                    showInLegend: true,
                    legendText: "Actual Hours",
                    // Change type to "bar", "area", "spline", "pie",etc.
                    type: "column",
                    dataPoints: {!! json_encode($datapoints[1], JSON_NUMERIC_CHECK) !!}
                    },
             @endhasrole
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
                    });
                    $(response.hours[1]).each(function(){
                        ac = { x: new Date($(this).get(0).label), y: $(this).get(0).y}
                        ActualHoursDataPoints.push(ac);
                    });
                    var options = "<option value='0'>Select a Resource</option>";
//                        dataPoint for Resources Pie Chart
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
                            @hasrole(['admin','engineer','teamlead'])
                            {
                                showInLegend: true,
                                legendText: "Actual Hours",
                                // Change type to "bar", "area", "spline", "pie",etc.
                                type: "line",
                                dataPoints: ActualHoursDataPoints
                            },
                                @endhasrole
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

                                    @hasrole(['admin','engineer','teamlead'])

                                    {
                                        type: "column",
                                        showInLegend: true,
                                        legendText: "Actual Hours",
                                        dataPoints: dataPoint_actual
                                    },
                                        @endhasrole
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
                });
                $(response.hours[1]).each(function(){
                    ac = { x: new Date($(this).get(0).label), y: $(this).get(0).y}
                    ActualHoursDataPoints.push(ac);
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

                        @hasrole(['admin','engineer','teamlead'])
                        {
                            showInLegend: true,
                            legendText: "Actual Hours",
                            // Change type to "bar", "area", "spline", "pie",etc.
                            type: "line",
                            dataPoints: ActualHoursDataPoints
                        },
                        @endhasrole
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
                                @hasrole(['admin','engineer','teamlead'])
                                {
                                    type: "column",
                                    showInLegend: true,
                                    legendText: "Actual Hours",
                                    dataPoints: dataPoint_actual
                                },
                                @endhasrole
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

                });
                $(response.hours[1]).each(function(){
                    ac = { x: new Date($(this).get(0).label), y: $(this).get(0).y}
                    ActualHoursDataPoints.push(ac);
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
                        @hasrole(['admin','engineer','teamlead'])
                        {
                            showInLegend: true,
                            legendText: "Actual Hours",
                            // Change type to "bar", "area", "spline", "pie",etc.
                            type: "line",
                            dataPoints: ActualHoursDataPoints
                        },
                        @endhasrole
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
                             @hasrole(['admin','engineer','teamlead'])
                             {
                                    type: "column",
                                    showInLegend: true,
                                    legendText: "Actual Hours",
                                    dataPoints: dataPoint_actual
                                },
                             @endhasrole
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
