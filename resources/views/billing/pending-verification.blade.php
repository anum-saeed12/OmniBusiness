@extends('layouts.basic')

@section('content')
    <div class="wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col text-center">
                        <h1>Account awaiting approval</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-warning">IN APPROVED</h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> Please try again later</h3>
                    <p>
                        Your account has been submitted in review.
                        You can access your dashboard after our administrators approve your registration.
                        Meanwhile, you may <a href="{{ route('landing') }}">read our terms and conditions</a>. We will
                        send you
                        an email when you have been approved.
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
