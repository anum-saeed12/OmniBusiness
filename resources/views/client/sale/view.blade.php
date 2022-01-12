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
        @include('client.sale.components.filters')
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link{{ request('tab')=='pos'||!request('tab')?' active':'' }}" id="nav-pos-tab" data-toggle="tab" href="#nav-pos" role="tab" aria-controls="nav-pos" aria-selected="true">Point of Sale</a>
                <a class="nav-item nav-link{{ request('tab')=='sale'?' active':'' }}" id="nav-sale-tab" data-toggle="tab" href="#nav-sale" role="tab" aria-controls="nav-sale" aria-selected="false">Sale Order</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane{{ request('tab')=='pos'||!request('tab')?' show active':'' }}" id="nav-pos" role="tabpanel" aria-labelledby="nav-pos-tab"><div class="row">
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
                            @if($sales->count() > 0)
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
                                        <a href="{{ route('sale.add.client') }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i> Add New</a>
                                    </div>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap table-compact" id="table">
                                        <thead>
                                        <tr>
                                            <th>Sale Ref.</th>
                                            <th class="pl-0">Creation Date</th>
                                            <th class="pl-0">Customer</th>
                                            <th class="pl-0">SalesPerson</th>
                                            <th class="pl-0">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody id="myTable">
                                        @foreach($pos as $sale)
                                            <tr style="cursor:pointer" class="no-select" data-toggle="modal"
                                                data-href="{{ route('sale.posview.client', $sale->id) }}">
                                                <td><a href="{{ route("sale.posview.client", $sale->id) }}">{{ strtoupper( substr($sale->sale,6,6 )) }}</a></td>

                                                <td><a href="{{ route("sale.posview.client", $sale->id) }}">
                                                    {{ \Carbon\Carbon::createFromTimeStamp(strtotime($sale->created_at))->format('d-M-Y')}}
                                                </td>
                                                <td><a href="{{ route("sale.posview.client", $sale->id) }}">
                                                        {{ ucwords($sale->buyer_name) }}
                                                    </a></td>

                                                <td><a href="{{ route("sale.posview.client", $sale->id) }}">
                                                        @if( $sale->employee_id != Null)
                                                            {{ ucfirst($sale->employee->firstname) }} {{ ucfirst($sale->employee->lastname) }}
                                                        @else
                                                            {{ ucfirst($client->name) }}
                                                        @endif
                                                    </a></td>

                                                <td><a href="{{ route("sale.posview.client", $sale->id) }}">{{ $currency }}{{ number_format($sale->total_amount) }}</a></td>
                                                <td class="text-right p-0">
                                                    <a class="bg-danger list-btn"  href="{{  route('sale.delete.client', $sale->id)  }}"><i class="fas fa-trash-alt" aria-hidden="false"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="results-not-found text-center">
                                    <div class="msg">
                                        Oops
                                        <i class="far fa-frown"></i>
                                    </div>
                                    <div class="graphics">
                                        <i class="fas fa-dolly"></i>
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <div class="msg">No sales found</div>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex flex-row-reverse">
                            {!! $pos->appends('#abcd')->appends($_GET)->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane{{ request('tab')=='sale'?' show active':'' }}" id="nav-sale" role="tabpanel" aria-labelledby="nav-sale-tab"><div class="row">
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
                            @if($sales->count() > 0)
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
                                        <form method="Get" action="{{ route('sale.list.client') }}">
                                            <div class="input-group">
                                                <input type="hidden" name="filters" value="true"/>
                                                <input type="text" id="myInput" name="find" onkeyup="myFunction()" placeholder="Filter" class="form-control"
                                                       aria-label="Filter">
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
                                            <th>Sale Ref.</th>
                                            <th class="pl-0">Creation Date</th>
                                            <th class="pl-0">Quotation Accepted Date</th>
                                            <th class="pl-0">Customer</th>
                                            <th class="pl-0">SalesPerson</th>
                                            <th class="pl-0">Total</th>
                                            <th class="pl-0">Due Date</th>
                                            <th class="text-center pl-0">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody id="myTable">
                                        @foreach($sales as $sale)
                                            <tr style="cursor:pointer" class="no-select" data-toggle="modal"
                                                data-href="{{ route('sale.view.client', $sale->id) }}">
                                                <td><a href="{{ route("sale.view.client", $sale->id) }}">{{ strtoupper( substr($sale->sale,6,6 )) }}</a></td>

                                                <td><a href="{{ route("sale.view.client", $sale->id) }}">
                                                    {{ \Carbon\Carbon::createFromTimeStamp(strtotime($sale->created_at))->format('d-M-Y')}}
                                                </td>

                                                <td>
                                                    @if($sale->quotation != Null )
                                                        <a href="{{ route("sale.view.client", $sale->id) }}">
                                                            {{\Carbon\Carbon::createFromTimeStamp(strtotime($sale->quotation->accepted_at))->format('d-M-Y')}}
                                                        </a>
                                                    @endif</td>
                                                <td><a href="{{ route("sale.view.client", $sale->id) }}">
                                                        {{ ucwords($sale->buyer_name) }}
                                                    </a></td>

                                                <td><a href="{{ route("sale.view.client", $sale->id) }}">
                                                        @if( $sale->employee_id != Null)
                                                            {{ ucfirst($sale->employee->firstname) }} {{ ucfirst($sale->employee->lastname) }}
                                                        @else
                                                            {{ ucfirst($client->name) }}
                                                        @endif
                                                    </a></td>

                                                <td><a href="{{ route("sale.view.client", $sale->id) }}">{{ $currency }}{{ number_format($sale->total_amount) }}</a></td>

                                                <td><a href="{{ route("sale.view.client", $sale->id) }}">{{\Carbon\Carbon::createFromTimeStamp(strtotime($sale->due_date))->format('d-M-Y')}}</a> </td>
                                                <td><a href="{{ route("sale.view.client", $sale->id) }}">
                                                        @if( (\Carbon\Carbon::now()->toDateString() ) > (\Carbon\Carbon::createFromTimeStamp(strtotime($sale->due_date))->format('Y-m-d')))
                                                            <span class="text-bold text-success"><i class="fas fa-check-circle text-success"></i> Completed</span>
                                                        @endif
                                                        @if( (\Carbon\Carbon::now()->toDateString() ) < (\Carbon\Carbon::createFromTimeStamp(strtotime($sale->due_date))->format('Y-m-d')))
                                                            <span class="text-bold text-warning"><i class="fas fa-clock text-warning text-warning"></i> Pending</span>
                                                        @endif
                                                        @if( (\Carbon\Carbon::now()->toDateString() ) == (\Carbon\Carbon::createFromTimeStamp(strtotime($sale->due_date))->format('Y-m-d')))
                                                            <span class="text-bold text-danger"><i class="fas fas fa-clock text-warning text-danger"></i>Due Today</span><br/>
                                                        @endif
                                                    </a></td>
                                                <td class="text-right p-0">
                                                    <a class="bg-danger list-btn"  href="{{  route('sale.delete.client', $sale->id)  }}"><i class="fas fa-trash-alt" aria-hidden="false"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="results-not-found text-center">
                                    <div class="msg">
                                        Oops
                                        <i class="far fa-frown"></i>
                                    </div>
                                    <div class="graphics">
                                        <i class="fas fa-dolly"></i>
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <div class="msg">No sales found</div>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex flex-row-reverse">
                            {!! $sales->appends(['tab'=>'sale'])->appends($_GET)->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div></div>
        </div>
    </div>
</section>
@stop

@section('extras')
<script type="text/javascript">
    $(function () {
        $('body').on('click', '[data-toggle="modal"]', function () {
            window.location.href = $(this).data('href');
        });
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $('input[name="dates"]').daterangepicker({
            autoUpdateInput: false, // Disables autofill
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Clear'
            }
        });
        $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
            // Adds dates when "Apply" button is pressed
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
    });
    function number_format(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
</script>
@stop
