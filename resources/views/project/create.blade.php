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
		    <input type="text" name="name" class="form-control" id="name">
		  
		  </div>
		  <div class="form-group">
		    <label for="technology">Technology:</label>
		    <input type="text" name="technology" class="form-control" id="technology">
		  </div>
		 
		  <div class="form-group">
		    <label for="sel1">Team Lead:</label>
		    <select class="form-control" id="teamlead" name="teamlead">
		    <option value="">Select Team Lead</option>
		    @foreach($teamleads as $teamlead)
		      <option value="{{$teamlead->id}}">{{$teamlead->name}}</option>
		  	@endforeach
		    </select>
		  </div>

		  <div class="form-group">
		    <label for="sel1">Developer:</label>
		    <select class="form-control" id="developer" name="developer">
		    <option value="">Select Developer</option>
		    @foreach($developers as $developer)
		      <option value="{{$developer->id}}">{{$developer->name}}</option>
		  	@endforeach
		    </select>
		  </div>
		   <div class="form-group">
		   <textarea class="form-control" name="description" rows="2" placeholder="Enter Project Description."></textarea>
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
</div></div></div>




@endsection