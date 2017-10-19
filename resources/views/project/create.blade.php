@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="row">
				<div class="col-md-12">
					<h3>Add New Project</h3>
				</div>
			</div>


			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<form action="/projects" method="POST" style="margin-top: 50px;">
						<div class="form-group">
							<label for="name">Project Name:</label>
							<input type="text" name="name" class="form-control" id="project-name" value="{{ old("name") }}">
						</div>

						<!--Project key Field Starts-->
						<div class="form-group">
							<label for="projectKey">Project Key:</label>
							<input type="text" name="key" class="form-control" id="key" onfocus="showSuggestions()">
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
								@if( !empty(old("technology")) )
								@foreach(old("technology") as $technology)
									<option value="{{ $technology }}" {{ "selected" }}>{{ $technology }}</option>
								@endforeach
								@endif
							</select>
						</div>

						<div class="form-group">
							<label for="teamlead">Team Lead(s):</label>
							@hasrole('teamlead')
							<input type="hidden" name="teamlead" value="{{ Auth::user()->id }}" />
							<select class="form-control" id="teamlead" name="teamlead" disabled>
								<option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->name }}</option>
							</select>
							@else
							<select class="form-control" id="teamlead" name="teamlead[]" multiple>
								<option value="">Select Team Lead</option>
								@foreach($teamleads as $teamlead)
									<option value="{{$teamlead->id}}" @if(old("teamlead") == $teamlead->id) {{ "selected" }} @endif>{{$teamlead->name}}</option>
								@endforeach
							</select>
							@endif
						</div>

						<div class="form-group">
							<label for="developer">Developer(s):</label>
							<select class="form-control" id="developer" name="developer[]" multiple>
								<option value="">Select Developers</option>
								@foreach($developers as $developer)
									<option value="{{$developer->id}}" @if( !empty(old("developer")) && in_array($developer->id, old("developer"))){{ "selected" }}@endif>{{$developer->name}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="sel1">Description:</label> <br>
							<textarea class="form-control" name="description" rows="2" placeholder="Enter Project Description.">{{ old("description") }}</textarea>
						</div>
						<div class="form-group">
							<label for="status">Status:</label> <br>
							<input type="radio" name="status" value="1" class="radio-inline" @if(old("status") == "1") {{ "checked" }} @endif> Active
							<input type="radio" name="status" value="0" class="radio-inline" @if(old("status") == "0") {{ "checked" }} @endif> Inactive<br>
						</div>
						<br>
						<div class="form-group">
							<label for="internal_deadline">Internal Deadline:</label> <br>
							<input type="datetime-local" class="form-control" name="internal_deadline" value="{{ old("internal_deadline") }}"><br>
						</div>

						<div class="form-group">
							<label for="external_deadline">External Deadline:</label> <br>
							<input type="datetime-local" class="form-control" name="external_deadline" value="{{ old("external_deadline") }}" ><br>
						</div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="text-right">
							<button type="submit" class="btn btn-primary">Submit</button>
							<a href="/projects" class="btn btn-default">Go Back</a>
						</div>
					</form>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</div>
</div>

	<script>
        $(document).ready(function() {
            $('#project-name').on("keyup change", function () {
                generateKey();
            })
        } );

        function generateKey() {
            // Code To Generate Key For Project Starts
            var projectName = document.getElementById('project-name').value;
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