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
                        <li class="breadcrumb-item">Purchase</li>
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
            @include('purchase.components.overview-filters')
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
                    @if(isset($today_purchases) || isset($month_purchases) || isset($year_purchases))
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Purchase
                                    @if(in_array(auth()->user()->user_role, ['client','manager','employee']))
                                        <a href="{{ route('purchase.add.' . auth()->user()->user_role) }}" class="btn btn-sm btn-success ml-2"><i class="fa fa-plus-circle mr-1"></i> Add New</a>
                                    @endif
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        @if(isset($today_purchases)) <li class="nav-item"><a class="nav-link active" href="#purchases-today" data-toggle="tab">Today</a></li> @endif
                                        @if(isset($month_purchases)) <li class="nav-item"><a class="nav-link" href="#purchases-this-month" data-toggle="tab">This Month</a></li>@endif
                                        @if(isset($year_purchases)) <li class="nav-item"><a class="nav-link" href="#purchases-this-year" data-toggle="tab">This Year</a></li>@endif
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content p-3">
                                <!-- Morris chart - purchases -->
                                @if(isset($today_purchases))
                                    <div class="chart tab-pane active" id="purchases-today">
                                        @if(count($today_purchases) > 0)
                                            <div class="row">
                                                <div class="col p-3" style="position:relative;min-height:300px;">
                                                    <canvas id="todaysGraph" height="100%" style="height: 100%;"></canvas>
                                                </div>
                                            </div>
                                            <div class="row"><div class="col"><hr></div></div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-borderless">
                                                            <thead>
                                                          {{--  <tr>
                                                                <th>Time</th>
                                                                <th>Amount</th>
                                                            </tr>--}}
                                                            </thead>
                                                            <tbody>
                                                            @foreach($today_purchases as $today_purchase)
                                                                @php
                                                                    $today_purchase = isset($today_purchase)?$today_purchase:'';
                                                                    $todayTimes = isset($todayTimes)?$todayTimes."'".\Carbon\Carbon::parse($today_purchase->creation_date)->format('h:i a')."',":"'".\Carbon\Carbon::parse($today_purchase->creation_date)->format('h:i a')."',";
                                                                    $todayData = isset($todayData)?$todayData."{$today_purchase->total}," :"{$today_purchase->total}," ;
                                                                    $todayColors = isset($todayColors)?$todayColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                @endphp
                                                               {{-- <tr>
                                                                    <td>{{\Carbon\Carbon::parse($today_purchase->creation_date)->format('h:i a')}}</td>
                                                                    <td>{{ number_format($today_purchase->total) }} {{ $currency }}</td>
                                                                </tr>--}}
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
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
                                                <div class="col p-3" style="position:relative;min-height:300px;">
                                                    <canvas id="thisMonthsGraph" height="100%" style="height: 100%;"></canvas>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-borderless">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>purchases</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($month_purchases as $month_purchase)
                                                                @php
                                                                    $month_purchase = isset($month_purchase) ? $month_purchase:'';
                                                                    $thisMonthsDates = isset($thisMonthsDates)?$thisMonthsDates."'{$month_purchase->creation_date}',":"'{$month_purchase->creation_date}',";
                                                                    $thisMonthsData = isset($thisMonthsData)?$thisMonthsData.$month_purchase->total.',':$month_purchase->total.',';
                                                                    $thisMonthsColors = isset($thisMonthsColors)?$thisMonthsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                    $thisMonthspurchase = isset($thisMonthspurchase)?
                                                                    $thisMonthspurchase+$month_purchase->total:
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
                                                        <h3>Total Amount = Rs.{!! number_format($thisMonthspurchase) !!}</h3>
                                                        <h1></h1>
                                                    </div>
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
                                                <div class="col p-3" style="position:relative;min-height:300px;">
                                                    <canvas id="yearGraph" height="100%" style="height: 100%;"></canvas>
                                                </div>
                                            </div>
                                            <div class="row"><div class="col"><hr></div></div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-borderless">
                                                            <thead>
                                                          {{--  <tr>
                                                                <th>Date</th>
                                                                <th>purchases</th>
                                                                <th>Amount</th>
                                                            </tr>--}}
                                                            </thead>
                                                            <tbody>
                                                            @foreach($year_purchases as $year_purchase)
                                                                @php
                                                                    $year_purchase = isset($year_purchase)?$year_purchase:'';
                                                                    $thisYearsDates = isset($thisYearsDates)?$thisYearsDates."'{$year_purchase->creation_date}',":"'{$year_purchase->creation_date}',";
                                                                    $thisYearsData = isset($thisYearsData)?$thisYearsData.$year_purchase->total.',':$year_purchase->total.',';
                                                                    $thisYearsColors = isset($thisYearsColors)?$thisYearsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                    $thisYearspurchase = isset($thisYearspurchase)?
                                                                    $thisYearspurchase+$year_purchase->total:
                                                                    $year_purchase->total;
                                                                @endphp
                                                           {{--     <tr>
                                                                    <td>{{ $year_purchase->creation_date }}</td>
                                                                    <td>{{ $year_purchase->counter }}</td>
                                                                    <td>{{ number_format($year_purchase->total) }} {{ $currency }}</td>
                                                                </tr>--}}
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                       {{-- <h3>Total Amount = Rs.{!! number_format($thisYearspurchase) !!}</h3>--}}
                                                    </div>
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
                @endif
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

            @if(isset($year_purchases) && count($year_purchases)>0)
                {!! $year_graph !!}
                @endif

                @if(isset($month_purchases) && count($month_purchases)>0)
                {!! $month_graph !!}
                @endif

                @if(isset($today_purchases) && count($today_purchases)>0)
                {!! $today_graph !!}
                @endif
        });
    </script>
@stop
