<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $invoice->creation }}</title>
    <style type="text/css">
        * {
            font-family:  Verdana, Arial, Helvetica, sans-serif;
        }
        table {
            font-size: small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: small;
        }
        hr.hr1 {
            border: 15px solid #eae8e4;
        }
    </style>
</head>
<hr class="hr1" />
<body onload="window.print()">
<table width="100%">
    <tr>
        <td align="center">
            <h1><b>Invoice</b></h1>
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td>
            <strong>From:</strong>
            <address>
                <strong>{{ ucwords($client->name) }}</strong><br>
                {{ ucwords($client->address_1) }}<br>
                {{ ucwords($client->address_2) }}<br>
                Phone: {{ $client->landline }}<br>
                Email: {{ $client->official_email }}
            </address>
        </td>
        <td><strong>To:</strong>
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
        </td>
        <td>
            <strong>Invoice #<b>{{ sprintf("%03d", $invoice->id) }}</b></strong>
            <br>
            <br>
        </td>
    </tr>
</table>

<br />

<table width="100%" style="text-align: left">
    <thead style="background-color: #eae8e4;">
    <tr>
        <th>Serial #</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->items as $product)
        <tr>
            <td>{{ sprintf("%05d",$product->product_id) }}</td>
            <td>{{ ucwords($product->product->name) }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $currency }} {{ number_format($product->unit_price) }}</td>
            <td>{{ $currency }} {{ number_format( $product->total_price ) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>

    <tr></tr>
    <tr>
        <td colspan="3"></td>
        <th align="right">Subtotal:</th>
        <td align="right">{{ $currency }}{{ number_format($invoice->original_amount ) }}</td>
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
    </tfoot>
</table>
<br><br><br>
<hr class="hr1" />
</body>
</html>
<script>
    function number_format(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
</script>
