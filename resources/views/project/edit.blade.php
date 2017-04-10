@extends('master')

@section('content')

<div class="row">
	<div class="col-md-12">
		<h2>Edit Details</h2>
		<hr>
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

		<form action="/projects/{{ $project->id }}" method="POST" style="margin-top: 50px;">
	<!-- 	 <input type="hidden" name="_method" value="PUT"> -->
		 {{ method_field('PUT') }}
		  <input type="hidden" name="_token" value="{{ csrf_token() }}">
		  <div class="form-group">
		    <label for="name">Project Name:</label>
		    <input type="text" name="name" class="form-control" id="name" 
		    value="{{$project->name}}">
		  </div>
		  <div class="form-group">
		    <label for="technology">Technology:</label>
		    <input type="text" name="technology" value="{{$project->technology}}" class="form-control" id="technology">
		  </div>
			<div class="form-group">
				<label for="sel1">Team Lead:</label>
				<select class="form-control" id="teamlead" name="teamlead" required>
					<option>Select</option>
					@foreach($teamleads as $teamlead)
						{{--<option value="{{$teamlead->id}}" @if($teamlead->id == $project->teamlead->id) {{"selected"}} @endif >{{$teamlead->name}}</option>--}}
						<option value="{{$teamlead->id}}">{{$teamlead->name}}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label for="sel1">Developer:</label>
				<select class="form-control" id="developer" name="developer" required>
					<option>Select Developer</option>
					@foreach($developers as $developer)
						{{--<option value="{{$developer->id}}" @if($developer->id == $project->developer->id) {{"selected"}} @endif >{{$developer->name}}</option>--}}
						<option value="{{$developer->id}}" >{{$developer->name}}</option>
					@endforeach
				</select>
			</div>
		   <div class="form-group">
		   <textarea class="form-control" name="description" rows="2" placeholder="Enter Project Description.">{{$project->description}}</textarea>
		  </div>
			<div class="form-group">
				<label for="sel1">Status:</label> <br>
				<input type="radio" name="status" value="1" class="radio-inline" checked> Active
				<input type="radio" name="status" value="0" class="radio-inline"> Inactive<br>
			</div>
		  <div class="text-right">
		  <button type="submit" class="btn btn-primary">Submit</button>
		  <a href="/projects" class="btn btn-default">Go Back</a>
		  </div>
		</form>
	</div>
	<div class="col-md-3"></div>
</div>
@endsection