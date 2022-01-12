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
                        <li class="breadcrumb-item">Quotation</li>
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
                                        <small class="float-right">Date: {{ $quotation->creation }}</small>
                                    </h4>
                                </div>
                            </div>
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    From
                                    <address>
                                        <strong>{{ ucwords($client->name) }}</strong><br>
                                        {{ ucwords($client->address_1) }}<br>
                                        {{ ucwords($client->address_2) }}<br>
                                        Phone: {{ $client->landline }}<br>
                                        Email: {{ $client->official_email }}
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    To
                                    <address>
                                        <strong>{{ ucwords($quotation->company) }}</strong><br>
                                        @if( ($quotation->vendor == NULL))
                                                {{ "N/A" }}<br>
                                                {{ "N/A" }}<br>
                                        Phone:  {{ "N/A" }}<br>
                                        Email:  {{ "N/A" }}
                                        @endif

                                        @if( ($quotation->vendor != NULL))
                                        {{ (ucfirst($quotation->vendor->address_1 )) ?? "N/A" }}<br>
                                        {{ (ucfirst($quotation->vendor->address_2 )) ?? "N/A" }}<br>
                                        Phone: {{ ($quotation->vendor->phone_num ) ?? "N/A" }}<br>
                                        Email: {{ ($quotation->vendor->personal_email ) ?? "N/A" }}
                                        @endif

                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    <label for="approved"> Quotation Status</label> <br/>

                                    @if($quotation->accepted_at == NULL && $quotation->rejected_at == NULL )
                                        Status:
                                        <b class="text-warning">Pending</b>
                                        <br><br>
                                        <form action="{{ route('quotation.accept.'.auth()->user()->user_role, $quotation->id) }}" method="post" id="_formAccept">@csrf</form>
                                        <form action="{{ route('quotation.reject.'.auth()->user()->user_role, $quotation->id) }}" method="post" id="_formReject">@csrf</form>

                                        <a class="btn btn-success" onclick="$('#_formAccept').submit();return false;" style="top:3px; color:white" href="{{ route('quotation.accept.'.auth()->user()->user_role, $quotation->id) }}">Accept Now</a>
                                        <a class="btn btn-danger" onclick="$('#_formReject').submit();return false;" style="top:3px; color:white" href="{{ route('quotation.reject.'.auth()->user()->user_role, $quotation->id) }}">Reject Now</a>
                                    @endif

                                    @if($quotation->accepted_at != NULL)
                                    Status:
                                    <b class="text-success">Accepted </b>
                                    <br/>
                                    Date: {{ $quotation->approval }}
                                    @endif

                                    @if($quotation->rejected_at != NULL )
                                    Status:
                                    <b class="text-danger">Rejected</b>
                                    <br/>
                                    Date: {{ $quotation->rejection }}
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
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Subtotal</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($quotation->items as $product)
                                        <tr>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ ucwords($product->product->name) }}</td>
                                            <td>{{ sprintf("%05d",$product->product_id) }}</td>
                                            <td>{{ $currency }} {{ number_format($product->unit_price) }}</td>
                                            <td>{{ $product->discount }}%</td>
                                            <td>{{ $currency }} {{ number_format($product->total_price) }}</td>
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
                                                <td>{{ $currency }}{{number_format($quotation->original_amount)}}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:50%">After Discount Total:</th>
                                                <td>{{ $currency }}{{ number_format($quotation->total_amount) }}</td>
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
                                                <td>{{ $currency }}{{number_format($tax + $quotation->total_amount)}}</td>
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
@section('extras')
<script>
    function number_format(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
</script>
    @stop
