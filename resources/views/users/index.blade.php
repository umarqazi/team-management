@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="text-right" style="margin-top:20px;">
                <a href="/users/create" class="btn btn-primary">Create User</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>
                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
                          <strong>Success!</strong> {{ Session::get('message') }}
                        </div>
                    @endif
                   <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>View</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
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
                            <td><a href="{{ url('/users/'. $user->id) }}"><span class="glyphicon glyphicon-eye-open"></span></a></td>
                          </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
