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
            <!-- left column -->
            <div class="col-md-10 offset-md-1">
                <!-- general form elements -->
                <div class="card card-info">
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal" action="{{ route('client.store.admin') }}" method="post">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="form-group row">
                                <label for="client_name" class="col-sm-2 col-form-label">Client Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" id="client_name"
                                           placeholder="Enter Client Name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-12 text-danger">@error('client_name'){{ $message }}@enderror</div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" id="email"
                                           placeholder="Enter Email" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-12 text-danger">@error('email'){{ $message }}@enderror</div>
                            </div>

                            <div class="form-group row">
                                <label for="username" class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" name="username" class="form-control" id="username"
                                           placeholder="Enter Username" value="{{ old('username') }}" required>
                                </div>
                                <div class="col-12 text-danger">@error('username'){{ $message }}@enderror</div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="Enter Password" required>
                                </div>
                                <div class="col-12 text-danger">@error('password'){{ $message }}@enderror</div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="ntn_number" class="col-form-label">NTN Number</label>
                                        <br/>

                                        <input type="text" name="ntn_number" class="form-control"
                                               id="ntn_number" placeholder="Enter NTN Number">
                                    </div>
                                    <div class="col-12 text-danger">@error('ntn_number'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="license" class="col-form-label">License Number</label><br/>
                                        <input type="text" name="license" class="form-control"
                                               id="license" placeholder="Enter License Number">
                                    </div>
                                    <div class="col-12 text-danger">@error('license'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="website" class="col-sm-2 col-form-label">Website URL</label>
                                <div class="col-sm-10">
                                    <input type="url" name="website" class="form-control" id="website"
                                           placeholder="Enter Website Url">
                                </div>
                                <div class="col-12 text-danger">@error('website'){{ $message }}@enderror</div>
                            </div>

                            <div class="form-group row">
                                <label for="overview" class="col-sm-2 col-form-label">Bio</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="overview" id="overview"
                                              placeholder="Write something about the company"></textarea>
                                </div>
                                <div class="col-12 text-danger">@error('overview'){{ $message }}@enderror</div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center pb-4">
                            <button type="submit" class="btn btn-default">Cancel</button>
                            <span class="mr-3"></span>
                            <button type="submit" class="btn btn-info">Add Client</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
@stop
