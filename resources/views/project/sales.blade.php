@extends('project.index')

@section('sales')

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

          </div>
@endsection          
