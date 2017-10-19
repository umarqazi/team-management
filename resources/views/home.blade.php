@extends('layouts.app')
@section('content')
    @hasrole(['developer', 'teamlead', 'engineer','pm','frontend'])
        @yield('engineers')
    @endrole
    @hasrole('admin')
        @yield('admin')
    @else('sales')
        @yield('sales')
    @endrole
    @include('dashboard.resources')
@endsection
