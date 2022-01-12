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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.manager') }}">Home</a></li>
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
                    <form class="form-horizontal" action="{{ route('category.store.manager') }}" method="post">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label">Category Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="title" class="form-control" id="title"
                                           placeholder="Enter Category Name" value="{{ old('title') }}" required>
                                    <div class="col-12 text-danger">@error('title'){{ $message }}@enderror</div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center pb-4">
                            <button type="submit" class="btn btn-default">Cancel</button>
                            <span class="mr-3"></span>
                            <button type="submit" class="btn btn-info">{{$title}}</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
