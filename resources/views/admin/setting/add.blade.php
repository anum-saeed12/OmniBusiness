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
                        <li class="breadcrumb-item">Setting</li>
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
                    <form class="form-horizontal" action="{{ route('setting.store.admin') }}" method="post">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="setting">Setting</label><br/>
                                    <input type="text" name="setting" class="form-control" id="setting"
                                           placeholder="Example - Tax" value="{{old('setting')}}">
                                    <div class="text-danger">@error('setting'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="value">Value</label><br/>
                                    <input type="text" name="value" class="form-control" id="value"
                                           placeholder="Example- 5" value="{{old('value')}}">
                                    <div class="text-danger">@error('value'){{ $message }}@enderror</div>
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

