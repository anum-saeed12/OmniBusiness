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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.' . auth()->user()->user_role) }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('purchase.list.' . auth()->user()->user_role) }}">Purchases</a></li>
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
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('purchase.invoice.print.' . auth()->user()->user_role, $purchase->invoice_id) }}" type="submit" class="btn btn-info toastrDefaultSuccess mr-2 btn-sm" target="btnActionIframe"><i class="fa fa-print mr-1"></i> Print Invoice</a>
                <a href="{{ route('purchase.invoice.' . auth()->user()->user_role, $purchase->invoice_id) }}" type="submit" class="btn btn-info toastrDefaultSuccess btn-sm" target="btnActionIframe"><i class="far fa-file-alt mr-1"></i> Create Invoice Pdf</a>
                <iframe name="btnActionIframe" style="display:none;" onload="setTimeout(function(){this.src=''},1000)"></iframe>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if(session()->has('success'))
                <div class="callout callout-success" style="color:green">
                    {{ session()->get('success') }}
                </div>
                @endif
                @if(session()->has('error'))
                <div class="callout callout-danger" style="color:red">
                    {{ session()->get('error') }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body p-0">
                        <div class="invoice p-3 mb-3">
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        {{ ucwords($client->name) }}
                                        <small class="float-right">Date: {{ $purchase->creation }}</small>
                                    </h4>
                                </div>
                            </div>
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    From
                                    <address>
                                        <strong>{{ ucwords($purchase->supplier_name) }}</strong><br>
                                        @if( ($purchase->vendor == NULL))
                                        {{ "N/A" }}<br>
                                        {{ "N/A" }}<br>
                                        Phone:  {{ "N/A" }}<br>
                                        Email:  {{ "N/A" }}
                                        @endif

                                        @if( ($purchase->vendor != NULL))
                                        {{ ($purchase->vendor->address_1 ) ?? "N/A" }}<br>
                                        {{ ($purchase->vendor->address_2 ) ?? "N/A" }}<br>
                                        Phone: {{ ($purchase->vendor->phone_num ) ?? "N/A" }}<br>
                                        Email: {{ ($purchase->vendor->personal_email ) ?? "N/A" }}
                                        @endif

                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    To
                                    <address>
                                        <strong>{{ ucwords($client->name) }}</strong><br>
                                        {{ ucwords($client->address_1) }}<br>
                                        {{ ucwords($client->address_2) }}<br>
                                        Phone: {{ $client->landline }}<br>
                                        Email: {{ $client->official_email }}
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    <label for="approved"> Purchase Order Status</label> <br/>

                                    Status:
                                    @if( (\Carbon\Carbon::now()->toDateString() ) > (\Carbon\Carbon::createFromTimeStamp(strtotime($purchase->due_date))->format('Y-m-d')))
                                    <b class="text-success"> Completed</b><br/>
                                    @endif

                                    @if( (\Carbon\Carbon::now()->toDateString() ) < (\Carbon\Carbon::createFromTimeStamp(strtotime($purchase->due_date))->format('Y-m-d')))
                                    <b class="text-warning"> Pending</b><br/>
                                    @endif

                                    @if( (\Carbon\Carbon::now()->toDateString() ) == (\Carbon\Carbon::createFromTimeStamp(strtotime($purchase->due_date))->format('Y-m-d')))
                                    <b class="text-danger"> Due Today</b><br/>
                                    @endif

                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th>Qty</th>
                                            <th>Product</th>
                                            <th>Serial #</th>
                                            <th>Discount</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($purchase->items as $product)
                                        <tr>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ ucwords($product->product->name) }}</td>
                                            <td>{{ sprintf("%05d",$product->product_id) }}</td>
                                            <td>{{ number_format($product->discount)}}%</td>
                                            <td>Rs.{{ number_format($product->unit_price) }}</td>
                                            <td>{{ number_format($product->total_price) }}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <p class="lead">Amount</p>
                                    <div class="table-responsive table-sm">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td>{{ $currency }}{{number_format($purchase->total_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:50%">After Discount Total:</th>
                                                <td>{{ $currency }}{{number_format($purchase->original_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tax ({{$gst}}%)</th>
                                                <td>{{ $currency  }}{{  number_format($tax) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping:</th>
                                                <td>{{ $currency }}0.00</td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td>{{ $currency }}{{number_format($purchase->total_amount + $tax)}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
