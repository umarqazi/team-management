
function configureFields(id){
    if ($('#'+id).is(":checked")){
        $("."+id).show();
    }

    else{
        $("."+id).hide();
    }
}

$(document).ready(function () {

    $('.editTask').on('click', function () {
        var id= $(this).attr('task-id');

        $.ajax({
            url: '/tasks/'+id+'/edit',
            type:'GET',
            data:{isAjax: true},        // isAjax is just a variable to check AJAX request
            dataType: 'json',
            success: function (data) {

                $('#editTaskModal').modal({
                    show: true,
                    backdrop:'static',
                    keyboard:false
                });

                $('#edit_project_name').html(data.project_options);
                $('#edit_task_assignee').html(data.user_options);
                $('#edit_task_assignee').val(data.task_assignee);
                $('#edit_task_follower').html(data.user_options);
                $('#edit_task_follower').val(data.task_follower);
                $('#edit_task_reporter').html(data.reporter_options);
                $('#edit_task_name').val(data.task_name);
                $('#edit_task_duedate').val(data.task_duedate);
                $('#edit_task_description').val(data.task_description);
                $('#edit_task_originalEstimate').val(data.task_estimated_hours);
                $('#edit_task_remainingEstimate').val(data.task_remaining_hours);
                $('#edit_task_tags').val(data.task_tags);
                $('#edit_task_type').val(data.task_type);
                $('#edit_task_component').val(data.task_component);
                $('#edit_task_priority').val(data.task_priority);
                $('#edit_task_workflow').val(data.task_workflow);

                $('#edit_task_assignee').selectpicker('refresh');
            }
        });
    });

    $('.hourEstimation').on('change', function () {
        // Select your input element.
        var numInput = document.querySelector($(this));

        // Listen for input event on numInput.
        numInput.addEventListener($(this) , function(){
            // Let's match only digits.
            var num = this.value.match(/^\d+$/);
            if (num === null) {
                // If we have no match, value will be empty.
                this.value = "";
            }
        }, false)

    });
});

/*
    Assign Task To users without Creating or Updating
    Through Assign Button On Admin And View.blade.php
 */

$(document).ready(function () {

    $('#taskAssign').on('change', function () {
        //var value= $(this).val();

        var taskArr = $(this).val().split('|');

        $.ajax({
            url: '/assign_task',
            type: 'GET',
            data: {UID:taskArr[0], TID: taskArr[1]},        // isAjax is just a variable to check AJAX request
            dataType: 'json',
            success: function (data) {

                if(data){
                    location.reload();
                }
            }
        });
    });
});
