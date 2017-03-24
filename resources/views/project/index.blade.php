
@extends('master')

@section('content')

<div id="ceo">
  <h2>CEO / SALES</h2>
  <div class="content" style="margin-top: 20px;">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Team lead</th>
          <th>Developer</th>
           <th>Productive Hours</th>
        </tr>
      </thead>
      <tbody>
      @foreach($projects as $project)
        <tr>
          <td>{{$project->name}}</td>
          <td>{{$project->teamlead}}</td>
          <td>{{$project->developer}}</td>
          <td>
            {{$project->hours->sum('productive_hours')}}
          </td>
        </tr>
    @endforeach
      </tbody> 
    </table>
  </div>
</div> <!-- /ceo -->

{{--

<div id="engineering_admin">
  <h2>Engineering Admin</h2>

  <div class="content" style="margin-top: 20px;">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Technology</th>
          <th>Team lead</th>
          <th>Developer</th>
          
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      @foreach($projects as $project)
        <tr>
          <td>{{ $project->name}}</td>
          <td>{{$project->technology}}</td>
          <td>{{$project->teamlead}}</td>
          <td>{{$project->developer}}</td>
         
          <td><a href="/project_view/{{$project->id}}"> View </a></td>
        </tr>
    @endforeach
      </tbody>
    </table>
  </div>
</div>

--}}
@endsection