
@extends('layouts.app')

@section('content')
  @hasrole(['developer', 'teamlead', 'engineer'])
    @yield('engineers')
  @else('sales')
    @yield('sales')
  @endrole
  @include('dashboard.resources')


@endsection