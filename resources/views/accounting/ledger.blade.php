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
        table {font-size:12px;}
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
            @include('accounting.components.filters')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        {{--<div class="row mb-3 mt-3 ml-3">
                            <div class="col-md-6">
                                <form action="" method="GET" id="perPage">
                                    <label for="perPageCount">Show</label>
                                    <select id="perPageCount" name="count" onchange="$('#perPage').submit();"
                                            class="input-select mx-2">
                                        <option value="15"{{ request('count')=='15'?' selected':'' }}>15 rows</option>
                                        <option value="25"{{ request('count')=='25'?' selected':'' }}>25 rows</option>
                                        <option value="50"{{ request('count')=='50'?' selected':'' }}>50 rows</option>
                                        <option value="100"{{ request('count')=='100'?' selected':'' }}>100 rows</option>
                                    </select>
                                </form>
                            </div>
                        </div>--}}
                        <div class="card-body table-responsive p-3">
                            <h3 class="mb-4">
                                Ledger records for {{ $client->name }} <br/>
                                <small style="margin-top:-8px;position:relative;top:-8px;" class="text-secondary">From {{ request('start','Start') }} to {{ request('end', 'End') }}</small>
                            </h3>
                            <table class="table text-nowrap table-sm table-bordered border">
                                <thead>
                                    <tr class="bg-gradient-gray text-center">
                                        <th>Date</th>
                                        <th>Vendor/Customer Name</th>
                                        <th>Account Type</th>
                                        <th>Transaction Id</th>
                                        <th class="text-center">Debit</th>
                                        <th class="text-center">Credit</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                    @forelse($ledger_records as $item)
                                        <tr>
                                            <td class="text-center">{{ \Illuminate\Support\Carbon::createFromTimestamp(strtotime($item->created_at))->format('d-M-Y') }}</td>
                                            <td>{{ $item->affiliate_name }}</td>
                                            <td>{{ $item->account_type }}</td>
                                            <td>{{ str_pad($item->transaction_id,6,0,STR_PAD_LEFT) }}</td>
                                            <td class="text-center text-bold">{{ $item->ledger_entry_type=='debit'?"{$item->ledger_amount} {$currency}":'' }}</td>
                                            <td class="text-center text-bold">{{ $item->ledger_entry_type=='credit'?"{$item->ledger_amount} {$currency}":'' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="pt-5 pb-4 text-center" style="opacity:.4">
                                                <div class="mb-3"><i class="fas fa-coins nav-icon" style="font-size:9rem;"></i></div>
                                                <div class="h4">No records found</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <th colspan="4"></th>
                                        <th class="text-center bg-gradient-gray">Total Debit</th>
                                        <th class="text-center bg-gradient-gray">Total Credit</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td class="text-center text-bold">{{ $total_debit }} {{ $currency }}</td>
                                        <td class="text-center text-bold">{{ $total_credit }} {{ $currency }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <th colspan="2" class="text-center bg-gradient-gray">Total Balance</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4"></th>
                                        <td colspan="2" class="text-center text-bold">
                                            {!! $total_debit - $total_credit !!} {{ $currency }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        {!! $ledger_records->appends($_GET)->links('pagination::bootstrap-4') !!}
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


