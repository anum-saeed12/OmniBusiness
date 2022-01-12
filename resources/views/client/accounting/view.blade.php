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
                        <li class="breadcrumb-item">Accounting</li>
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

                    <div class="row">
                        <div class="col">
                            <form action="{{route('client.charts.index')}}" method="GET" id="accountType">
                                <label for="perPageCount">Account Type:</label>
                                <select id="perPageCount" class="form-control form-control-sm mb-3" style="width:auto;" name="type" onchange="$('#accountType').submit();">
                                    <option>Select account type</option>
                                    <option value="Receivable"{{ request('type')=='Receivable'?' selected':'' }}>Receivable</option>
                                    <option value="SalesTaxPayable"{{ request('type')=='SalesTaxPayable'?' selected':'' }}>SalesTaxPayable</option>
                                    <option value="Revenue"{{ request('type')=='Revenue'?' selected':'' }}>Revenue</option>
                                </select>
                            </form>
                        </div>
                    </div>

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
                            <div class="row offset-md-2 col-md-4">
                                <div class="col-sm-8">
                                    <form method="Get" action="">
                                        <div class="input-group">
                                            <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Search" class="form-control"
                                                   aria-label="Search">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th class="pl-0">Transaction Type</th>
                                    <th class="pl-0">Account Type</th>
                                    <th class="pl-0">Transaction</th>
                                    <th class="pl-0">Amount</th>
                                    <th class="pl-0">Dated</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @forelse($accounts as $coa)
                                    <tr>
                                        <td>{{ $coa->invoice_id }}</td>
                                        <td>{{ ucwords($coa->transaction_type) }}</td>
                                        <td>{{ ucwords($coa->account_type) }}</td>
                                        <td>{{ ucwords($coa->transaction) }}</td>
                                        <td>{{ $coa->amount }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::createFromTimestamp(strtotime($coa->created_at))->format('d-M-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="pt-3 pb-3 text-center">
                                            No accounts found
                                        </td>
                                    </tr>
                                @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        {!! $accounts->appends($_GET)->appends($_GET)->links('pagination::bootstrap-4') !!}
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
            });
        });
    </script>
@stop


