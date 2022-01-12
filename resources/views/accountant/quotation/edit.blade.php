@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                <div class="alert alert-danger">
                    <p><strong>Opps Something went wrong</strong></p>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="card card-info">
                    <form class="form-horizontal" action="{{ route('sale.store.client') }}" method="post">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="quotation_id">Quotation</label>
                                            <select class="form-control" id="quotation_id" name="quotation_id">
                                                <option value="">No quotation selected</option>
                                                @foreach($quotations as $quotation)
                                                <option value="{{ $quotation->id }}">{{ $quotation->company }} | {{$currency}}{{ $quotation->total_amount }} | {{ \Carbon\Carbon::createFromTimeStamp(strtotime($quotation->created_at))->format('Y/m/d h:i') }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger">@error('quotation_id'){{ $message }}@enderror</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="due_date">Due Date</label>
                                            <div class="input-group date" id="due_date" data-target-input="nearest">
                                                <input type="text" id="due_date" name="due_date"
                                                       class="form-control datetimepicker-input"
                                                       data-target="#due_date" placeholder="yyyy-mm-dd"
                                                       data-toggle="datetimepicker" autocomplete="off" aria-autocomplete="off"/>
                                                <div class="input-group-append" data-target="#due_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="approved">Quotation Status</label><br/>
                                            <div class="quotation-status"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="quotation-info"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center pb-4">
                            <button type="submit" class="btn btn-info">{{ $title }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('extras')
<script>
    let selected = '', quotation;
    @client
    function accept_quotation(e)
    {
        let quotation_id = e.data('id');
        let status = $('.quotation-status'), info = $('.quotation-info');
        event.preventDefault();
        $.ajax({
            url: "{{ env('APP_URL') }}/client/quotation/ajax/accept/" + quotation_id,
            dataType: "json",
            beforeSend: function(){
                info.html('<div class="mt-5 text-center"><div class="lds-roller" style="transform:scale(1.3,1.3);"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>');
            },
            success: function (data) {
                info.html('');
                selected = -1;
                get_quotation();
            }
        });
        return false;
    }
    @endclient
    function number_format(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
    function get_quotation() {
        let quotation_id = $('#quotation_id').val(), html, accepted;
        let status = $('.quotation-status'), info = $('.quotation-info');
        if (quotation_id !== '' && quotation_id !== selected) {
            selected = quotation_id;
            $.ajax({
                url: "{{ env('APP_URL') }}/client/quotation/ajax/" + quotation_id,
                dataType: "json",
                beforeSend: function(){
                    info.html('<div class="mt-5 text-center"><div class="lds-roller" style="transform:scale(1.3,1.3);"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>');
                },
                success: function (data) {
                    quotation = data;
                    console.log(quotation);
                    // Checks if quotation is accepted
                    if (quotation.accepted_at === null) {
                        if (quotation.rejected_at === null) {
                            accepted = '<span class="text-warning">Pending</span>';
                            @client
                            let button = `<span class="alert alert-warning pt-2 pb-2 mr-2" style="top:2px;">Pending</span> <a class="btn btn-success" href="#" style="top:3px;" onclick="accept_quotation($(this))" data-id="${quotation.id}">Accept Now</a>`;
                            status.html(button);
                        @endclient
                        } else {
                            accepted = `<b class="text-danger">Rejected</b><br/>Date: ${quotation.rejection}`;
                            @client
                            let msg = `<div class="text-danger pt-2 pb-2">Rejected on: ${quotation.rejection}</div>`;
                            status.html(msg);
                        @endclient
                        }
                    } else {
                        accepted = `<b class="text-success">Accepted</b><br/>Date: ${quotation.approval}`;
                        @client
                        let msg = `<div class="text-success">Accepted on: ${quotation.approval}</div>`;
                        status.html(msg);
                    @endclient
                    }
                    html = `<div class="invoice p-3 mb-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>
                                                {{ $client->name }}
                                                <small class="float-right">Date: ${quotation.creation}</small>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                            From
                                            <address>
                                                <strong>{{ $client->name }}</strong><br>
                                                Address Line 1<br>
                                                Address Line 2<br>
                                                Phone: (804) 123-5432<br>
                                                Email: info@almasaeedstudio.com
                                            </address>
                                        </div>
                                        <!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                    To
                    <address>
                    <strong>${quotation.company}</strong><br>
                    Address Line 1<br>
                    Address Line 2<br>
                    Phone: (555) 539-1037<br>
                    Email: ${quotation.company}@example.com
                    </address>
                    </div>
                    <div class="col-sm-4 invoice-col">
                    Quotation Status
                    <address>
                    Status: ${accepted}
                    </address>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-12 table-responsive">
                    <table class="table table-striped">
                    <thead>
                    <tr>
                    <th>Qty</th>
                    <th>Product</th>
                    <th>Serial #</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody id="product_list"></tbody>
                    </table>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6">
                    <p class="lead">Amount</p>
                    <div class="table-responsive">
                    <table class="table">
                    <tr>
                    <th style="width:50%">Subtotal:</th>
                    <td>{{ $currency }}${number_format(quotation.total_amount - ((quotation.gst/100) * quotation.total_amount))}</td>
                    </tr>
                    <tr>
                    <th>Tax ({{$gst}}%)</th>
                    <td>{{ $currency  }}${number_format((quotation.gst/100) * quotation.total_amount)}</td>
                    </tr>
                    <tr>
                    <th>Shipping:</th>
                    <td>{{ $currency }}0.00</td>
                    </tr>
                    <tr>
                    <th>Total:</th>
                    <td>{{ $currency }}${number_format(quotation.total_amount)}</td>
                    </tr>
                    </table>
                    </div>
                    </div>
                    </div>
                    </div>`;
                    info.html(html);

                    $.each(quotation.products, function (index, json) {
                    $('#product_list').append(
                    $("<tr></tr>")
                    .html(`<td>${json.quantity}</td><td>${json.product.name}</td><td>0000${json.product_id}</td><td>{{ $currency }}${number_format(json.unit_price)}</td><td>{{ $currency }}${number_format(json.total_price)}</td>`) // Using ES6
                        //.html(json.name + ' <b>(' + json.in_stock + ' ' + json.unit + ' left)<b/>')
                    );
                    });
                    }
                    });
                    }
                    }

                    $(function () {
                        //Date range picker
                    $('#due_date').datetimepicker({
                    format: 'Y-M-D'
                    });
                    $('#quotation_id').on('keyup keypress keydown change focus blur', get_quotation);
                    });
</script>
@stop
