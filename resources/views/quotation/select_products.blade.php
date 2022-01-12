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
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Sale Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label for="products">Search product to add to the {{ strtolower(explode(' ', $title)[1]) }}</label>
                                <input type="text" class="form-control" id="products"
                                       data-target="#suggestions"
                                       data-limit="5"
                                       autocomplete="off"
                                       -webkit-autocomplete="off"
                                       onautocomplete="return false"
                                       placeholder="Product name, or id" required>
                                <span class="fa fa-times clear-search" onclick="reset_search($(this))"></span>
                                <style>
                                    .ac-suggested {}
                                    .ac-suggested > div {padding: 4px 0;}
                                    .ac-suggested > ul {
                                        background: #fff;
                                        list-style: none;
                                        margin: 0;
                                        padding: 0;
                                        border: 1px solid #e0e0e0;
                                        position: absolute;
                                        width: calc(100% - 15px);
                                        z-index: 99;
                                    }
                                    .ac-suggested > ul > li {
                                        margin: 0;
                                        padding: 8px 15px;
                                        list-style: none;
                                        border-bottom: 1px solid #e0e0e0;
                                        cursor: pointer;
                                        transition: all .2s;
                                    }
                                    .ac-suggested > ul > li:last-child {
                                        border-bottom: none;
                                    }
                                    .ac-suggested > ul > li:hover {
                                        background: rgba(0,0,0,0.06);
                                    }
                                    .clear-search {
                                        position: absolute;
                                        right: 10px;
                                        color: #999;
                                        padding: 8px 10px;
                                        margin-top: -36px;
                                        cursor: pointer;
                                        display: none;
                                    }
                                    .total {
                                        padding: 15px 0;
                                        margin: 0 auto;
                                        display: inline-block;
                                        font-family:"Poppins",sans-serif;
                                        font-weight:600;
                                        color: #303040;
                                    }
                                </style>
                                <div id="suggestions" class="ac-suggested"></div>
                            </div>
                        </div>
                        <form action="../test/submit" method="post">
                            @csrf
                            <div class="row">
                                <div id="selected" class="ac-selected col-12"></div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <h4 class="total">Total: {{ $currency }}<span id="show_total">0</span></h4>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 text-center pt-3">
                                    <button type="submit" class="btn btn-info btn-block"><i class="fa fa-plus-circle mr-2"></i> {{$title}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('extras')
    <script>
        let product_search = '', selected = [], total = 0;
        function select_product(e) {
            let parent = e.parent(),
                product_id = e.data('id'),
                product_unit = e.data('unit'),
                product_name = e.data('name'),
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
            let input_product_id = $("<input/>")
                .attr('required', 'required')
                .attr('type', 'hidden')
                .attr('name', "products[" + product_id + "][id]")
                .attr('value', product_id)
            let input_quantity = $("<input/>")
                .attr('id', 'input-qty-' + product_id)
                .attr('class', 'form-control')
                .attr('type', 'number')
                .attr('placeholder', 'Quantity')
                .attr('required', 'required')
                .attr('name', "products[" + product_id + "][quantity]")
                .attr('max', product_stock)
            let unit = $("<div></div>").attr('class', 'input-group-append').html('<span class="input-group-text">' + product_unit + '</span>')

            let input_price = $("<input/>")
                .attr('id', 'input-price-' + product_id)
                .attr('class', 'form-control')
                .attr('type', 'number')
                .attr('placeholder', 'Price')
                .attr('required', 'required')
                .attr('name', "products[" + product_id + "][price]")

            let per_unit = $("<div></div>").attr('class', 'input-group-append').html('<span class="input-group-text">per ' + product_unit + '</span>')

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

            $('#selected').append(row);
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
                    url: "{{ env('APP_URL') }}/test/autocomplete/" + product_search,
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
                                            .attr("data-id", json.id)
                                            .attr("data-name", json.name)
                                            .attr("data-unit", json.unit)
                                            .attr("data-stock", json.in_stock)
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
