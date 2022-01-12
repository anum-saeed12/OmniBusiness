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
                        <a href="{{ route('client.list.admin') }}" class="small-box-footer bg-danger" style="color:white!important;">View Employees <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

          {{--      --}}{{--PRODUCT BOX--}}{{--
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

                --}}{{--SALE BOX--}}{{--
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

                --}}{{--Purchase BOX--}}{{--
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
                </div>--}}
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Recap Report</h5>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="text-center">
                                        <strong>Sales: </strong>
                                    </p>

                                    <div class="chart">
                                        <!-- Sales Chart Canvas -->
                                        <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                                    </div>
                                    <!-- /.chart-responsive -->
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- ./card-body -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-4 col-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                                        <h5 class="description-header">$35,210.43</h5>
                                        <span class="description-text">TOTAL REVENUE</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                                        <h5 class="description-header">$10,390.90</h5>
                                        <span class="description-text">TOTAL COST</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                                        <h5 class="description-header">$24,813.53</h5>
                                        <span class="description-text">TOTAL PROFIT</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
    </section>
@stop

@section('extras')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@stop
