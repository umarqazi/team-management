
@extends('layouts.app')

@section('content')
    @hasrole(['developer', 'teamlead', 'engineer', 'frontend'])
    @yield('engineers')
    @endrole
    @hasrole(['admin', 'sales', 'pm'])
    @yield('admin')
    @endrole

@endsection