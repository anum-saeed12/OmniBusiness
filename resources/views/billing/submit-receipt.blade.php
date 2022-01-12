@extends('layouts.basic')

@section('content')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ $base_url }}">{{ config('app.name') }}<span class="text-primary">.</span></a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Submit your payment receipt</p>

                <form action="{{ route('billing.save.client') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="input-group mt-3">
                        <input name="official_email" type="email"
                               class="form-control" placeholder="Registered Email"
                               value="{{ old('official_email') }}" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger">@error('official_email'){{ $message }}@enderror</div>

                    <div class="input-group mt-3">
                        <input name="receipt" type="file"
                               class="form-control-file"
                               required="required">
                    </div>

                    <div class="mt-3"></div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                {{--@include('includes.social')--}}

                <a href="{{ route('login') }}" class="text-center">I have already paid my dues</a>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->
@stop
