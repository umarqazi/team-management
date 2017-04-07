@extends('project.index')

@section('content')

<div id="ceo">
            <h2>CEO / SALES</h2>
            <div class="content" style="margin-top: 20px;">
              <table class="table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Team lead</th>
                    <th>Developer</th>
                     <th>Productive Hours</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                  <tr>
                    <td>{{$project->name}}</td>
                    <td>{{$project->teamlead}}</td>
                    <td>{{$project->developer}}</td>
                    <td>
                      {{$project->hours->sum('productive_hours')}}
                    </td>
                  </tr>
              @endforeach
                </tbody> 
              </table>
            </div>

          </div> <!-- /ceo --> 
@endsection          
