@extends('layouts.basic')

@section('content')
    <div class="wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col text-center">
                        <h1>Account unpaid</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-danger">UNPAID DUES</h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-danger"></i> Please pay your dues</h3>
                    <p>
                        Your account has been marked as "unpaid".
                        You can access your dashboard after our administrators approve your payment.
                        If you have paid your dues, you can <a href="{{ route('billing.submit.client') }}">Submit your receipt</a> so
                        we can activate your account after reviewing your payment.
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
