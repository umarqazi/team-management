@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@hasrole(['developer', 'teamlead', 'engineer'])
    @yield('engineers')
@endrole
@hasrole('admin')
    @yield('admin')
@else('sales')
    @yield('sales')
@endrole
    @include('dashboard.resources')
@endsection