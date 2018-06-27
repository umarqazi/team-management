@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{URL::asset('css/mainProjectView.css')}}">
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md-12 projectContainer">
                <div class="projectHeader">
                    <div class="projectTitle"><h1>Friday</h1></div>
                    <ul class="operations-list">
                        <li><span>Key:</span></li>
                        <li><span>Lead:</span></li>
                        <li><span>Category:</span></li>
                    </ul>
                </div>
                <div class="projectSubheader">
                    <ul>
                        <li><span><a href="#">Overview</a></span></li>
                        <li><span><a href="#">Administration</a></span></li>
                    </ul>
                </div>
                <div class="col-md-12 content">
                    <div class="leftSideBox">
                        <ul>
                            <li><a href="#">Summary</a></li>
                            <li><a href="#">Issues</a></li>
                            <li><a href="#">Road Map</a></li>
                            <li><a href="#">Gantt Chart</a></li>
                        </ul>
                    </div>
                    <div class="rightContentBox">
                        <div class="rightHeader clearfix">
                            <div class="rightboxTop col-md-12">
                                <div id="rightboxHeading">
                                    <h3>Tasks</h3>
                                </div>
                            </div>

                            <div class="taskFilters col-md-12">
                                <ul class="col-md-12">
                                    <li class="col-lg-3"><a href="{{route('taskDetailView')}}">All Tasks</a></li>
                                    <li class="col-lg-3"><a href="{{route('taskDetailView')}}">Added Recently</a></li>
                                    <li class="col-lg-3"><a href="{{route('taskDetailView')}}">Resolved Tasks</a></li>
                                    <li class="col-lg-3"><a href="{{route('taskDetailView')}}">Un Resolved Tasks</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection