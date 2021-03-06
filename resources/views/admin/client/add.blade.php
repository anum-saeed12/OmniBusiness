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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Home</a></li>
                        <li class="breadcrumb-item">Client</li>
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
            <div class="col-md-12">
                <div class="card card-info">
                    <form class="form-horizontal" action="{{ route('client.store.admin') }}" method="POST">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name">Client Name</label><br/>
                                    <input type="text" name="name" class="form-control" id="name"
                                           placeholder="Client Name" value="{{ old('name') }}">
                                    <div class="text-danger">@error('name'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="website">Website URL</label><br/>
                                    <input type="url" name="website" class="form-control" id="website"
                                           placeholder="Example-www.omnibiz.com" value="{{ old('website') }}">
                                    <div class="text-danger">@error('website'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="username">Username</label><br/>
                                    <input type="text" name="username" class="form-control" id="username"
                                           placeholder="Username" value="{{ old('username') }}">
                                    <div class="text-danger">@error('username'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password">Password</label><br/>
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="Password">
                                    <div class="text-danger">@error('password'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email">Your Email</label><br/>
                                    <input type="email" name="email" class="form-control" id="email"
                                           placeholder="Example-admin@omni.com" value="{{ old('email') }}">
                                    <div class="text-danger">@error('email'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="official_email">Company Email</label><br/>
                                    <input type="email" name="official_email" class="form-control" id="official_email"
                                           placeholder="Example-info@omni.com" value="{{ old('official_email') }}">
                                    <div class="text-danger">@error('official_email'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="address_1">Address Line 1</label><br/>
                                    <input type="text" name="address_1" class="form-control" id="address_1"
                                           placeholder="Address Line 1" value="{{ old('address_1') }}">
                                    <div class="text-danger">@error('address_1'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="address_2">Address Line 2</label><br/>
                                    <input type="text" name="address_2" class="form-control" id="address_2"
                                           placeholder="Address Line 2" value="{{ old('address_2') }}">
                                    <div class="text-danger">@error('address_1'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ntn_number">NTN Number</label><br/>
                                    <input type="text" name="ntn_number" class="form-control"
                                           id="ntn_number" placeholder="Example-12345678" value="{{ old('ntn_number') }}">
                                    <div class="text-danger">@error('ntn_number'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="license">License Number</label><br/>
                                    <input type="text" name="license" class="form-control"
                                           id="license" placeholder="Example-A012345" value="{{ old('license') }}">
                                    <div class="text-danger">@error('license'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="mobile">Mobile</label><br/>
                                    <input type="text" name="mobile" class="form-control" id="mobile"
                                           placeholder="Example-03001234567" value="{{ old('mobile') }}">
                                    <div class=" text-danger">@error('mobile'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="landline">Landline</label><br/>
                                    <input type="text" name="landline" class="form-control" id="landline"
                                           placeholder="Example-0211234567" value="{{ old('landline') }}">
                                    <div class=" text-danger">@error('landline'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="landline">Prefix</label><br/>
                                    <input type="text" name="prefix" class="form-control" id="prefix"
                                           placeholder="Example-SUP" value="{{ old('prefix') }}">
                                    <div class=" text-danger">@error('prefix'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="overview">Bio</label><br/>
                                    <textarea class="form-control" name="overview" id="overview"
                                              placeholder="Write something about the company"></textarea>
                                    <div class="text-danger">@error('overview'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-3 text-center">
                                    <button type="submit" class="btn btn-default">Cancel</button>
                                    <span class="mr-3"></span>
                                    <button type="submit" class="btn btn-info">{{$title}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
