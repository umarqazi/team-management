@extends('project.index')

@section('engineers')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                @if(Auth::user()->can('create project'))
                <div class="text-right" style="margin:20px;">
                    <a href="/projects/create" class="btn btn-primary">Create Project</a>
                </div>
                @endif
                <div class="content" style="margin-top: 20px;">
                    <table class="table">
                        <thead>
                        <tr>
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
                        @foreach($projects as $project)
                            <tr>
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
                    <div id="paginator" class="text-center">

                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
