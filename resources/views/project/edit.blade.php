@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<h2>Edit Details</h2>
				<hr>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				<form action="/projects/{{ $project->id }}" method="POST" style="margin-top: 50px;">
					<input type="hidden" name="_method" value="PUT">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label for="name">Project Name:</label>
						<input type="text" name="name" class="form-control" id="name"
							   value="{{$project->name}}">
					</div>

					<!--Project key Field Starts-->
					<div class="form-group">
						<label for="projectKey">Project Key:</label>
						<input type="text" name="key" class="form-control" id="key" value="{{$project->key}}" onfocus="showSuggestions()">
					</div>
					<!--Project key Field Ends-->

					<!--Project Key Suggestions Starts-->
					<div class="form-group" id="suggestions" style="display:none">
						<label>Suggestions:</label><br>
						<input type="radio" name="ProjectKeySuggestion" id="projectKey1" value="" onclick="addSuggestion('projectKey1')" > <label id="ProjectKeySugg1"></label><br>
						<input type="radio" name="ProjectKeySuggestion" id="projectKey2" value="" onclick="addSuggestion('projectKey2')" > <label id="ProjectKeySugg2"></label><br>
						<input type="radio" name="ProjectKeySuggestion" id="projectKey3" value="" onclick="addSuggestion('projectKey3')" > <label id="ProjectKeySugg3"></label><br>
						<input type="radio" name="ProjectKeySuggestion" id="projectKey4" value="" onclick="addSuggestion('projectKey4')" > <label id="ProjectKeySugg4"></label><br>
						<input type="radio" name="ProjectKeySuggestion" id="projectKey5" value="" onclick="addSuggestion('projectKey5')" > <label id="ProjectKeySugg5"></label><br>
					</div>
					<!--Project Key Suggestions Ends-->

					<div class="form-group">
						<label for="technology">Technology:</label>
						<select id="technology" class="form-control" name="technology[]" multiple>
							@if(is_array(json_decode($project->technology)))
							@foreach(json_decode($project->technology) as $technology))
								<option value="{{$technology}}" {{"selected"}}>{{$technology}}</option>
							@endforeach
							@else
								<option value="{{ $project->technology }}" {{"selected"}}>{{ $project->technology }}</option>
							@endif
						</select>
					</div>
					<div class="form-group">
						<label for="teamlead">Team Lead(s):</label>
						<select class="form-control" id="teamlead" name="teamlead[]" multiple>
							<option value="">Select Team Lead</option>
							@foreach($teamleads as $teamlead)
								<option value="{{$teamlead->id}}" @if( ! empty($project->teamlead) && in_array($teamlead->id, $project->teamlead)) {{"selected"}} @endif >{{$teamlead->name}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label for="developer">Developer(s):</label>
						<select class="form-control" id="developer" name="developer[]" multiple>
							<option value="">Select Developer</option>
							@foreach($developers as $developer)
								<option value="{{$developer->id}}" @if( ! empty($project->developers) && in_array($developer->id, $project->developers)) {{"selected"}} @endif >{{$developer->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<textarea class="form-control" name="description" rows="2" placeholder="Enter Project Description.">{{$project->description}}</textarea>
					</div>
					<div class="form-group">
						<label for="status">Status:</label> <br>
						<input type="radio" name="status" value="1" class="radio-inline" @if($project->status == "1") {{ "checked" }} @endif> Active
						<input type="radio" name="status" value="0" class="radio-inline" @if($project->status == "0") {{ "checked" }} @endif> Inactive
					</div>
					<br>
					<div class="form-group">
						<label for="internal_deadline">Internal Deadline:</label> <br>
						<input type="datetime-local" class="form-control" name="internal_deadline" value="{{\Carbon\Carbon::parse($project->internal_deadline)->format('Y-m-d\Th:i')}}">
					</div>
					<div class="form-group">
						<label for="external_deadline">External Deadline:</label> <br>
						<input type="datetime-local" class="form-control" name="external_deadline"  value="{{\Carbon\Carbon::parse($project->external_deadline)->format('Y-m-d\Th:i')}}">
					</div>
					<div class="text-right">
						<button type="submit" class="btn btn-primary">Update</button>
						<a href="/projects" class="btn btn-default">Go Back</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
        $(document).ready(function() {
            $('#name').on("keyup change click", function () {
                generateKey();
            })
        } );

        function generateKey() {
            // Code To Generate Key For Project Starts
            var projectName = document.getElementById('name').value;
            var matches = projectName.match(/\b(\w)/g);
            var key = matches.join('').toUpperCase();
            document.getElementById('key').value = key;
            // Code To Generate Key For Project Ends

            // Code To Generate Key Suggestions For Project Starts
            //Suggestion 1
            var num = Math.floor(Math.random() * 9000) + 1000;
            var alphaNumericKey = key + num;
            document.getElementById('projectKey1').value= alphaNumericKey;
            document.getElementById('ProjectKeySugg1').innerHTML= alphaNumericKey;

            //Suggestion 2
            var subProjectName = projectName.substring(0,3);
            var subProjectKey = subProjectName + num;
            document.getElementById('projectKey2').value= subProjectKey;
            document.getElementById('ProjectKeySugg2').innerHTML= subProjectKey;

            //Suggestion 3
            var ProjectSuggName5 = projectName.split(' ');
            document.getElementById('projectKey3').value= ProjectSuggName5[0];
            document.getElementById('ProjectKeySugg3').innerHTML= ProjectSuggName5[0];

            //Suggestion 4
            var ProjectSuggName4 = key.toLowerCase();
            document.getElementById('projectKey4').value= ProjectSuggName4;
            document.getElementById('ProjectKeySugg4').innerHTML= ProjectSuggName4;

            //Suggestion 5
            var ProjectSuggName = projectName.substring(0,4);
            document.getElementById('projectKey5').value= ProjectSuggName;
            document.getElementById('ProjectKeySugg5').innerHTML= ProjectSuggName;
            // Code To Generate Key Suggestions For Project Ends
        }

        function showSuggestions() {
            $('#suggestions').fadeIn(500);
            document.getElementById('suggestions').style.display = "block";
        }

        function hideSuggestions() {
            $('#suggestions').fadeOut(500);
            document.getElementById('suggestions').style.display = "none";
        }

        function addSuggestion($ID)
        {
            document.getElementById('key').value = '';
            document.getElementById('key').value = document.getElementById($ID).value;
        }

	</script>
@endsection