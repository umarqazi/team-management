
@extends('master')

@section('content')

<h2>CEO / SALES</h2>
<div class="content" style="margin-top: 20px;">
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Team lead</th>
        <th>Developer</th>
        <!--  <th>Actual Hours</th> -->
        <!-- <th>Action</th> -->
      </tr>
    </thead>
    <tbody>
    @foreach($projects as $project)
      <tr>
        <td>{{$project->name}}</td>
        <td>{{$project->teamlead}}</td>
        <td>{{$project->developer}}</td>
        <!-- <td><p> 8 </p></td> -->
     <!--    <td><a href=""> View </a></td> -->
      </tr>
  @endforeach
    </tbody>
  </table>
<p> </p> 
</div>


@endsection