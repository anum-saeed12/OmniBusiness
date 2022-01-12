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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.'.auth()->user()->user_role) }}">Home</a></li>
                        <li class="breadcrumb-item">Edit Vendor </li>
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
                    <form class="form-horizontal" action="{{ route('vendor.update.'.auth()->user()->user_role,$vendor->id) }}" method="post">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="name">Vendor Name</label><br/>
                                    <input type="text" name="name" class="form-control" id="name"
                                           placeholder="Vendor Name" value="{{ $vendor->name }}">
                                    <div class="text-danger">@error('name'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="phone_num">Phone Number</label><br/>
                                    <input type="text" name="phone_num" class="form-control" id="phone_num"
                                           placeholder="00000000000000" value="{{ $vendor->phone_num }}">
                                    <div class="text-danger">@error('phone_num'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="personal_email">Vendor Email</label><br/>
                                    <input type="email" name="personal_email" class="form-control" id="personal_email"
                                           placeholder="Example-admin@omni.com" value="{{ $vendor->personal_email }}">
                                    <div class="text-danger">@error('personal_email'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="address_1">Address Line 1</label><br/>
                                    <input type="text" name="address_1" class="form-control" id="address_1"
                                           placeholder="Address Line 1"  value="{{ $vendor->address_1 }}">
                                    <div class="text-danger">@error('address_1'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="address_2">Address Line 2</label><br/>
                                    <input type="text" name="address_2" class="form-control" id="address_2"
                                           placeholder="Address Line 2" value="{{ $vendor->address_2 }}">
                                    <div class="text-danger">@error('address_1'){{ $message }}@enderror</div>
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
