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
                        <li class="breadcrumb-item">Invoice</li>
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
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('invoice.invoice.print.'.auth()->user()->user_role, $invoice->id) }}?type={{ $invoice->invoice_type }}" type="submit" class="btn btn-info toastrDefaultSuccess mr-2 btn-sm" target="btnActionIframe">
                    <i class="fa fa-print mr-1"></i> Print Invoice
                </a>
                <a href="{{ route('invoice.invoice.'.auth()->user()->user_role, $invoice->id) }}?type={{ $invoice->invoice_type }}" type="submit" class="btn btn-info toastrDefaultSuccess btn-sm" target="btnActionIframe">
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
                                        <small class="float-right">Date: {{ $invoice->creation }}</small>
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
                                        <strong>{{ ucwords($invoice->product_supplier_and_buyer) }}</strong><br>
                                        @if( ($invoice->vendor == NULL))
                                                {{ "N/A" }}<br>
                                                {{ "N/A" }}<br>
                                        Phone:  {{ "N/A" }}<br>
                                        Email:  {{ "N/A" }}
                                        @endif

                                        @if( ($invoice->vendor != NULL))
                                        {{ ($invoice->vendor->address_1 ) ?? "N/A" }}<br>
                                        {{ ($invoice->vendor->address_2 ) ?? "N/A" }}<br>
                                        Phone: {{ ($invoice->vendor->phone_num ) ?? "N/A" }}<br>
                                        Email: {{ ($invoice->vendor->personal_email ) ?? "N/A" }}
                                        @endif

                                    </address>
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
                                        @foreach($invoice->items as $product)
                                        <tr>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ ucwords($product->product->name) }}</td>
                                            <td>{{ sprintf("%05d",$product->product_id) }}</td>
                                            <td>{{ $product->discount }}%</td>
                                            <td>{{ $currency }} {{ number_format($product->unit_price )}}</td>
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
                                                <td colspan="3"></td>
                                                <th align="right">Subtotal:</th>
                                                <td align="right">{{ $currency }}{{ number_format($invoice->original_amount ) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <th align="right">After Discount Total:</th>
                                                <td align="right">{{ $currency }}{{ number_format($invoice->total_amount ) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <th align="right">Tax ({{$gst}}%)</th>
                                                <td align="right">{{ $currency }}{{ number_format($tax)}}
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <th align="right">Shipping:</th>
                                                <td align="right">{{ $currency }}0.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <th align="right">Total:</th>
                                                <td align="right">{{ $currency }}{{number_format($invoice->total_amount + $tax)}}</td>
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
