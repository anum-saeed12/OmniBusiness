@extends('layouts.basic')

@section('content')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ $base_url }}">{{ config('app.name') }}<span class="text-primary">.</span></a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Register a new company</p>

                <form action="{{ route('auth.register') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input name="name" type="text"
                               class="form-control" placeholder="Company name"
                               value="{{ old('name') }}" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-briefcase"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger">@error('company'){{ $message }}@enderror</div>
                    <div class="input-group mt-3">
                        <input name="official_email" type="email"
                               class="form-control" placeholder="Email"
                               value="{{ old('official_email') }}" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger">@error('official_email'){{ $message }}@enderror</div>
                    <div class="input-group mt-3">
                        <input name="password" type="password"
                               class="form-control" placeholder="Password"
                               required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger">@error('password'){{ $message }}@enderror</div>

                    <div class="input-group mt-3">
                        <input name="password_confirmation" type="password"
                               class="form-control" placeholder="Retype password"
                               required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger">@error('password_confirmation'){{ $message }}@enderror</div>
                    <div class="input-group mt-3">
                        <select name="type_of_subscription" class="form-control"
                                required="required">
                            <option>Please select Subscription</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-handshake"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger">@error('type_of_subscription'){{ $message }}@enderror</div>

                    <div class="input-group mt-3">
                        <input name="receipt" type="file"
                               class="form-control-file"
                               required="required">
                    </div>

                    <div class="mt-3"></div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree" required="required">
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                {{--@include('includes.social')--}}

                <a href="{{ route('login') }}" class="text-center">I have already registered</a>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->
@stop
