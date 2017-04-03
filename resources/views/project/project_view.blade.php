@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        <h2>{{$project->name}}</h2>
          <div class="content" style="margin-top: 40px;">
          
            <table class="table">
              <thead>
                <tr>
                  <th>Month</th>
                  <th>Team lead</th>
                  <th>Developer</th>
                  <th>Actual Hours</th>
                  <th>Productive Hours</th>
                </tr>
              </thead>
              <tbody>
            
                @foreach($hours as $hour)
                <tr>
                  <td>{{$hour['month']}}</td>
                  <td>{{$project->teamlead}}</td>
                  <td>{{$project->developers}}</td>
                  <td>{{$hour['actual_hours']}}</td>
                  <td>{{$hour['productive_hours']}}</td>
                </tr>
                @endforeach
           
              </tbody>
            </table>
            <!-- Trigger the modal with a button -->
            <div class="text-center" style="margin-top: 50px;">
            	<button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add Hours
              </button>
            	<a href="/projects" class="btn btn-default">Go Back</a>
            </div>

          </div>
       </div></div></div>   


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Enter Hours</h4>
          </div>
          <form action="/project/{{$project->id}}" method="POST">
              <div class="modal-body">
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