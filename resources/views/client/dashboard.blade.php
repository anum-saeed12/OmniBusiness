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

                {{--EMPLOYEE BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ number_format($employees->total) }}</h3>
                            <p>Employees</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user nav-icon text-danger"></i>
                        </div>
                        <a href="{{ route('employee.list.client') }}" class="small-box-footer bg-danger" style="color:white!important;">View Employees <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--PRODUCT BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ number_format($products->total) }}</h3>
                            <p>Products</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes nav-icon text-success"></i>
                        </div>
                        <a href="{{ route('product.list.client') }}" class="small-box-footer bg-success" style="color:white!important;">View Inventory <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--SALE BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ number_format($sales->total) }}<sup style="font-size: 14px;top:0;">{{ $currency }}</sup></h3>

                            <p>New Sales</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dolly nav-icon text-info"></i>
                        </div>
                        <a href="{{ route('sale.overview.client') }}" class="small-box-footer bg-info"  style="color:white!important;">Overview <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--Purchase BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ number_format($purchases->total) }}<sup style="font-size: 14px;top:0;">{{ $currency }}</sup></h3>
                            <p>New Purchases</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-truck-loading nav-icon text-warning"></i>
                        </div>
                        <a href="{{ route('purchase.overview.client') }}"  class="small-box-footer bg-warning"  style="color:white!important;">Overview <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Yearly Recap Report</h5>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    {{-- Only display the overview if no filter is provided--}}
                                    @if(isset($year_sales) || isset($year_purchases))
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <i class="fas fa-chart-pie mr-1"></i>
                                                </h3>
                                                <div class="card-tools">
                                                    <ul class="nav nav-pills ml-auto">
                                                        @if(isset($year_purchases) || isset($year_sales)) <li class="nav-item"><a class="nav-link active" href="#purchases-this-year" data-toggle="tab">This Year</a></li>@endif
                                                    </ul>
                                                </div>
                                            </div><!-- /.card-header -->
                                            <div class="card-body">
                                                <div class="tab-content p-0">
                                                    <div class="chart tab-pane active" id="purchases-this-year">
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                @foreach($year_purchases as $year_purchase)
                                                                    @php
                                                                        $year_purchase = isset($year_purchase)?$year_purchase:'';
                                                                        $thisYearsDates = isset($thisYearsDates)?$thisYearsDates."'{$year_purchase->creation_date}',":"'{$year_purchase->creation_date}',";
                                                                        $thisYearsPurchaseData = isset($thisYearsPurchaseData)?$thisYearsPurchaseData.intval($year_purchase->total).',':intval($year_purchase->total).',';
                                                                        $thisYearsColors = isset($thisYearsColors)?$thisYearsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                        $thisYearsPurchase = isset($thisYearsPurchase)?
                                                                        $thisYearsPurchase+$year_purchase->total:
                                                                        $year_purchase->total;
                                                                    @endphp
                                                                @endforeach

                                                                    @foreach($year_sales as $year_sale)
                                                                    @php
                                                                        $year_sale = isset($year_sale)?$year_sale:'';
                                                                        $thisYearsDates = isset($thisYearsDates)?$thisYearsDates."'{$year_sale->creation_date}',":"'{$year_sale->creation_date}',";
                                                                        $thisYearsSaleData = isset($thisYearsSaleData)?$thisYearsSaleData.$year_sale->total.',':$year_sale->total.',';
                                                                        $thisYearsColors = isset($thisYearsColors)?$thisYearsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                        $thisYearsSale = isset($thisYearsSale)?
                                                                        $thisYearsSale+$year_sale->total:
                                                                        $year_sale->total;
                                                                    @endphp
                                                                @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="col-12" style="position:relative;min-height:440px;">
                                                                <canvas id="thisYearsGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-4 col-6">
                                    <div class="description-block border-right">
                                        <h5 class="description-header">Rs.{!! isset($thisYearsSale)?number_format($thisYearsSale):0 !!}</h5>
                                        <span class="description-text">TOTAL REVENUE</span>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-6">
                                    <div class="description-block border-right">
                                        <h5 class="description-header">Rs.{!! isset($thisYearsPurchase)?number_format($thisYearsPurchase):0 !!}</h5>
                                        <span class="description-text">TOTAL COST</span>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-6">
                                    <div class="description-block border-right">
                                        <h5 class="description-header">Rs.{!! isset($thisYearsSale)?(isset($thisYearsPurchase)?number_format($thisYearsSale - $thisYearsPurchase):$thisYearsSale):0 !!}</h5>
                                        <span class="description-text">TOTAL PROFIT</span>
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
    <script type="text/javascript">
        $(function () {
            $('body').on('click', '[data-toggle="modal"]', function () {
                window.location.href = $(this).data('href');
            });
            let yearGraphType = 'line';
            let thisYearsBarOptions = {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                maintainAspectRatio: false,
                responsive: true,
            };
            let thisYearsData = {
                labels: [{!! isset($thisYearsDates)?($thisYearsDates):'' !!}],
                datasets: [
                    {
                        label: 'Sale',
                        data: [{!! isset($thisYearsSaleData)?$thisYearsSaleData:'' !!}],
                        borderColor: 'rgb(124,255,1)',
                        //backgroundColor: 'rgba(124,255,1,0.8)',
                        backgroundColor:'rgba(0,0,0,0)',
                        type: 'line'
                    },
                    {
                        label: 'Purchase',
                        data: [{!! isset($thisYearsPurchaseData)?$thisYearsPurchaseData:'' !!}],
                        borderColor: 'rgb(2,205,241)',
                        //backgroundColor: 'rgba(241,2,2,0.8)',
                        backgroundColor:'rgba(0,0,0,0)',
                        type: 'line'
                    }
                ]
            };
            let thisYearsConfig = {
                type: 'line',
                data: thisYearsData,
                options: thisYearsBarOptions,
                scales: {
                    y: {
                        stacked: true
                    }
                }
            };
            // You can switch between pie and doughnut using the method below.
            let thisYearsBarChart = new Chart(thisYearsGraph, thisYearsConfig);

        });
    </script>
@stop
