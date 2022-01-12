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
        </div><!-- /.container-fluid -->
    </section>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">

            {{--Employee BOX--}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3>{{ number_format($employees->total) }}<sup style="font-size: 14px;top:0;"></sup></h3>

                        <p>Employees</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dolly nav-icon text-purple"></i>
                    </div>
                    <a href="{{ route('employee.list.client') }}" class="small-box-footer bg-purple"  style="color:white!important;">See all <i class="fas fa-arrow-circle-right"></i></a>
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
                    <a href="{{ route('sale.list.manager') }}" class="small-box-footer bg-info"  style="color:white!important;">See all <i class="fas fa-arrow-circle-right"></i></a>
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
                    <a href="{{ route('purchase.list.manager') }}"  class="small-box-footer bg-warning"  style="color:white!important;">See all <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('extras')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@stop
