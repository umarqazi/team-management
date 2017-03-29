@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>

                <div class="panel-body">
                   <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td><a href="{{ url('/users/'. $user->id.'/edit') }}"><span class="glyphicon glyphicon-edit"></span> Edit</a></td>
                            <td>
                                {{ Form::open(array('url' => '/users/' . $user->id, 'class' => 'pull-right')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button type="submit" class="btn btn-warning"><i class="glyphicon glyphicon-trash"></i>Delete</button>
                                {{ Form::close() }}
                            </td>
                          </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
 <div class="row">
 <div class="col-md-10 col-md-offset-1">
  <div class="panel panel-default">
      <div class="panel-heading">Projects</div>
        <div class="panel-body">
        <div class="content" style="margin-top: 20px;">
          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Technology</th>
                <th>Team lead</th>
                <th>Developer</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
              <tr>
                <td>{{$project->name}}</td>
                <td>{{$project->technology}}</td>
                <td>{{$project->teamlead}}</td>
                <td>{{$project->developer}}</td>
               
                <td><a href="/project/{{$project->id}}"> <span class="glyphicon glyphicon-eye-open"></span> </a>  </td>
                <td><a href="/project/{{$project->id}}/edit"> <span class="glyphicon glyphicon-edit"></span></a></td> 
               <td>
                 {{ Form::open(array('url' => '/projects/' . $project->id)) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    <button type="submit" class="btn btn-warning"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                {{ Form::close() }}
              </td>
            </tr>
          @endforeach
            </tbody>
          </table>
          <div id="paginator" class="text-center">
     
            </div>
        </div>
        </div>
        </div>
        </div>
</div>
</div>
</div>

@endsection