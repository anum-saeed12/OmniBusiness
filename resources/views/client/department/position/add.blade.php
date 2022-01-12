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
                    <li class="breadcrumb-item">Position</li>
                    <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <form class="form-horizontal" action="{{ route('job.store.'.auth()->user()->user_role) }}" method="POST">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="title">Job Position Title </label> <br/>
                                    <input type="text" name="title" class="form-control" id="title"
                                           placeholder="Enter Job Position " value="{{old('title')}}" >
                                    <div class="text-danger">@error('title'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="department_id">Department Name</label><br/>
                                    <select name="department_id" id="department_id" class="form-control" >
                                        <option selected="selected" value>Select</option>
                                        @foreach ($departments as $department)
                                        <option value="{{ $department->departments->id }}">{{ ucwords($department->departments->name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">@error('department_id'){{ $message }}@enderror</div>
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

