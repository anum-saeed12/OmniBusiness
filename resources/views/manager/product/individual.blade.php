@extends('layouts.panel')

@section('breadcrumbs')
    <br/>
@stop

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-blue card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <div class="icon">
                                <i class="fa fa-shopping-basket fa-7x" style="color:#eab45f"></i>
                            </div>
                        </div>
                        <h2 class="profile-username text-center"><b>{{ ucfirst($product->name) }}</b></h2>
                        <div class="card-body">
                            <strong><i class="fas fa-sitemap mr-1"></i> Category</strong>
                            <p class="text-muted ml-4">{{ ucfirst($product->category->category->title) }}</p>
                            <hr>

                            <strong><i class="fa fa-warehouse"></i> Stock</strong>
                            <p class="text-muted ml-4"> {{ $product->in_stock }}</p>
                            <hr>

                            <strong><i class="fas fa-balance-scale"></i> Unit</strong>
                            <p class="text-muted ml-4">{{ $product->unit }}</p>
                            <hr>
                            <strong><i class="fas fa-dollar"></i> Price</strong>
                            <p class="text-muted ml-4">{{ $product->unit_price }}</p>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#sale" data-toggle="tab">Sales</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#purchase" data-toggle="tab">Purchase</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="sale">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-tools">
                                            <ul class="nav nav-pills ml-auto">
                                                <li class="nav-item"><a class="nav-link active" href="#sales-today" data-toggle="tab">Today</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#sales-this-month" data-toggle="tab">This Month</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#sales-this-year" data-toggle="tab">This Year</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content p-0">
                                            <!-- Morris chart - Sales -->
                                            @if(isset($today_employee_sales))
                                                <div class="chart tab-pane active" id="sales-today">
                                                    @if(count($today_employee_sales) > 0)
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
                                                                        @foreach($today_employee_sales as $today_employee_sale)
                                                                            @php
                                                                                $today_employee_sale = isset($today_employee_sale)?$today_employee_sale:'';
                                                                                $todayTimes = isset($todayTimes)?$todayTimes."'".\Carbon\Carbon::parse($today_employee_sale->creation_date)->format('h:i a')."',":"'".\Carbon\Carbon::parse($today_employee_sale->creation_date)->format('h:i a')."',";
                                                                                $todayData = isset($todayData)?$todayData."{$today_employee_sale->total}," :"{$today_employee_sale->total}," ;
                                                                                $todayColors = isset($todayColors)?$todayColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisTodaysSale = isset($thisTodaysSale)?
                                                                                $thisTodaysSale+$today_employee_sale->total:
                                                                                $today_employee_sale->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{\Carbon\Carbon::parse($today_employee_sale->creation_date)->format('h:i a')}}</td>
                                                                                <td>{{ number_format($today_employee_sale->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h3>Total Amount = Rs.{!! number_format($thisTodaysSale) !!}</h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4"  style="position:relative;min-height:200px;">
                                                                <canvas id="todaysSaleGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No sale for today
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(isset($month_employee_sales))
                                                <div class="chart tab-pane" id="sales-this-month">
                                                    @if(count($month_employee_sales) > 0)
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
                                                                        @foreach($month_employee_sales as $month_employee_sale)
                                                                            @php
                                                                                $month_employee_sale = isset($month_employee_sale)?$month_employee_sale:'';
                                                                                $thisMonthsDates = isset($thisMonthsDates)?$thisMonthsDates."'{$month_employee_sale->creation_date}',":"'{$month_employee_sale->creation_date}',";
                                                                                $thisMonthsData = isset($thisMonthsData)?$thisMonthsData.$month_employee_sale->total.',':$month_employee_sale->total.',';
                                                                                $thisMonthsColors = isset($thisMonthsColors)?$thisMonthsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisMonthsSale = isset($thisMonthsSale)?
                                                                                $thisMonthsSale+$month_employee_sale->total:
                                                                                $month_employee_sale->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $month_employee_sale->creation_date }}</td>
                                                                                <td>{{ $month_employee_sale->counter }}</td>
                                                                                <td>{{ number_format($month_employee_sale->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h3>Total Amount = Rs.{!! number_format($thisMonthsSale) !!}</h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" style="position:relative;min-height:200px;">
                                                                <canvas id="thisMonthsSaleGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No sale for this month
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(isset($year_employee_sales))
                                                <div class="chart tab-pane" id="sales-this-year">
                                                    @if(count($year_employee_sales) > 0)
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
                                                                        @foreach($year_employee_sales as $year_employee_sale)
                                                                            @php
                                                                                $year_employee_sale = isset($year_employee_sale)?$year_employee_sale:'';
                                                                                $thisYearsDates = isset($thisYearsDates)?$thisYearsDates."'{$year_employee_sale->creation_date}',":"'{$year_employee_sale->creation_date}',";
                                                                                $thisYearsData = isset($thisYearsData)?$thisYearsData.$year_employee_sale->total.',':$year_employee_sale->total.',';
                                                                                $thisYearsColors = isset($thisYearsColors)?$thisYearsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisYearsSale = isset($thisYearsSale)?
                                                                                $thisYearsSale+$year_employee_sale->total:
                                                                                $year_employee_sale->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $year_employee_sale->creation_date }}</td>
                                                                                <td>{{ $year_employee_sale->counter }}</td>
                                                                                <td>{{ number_format($year_employee_sale->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h3>Total Amount = Rs.{!! number_format($thisYearsSale) !!}</h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5" style="position:relative;min-height:200px;">
                                                                <canvas id="thisYearSaleGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
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
                            </div>

                            <div class="tab-pane" id="purchase">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-tools">
                                            <ul class="nav nav-pills ml-auto">
                                                <li class="nav-item"><a class="nav-link active" href="#purchases-today" data-toggle="tab">Today</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#purchases-this-month" data-toggle="tab">This Month</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#purchases-this-year" data-toggle="tab">This Year</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content p-0">
                                            <!-- Morris chart - purchases -->
                                            @if(isset($today_purchases))
                                                <div class="chart tab-pane active" id="purchases-today">
                                                    @if(count($today_purchases) > 0)
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
                                                                        @foreach($today_purchases as $today_purchase)
                                                                            @php
                                                                                $today_purchase = isset($today_purchase)?$today_purchase:'';
                                                                                $todayTimes = isset($todayTimes)?$todayTimes."'".\Carbon\Carbon::parse($today_purchase->creation_date)->format('h:i a')."',":"'".\Carbon\Carbon::parse($today_purchase->creation_date)->format('h:i a')."',";
                                                                                $todayData = isset($todayData)?$todayData."{$today_purchase->total}," :"{$today_purchase->total}," ;
                                                                                $todayColors = isset($todayColors)?$todayColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisTodaysPurchase = isset($thisTodaysPurchase)?
                                                                                $thisTodaysPurchase+$today_purchase->total:
                                                                                $today_purchase->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{\Carbon\Carbon::parse($today_purchase->creation_date)->format('h:i a')}}</td>
                                                                                <td>{{ number_format($today_purchase->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h3>Total Amount = Rs.{!! number_format($thisTodaysPurchase) !!}</h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4"  style="position:relative;min-height:200px;">
                                                                <canvas id="todaysGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No purchase for today
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(isset($month_purchases))
                                                <div class="chart tab-pane" id="purchases-this-month">
                                                    @if(count($month_purchases) > 0)
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Date</th>
                                                                            <th>Purchases</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($month_purchases as $month_purchase)
                                                                            @php
                                                                                $month_purchase = isset($month_purchase)?$month_purchase:'';
                                                                                $thisMonthsDates = isset($thisMonthsDates)?$thisMonthsDates."'{$month_purchase->creation_date}',":"'{$month_purchase->creation_date}',";
                                                                                $thisMonthsData = isset($thisMonthsData)?$thisMonthsData.$month_purchase->total.',':$month_purchase->total.',';
                                                                                $thisMonthsColors = isset($thisMonthsColors)?$thisMonthsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisMonthsPurchase = isset($thisMonthsPurchase)?
                                                                                $thisMonthsPurchase+$month_purchase->total:
                                                                                $month_purchase->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $month_purchase->creation_date }}</td>
                                                                                <td>{{ $month_purchase->counter }}</td>
                                                                                <td>{{ number_format($month_purchase->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h3>Total Amount = Rs.{!! number_format($thisMonthsPurchase) !!}</h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" style="position:relative;min-height:200px;">
                                                                <canvas id="thisMonthsGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No purchase for this month
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(isset($year_purchases))
                                                <div class="chart tab-pane" id="purchases-this-year">
                                                    @if(count($year_purchases) > 0)
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Date</th>
                                                                            <th>Purchases</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($year_purchases as $year_purchase)
                                                                            @php
                                                                                $year_purchase = isset($year_purchase)?$year_purchase:'';
                                                                                $thisYearsDates = isset($thisYearsDates)?$thisYearsDates."'{$year_purchase->creation_date}',":"'{$year_purchase->creation_date}',";
                                                                                $thisYearsData = isset($thisYearsData)?$thisYearsData.$year_purchase->total.',':$year_purchase->total.',';
                                                                                $thisYearsColors = isset($thisYearsColors)?$thisYearsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisYearsPurchase = isset($thisYearsPurchase)?
                                                                                $thisYearsPurchase+$year_purchase->total:
                                                                                $year_purchase->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $year_purchase->creation_date }}</td>
                                                                                <td>{{ $year_purchase->counter }}</td>
                                                                                <td>{{ number_format($year_purchase->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h3>Total Amount = Rs.{!! number_format($thisYearsPurchase) !!}</h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5" style="position:relative;min-height:200px;">
                                                                <canvas id="thisYearsGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No purchases for this year
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('extras')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function () {
            $('body').on('click', '[data-toggle="modal"]', function () {
                window.location.href = $(this).data('href');
            });
            @if(isset($today_purchases) && count($today_purchases)>0)
            // Donut Chart
            let todaysPurchaseGraph = $('#todaysGraph').get(0).getContext('2d'),
                todayPurchaseData = {
                    labels: [{!! $todayTimes !!}],
                    datasets: [{
                        data: [{!! $todayData !!}],
                        backgroundColor : [{!! $todayColors !!}],
                    }]
                };
            let todaysPurchasePieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let todaysPurchasePieChart = new Chart(todaysPurchaseGraph,{type: 'pie',data: todayPurchaseData,options: todaysPurchasePieOptions});
            @endif

            @if(isset($month_purchases) && count($month_purchases)>0)
            // Donut Chart
            let thisMonthsPurchaseGraph = $('#thisMonthsGraph').get(0).getContext('2d'),
                thisMonthsPurchaseData = {
                    labels: [{!! $thisMonthsDates !!}],
                    datasets: [{
                        data: [{!! $thisMonthsData !!}],
                        backgroundColor : [{!! $thisMonthsColors !!}],
                    }]
                };
            let thisMonthsPieOptions = {legend: {display: true,position: 'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let thisMonthsPieChart = new Chart(thisMonthsPurchaseGraph,{type: 'doughnut',data: thisMonthsPurchaseData,options: thisMonthsPieOptions});
            @endif

            @if(isset($year_purchases) && count($year_purchases)>0)
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
        $(function () {
            $('body').on('click', '[data-toggle="modal"]', function () {
                window.location.href = $(this).data('href');
            });
            @if(isset($today_employee_sales) && count($today_employee_sales)>0)
            // Donut Chart
            let todaysSaleGraph = $('#todaysSaleGraph').get(0).getContext('2d'),
                todaySaleData = {
                    labels: [{!! $todayTimes !!}],
                    datasets: [{
                        data: [{!! $todayData !!}],
                        backgroundColor : [{!! $todayColors !!}],
                    }]
                };
            let todaysSalePieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let todaysSalePieChart = new Chart(todaysSaleGraph,{type: 'pie',data: todaySaleData,options: todaysSalePieOptions});
            @endif

            @if(isset($month_employee_sales) && count($month_employee_sales)>0)
            // Donut Chart
            let thisMonthsSaleGraph = $('#thisMonthsSaleGraph').get(0).getContext('2d'),
                thisMonthsSaleData = {
                    labels: [{!! $thisMonthsDates !!}],
                    datasets: [{
                        data: [{!! $thisMonthsData !!}],
                        backgroundColor : [{!! $thisMonthsColors !!}],
                    }]
                };
            let thisMonthsSalePieOptions = {legend: {display: true,position: 'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let thisMonthsSalePieChart = new Chart(thisMonthsSaleGraph,{type: 'doughnut',data: thisMonthsSaleData,options: thisMonthsSalePieOptions});
            @endif

            @if(isset($year_employee_sales) && count($year_employee_sales)>0)
            let yearSaleGraphType = 'bar';
            // Donut Chart
            let thisYearSaleGraph = $('#thisYearSaleGraph').get(0).getContext('2d'),
                thisYearSaleData = {
                    labels: [{!! $thisYearsDates !!}],
                    datasets: [{
                        data: [{!! $thisYearsData !!}],
                        backgroundColor : [{!! $thisYearsColors !!}],
                    }]
                };
            if (yearSaleGraphType === 'pie' || yearSaleGraphType === 'doughnut') {
                let thisYearSalePieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
                // You can switch between pie and doughnut using the method below.
                let thisYearsPieChart = new Chart(thisYearSaleGraph,{type: 'doughnut',data: thisYearSaleData,options: thisYearSalePieOptions});
            } else if (yearSaleGraphType === 'bar') {
                let thisYearSaleBarOptions = {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                };
                // You can switch between pie and doughnut using the method below.
                let thisYearSaleBarChart = new Chart(thisYearSaleGraph, {
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
                    options: thisYearSaleBarOptions,
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
