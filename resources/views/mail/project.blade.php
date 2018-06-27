<!DOCTYPE html>
<html lang="en">
<head>

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="emailHeader">
                    <div class="title">Team Management Notification Email</div>
                </div>

                <div class="emailContent">
                    <div class="emailContentHeader">A Project Has Been Assigned To You. Below is the Details of the Project:</div>

                    <div class="emailContentDetails">
                        <div class="ProjectName">Project Name: @if(!empty($projectName)) {{$projectName}} @endif</div>
                        <div class="TeamleadName">Teamlead Name:
                            @if(!empty($teamleadName))
                                @foreach($teamleadName as $name)
                                    {{\App\User::where('id', $name)->pluck('name')->first()}}
                                @endforeach
                            @endif
                        </div>
                        <div class="ProjectDescription">Description: @if(!empty($description)) {{$description}} @endif</div>
                        <div class="ProjectInternalDeadline">Internal Deadline: @if(!empty($int_deadline)) {{$int_deadline}} @endif</div>
                        <div class="ProjectExternalDeadline">External Deadline: @if(!empty($ext_deadline)) {{$ext_deadline}} @endif</div>
                        <a href="#"><div>View Project</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>