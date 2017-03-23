@extends('master')

@section('content')

<h2>{{$project->name}}</h2>

<div class="content" style="margin-top: 20px;">
  <table class="table">
    <thead>
      <tr>
        <th>Month</th>
        <th>Team lead</th>
        <th>Developer</th>
        <th>Actual Hours</th>
        <th>Productive Hours</th>

        <!-- <th>Action</th> -->
      </tr>
    </thead>
    <tbody>
   
      <tr>
      <!--   <td>{{$project->created_at->format('d-m-Y')}}</td> -->
        <td>{{$project->created_at->diffForHumans()}}</td>
        <td>{{$project->teamlead}}</td>
        <td>{{$project->developer}}</td>
        <td>{{$sum_actual_hours}}</td>
        <td>{{$sum_productive_hours}}</td>
      </tr>
 
    </tbody>
  </table>
  <!-- Trigger the modal with a button -->
  <div class="text-center" style="margin-top: 50px;">
  	<button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add Hours
    </button>
  	<a href="/projects" class="btn btn-default">Go Back</a>
  	<!-- <button class="btn btn-default">Go Back</button> -->
  </div>

</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Hours</h4>
      </div>
      <div class="modal-body">
      <form action="/project_view/{{$project->id}}" method="POST">
         <div class="form-group">
          <label for="actual_hours">Actual Hours:</label>
          <input type="text" name="actual_hours" class="form-control" id="actual_hours">
        </div>
         <div class="form-group">
          <label for="productive_hours">Productive Hours:</label>
          <input type="text" name="productive_hours" class="form-control" id="productive_hours">
        </div>
      <input type="hidden" name="project_id" value="{{$project->id}}">
      </div>
      <div class="modal-footer">
       <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <button type="submit" class="btn btn-default">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>


@endsection