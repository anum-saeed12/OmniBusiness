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
            <div class="col-md-12">
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
                <div class="card card-info">
                    <form class="form-horizontal" action="{{ route('quotation.store.'.auth()->user()->user_role) }}" method="post">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="company">Company Name</label>
                                            <input type="text" name="company" class="form-control" id="company"
                                                   placeholder="Enter Company Name" required>
                                            <div class="text-danger">@error('company'){{ $message }}@enderror</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="quotation_type">Quotation</label>
                                            <select class="form-control" id="quotation_type" name="quotation_type" required>
                                                <option selected="selected" value>Select</option>
                                                <option value="rcvd">Received</option>
                                                <option value="sent">Sent</option>
                                            </select>
                                            <div class="text-danger">@error('quotation_type'){{ $message }}@enderror</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="products">Search product to add to the {{ strtolower(explode(' ', $title)[1]) }}</label>
                                    <input type="text" class="form-control" id="products"
                                           data-target="#suggestions"
                                           data-limit="5"
                                           autocomplete="off"
                                           -webkit-autocomplete="off"
                                           onautocomplete="return false"
                                           placeholder="Product name, or id" >
                                    <span class="fa fa-times clear-search" onclick="reset_search($(this))"></span>
                                    <div id="suggestions" class="ac-suggested"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div id="selected" class="ac-selected col-12"></div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <h4 class="total">Total: <span id="show_total">0</span></h4>
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
        </div>
    </div>
</section>
@stop

@section('extras')
<script>
    let product_search = '', selected = [], total = 0, quotation_type='';
    $('#quotation_type').change(function(){
        quotation_type = $(this).val();
        if (quotation_type === 'sent') {
            $('._aGFfjSbC_').each(function(i, obj) {
                $(this).attr('max', $(this).data('conditioned-max'));
            });
        }
        if (quotation_type === 'rcvd') {
            $('._aGFfjSbC_').each(function(i, obj) {
                $(this).attr('data-conditioned-max',$(this).attr('max'));
                $(this).removeAttr('max');
            });
        }
    });
    function select_product(e) {
        let parent = e.parent(),
            product_id = e.data('id'),
            product_unit = e.data('unit'),
            product_name = e.data('name'),
            product_price = e.data('price'),
            product_stock = e.data('stock');
        selected.push(e.data('id'));
        e.remove();
        product_search = '';
        $('#suggestions').html('');
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
            .attr("data-price", product_price)
            .attr('id', 's-row-' + product_id);

        let col_quantity = $('<div></div>')
            .attr('class', 'col-md-3');

        let col_price = $('<div></div>')
            .attr('class', 'col-md-3');

        let col_total = $('<div></div>')
            .attr('class', 'col-md-3');

        let col_discount = $('<div></div>')
            .attr('class', 'col-md-2');

        let col_remove = $('<div></div>')
            .attr('class', 'col-md-1');

        let input_group_quantity = $("<div></div>")
            .attr('class', 'input-group mb-3')
            .attr('id', 's-qty-' + product_id);

        let input_group_price = $("<div></div>")
            .attr('class', 'input-group mb-3')
            .attr('id', 's-price-' + product_id);

        let input_group_discount = $("<div></div>")
            .attr('class', 'input-group mb-3')
            .attr('id', 's-discount-' + product_id);

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
            .attr('class', 'form-control _aGFfjSbC_')
            .attr('type', 'number')
            .attr('min',0)
            .attr('placeholder', 'Quantity')
            .attr('required', 'required')
            .attr('name', "products[" + product_id + "][quantity]")
            .attr('data-conditioned-max', product_stock)
        if (quotation_type === 'sent') input_quantity = input_quantity.attr('max', product_stock)
        let unit = $("<div></div>").attr('class', 'input-group-append').html('<span class="input-group-text" >' + product_unit + '</span>')

        let input_price = $("<input/>")
            .attr('id', 'input-price-' + product_id)
            .attr('class', 'form-control')
            .attr('type', 'number')
            .attr('min',0)
            .attr('value', product_price)
            .attr('placeholder', 'Price')
            .attr('required', 'required')
            .attr('name', "products[" + product_id + "][price]")

        let per_unit = $("<div></div>").attr('class', 'input-group-append').html('<span class="input-group-text " >per ' + product_unit + '</span>')

        let discount = $("<input/>")
            .attr('id', 'input-discount-' + product_id)
            .attr('class', 'form-control')
            .attr('type', 'number')
            .attr('min',0)
            .attr('value',0)
            .attr('placeholder', 'Discount')
            .attr('required', 'required')
            .attr('name', "products[" + product_id + "][discount]")

        let discount_unit = $("<div></div>").attr('class', 'input-group-append').html('<span class="input-group-text " >%</span>')

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

        input_group_discount.append(discount).append(discount_unit);

        col_remove.append(remove);

        col_total.append(input_group_total);

        col_discount.append(input_group_discount);

        row.append(col_quantity).append(col_price).append(col_total).append(col_discount).append(col_remove);

        $('#selected').append(row);
        row.hide();
        row.fadeIn(300);
        $('#products').select().focus().val('');

        $('#input-qty-' + product_id).on('keyup keypress keydown keyup change focus blur', function(){
            let quantity = $(this).val() || 0;
            let discount = $('#input-discount-' + product_id).val() || 0;
            let price = $('#input-price-' + product_id).val() || 0;
            let discounted_price = (price * quantity) - ((price * quantity)*(discount/100));
            $('#s-total-' + product_id).val(discounted_price);
            calculate_total()
        });
        $('#input-price-' + product_id).on('keyup keypress keydown keyup change focus blur', function(){
            let price = $(this).val() || 0;
            let discount = $('#input-discount-' + product_id).val() || 0;
            let quantity = $('#input-qty-' + product_id).val() || 0;
            let discounted_price = (price * quantity) - ((price * quantity)*(discount/100));
            $('#s-total-' + product_id).val(discounted_price);
            calculate_total()
        })
        $('#input-discount-' + product_id).on('keyup keypress keydown keyup change focus blur', function(){
            let discount = $(this).val() || 0;
            let quantity = $('#input-qty-' + product_id).val() || 0;
            let price = $('#input-price-' + product_id).val() || 0;
            let discounted_price = (price * quantity) - ((price * quantity)*(discount/100));
            $('#s-total-' + product_id).val(discounted_price);
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
        let index = selected.indexOf(parseInt(product_id, 10));
        if (index > -1) {
            selected.splice(index, 1);
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
        $('#suggestions').html('');
        el.hide();
    }
    function search() {
        let ele = $('#products'),
            key = event.keyCode;

        // Checks if esc key was pressed
        if (key === 27) {
            $("#suggestions").html('');
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
                url: "{{ url('/client/sales/autocomplete/') }}/" + product_search,
                dataType: "json",
                beforeSend: function(){
                    $("#suggestions").html("<div class='text-info'>Searching for products...</div>");
                },
                success: function (data) {
                    if (data.length > 0) {
                        let container = $("<ul></ul>").attr("id", "products");
                        let dynamic_counter = 0;
                        $.each(data, function (index, json) {
                            // Checks if this item was already selected
                            if (!selected.includes(json.id)) {
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
                                        .attr("data-price",json.unit_price)
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

