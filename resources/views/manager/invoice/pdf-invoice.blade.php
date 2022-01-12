<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('/dist/css/adminlte.min.css') }}">
    <script src="{{ asset('/assets/dist/html2pdf.bundle.js') }}"></script>
    <style>
        .sansserif {
                     font-family: Verdana, Arial, Helvetica, sans-serif;
                     }
    </style>
</head>
<body onload="javascript:generatePDF()">
<div class="container-fluid sansserif" id="invoice">
    <br/><br/>
    <hr style="height:30px;border-width:30px;color:#39bf3d!important;background-color:#39bf3d!important">
    <div>
    <span class="triangle_top_left float-left"></span><span class="triangle_top_right float-right"></span></div>
    <br/><br/>
    <h1 class="text-center">Invoice</h1>
    <div class="row" id="content">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="invoice p-3 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                     {{ ucwords($client->name) }}
                                    <small class="float-right ">Date: {{ $invoice->creation }}</small>
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
                                    Phone: {{ "N/A" }}<br>
                                    Email: {{ "N/A" }}
                                    @endif

                                    @if( ($invoice->vendor != NULL))
                                    {{ ($invoice->vendor->address_1 ) ?? "N/A" }}<br>
                                    {{ ($invoice->vendor->address_2 ) ?? "N/A" }}<br>
                                    Phone: {{ ($invoice->vendor->phone_num ) ?? "N/A" }}<br>
                                    Email: {{ ($invoice->vendor->personal_email ) ?? "N/A" }}
                                    @endif
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice #{{ sprintf("%05d",$invoice->id) }}</b><br>
                                <br>
                                <b>{{ ucfirst($invoice_type) }} Ref:</b> {{ strtoupper(substr($invoice->{$invoice_type}->{$invoice_type},-6,6)) }}-{{ sprintf("%04d", $invoice->{$invoice_type}->id) }}<br>
                                <b>Due Date:</b> {{ $invoice->{$invoice_type}->due_date_formatted }}<br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                    <tr>
                                        <th>Serial #</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Discount</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoice->items as $product)
                                    <tr>
                                        <td>{{ sprintf("%05d",$product->product_id) }}</td>
                                        <td>{{ ucwords($product->product->name) }}</td>
                                        <td>{{ $product->product->discount }}%</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>{{$currency}} {{ number_format($product->unit_price) }}</td>
                                        <td>{{$currency}} {{ number_format($product->total_price) }}</td>
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
    <hr style="height:30px;border-width:30px;color:#39bf3d!important;background-color:#39bf3d!important">
</div>
<br/><br/>
<!-- jQuery -->
<script src="{{ env('APP_URL', 'http://omnibiz.local') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ env('APP_URL', 'http://omnibiz.local') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    function generatePDF() {
        const element = document.getElementById("invoice");
        let opt = {
            margin:       1,
            filename:     'Invoice-{{ $invoice->creation }}.pdf',
            pagebreak:    { mode: 'css'},
            image:        { type: 'jpeg', quality: 0.98 },
        };
        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();
    }

</script>
<script>
    function number_format(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
</script>
</body>
</html>
