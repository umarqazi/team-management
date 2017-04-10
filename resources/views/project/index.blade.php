
@extends('layouts.app')

@section('content')

  @hasrole(['developer', 'teamlead', 'engineer'])
  @yield('engineers')
  @else('sales')
    @yield('sales')
    @endrole

{{--<div class="container">--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-10 col-md-offset-1">--}}
          {{--<div id="ceo">--}}
            {{--<div class="content" style="margin-top: 20px;">--}}
              {{--<table class="table">--}}
                {{--<thead>--}}
                  {{--<tr>--}}
                    {{--<th>Name</th>--}}
                    {{--<th>Team lead</th>--}}
                    {{--<th>Developer</th>--}}
                    {{--<th>Productive Hours</th>--}}
                  {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody>--}}
                {{--@foreach($projects as $project)--}}
                  {{--<tr>--}}
                    {{--<td>{{$project->name}}</td>--}}
                    {{--<td>{{$project->teamlead}}</td>--}}
                    {{--<td>{{$project->developers}}</td>--}}
                    {{--<td>--}}
                      {{--{{$project->hours->sum('productive_hours')}}--}}
                    {{--</td>--}}
                  {{--</tr>--}}
              {{--@endforeach--}}
                {{--</tbody> --}}
              {{--</table>--}}
            {{--</div>--}}

          {{--</div> <!-- /ceo --> --}}
{{--</div></div></div>--}}


@endsection