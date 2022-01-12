@extends('layouts.panel')

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$title}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.client') }}">Home</a></li>
                        <li class="breadcrumb-item">User</li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card card-info">
                    <form class="form-horizontal" action="{{ route('user.store.client') }}" method="post">
                        @csrf
                        <div class="card-body pb-0">

                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" class="form-control" id="email"
                                           placeholder="Enter Email" value="{{ old('email') }}" required>
                                    <div class="col-12 text-danger">@error('email'){{ $message }}@enderror</div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="username" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm-9">
                                    <input type="text" name="username" class="form-control" id="username"
                                           placeholder="Enter Username" value="{{ old('username') }}" required>
                                    <div class="col-12 text-danger">@error('username'){{ $message }}@enderror</div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="Enter Password" required>
                                    <div class="col-12 text-danger">@error('password'){{ $message }}@enderror</div>

                                </div>
                            </div>
                        </div>
                            <div class="card-footer text-center pb-4">
                                <button type="submit" class="btn btn-default">Cancel</button>
                                <span class="mr-3"></span>
                                <button type="submit" class="btn btn-info">{{ $title }}</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
