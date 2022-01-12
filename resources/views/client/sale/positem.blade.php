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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.client') }}">Home</a></li>
                        <li class="breadcrumb-item">Sale</li>
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
                <a href="{{ route('possale.invoice.print.client', $sales->invoice_id) }}" type="submit" class="btn btn-info toastrDefaultSuccess mr-2 btn-sm" target="btnActionIframe">
                    <i class="fa fa-print mr-1"></i> Print Invoice
                </a>
                <a href="{{ route('possale.invoice.client', $sales->invoice_id) }}" type="submit" class="btn btn-info toastrDefaultSuccess btn-sm" target="btnActionIframe">
                    <i class="far fa-file-alt mr-1"></i> Create Invoice Pdf
                </a>
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
                                        <small class="float-right">Date: {{ $sales->creation }}</small>
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
                                        <strong>{{ ucwords($sales->buyer_name) }}</strong><br>
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">

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
                                            <th>Subtotal</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($sales->items as $product)
                                        <tr>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ ucwords($product->product->name) }}</td>
                                            <td>{{ sprintf("%05d",$product->product_id) }}</td>
                                            <td>{{ $currency }}{{ number_format($product->unit_price) }}</td>
                                            <td>{{ $currency }}{{ number_format( $product->total_price) }}</td>
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
                                                <td>{{ $currency }}{{number_format($sales->total_amount - (($sales->gst/100) * $sales->total_amount)) }}</td>
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
                                                <td>{{ $currency }}{{number_format($sales->total_amount + $tax)}}</td>
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
    @if(session()->has('success'))
        <script>
            $(function () {
                toastr.success("{{ session()->get('success') }}");
            });
        </script>
    @endif

    @if(session()->has('error'))
        <script>
            $(function () {
                toastr.error("{{ session()->get('error') }}");
            });
        </script>
    @endif
    <script>
        function number_format(num)
        {
            var num_parts = num.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return num_parts.join(".");
        }
    </script>
@stop
