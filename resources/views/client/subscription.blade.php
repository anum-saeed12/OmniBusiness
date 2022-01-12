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
                        <li class="breadcrumb-item">Subscription</li>
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
            @include('client.membership.components.actions')
            <div class="row">
                <div class="col-12">
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-sm">
                                <thead>
                                <tr>
                                    <th>Last Payment Date</th>
                                    <th>Next Payment Date</th>
                                    <th>Membership Start</th>
                                    <th>Membership End</th>
                                    <th>Last Amount</th>
                                    <th>Paid</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subscription as $item)
                                    <tr>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->last_payment_date)->format('d-M-Y') }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->next_payment_date)->format('d-M-Y')  }}</td>
                                        @if($item->approved == 1)
                                            <td>{{ \Illuminate\Support\Carbon::parse($item->membership_start)->format('d-M-Y') }}</td>
                                            <td>{{ \Illuminate\Support\Carbon::parse($item->membership_end)->format('d-M-Y') }}</td>
                                        @else
                                            <td colspan="2" class="text-center">
                                                <div style="font-size:11px;background:#fdb66e;padding:2px 5px;border-radius:4px;height:22px;line-height:21px;text-transform:uppercase;margin:3px 0;">Approval Pending</div>
                                            </td>
                                        @endif
                                        <td>{{ number_format($item->last_paid_amount,2) }}</td>
                                        <td>{{ ucfirst($item->type_of_subscription) }}</td>
                                        <td>
                                            @if(!empty($item->receipt))
                                                <a class="bg-warning list-btn" href="{{ url($item->receipt) }}" title="View submitted receipt" target="_blank"><i class="fas fa-file-pdf" aria-hidden="false"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

