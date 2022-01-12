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
        <div class="row">
            <div class="col">
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
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-pos-tab" data-toggle="tab" href="#nav-pos" role="tab" aria-controls="nav-pos" aria-selected="true">Sale Order</a>
                <a class="nav-item nav-link" id="nav-sale-tab" data-toggle="tab" href="#nav-sale" role="tab" aria-controls="nav-sale" aria-selected="false">Point of Sale</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane show active" id="nav-pos" role="tabpanel" aria-labelledby="nav-pos-tab"><div class="row">
                    <div class="col-md-12">
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
                                                            <option value="{{ $quotation->id }}">{{ ucwords($quotation->company) }} | {{ $quotation->total_amount }} {{$currency}} | {{ \Carbon\Carbon::createFromTimeStamp(strtotime($quotation->created_at))->format('Y/m/d h:i') }}
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
                                                    <div class="text-danger">@error('due_date'){{ $message }}@enderror</div>
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
                </div></div>
            <div class="tab-pane " id="nav-sale" role="tabpanel" aria-labelledby="nav-sale-tab"><div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <form class="form-horizontal" action="{{ route('sale.pos.client') }}" method="post">
                                @csrf
                                <div class="card-body pb-0">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label for="buyer_name">Buyer Name</label>
                                                    <input type="text" name="buyer_name" class="form-control" id="buyer_name"
                                                           placeholder="Enter Buyer Name" >
                                                    <div class="text-danger">@error('buyer_name'){{ $message }}@enderror</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="products">Search product to add to the {{ strtolower(explode(' ', $title)[1]) }}</label>
                                            <input type="text" class="form-control" id="products"
                                                   data-target="#suggestion"
                                                   data-limit="5"
                                                   autocomplete="off"
                                                   -webkit-autocomplete="off"
                                                   onautocomplete="return false"
                                                   placeholder="Product name, or id" >
                                            <span class="fa fa-times clear-search" onclick="reset_search($(this))"></span>
                                            <div id="suggestion" class="ac-suggested"></div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div id="selected1" class="ac-selected col-12"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <div class="form-group mb-0">
                                                <label for="tax_included">
                                                    <input id="tax_included" class="mr-1" type="checkbox" name="tax_included" checked>
                                                    Tax Included
                                                </label>
                                            </div>
                                            <h4 class="total">
                                                Total: <span id="show_total">0</span>
                                            </h4>
                                            <small style="margin-top:-1rem;display:block;">Inclusive of tax {{ fetchSetting('gst') }}%</small>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center pb-4">
                                    <button type="submit" class="btn btn-default">Cancel</button>
                                    <span class="mr-3"></span>
                                    <button type="submit" class="btn btn-info">{{ $title }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div></div>
        </div>
    </div>
</section>
@stop

@section('extras')
    <script>
        let selected = '', quotation;
        function accept_quotation(e)
        {
            let quotation_id = e.data('id');
            let status = $('.quotation-status'), info = $('.quotation-info');
            event.preventDefault();
            $.ajax({
                url: "{{ url(auth()->user()->user_role.'/quotation/ajax/accept/') }}/" + quotation_id,
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
                    url: "{{ url(auth()->user()->user_role.'/quotation/ajax/') }}/" + quotation_id,
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
                                @if(in_array(auth()->user()->user_role,['client','manager']))
                                    let button = `<span class="alert alert-warning pt-2 pb-2 mr-2" style="top:2px;">Pending</span> <a class="btn btn-success" href="#" style="top:3px;" onclick="accept_quotation($(this))" data-id="${quotation.id}">Accept Now</a>`;
                                    status.html(button);
                                @endif
                            } else {
                                accepted = `<b class="text-danger">Rejected</b><br/>Date: ${quotation.rejection}`;
                                let msg = `<div class="text-danger pt-2 pb-2">Rejected on: ${quotation.rejection}</div>`;
                                status.html(msg);
                            }
                        } else {
                            accepted = `<b class="text-success">Accepted</b><br/>Date: ${quotation.approval}`;
                            let msg = `<div class="text-success">Accepted on: ${quotation.approval}</div>`;
                            status.html(msg);
                        }
                        let vendor          = quotation.vendor      || {} ;
                        let vendor_address1 = vendor.address_1      || "N/A";
                        let vendor_address2 = vendor.address_2      || "N/A";
                        let vendor_phone    = vendor.phone_num      || "N/A";
                        let vendor_email    = vendor.personal_email || "N/A";

                        let tax = parseFloat(quotation.gst) / 100;
                        let sub_total_amount = parseFloat(quotation.total_amount).toFixed(2);
                        let tax_amount = parseFloat(sub_total_amount * tax);
                        let total_amount = parseFloat(sub_total_amount) + tax_amount;

                        html = `<div class="invoice p-3 mb-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>
                                                {{ ucwords($client->name) }}
                                                <small class="float-right">Date: ${quotation.creation}</small>
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
                                                <strong>${(quotation.company).toLowerCase().split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(' ')}</strong><br>
                                                ${vendor_address1.toLowerCase().split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(' ')}<br>
                                                ${vendor_address2.toLowerCase().split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(' ')}<br>
                                                Phone: ${vendor_phone}<br>
                                                Email: ${vendor_email}
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
                                                <tbody id="product_list"></tbody>
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
                                                        <td>${number_format(quotation.original_amount)} {{ $currency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:50%">After Discount Price:</th>
                                                        <td>${ sub_total_amount } {{ $currency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tax ({{$gst}}%)</th>
                                                        <td>${ tax_amount.toFixed(2) } {{ $currency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Shipping:</th>
                                                        <td>0.00 {{ $currency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total:</th>
                                                        <td>${ total_amount.toFixed(2) } {{ $currency }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                        info.html(html);

                        $.each(quotation.items, function (index, json) {
                            let product_id = json.product_id;
                            let serial_number = product_id.toString().padStart(6, '0');
                            $('#product_list').append(
                                $("<tr></tr>")
                                    .html(`<td>${json.quantity}</td><td>${json.product.name}</td><td>${serial_number}</td><td>${number_format(json.discount)}%</td><td>${number_format(json.unit_price)} {{ $currency }}</td><td>${number_format(json.total_price)} {{ $currency }}</td>`) // Using ES6
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

    <script>
        function number_format(num)
        {
            var num_parts = num.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return num_parts.join(".");
        }
    </script>

    <script>
        let product_search = '', selected1 = [], total = 0;
        function select_product(e) {
            let parent = e.parent(),
                product_id = e.data('id'),
                product_unit = e.data('unit'),
                product_name = e.data('name'),
                product_stock = e.data('stock');
            selected1.push(e.data('id'));
            e.remove();
            product_search = '';
            $('#suggestion').html('');
            $('.clear-search').hide();
            //$('#products').val('');
            //reset_search()
            //search();
            // Create the row
            let row = $('<div></div>')
                .attr('class', 'row')
                .attr("data-id", product_id)
                .attr("data-name", product_name)
                .attr("data-unit", product_unit)
                .attr("data-stock", product_stock)
                .attr('id', 's-row-' + product_id);

            let col_quantity = $('<div></div>')
                .attr('class', 'col-md-4');

            let col_price = $('<div></div>')
                .attr('class', 'col-md-4');

            let col_total = $('<div></div>')
                .attr('class', 'col-md-3');

            let col_remove = $('<div></div>')
                .attr('class', 'col-md-1');

            let input_group_quantity = $("<div></div>")
                .attr('class', 'input-group mb-3')
                .attr('id', 's-qty-' + product_id);

            let input_group_price = $("<div></div>")
                .attr('class', 'input-group mb-3')
                .attr('id', 's-price-' + product_id);

            let input_group_total = $("<div></div>")
                .attr('class', 'input-group mb-3');

            let product = $("<div></div>").attr('class', 'input-group-prepend').html('<span class="input-group-text">' + product_name + '</span>')
            let input_product_id = $("<input />")
                .attr('required', 'required')
                .attr('type', 'hidden')
                .attr('name', "products[" + product_id + "][id]")
                .attr('value', product_id)
            let input_quantity = $("<input/>")
                .attr('id', 'input-qty-' + product_id)
                .attr('class', 'form-control')
                .attr('type', 'number')
                .attr('min',0)
                .attr('placeholder', 'Quantity')
                .attr('required', 'required')
                .attr('name', "products[" + product_id + "][quantity]")
                .attr('max', product_stock)
            let unit = $("<div></div>").attr('class', 'input-group-append').html('<span class="input-group-text" >' + product_unit + '</span>')

            let input_price = $("<input/>")
                .attr('id', 'input-price-' + product_id)
                .attr('class', 'form-control')
                .attr('type', 'number')
                .attr('min',0)
                .attr('placeholder', 'Price')
                .attr('required', 'required')
                .attr('name', "products[" + product_id + "][price]")

            let per_unit = $("<div></div>").attr('class', 'input-group-append').html('<span class="input-group-text " >per ' + product_unit + '</span>')

            let remove = $("<a></a>")
                .attr('href', '#')
                .attr('title', 'Remove item')
                .attr('class', 'btn btn-danger d-lg-block')
                .attr('onclick', 'remove_product("'+product_id+'")')
                .html('<i class="fa fa-trash"></i> <span class="d-md-none d-lg-none">Remove ' + product_name + '</span>')


            let total_price = $("<input/>")
                .attr('id', 's-total-' + product_id)
                .attr('class', 'form-control input-disabled disabled product-total')
                .attr('type', 'text')
                .attr('disabled', 'disabled')
                .attr('value', '0')

            let total_label = $("<div></div>").attr('class', 'input-group-append').html('<span class="input-group-text">Sub-total</span>')
            // Append the products
            input_group_quantity.append(product).append(input_product_id).append(input_quantity).append(unit);
            col_quantity.append(input_group_quantity);

            input_group_price.append(input_price).append(per_unit);
            col_price.append(input_group_price);

            input_group_total.append(total_label).append(total_price);

            col_remove.append(remove);

            col_total.append(input_group_total);

            row.append(col_quantity).append(col_price).append(col_total).append(col_remove);

            $('#selected1').append(row);
            row.hide();
            row.fadeIn(300);
            $('#products').select().focus().val('');

            $('#input-qty-' + product_id).on('keyup keypress keydown keyup change focus blur', function(){
                let quantity = $(this).val() || 0;
                let price = $('#input-price-' + product_id).val() || 0;
                $('#s-total-' + product_id).val(price * quantity);
                calculate_total()
            });
            $('#input-price-' + product_id).on('keyup keypress keydown keyup change focus blur', function(){
                let price = $(this).val() || 0;
                let quantity = $('#input-qty-' + product_id).val() || 0;
                $('#s-total-' + product_id).val(price * quantity);
                calculate_total()
            })
        }
        function calculate_total()
        {
            total = 0;
            $('.product-total').each(function(){
                total += parseFloat($(this).val());
            });
            $('#show_total').html(total);
        }
        function remove_product(product_id) {
            event.preventDefault();
            let row = $('#s-row-' + product_id),
                product_unit = row.data('unit'),
                product_name = row.data('name'),
                product_stock = row.data('stock');
            $('#input-qty-' + product_id).off();
            $('#input-price-' + product_id).off();
            let index = selected1.indexOf(parseInt(product_id, 10));
            if (index > -1) {
                selected1.splice(index, 1);
            }
            row.slideUp(300);
            setTimeout(function(){
                row.remove();
                calculate_total();
            }, 300);
            product_search = '';
            return false;
        }
        function reset_search(el) {
            $('#products').val('');
            search();
            $('#suggestion').html('');
            el.hide();
        }
        function search() {
            let ele = $('#products'),
                key = event.keyCode;

            // Checks if esc key was pressed
            if (key === 27) {
                $("#suggestion").html('');
                ele.val('');
                $('.clear-search').hide();
                return false;
            }
            // DO nothing if the search query is same
            if (ele.val() !== product_search) {
                // Continue if the search query is changed
                let suggestion_container = ele.data('target');
                let limit = ele.data('limit') || 5;
                product_search = ele.val();
                $.ajax({
                    url: "{{ url(auth()->user()->user_role.'/sales/autocomplete/') }}" + product_search,
                    dataType: "json",
                    beforeSend: function(){
                        $("#suggestion").html("<div class='text-info'>Searching for products...</div>");
                    },
                    success: function (data) {
                        if (data.length > 0) {
                            let container = $("<ul></ul>").attr("id", "products");
                            let dynamic_counter = 0;
                            $.each(data, function (index, json) {
                                // Checks if this item was already selected
                                if (!selected1.includes(json.id)) {
                                    // Increment the value of the counter
                                    dynamic_counter++;
                                    // Limits the result
                                    if (dynamic_counter > limit) return false;
                                    container.append(
                                        $("<li></li>")
                                            .attr('id', 'p' + json.id)
                                            .attr("data-id",  json.id)
                                            .attr("data-name",json.name)
                                            .attr("data-unit",json.unit)
                                            .attr("data-stock",json.in_stock)
                                            .attr("onclick", "select_product($(this))")
                                            .html(`${json.name} <b>${json.in_stock} ${json.unit} left</b>`) // Using ES6
                                        //.html(json.name + ' <b>(' + json.in_stock + ' ' + json.unit + ' left)<b/>')
                                    );
                                    $('.clear-search').show();
                                }
                            });
                            $(suggestion_container).html(container);
                            if (dynamic_counter <= 0) {
                                $(suggestion_container).html("<div class='text-danger'>No more products found</div>");
                            }
                        } else {
                            $(suggestion_container).html("<div class='text-danger'>No products found</div>");
                        }
                    }
                });
            }
            if (ele.val() === '' || ele.val() === '.') {
                $('.clear-search').hide();
                $("#suggestions").html('');
            }
        }
        $(function () {
            //Date range picker
            $('#due_date').datetimepicker({
                format: 'Y/M/D'
            });
            $("#products").on('keyup keypress keydown keyup', search);
        })
    </script>
@stop
