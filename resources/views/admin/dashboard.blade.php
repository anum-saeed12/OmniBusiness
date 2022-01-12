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

                {{--USER BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ number_format($total_user->total) }}</h3>
                            <p>User</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users nav-icon text-danger"></i>
                        </div>
                        <a href="{{ route('user.list.admin') }}" class="small-box-footer bg-danger" style="color:white!important;">View Users <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--CLIENT BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ number_format($client->total) }}</h3>
                            <p>Client</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user nav-icon text-success"></i>
                        </div>
                        <a href="{{ route('client.list.admin') }}" class="small-box-footer bg-success" style="color:white!important;">View Clients <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--CLIENT BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ number_format($client->total) }}</h3>
                            <p>Department</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-building nav-icon text-gray"></i>
                        </div>
                        <a href="{{ route('department.list.admin') }}" class="small-box-footer bg-gray" style="color:white!important;">View Departments <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ number_format($vendor->total) }}</h3>
                            <p>Vendor</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-landmark  nav-icon text-info"></i>
                        </div>
                        <a href="#" class="small-box-footer bg-info" style="color:white!important;"> <i class="fas"></i></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                @if(isset($today_clients) || isset($month_clients) || isset($year_clients))
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Clients
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    @if(isset($today_clients)) <li class="nav-item"><a class="nav-link active" href="#purchases-today" data-toggle="tab">Today</a></li> @endif
                                    @if(isset($month_clients)) <li class="nav-item"><a class="nav-link" href="#purchases-this-month" data-toggle="tab">This Month</a></li>@endif
                                    @if(isset($year_clients)) <li class="nav-item"><a class="nav-link" href="#purchases-this-year" data-toggle="tab">This Year</a></li>@endif
                                </ul>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <!-- Morris chart - clients -->
                                @if(isset($today_clients))
                                    <div class="chart tab-pane active" id="purchases-today">
                                        @if(count($today_clients) > 0)
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-borderless">
                                                            <thead>
                                                            <tr>
                                                                <th>Time</th>
                                                                <th>Clients</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($today_clients as $today_client)
                                                                @php
                                                                    $today_client = isset($today_client)?$today_client:'';
                                                                    //$todayTimes = isset($todayTimes)?$todayTimes."'".\Carbon\Carbon::parse($today_client->creation_date)->format('h:i a')."',":"'".\Carbon\Carbon::parse($today_client->creation_date)->format('h:i a')."',";
                                                                    $todayTimes = isset($todayTimes)?$todayTimes."'".\Carbon\Carbon::parse($today_client->creation_date)->format('h:i a')."',":"'".\Carbon\Carbon::parse($today_client->creation_date)->format('h:i a')."',";
                                                                    $todayData = isset($todayData)?$todayData."{$today_client->total}," :"{$today_client->total}," ;
                                                                    $todayColors = isset($todayColors)?$todayColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                    $thisTodayClient = isset($thisTodayClient)?
                                                                    $thisTodayClient+$today_client->total:
                                                                    $today_client->total;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{\Carbon\Carbon::parse($today_client->creation_date)->format('h:i a')}}</td>
                                                                    <td>{{ $today_client->total }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        <h3>Total Clients = {!! $thisTodayClient !!}</h3>
                                                    </div>
                                                </div>
                                                <div class="col p-3" style="position:relative;min-height:300px;">
                                                    <canvas id="todaysGraph" height="100%" style="height: 100%;"></canvas>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                No client for today
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if(isset($month_clients))
                                    <div class="chart tab-pane" id="purchases-this-month">
                                        @if(count($month_clients) > 0)
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-borderless">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Clients</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($month_clients as $month_client)
                                                                @php
                                                                    $month_client = isset($month_client)?$month_client:'';
                                                                    $thisMonthsDates = isset($thisMonthsDates)?$thisMonthsDates."'{$month_client->creation_date}',":"'{$month_client->creation_date}',";
                                                                    $thisMonthsData = isset($thisMonthsData)?$thisMonthsData.$month_client->total.',':$month_client->total.',';
                                                                    $thisMonthsColors = isset($thisMonthsColors)?$thisMonthsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                    $thisMonthsClient = isset($thisMonthsClient)?
                                                                    $thisMonthsClient+$month_client->total:
                                                                    $month_client->total;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $month_client->creation_date }}</td>
                                                                    <td>{{ ($month_client->total) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        <h3>Total Client = {!! number_format($thisMonthsClient) !!}</h3>
                                                    </div>
                                                </div>
                                                <div class="col p-3" style="position:relative;min-height:300px;">
                                                    <canvas id="thisMonthsGraph" height="100%" style="height: 100%;"></canvas>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                No client for this month
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if(isset($year_clients))
                                    <div class="chart tab-pane" id="purchases-this-year">
                                        @if(count($year_clients) > 0)
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-borderless">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Clients</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($year_clients as $year_client)
                                                                @php
                                                                    $year_client = isset($year_client)?$year_client:'';
                                                                    $thisYearsDates = isset($thisYearsDates)?$thisYearsDates."'{$year_client->creation_date}',":"'{$year_client->creation_date}',";
                                                                    $thisYearsData = isset($thisYearsData)?$thisYearsData.$year_client->total.',':$year_client->total.',';
                                                                    $thisYearsColors = isset($thisYearsColors)?$thisYearsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                    $thisYearsClient = isset($thisYearsClient)?
                                                                    $thisYearsClient+$year_client->total:
                                                                    $year_client->total;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $year_client->creation_date }}</td>
                                                                    <td>{{ ($year_client->total) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        <h3>Total Client = {!! number_format($thisYearsClient) !!}</h3>
                                                    </div>
                                                </div>
                                                <div class="col p-3" style="position:relative;min-height:300px;">
                                                    <canvas id="thisYearsGraph" height="100%" style="height: 100%;"></canvas>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                No clients for this year
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
            @if(isset($today_clients) && count($today_clients)>0)
            // Donut Chart
            let todaysGraph = $('#todaysGraph').get(0).getContext('2d'),
                todayData = {
                    labels: [{!! trim($todayTimes,",") !!}],
                    datasets: [{
                        label: "Today's Data",
                        data: [{!! $todayData !!}],
                        backgroundColor : [{!! $todayColors !!}],
                    }]
                };
            let todaysPieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let todaysPieChart = new Chart(todaysGraph,{type: 'line',data: todayData,options: todaysPieOptions});
            @endif

            @if(isset($month_clients) && count($month_clients)>0)
            // Donut Chart
            let thisMonthsGraph = $('#thisMonthsGraph').get(0).getContext('2d'),
                thisMonthsData = {
                    labels: [{!! $thisMonthsDates !!}],
                    datasets: [{
                        label: "This Month's Data",
                        data: [{!! $thisMonthsData !!}],
                        backgroundColor : [{!! $thisMonthsColors !!}],
                    }]
                };
            let thisMonthsPieOptions = {legend: {display: true,position: 'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let thisMonthsPieChart = new Chart(thisMonthsGraph,{type: 'line',data: thisMonthsData,options: thisMonthsPieOptions});
                              @endif

            @if(isset($year_clients) && count($year_clients)>0)
            let yearGraphType = 'bar';
            // Donut Chart
            let thisYearsGraph = $('#thisYearsGraph').get(0).getContext('2d'),
                thisYearsData = {
                    labels: [{!! $thisYearsDates !!}],
                    datasets: [{
                        label: "Yearly Data",
                        data: [{!! $thisYearsData !!}],
                        backgroundColor : [{!! $thisYearsColors !!}],
                    }]
                };
            if (yearGraphType === 'pie' || yearGraphType === 'doughnut') {
                let thisYearsPieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
                // You can switch between pie and doughnut using the method below.
                let thisYearsPieChart = new Chart(thisYearsGraph,{type: 'line',data: thisYearsData,options: thisYearsPieOptions});
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
                            label: "Yearly Data",
                            data: [{!! $thisYearsData !!}],
                            borderColor: 'rgb(111,193,97)',
                            backgroundColor: 'rgba(111,193,97,0.8)',
                            stack: 'combined',
                            type: 'line'
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
