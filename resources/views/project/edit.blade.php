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
						<button type="submit" class="btn btn-primary">Submit</button>
						<a href="/projects" class="btn btn-default">Go Back</a>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection