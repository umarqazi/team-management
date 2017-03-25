
@extends('master')

@section('content')
<h2>Engineering Admin</h2>
 <div class="text-right">
  <a href="/project/create">Create Project</a>
  </div>

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
        <td>{{$project->name}}</td>
        <td>{{$project->technology}}</td>
        <td>{{$project->teamlead}}</td>
        <td>{{$project->developer}}</td>
       
        <td><a href="/project_view/{{$project->id}}"> View </a></td>
      </tr>
  @endforeach
    </tbody>
  </table>
</div>
@endsection
