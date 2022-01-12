@extends('layouts.basic')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ $base_url }}">{{ config('app.name') }}<span class="text-primary">.</span></a>
        </div>
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="{{ route('auth.login') }}" method="post">
                    @csrf
                    <div class="input-group">
                        <input name="email" type="email"
                               class="form-control" placeholder="Email"
                               value="{{ old('email') }}" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger">@error('email'){{ $message }}@enderror</div>
                    <div class="input-group mt-3">
                        <input name="password" type="password"
                               class="form-control" placeholder="Password"
                               required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger">@error('password'){{ $message }}@enderror</div>
                    <div class="mb-3"></div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input name="remember" type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                {{-- @include('includes.social') --}}

                <p class="mb-1">
                    <a href="{{ route('forgot') }}">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@stop
