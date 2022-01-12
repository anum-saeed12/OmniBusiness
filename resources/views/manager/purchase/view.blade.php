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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.manager') }}">Home</a></li>
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
                        <div class="col-md-6 text-right pr-md-4">
                            <form method="Get" action="" style="display:inline-block;vertical-align:top;" class="mr-2">
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
                            <a href="{{ route('purchase.add.manager') }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i> Add New</a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-compact" id="table">
                            <thead>
                            <tr>
                                <th>Purchase Ref</th>
                                <th class="pl-0">Creation Date</th>
                                <th class="pl-0">Quotation Received Date</th>
                                <th class="pl-0">Client</th>
                                <th class="pl-0">SalesPerson</th>
                                <th class="pl-0">Total</th>
                                <th class="pl-0">Due Date</th>
                                <th class="text-center pl-0">Status</th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($purchases as $purchase)
                            <tr style="cursor:pointer" class="no-select" data-toggle="modal"
                                data-href="{{ route('purchase.view.manager', $purchase->id) }}">
                                <td><a href="{{ route("purchase.view.manager", $purchase->id) }}">{{ strtoupper(substr($purchase->purchase,6,6)) }}</a></td>

                                <td><a href="{{ route("purchase.view.manager", $purchase->id) }}">
                                    {{ \Carbon\Carbon::createFromTimeStamp(strtotime($purchase->created_at))->format('d-M-Y')}}</a>
                                </td>

                                <td><a href="{{ route("purchase.view.manager", $purchase->id) }}">
                                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($purchase->quotation->accepted_at))->format('d-M-Y')}}</a>
                                </td>
                                <td><a href="{{ route("purchase.view.manager", $purchase->id) }}">
                                    {{ ucwords($purchase->supplier_name) }}</a>
                                </td>

                                <td><a href="{{ route("purchase.view.manager", $purchase->id) }}">
                                    @if( $purchase->employee_id != Null)
                                    {{ ucfirst($purchase->employee->firstname) }} {{ ucfirst($purchase->employee->lastname) }}
                                    @else
                                    {{ ucfirst($client->name) }}
                                    @endif
                                    </a></td>

                                <td><a href="{{ route("purchase.view.manager", $purchase->id) }}">{{ $currency }}{{ number_format($purchase->total_amount) }}</a></td>

                                <td><a href="{{ route("purchase.view.manager", $purchase->id) }}">{{\Carbon\Carbon::createFromTimeStamp(strtotime($purchase->due_date))->format('d-M-Y')}} </a></td>
                                <td><a href="{{ route("purchase.view.manager", $purchase->id) }}">
                                    @if( (\Carbon\Carbon::now()->toDateString() ) > (\Carbon\Carbon::createFromTimeStamp(strtotime($purchase->due_date))->format('Y-m-d')))
                                    <span class="text-bold text-success"><i class="fas fa-check-circle text-success"></i> Completed</span>
                                    @endif
                                    @if( (\Carbon\Carbon::now()->toDateString() ) < (\Carbon\Carbon::createFromTimeStamp(strtotime($purchase->due_date))->format('Y-m-d')))
                                    <span class="text-bold text-warning"><i class="fas fa-clock text-warning text-warning"></i> Pending</span>
                                    @endif
                                    @if( (\Carbon\Carbon::now()->toDateString() ) == (\Carbon\Carbon::createFromTimeStamp(strtotime($purchase->due_date))->format('Y-m-d')))
                                    <span class="text-bold text-danger"><i class="fas fas fa-clock text-warning text-danger"></i>Due Today</span><br/>
                                    @endif
                                    </a></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse">
                    {!! $purchases->appends($_GET)->links('pagination::bootstrap-4') !!}
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
