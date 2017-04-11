
@extends('layouts.app')

@section('content')

  @hasrole(['developer', 'teamlead', 'engineer'])
  @yield('engineers')
  @else('sales')
    @yield('sales')
    @endrole


@endsection