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
                        <li class="breadcrumb-item">Petty Cash Book</li>
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
                    <form class="form-horizontal" action="{{ route(auth()->user()->user_role . '.petty-cash.store') }}" method="post">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="description">Description</label><br/>
                                        <input type="text" name="description" class="form-control" id="description"
                                               placeholder="" value="{{old('description')}}">
                                        <div class="text-danger">@error('description'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label><br/>
                                        <input type="text" name="quantity" class="form-control" id="quantity"
                                               placeholder="" value="{{old('quantity')}}">
                                        <div class="text-danger">@error('quantity'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="unit_price">Unit Price</label><br/>
                                                <input type="text" name="unit_price" class="form-control" id="unit_price"
                                                       placeholder="" value="{{old('unit_price')}}">
                                                <div class="text-danger">@error('unit_price'){{ $message }}@enderror</div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="total_price">Total Amount</label><br/>
                                                <input type="text" name="total_price" class="form-control" id="total_price"
                                                       placeholder="" value="{{old('total_price')}}">
                                                <div class="text-danger">@error('total_price'){{ $message }}@enderror</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="value">Value</label><br/>
                                        <input type="text" name="value" class="form-control" id="value"
                                               placeholder="Example- 5" value="{{old('value')}}">
                                        <div class="text-danger">@error('value'){{ $message }}@enderror</div>
                                    </div>
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

