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
                        <li class="breadcrumb-item">Product</li>
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
                    <form class="form-horizontal" action="{{ route('product.store.'.auth()->user()->user_role) }}" method="POST">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="name">Product Name</label><br/>
                                    <input type="text" name="name" class="form-control" id="name"
                                           placeholder="Enter Product Name" value="{{ old('name') }}">
                                    <div class="text-danger">@error('name'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="unit">Unit</label><br/>
                                    <select name="unit" class="form-control" id="unit">
                                        <option selected="selected" value>Select</option>
                                        <option value="lb">LB</option>
                                        <option value="g">Gram</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="ton">Ton</option>
                                        <option value="ml">Millilitre</option>
                                        <option value="lt">Litre</option>
                                        <option value="pc">Piece</option>
                                    </select>
                                    <div class="text-danger">@error('unit'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="unit_price" >Unit Price</label><br/>
                                    <input type="number" min='0' name="unit_price" class="form-control" id="unit_price"
                                           placeholder="Enter Price " value="{{ old('unit_price') }}">
                                    <div class="text-danger">@error('unit_price'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="in_stock" >Stock</label><br/>
                                    <input type="number" min='0' name="in_stock" class="form-control" id="in_stock"
                                           placeholder="Enter Stock " value="{{ old('in_stock') }}">
                                    <div class="text-danger">@error('in_stock'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="category_id" >Category</label><br/>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option selected="selected" value>Select</option>
                                        @foreach ($category as $item)
                                        <option value="{{ $item->id }}">{{ ucwords($item->title) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">@error('category_id'){{ $message }}@enderror</div>
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
