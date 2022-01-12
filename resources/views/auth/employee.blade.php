@extends('layouts.basic')

@section('content')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ $base_url }}"><b>Omni</b>Business</a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <h2 class="register-box-msg">Greetings</h2>
                <form action="{{ route('auth.register') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input name="company" type="text" class="form-control" placeholder="Company Name" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-briefcase"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input name="email" type="email" class="form-control" placeholder="Email" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input name="password" type="password" class="form-control" placeholder="Password" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input name="confirm_password" type="password" class="form-control" placeholder="Retype password" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <select name="employee_type" class="form-control" required="required">
                            <option>Please select association</option>
                            <option value="employee">Employee</option>
                            <option value="owner">Owner</option>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-handshake"></span>
                            </div>
                        </div>
                    </div>
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
