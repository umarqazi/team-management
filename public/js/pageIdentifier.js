$('#createTask').on('click', function () {

    var projectID = ' ';
    projectID = $('.pageIdentifier').attr('data-project-id');

    $.ajax({
        url: '/tasks/project/',
        type: 'GET',
        data: {projectId:projectID},
        dataType: 'json',
        success: function (data) {

            $('#project_name').html('<option id="" value="">Select A Project</option>');
            $('#project_name').append(data.project_options);
            $('#task_assignee').html('<option id="" value="" disabled>Select An Assignee</option>');
            $('#task_assignee').append(data.user_options);
            $('#task_follower').html('<option id="" value="" disabled>Select A Follower</option>');
            $('#task_follower').append(data.user_options);
            $('#task_reporter').html('<option id="" value="" disabled>Select A Reporter</option>');
            $('#task_reporter').append(data.reporter_options);

            $('#task_assignee').selectpicker('refresh');
        }
    });
});