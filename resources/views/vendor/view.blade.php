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
                        <li class="breadcrumb-item">Product</li>
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
                            <a href="{{ route('vendor.add.'.auth()->user()->user_role) }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i> Add New</a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-compact">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th class="pl-0">Address</th>
                                <th class="pl-0">Phone Number</th>
                                <th class="pl-0">Personal Email</th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($vendors as $vendor)

                            <tr>
                                <td>{{ ucfirst($vendor->name ) }}</td>
                                <td>{{ $vendor->address_1 }} {{ $vendor->address_2 }}</td>
                                <td>{{ $vendor->phone_num }}</td>
                                <td>{{ $vendor->personal_email }}</td>
                                <td class="text-right p-0">
                                    <a class="bg-primary list-btn"  href="{{ route('vendor.edit.'.auth()->user()->user_role, $vendor->id)  }}"><i class="fas fa-tools" aria-hidden="false"></i></a>
                                    <a class="bg-danger list-btn"  href="{{  route('vendor.delete.'.auth()->user()->user_role, $vendor->id)  }}"><i class="fas fa-trash-alt" aria-hidden="false"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse">
                    {!! $vendors->appends($_GET)->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('extras')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $('body').on('click', '[data-toggle="modal"]', function () {
                window.location.href = $(this).data('href');
            });
            @if(isset($today_sales) && count($today_sales)>0)
            // Donut Chart
            let todaysGraph = $('#todaysGraph').get(0).getContext('2d'),
                todayData = {
                    labels: [{!! $todayTimes !!}],
                    datasets: [{
                        data: [{!! $todayData !!}],
                        backgroundColor : [{!! $todayColors !!}],
                    }]
                };
            let todaysPieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let todaysPieChart = new Chart(todaysGraph,{type: 'pie',data: todayData,options: todaysPieOptions});
            @endif

            @if(isset($month_sales) && count($month_sales)>0)
            // Donut Chart
            let thisMonthsGraph = $('#thisMonthsGraph').get(0).getContext('2d'),
                thisMonthsData = {
                    labels: [{!! $thisMonthsDates !!}],
                    datasets: [{
                        data: [{!! $thisMonthsData !!}],
                        backgroundColor : [{!! $thisMonthsColors !!}],
                    }]
                };
            let thisMonthsPieOptions = {legend: {display: true,position: 'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let thisMonthsPieChart = new Chart(thisMonthsGraph,{type: 'doughnut',data: thisMonthsData,options: thisMonthsPieOptions});
            @endif

            @if(isset($year_sales) && count($year_sales)>0)
            let yearGraphType = 'bar';
            // Donut Chart
            let thisYearsGraph = $('#thisYearsGraph').get(0).getContext('2d'),
                thisYearsData = {
                    labels: [{!! $thisYearsDates !!}],
                    datasets: [{
                        data: [{!! $thisYearsData !!}],
                        backgroundColor : [{!! $thisYearsColors !!}],
                    }]
                };
            if (yearGraphType === 'pie' || yearGraphType === 'doughnut') {
                let thisYearsPieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
                // You can switch between pie and doughnut using the method below.
                let thisYearsPieChart = new Chart(thisYearsGraph,{type: 'doughnut',data: thisYearsData,options: thisYearsPieOptions});
            } else if (yearGraphType === 'bar') {
                let thisYearsBarOptions = {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                };
                // You can switch between pie and doughnut using the method below.
                let thisYearsBarChart = new Chart(thisYearsGraph, {
                    type: 'line',
                    data: {
                        labels: [{!! $thisYearsDates !!}],
                        datasets: [{
                            label: 'Monthly data',
                            data: [{!! $thisYearsData !!}],
                            borderColor: 'rgb(111,193,97)',
                            backgroundColor: 'rgba(111,193,97,0.8)',
                            stack: 'combined',
                            type: 'bar'
                        }]
                    },
                    options: thisYearsBarOptions,
                    scales: {
                        y: {
                            stacked: true
                        }
                    }
                });
            }
            @endif
        });
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
