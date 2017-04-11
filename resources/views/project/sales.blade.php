@extends('project.index')

@section('sales')
  <div class="container">
    <div class="row">

            <div class="content" style="margin-top: 20px;">
              <table class="table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Team lead</th>
                    <th>Developer</th>
                     <th>Productive Hours</th>
                    <th>View</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                  <tr>
                    <td>{{$project->name}}</td>
                    <td>{{$project->teamlead}}</td>
                    <td>{{$project->developers}}</td>
                    <td>
                      {{$project->hours->sum('productive_hours')}}
                    </td>
                    <td><a href="/project/{{$project->id}}"> <span class="glyphicon glyphicon-eye-open"></span> </a>  </td>
                  </tr>
              @endforeach
                </tbody> 
              </table>
            </div>

          </div>
    </div>
  </div>
@endsection          
