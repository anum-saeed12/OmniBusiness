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
                <div class="col-md-12">
                    <div class="card card-info">
                        <form class="form-horizontal" action="{{ route('product.update.manager',$product->id) }}" method="post">
                            @csrf
                            <div class="card-body pb-0">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="name">Product Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                               placeholder="Enter Product Name" value="{{ old('name', ucwords($product->name)) }}" required>
                                        <div class="text-danger">@error('name'){{ $message }}@enderror</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="unit">Product Unit</label><br/>
                                        <select name="unit" class="form-control" id="unit">
                                            <option value="lb"  {{ $product->unit == 'lb'  ? ' selected="selected"' : '' }}>LB</option>
                                            <option value="g"   {{ $product->unit == 'g'   ? ' selected="selected"' : '' }}>Gram</option>
                                            <option value="kg"  {{ $product->unit == 'kg'  ? ' selected="selected"' : '' }}>Kilogram</option>
                                            <option value="ton" {{ $product->unit == 'ton' ? ' selected="selected"' : '' }}>Ton</option>
                                            <option value="ml"  {{ $product->unit == 'ml'  ? ' selected="selected"' : '' }}>Millilitre</option>
                                            <option value="lt"  {{ $product->unit == 'lt'  ? ' selected="selected"' : '' }}>Litre</option>
                                            <option value="pc"  {{ $product->unit == 'pc'  ? ' selected="selected"' : '' }}>Piece</option>
                                        </select>
                                        <div class="text-danger">@error('unit'){{ $message }}@enderror</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="in_stock" >Stock</label><br/>
                                        <input type="number" min='0' name="in_stock" class="form-control" id="in_stock"
                                               placeholder="Enter Stock " value="{{ old('in_stock', $product->in_stock) }}" required>
                                        <div class="text-danger">@error('in_stock'){{ $message }}@enderror</div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="unit_price" >Price</label><br/>
                                        <input type="number" min='0' name="unit_price" class="form-control" id="unit_price"
                                               placeholder="Enter Stock " value="{{ old('unit_price', $product->unit_price) }}" required>
                                        <div class="text-danger">@error('unit_price'){{ $message }}@enderror</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="category_id" >Category</label><br/>
                                        <select name="category_id" id="category_id" class="form-control">
                                            @foreach ($category as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $product->category->category_id ? ' selected="selected" ' : '' }}> {{ ucwords($item->title)}}</option>
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
