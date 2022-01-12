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
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@stop

@section('content')
    <style>
        .card-body.p-0 .table tbody>tr>td:first-of-type, .card-body.p-0 .table tbody>tr>th:first-of-type, .card-body.p-0 .table thead>tr>td:first-of-type, .card-body.p-0 .table thead>tr>th:first-of-type {padding-left:.3rem;}
        table {font-size:14px;}
        td, th {padding:3px 8px!important;}
    </style>
    <section class="content">
        <div class="container-fluid">
            @if(session()->has('success'))
                <div class="row">
                    <div class="col">
                        <div class="callout callout-success" style="color:green">{{ session()->get('success') }}</div>
                    </div>
                </div>
            @endif
            @if(session()->has('error'))
                <div class="row">
                    <div class="col">
                        <div class="callout callout-danger" style="color:red">{{ session()->get('error') }}</div>
                    </div>
                </div>
            @endif
                @include('accounting.components.filters', ['exclude'=>['account-type'],'action'=>'incomeStatement.index.'.auth()->user()->user_role,'start'=>\Carbon\Carbon::now()->firstOfYear()->format('Y-m-d')])
            <div class="row">
                <div class="col-sm-10 offset-sm-1 col-md-8 offset-md-2 col col-lg-6 offset-lg-3 col-xl-6 offset-xl-3 pr-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <h5 class="mb-4 text-center">
                                Income Statement for {{ $client->name }}
                                <hr style="margin:.5rem 0;border-color:#ccc;">
                                <small style="margin-top:0px;position:relative;top:0px;" class="text-secondary">
                                    From
                                    <span class="mx-2">{{ request('start')?\Carbon\Carbon::createFromDate(request('start'))->format('d-M-Y'):\Carbon\Carbon::now()->firstOfYear()->format('d-M-Y') }}</span>
                                    to
                                    <span class="mx-2">{{ request('end')?\Carbon\Carbon::createFromDate(request('end'))->format('d-M-Y'):'Today' }}</span>
                                </small>
                            </h5>
                            <div class="table-responsive">
                                <table class="table text-nowrap table-borderless table-sm">
                                    <tbody id="myTable">

                                    <tr>
                                        <th>REVENUE</th>
                                        <td style="width:200px;"></td>
                                    </tr>

                                    <tr>
                                        <td>Total Revenue</td>
                                        <td>{{ number_format($revenue->amount + ($revenue->amount * fetchSetting('gst')/100),2) }} {{ $currency }}</td>
                                    </tr>

                                    <tr>
                                        <td>Cost Of Revenue</td>
                                        <td>{{ number_format(($inventory->cogs + ($inventory->cogs * fetchSetting('gst')/100)),2) }} {{ $currency }}</td>
                                    </tr>

                                    <tr>
                                        <td>Gross Profit</td>
                                        <td class="text-bold">{{ number_format(($revenue->amount + ($revenue->amount * fetchSetting('gst')/100)) - ($inventory->cogs + ($inventory->cogs * fetchSetting('gst')/100)), 2) }} {{ $currency }}</td>
                                    </tr>

                                    <tr><td colspan="3">&nbsp;</td></tr>

                                    <tr>
                                        <th>EXPENSES</th>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Tax</td>
                                        <td>{{ number_format((($inventory->cogs * fetchSetting('gst')) / 100) + ($revenue->amount * fetchSetting('gst')/100),2) }} PKR</td>
                                    </tr>

                                    {{--<tr>
                                        <td>Sales Discount</td>
                                        <td>100 PKR</td>
                                        <td>100 PKR</td>
                                    </tr>--}}

                                    <tr><td colspan="3">&nbsp;</td></tr>

                                    <tr style="border-top:1px solid #000;border-bottom:1px solid #000;">
                                        <td>Net Income</td>
                                        <td class="text-bold">{{ number_format(($revenue->amount + ($revenue->amount * fetchSetting('gst')/100)) + ($inventory->cogs + ($inventory->cogs * fetchSetting('gst')/100)) - ((($inventory->cogs * fetchSetting('gst')) / 100) + ($revenue->amount * fetchSetting('gst')/100)),2) }} PKR</td>
                                    </tr>
                                    </tbody>
                                </table>
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
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });;
            $('input.date').daterangepicker({
                autoUpdateInput: false, // Disables autofill
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 0,
                maxYear: parseInt(moment().format('YYYY'),10),
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            });
            $('input.date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
        });
    </script>
@stop


