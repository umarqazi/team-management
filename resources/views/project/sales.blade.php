@extends('project.index')

@section('sales')
  <div class="container">
    <div class="row">
      @if(Auth::user()->can('create project'))
        <div class="text-right" style="margin:20px;">
          <a href="/projects/create" class="btn btn-primary">Create Project</a>
        </div>
      @endif
        <ul class="nav nav-tabs" id="myTabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#content_active" id="active-tab" role="tab" data-toggle="tab" aria-controls="content_active" aria-expanded="false">Active Projects</a>
            </li>
            <li role="presentation" class="">
                <a href="#content_inactive" id="inactive-tab" role="tab" data-toggle="tab" aria-controls="content_inactive" aria-expanded="false">InActive Projects</a>
            </li>
        </ul>
      <div class="tab-content" id="myTabContent">
          <div class="content tab-pane active in" role="tabpanel" id="content_active" aria-labelledby="active-tab" style="margin: 20px;margin-top: 50px;">
            <table class="display" id="active_projects" cellspacing="0" width="100%">
              <thead>
              <tr>
                  <th>Key</th>
                  <th>Name</th>
                  <th>Technology</th>
                  <th>Team lead</th>
                  <th>Developer</th>
                  <th>Status</th>
                  <th>View</th>
                @if(Auth::user()->can('edit project'))
                  <th>Edit</th>
                @endif
                @if(Auth::user()->can('delete project'))
                  <th>Delete</th>
                @endif
              </tr>
              </thead>
              <tbody>
              @foreach($projects['active'] as $project)
                <tr>
                    <td>{{$project->key}}</td>
                  <td>{{$project->name}}</td>
                  <td>
                    @if(is_array(json_decode($project->technology)))
                      {{ @implode(", ", json_decode($project->technology)) }}
                    @else
                      {{ $project->technology }}
                    @endif
                  </td>
                  <td>{!! $project->teamlead !!}</td>
                  <td>{!! $project->developers !!}</td>
                  <td> @if( $project->status == "1")
                      <b>Active</b>
                    @else
                      <b>Inactive</b>
                    @endif
                  </td>
                  <td><a href="/projects/{{$project->id}}"> <span class="glyphicon glyphicon-eye-open"></span> </a>  </td>
                  @if(Auth::user()->can('edit project'))
                    <td><a href="/projects/{{$project->id}}/edit"> <span class="glyphicon glyphicon-edit"></span></a></td>
                  @endif
                  @if(Auth::user()->can('delete project'))
                    <td>
                      {{ Form::open(array('url' => '/projects/' . $project->id)) }}
                      {{ Form::hidden('_method', 'DELETE') }}
                      <button type="submit" class="no_button"><i class="glyphicon glyphicon-trash"></i> </button>
                      {{ Form::close() }}
                    </td>
                  @endif
                </tr>
              @endforeach
              </tbody>
            </table>
            {{--<div id="paginator" class="text-center">--}}
              {{--{{ $projects['active']->links() }}--}}
            {{--</div>--}}
          </div>

          <div class="content tab-content tab-pane" role="tablpanel" aria-labelledby="inactive-tab"
               id="content_inactive" style="margin: 20px; margin-top: 50px;">
              <table class="display"  cellspacing="0" width="100%" id="inactive_projects">
                  <thead>
                  <tr>
                      <th>Key</th>
                      <th>Name</th>
                      <th>Technology</th>
                      <th>Team lead</th>
                      <th>Developer</th>
                      <th>Status</th>
                      <th>View</th>
                      @if(Auth::user()->can('edit project'))
                          <th>Edit</th>
                      @endif
                      @if(Auth::user()->can('delete project'))
                          <th>Delete</th>
                      @endif
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($projects['inactive'] as $project)
                      <tr>
                          <td>{{$project->key}}</td>
                          <td>{{$project->name}}</td>
                          <td>
                              @if(is_array(json_decode($project->technology)))
                                  {{ @implode(", ", json_decode($project->technology)) }}
                              @else
                                  {{ $project->technology }}
                              @endif
                          </td>
                          <td>{!! $project->teamlead !!}</td>
                          <td>{!! $project->developers !!}</td>
                          <td> @if( $project->status == "1")
                                  <b>Active</b>
                              @else
                                  <b>Inactive</b>
                              @endif
                          </td>
                          <td><a href="/projects/{{$project->id}}"> <span class="glyphicon glyphicon-eye-open"></span> </a>  </td>
                          @if(Auth::user()->can('edit project'))
                              <td><a href="/projects/{{$project->id}}/edit"> <span class="glyphicon glyphicon-edit"></span></a></td>
                          @endif
                          @if(Auth::user()->can('delete project'))
                              <td>
                                  {{ Form::open(array('url' => '/projects/' . $project->id)) }}
                                  {{ Form::hidden('_method', 'DELETE') }}
                                  <button type="submit" class="no_button"><i class="glyphicon glyphicon-trash"></i> </button>
                                  {{ Form::close() }}
                              </td>
                          @endif
                      </tr>
                  @endforeach
                  </tbody>
              </table>
              {{--<div id="paginator" class="text-center">--}}
              {{--{{ $projects['inactive']->links() }}--}}
              {{--</div>--}}
          </div>

      </div> <!-- / content\-inactive -->
    </div>  <!-- myTabContent -->

  </div>

    <script>
        $('#myTabs a').click(function (e) {
            e.preventDefault()
            $(this).tab('show');
        })
    </script>
@endsection          
