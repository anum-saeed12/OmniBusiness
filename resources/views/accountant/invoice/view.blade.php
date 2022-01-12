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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.accountant') }}">Home</a></li>
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
                        <div class="row mb-3 mt-3 ml-3">
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
                            <div class="row offset-md-3 col-md-3">
                                <form method="Get" action="">
                                    <div class="input-group">
                                        <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Search" class="form-control"
                                               aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit"><i
                                                    class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact" id="table">
                                <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th class="pl-0">Creation Date</th>
                                    <th class="pl-0">Customer</th>
                                    <th class="pl-0">SalesPerson</th>
                                    <th class="pl-0">Total</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @foreach($invoices as $invoice)
                                    <tr style="cursor:pointer" class="no-select" data-toggle="modal"
                                        data-href="{{ route('invoice.view.accountant', $invoice->id) }}">
                                        <td>{{ sprintf("%06d",$invoice->id)}}</td>
                                        <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y')}}</td>
                                        <td>{{ ucwords($invoice->product_supplier_and_buyer) }}</td>
                                        <td>@if( $invoice->employee_id != Null)
                                                {{ ucfirst($invoice->employee->firstname) }} {{ ucfirst($invoice->employee->lastname) }}
                                            @else
                                                {{ ucfirst($client->name) }}
                                            @endif
                                        </td>

                                        <td>{{ $currency }}{{ number_format($invoice->total_amount) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        {!! $invoices->appends($_GET)->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('extras')
    <script>
        $(function () {
            $('body').on('click', '[data-toggle="modal"]', function () {
                window.location.href = $(this).data('href');
            });
        });
        function number_format(num)
        {
            var num_parts = num.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return num_parts.join(".");
        }
    </script>

    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

@stop
