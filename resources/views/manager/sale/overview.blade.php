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

                    {{-- Only display the overview if no filter is provided--}}
                    @if(isset($today_sales) || isset($month_sales) || isset($year_sales))
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Sales
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        @if(isset($today_sales)) <li class="nav-item"><a class="nav-link active" href="#sales-today" data-toggle="tab">Today</a></li> @endif
                                        @if(isset($month_sales)) <li class="nav-item"><a class="nav-link" href="#sales-this-month" data-toggle="tab">This Month</a></li>@endif
                                        @if(isset($year_sales)) <li class="nav-item"><a class="nav-link" href="#sales-this-year" data-toggle="tab">This Year</a></li>@endif
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <!-- Morris chart - Sales -->
                                    @if(isset($today_sales))
                                        <div class="chart tab-pane active" id="sales-today">
                                            @if(count($today_sales) > 0)
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-borderless">
                                                                <thead>
                                                                <tr>
                                                                    <th>Time</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($today_sales as $today_sale)
                                                                    @php
                                                                        $today_sale = isset($today_sale)?$today_sale:'';
                                                                        $todayTimes = isset($todayTimes)?$todayTimes."'".\Carbon\Carbon::parse($today_sale->creation_date)->format('h:i a')."',":"'".\Carbon\Carbon::parse($today_sale->creation_date)->format('h:i a')."',";
                                                                        $todayData = isset($todayData)?$todayData."{$today_sale->total}," :"{$today_sale->total}," ;
                                                                        $todayColors = isset($todayColors)?$todayColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{\Carbon\Carbon::parse($today_sale->creation_date)->format('h:i a')}}</td>
                                                                        <td>{{ number_format($today_sale->total) }} {{ $currency }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="col-12"  style="position:relative;min-height:200px;">
                                                        <canvas id="todaysGraph" height="100%" style="height: 100%;"></canvas>
                                                    </div>
                                            @else
                                                <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                    No sale for today
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if(isset($month_sales))
                                        <div class="chart tab-pane" id="sales-this-month">
                                            @if(count($month_sales) > 0)
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-borderless">
                                                                <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Sales</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($month_sales as $month_sale)
                                                                    @php
                                                                        $month_sale = isset($month_sale)?$month_sale:'';
                                                                        $thisMonthsDates = isset($thisMonthsDates)?$thisMonthsDates."'{$month_sale->creation_date}',":"'{$month_sale->creation_date}',";
                                                                        $thisMonthsData = isset($thisMonthsData)?$thisMonthsData.$month_sale->total.',':$month_sale->total.',';
                                                                        $thisMonthsColors = isset($thisMonthsColors)?$thisMonthsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                        $thisMonthsSale = isset($thisMonthsSale)?
                                                                        $thisMonthsSale+$month_sale->total:
                                                                        $month_sale->total;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $month_sale->creation_date }}</td>
                                                                        <td>{{ $month_sale->counter }}</td>
                                                                        <td>{{ number_format($month_sale->total) }} {{ $currency }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                            <h3>Total Amount = Rs.{!! number_format($thisMonthsSale) !!}</h3>
                                                            <h1></h1>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="col-12" style="position:relative;min-height:200px;">
                                                        <canvas id="thisMonthsGraph" height="100%" style="height: 100%;"></canvas>
                                                    </div>
                                            @else
                                                <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                    No sale for this month
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if(isset($year_sales))
                                        <div class="chart tab-pane" id="sales-this-year">
                                            @if(count($year_sales) > 0)
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-borderless">
                                                                <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Sales</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($year_sales as $year_sale)
                                                                    @php
                                                                        $year_sale = isset($year_sale)?$year_sale:'';
                                                                        $thisYearsDates = isset($thisYearsDates)?$thisYearsDates."'{$year_sale->creation_date}',":"'{$year_sale->creation_date}',";
                                                                        $thisYearsData = isset($thisYearsData)?$thisYearsData.$year_sale->total.',':$year_sale->total.',';
                                                                        $thisYearsColors = isset($thisYearsColors)?$thisYearsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                        $thisYearsSale = isset($thisYearsSale)?
                                                                        $thisYearsSale+$year_sale->total:
                                                                        $year_sale->total;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $year_sale->creation_date }}</td>
                                                                        <td>{{ $year_sale->counter }}</td>
                                                                        <td>{{ number_format($year_sale->total) }} {{ $currency }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                            <h3>Total Amount = Rs.{!! number_format($thisYearsSale) !!}</h3>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="col-12" style="position:relative;min-height:200px;">
                                                        <canvas id="thisYearsGraph" height="100%" style="height: 100%;"></canvas>
                                                    </div>
                                            @else
                                                <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                    No sales for this year
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
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
@stop
