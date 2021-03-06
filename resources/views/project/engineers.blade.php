@extends('project.index')

@section('engineers')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                @if(Auth::user()->can('create project'))
                <div class="text-right" style="margin:20px 0px 20px 20px;">
                    <a href="/projects/create" class="btn btn-primary">Create Project</a>
                </div>
                @endif

                @if(Session::has('message'))
                    <script>
                        toastr.success('{{ Session::get('message') }}')
                    </script>
                @endif

                <div class="content" style="margin-top: 20px;">
                    <div class="table-responsive">
                        <table id="eng_projects" class="display table table-striped" cellspacing="0" width="100%">
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
                                <th>View Task</th>

                            </tr>
                            </thead>
                            {{--<tfoot>
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
                                <th>View Task</th>

                            </tr>
                            </tfoot>--}}
                            <tbody>
                            @foreach($projects as $project)
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
                                    <td><a href="/tasks/specific/{{$project->id}}" style="text-decoration: none;color: #FFFFFF;"><button class="btn btn-sm btn-success">View Tasks</button></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
