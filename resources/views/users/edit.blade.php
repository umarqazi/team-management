@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit {{ $user->name }}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/users/'. $user->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{--@hasrole('admin')--}}
                        @if(Auth::user()->can('edit user') && Auth::user()->id != $user->id)
                            <div class="form-group{{ $errors->has('role-name') ? ' has-error' : '' }}">
                                <label for="role-name" class="col-md-4 control-label">Role:</label>

                                <div class="col-md-6">
                                    <select name="role-name" class="form-control" required>
                                        @foreach($roles as $role)
                                            <option value="{{$role->name}}" @if( ! empty($user->roles()->pluck('name')) && in_array($role->name, $user->roles()->pluck('name')->toArray()) ) {{"selected"}} @endif>{{ucwords($role->name)}}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('role-name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('role-name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{--@endrole--}}

                        {{--@hasrole('admin')--}}
                        @if(Auth::user()->can('edit user') && Auth::user()->id != $user->id)
                        <div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
                            <label for="permissions" class="col-md-4 control-label">Permissions:</label>

                            <div class="col-md-6">
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="col-md-4">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="checkbox-inline" @if( !empty($user->permissions()->pluck('name')) && in_array($permission->name, $role_permissions)) checked disabled @endif  @if( !empty($user->permissions()->pluck('name')) && in_array($permission->name, $user->permissions()->pluck('name')->toArray())) {{ "checked" }} @endif> {{ ucwords($permission->name) }}
                                        </div>
                                    @endforeach
                                </div>

                                @if ($errors->has('permissions'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('permissions') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @endif
                        {{--@endrole--}}

                        @if(Auth::user()->can('Add Pay'))
                            <div class="form-group{{ $errors->has('pay') ? ' has-error' : '' }}">
                                <label for="pay" class="col-md-4 control-label">Add Salery</label>

                                <div class="col-md-6">
                                    <input id="pay" type="text" class="form-control" name="pay">

                                    @if ($errors->has('pay'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('pay') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> Update User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection