@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="text-right" style="margin:20px;">
                <a href="/users/create" class="btn btn-primary">Create User</a>
                <button class="btn btn-primary" data-toggle="modal" data-target="#role-modal">Add Role
                </button>
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
                                {{ Form::open(array('url' => '/users/' . $user->id, 'class' => '')) }}
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
<!-- Modal -->
<div id="role-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Role</h4>
            </div>
            <form action="/roles" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Role Title:</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>
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
