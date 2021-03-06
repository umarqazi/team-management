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

                @if(Auth::user()->can('create user'))
                <div class="text-right" style="margin:20px 0px 20px 20px;">
                    <a href="/users/create" class="btn btn-primary">Create User</a>
                    @hasrole('admin')
                    {{--<button class="btn btn-primary" data-toggle="modal" data-target="#role-modal">Add Role--}}
                    {{--</button>--}}
                    @endrole
                </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">Users</div>
                    <div class="panel-body">

                        @if(Session::has('message'))
                            <script>
                                toastr.success('{{ Session::get('message') }}')
                            </script>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ Carbon\Carbon::parse($user->created_at)->format("dS \of F, Y") }}</td>
                                        <td>
                                            {{ Form::open(array('url' => '/users/' . $user->id, 'class' => '')) }}
                                            {{ Form::hidden('_method', 'DELETE') }}

                                            @if(auth()->user()->can('edit user'))
                                                <a href="{{ url('/users/'. $user->id.'/edit') }}"><span class="glyphicon glyphicon-edit"></span></a> |
                                            @endif

                                            @if(auth()->user()->can('delete user'))
                                                <button type="submit" class="no_button"><i class="glyphicon glyphicon-trash"></i></button> |
                                            @endif

                                            <a href="{{ url('/users/'. $user->id) }}"><span class="glyphicon glyphicon-eye-open"></span></a>
                                            {{ Form::close() }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.resources')

    <!-- Modal -->
    @hasrole('admin')
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
    @endrole


@endsection
