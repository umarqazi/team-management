@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{URL::asset('css/login.css')}}" type="text/css" media="all">
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 loginBox">
                <div class="login">
                    <div class="login-heading"><h1>Login</h1></div>
                    <div class="login-body">
                        <form class="form-horizontal loginForm" role="form" method="POST" action="{{ url('/login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="group">

                                    <input id="email" type="email" class="email" name="email" value="{{ old('email') }}"><span class="highlight"></span><span class="bar"></span>
                                    <label class="control-label loginLabels">Email</label>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="group">
                                    <input id="password" type="password" class="password" name="password" value="{{ old('password') }}"><span class="highlight"></span><span class="bar"></span>
                                    <label class="control-label loginLabels">Password</label>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="checkbox loginCheckbox">
                                        <label>
                                            <input type="checkbox" >
                                            <span class="box"></span>
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group login_with">
                                <div class="col-md-8 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i> Login
                                    </button>

                                    <a href="{{ url('redirect/google') }}" class="btn btn-danger btn-social btn-google"><span class="fa fa-google"></span> | Sign in with Google</a>
                                    <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row login-footer">
                        <div class="footerContent">
                            <span>Not A Member?</span>
                            <a href="{{url('/register')}}"><span><strong>Register Now!</strong></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{URL::asset('js/login.js')}}"></script>
@endsection
