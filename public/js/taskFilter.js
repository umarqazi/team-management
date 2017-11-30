$(document).ready(function () {

    //Fetch User of the Selected Project 0n Create Task View
    $('#project_name').on('change', function () {
        var ProjectID = $(this).val();
        if (ProjectID != "null"){
            // window.location.href = '/tasks/users/'+ProjectID;
            $("#task_assignee, #task_follower").html('');

            $("#task_assignee, #task_follower").load('/tasks/users/'+ProjectID, function (data) {

                $('#task_assignee, #task_follower').selectpicker('refresh');
            });
        }
    });

    // Fetch Selected Project And its Tasks
    $('#project_select').on('change', function () {

        var id = $(this).val();
        if (id != "null"){
            window.location.href = '/tasks/specific/'+id;
        }
    });

    // Task Type Filter
    $('#task_type li').on('click', function () {
        var cls = $(this).attr('class');
        if (cls == 'all') {
            $('.eachTask li').show();
        } else {
            $('.eachTask li').hide();
            $('li.'+cls).show();
        }
    });

    // Task Component Filter
    $('#task_component li').on('click', function () {
        var tid = $(this).attr('id');
        if (tid == 'all') {
            $('.eachTask li').show();
        } else {
            $('.eachTask li').hide();
            $('li.'+tid).show();
        }
    });

    // Task Priority Filter
    $('#task_priority li').on('click', function () {
        var pid = $(this).attr('id');
        if (pid == 'all') {
            $('.eachTask li').show();
        } else {
            $('.eachTask li').hide();
            $('li.'+pid).show();
        }
    });

    // Task Workflow Filter
    $('#task_workflow li').on('click', function () {
        var wid = $(this).attr('id');
        if (wid == 'all') {
            $('.eachTask li').show();
        } else {
            $('.eachTask li').hide();
            $('li.'+wid).show();
        }
    });

    // Task Assignee Filter
    $('#task_assignee').on('change', function () {
        var aid = $(this).val();
        if (aid == 'all') {
            $('.eachTask li').show();
        } else {
            $('.eachTask li').hide();
            $('li.'+aid).show();
        }
    });

    // Task Tags Filter
    $('#task_tag').on('keydown', function () {
        if (event.keyCode == 13)
        {
            var tag = $(this).val();
            $('.eachTask li').hide();
            $('li.'+tag).show();
        }
        else{
            $('.eachTask li').show();
        }
    });
});
