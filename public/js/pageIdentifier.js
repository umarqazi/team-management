$('#createTask').on('click', function () {

    var projectID = ' ';
    projectID = $('.pageIdentifier').attr('data-project-id');

    $.ajax({
        url: '/tasks/project/',
        type: 'GET',
        data: {projectId:projectID},
        dataType: 'json',
        success: function (data) {
            $('#project_name').append(data.project_options);
            $('#task_assignee').append(data.user_options);
            $('#task_follower').append(data.user_options);
            $('#task_reporter').append(data.reporter_options);

            $('#task_assignee').selectpicker('refresh');
        }
    });
});