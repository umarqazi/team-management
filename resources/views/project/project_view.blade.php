@extends('master')

@section('content')

<h2>{{$project->name}}</h2>

<div class="content" style="margin-top: 20px;">
  <table class="table">
    <thead>
      <tr>
        
        <th>Team lead</th>
        <th>Developer</th>
        <th>Actual Hours</th>
        <th>Productive Hours</th>

        <!-- <th>Action</th> -->
      </tr>
    </thead>
    <tbody>
   
      <tr>
        <td>{{$project->teamlead}}</td>
        <td>{{$project->developer}}</td>
        <td><p> 8 </p></td>
        <td><p> 8 </p></td>
      </tr>
 
    </tbody>
  </table>
  <div class="text-center">
  	<button class="btn btn-primary">Edit</button>
  	<a href="/projects"><button class="btn btn-default">Go Back</button</a>
  	<!-- <button class="btn btn-default">Go Back</button> -->
  </div>

</div>

@endsection