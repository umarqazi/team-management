@extends('master')

@section('content')

<div class="row">
	<div class="col-md-12">
		<h2>Add New Project</h2>
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
		    <label for="teamlead">Team lead:</label>
		    <input type="text" name="teamlead" class="form-control" id="teamlead">
		  </div>
		   <div class="form-group">
		    <label for="developer">Developer:</label>
		    <input type="text" name="developer" class="form-control" id="developer">
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




@endsection