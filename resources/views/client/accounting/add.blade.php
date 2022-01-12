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
                        <li class="breadcrumb-item">Employee</li>
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
        <form action="{{ route('employee.store.client') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Personal Information</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label for="firstname">First Name</label><br/>
                                    <input type="text" name="firstname" class="form-control" id="firstname"
                                           placeholder="First Name" value="{{ old('firstname')}}" >
                                    <div class="text-danger">@error('firstname'){{ $message }}@enderror</div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="lastname">Last Name</label><br/>
                                    <input type="text" name="lastname" class="form-control" id="lastname"
                                           placeholder="Last Name" value="{{ old('lastname')}}" >
                                    <div class="text-danger">@error('lastname'){{ $message }}@enderror</div>
                                </div>

                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label for="gender">Gender</label><br/>
                                    <select name="gender" class="form-control" id="gender">
                                        <option selected="selected" value>Select</option>
                                        <option value="male" >Male</option>
                                        <option value="female" >Female</option>
                                    </select>
                                    <div class="text-danger">@error('gender'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="nationality">Nationality</label><br/>
                                    <select name="nationality" class="form-control" id="nationality">
                                        <option selected="selected" value>Select</option>
                                        <option value="pakistani" >Pakistan</option>
                                        <option value="british" >British</option>
                                    </select>
                                    <div class="text-danger">@error('nationality'){{ $message }}@enderror</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label for="birth_place">Birth Place</label><br/>
                                    <select name="birth_place" class="form-control" id="birth_place">
                                        <option selected="selected" value>Select</option>
                                        <option value="pakistan">Pakistan</option>
                                        <option value="uk">United Kingdom</option>
                                        <option value="usa">United States</option>
                                    </select>
                                    <div class="text-danger">@error('birth_place'){{ $message }}@enderror</div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="birth_country">Birth Country</label><br/>
                                    <select name="birth_country" class="form-control" id="birth_country">
                                        <option selected="selected" value>Select</option>
                                        <option value="pakistani">Pakistan</option>
                                        <option value="british">British</option>
                                    </select>
                                    <div class="text-danger">@error('birth_country'){{ $message }}@enderror</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label for="martial_status">Martial Status</label><br/>
                                    <select name="martial_status" class="form-control" id="martial_status">
                                        <option selected="selected" value>Select</option>
                                        <option value="single" >Single</option>
                                        <option value="married" >Married</option>
                                    </select>
                                    <div class="text-danger">@error('martial_status'){{ $message }}@enderror</div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="children">Children</label><br/>
                                    <input type="text" name="children" class="form-control"
                                           id="children" value="{{ old('children')}}">
                                    <div class="text-danger">@error('children'){{ $message }}@enderror</div>
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="mobile_no">Mobile Number</label><br/>
                                    <input type="text" name="mobile_no" class="form-control"
                                           id="mobile_no" value="{{ old('mobile_no')}}"
                                           placeholder="Example: 03211234567" >
                                    <div class="text-danger">@error('mobile_no'){{ $message }}@enderror</div>
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="emergency_contact">Emergency Contact Number</label><br/>
                                    <input type="text" name="emergency_contact"
                                           value="{{ old('emergency_contact')}}" class="form-control"
                                           id="emergency_contact" placeholder="Example: 03211234567">
                                    <div class="text-danger">@error('emergency_contact'){{ $message }}@enderror</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="birth_date">Date of Birth</label><br/>
                                    <div class="input-group date" id="birth_date" data-target-input="nearest">
                                        <input type="text" id="date_of_birth" name="birth_date"
                                               class="form-control datetimepicker-input"
                                               data-target="#date_of_birth" placeholder="yyyy/mm/dd"
                                               value="{{ old('birth_date')}}"
                                               data-toggle="datetimepicker"/>
                                        <div class="input-group-append" data-target="#date_of_birth"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-danger">@error('birth_date'){{ $message }}@enderror</div>
                                </div>
                            </div>
                            <div class="row mb-2">

                                <div class="col-sm-12">
                                    <label for="personal_email">Personal Email</label><br/>
                                    <input type="email" name="personal_email" class="form-control"
                                           id="personal_email" value="{{ old('personal_email')}}"
                                           placeholder="Enter Personal Email">
                                    <div class="text-danger">@error('personal_email'){{ $message }}@enderror
                                    </div>

                                </div>
                            </div>
                            <div class="row mb-2">

                                <div class="col-sm-12">
                                    <label for="bank_account_no">Account Number</label><br/>
                                    <input type="text" name="bank_account_no"
                                           value="{{ old('bank_account_no')}}"
                                           class="form-control"
                                           id="bank_account_no" placeholder="Enter Account Number without -">
                                    <div class="text-danger">@error('bank_account_no'){{ $message }}@enderror
                                    </div>

                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="nic_no">NIC Number</label><br/>
                                    <input type="text" name="nic_no" value="{{ old('nic_no')}}"
                                           class="form-control"
                                           id="nic_no" placeholder="Enter Nic No. without -">
                                    <div class="text-danger">@error('nic_no'){{ $message }}@enderror</div>

                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="location">Location</label><br/>
                                    <input type="text" name="location" class="form-control" id="location"
                                           value="{{ old('location')}}"
                                           placeholder="Enter Location ">
                                    <div class="text-danger">@error('location'){{ $message }}@enderror</div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">User Information</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="username">User Name</label><br/>
                                    <input type="text" name="username" class="form-control"
                                           id="username" value="{{ $users->_username }}"
                                           placeholder="Enter User Name">
                                    <input type="hidden" id="username_prefix" value="{{ $users->_username }}">
                                    <div class="text-danger">@error('username'){{ $message }}@enderror</div>

                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="password">Password</label><br/>
                                    <input type="password" name="password" class="form-control"
                                           id="password" value="{{ $password }}"
                                           placeholder="Enter Password">
                                    <div class="text-danger">@error('password'){{ $message }}@enderror</div>

                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="email">Company Email</label><br/>
                                    <input type="email" name="email" class="form-control"
                                           id="email" value="{{ old('email')}}"
                                           placeholder="Enter Company Email">
                                    <div class="text-danger">@error('email'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="user_role">User Role</label><br/>
                                    <select name="user_role" class="form-control" id="user_role">
                                        <option selected="selected" value>Select</option>
                                        <option value="manager">Manager</option>
                                        <option value="accountant">Accountant</option>
                                        <option value="employee">Employee</option>
                                    </select>
                                    <div class="text-danger">@error('user_role'){{ $message }}@enderror</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Education</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="education_level"> Education Level</label><br/>
                                    <select name="education_level" class="form-control" id="education_level">
                                        <option selected="selected" value>Select</option>
                                        <option value="matriculation">Matriculation</option>
                                        <option value="intermediate">Intermediate</option>
                                        <option value="bachelor's">Bachelor's</option>
                                        <option value="master's">Master's</option>
                                        <option value="doctorate">Doctorate</option>
                                        <option value="nothing">Nothing</option>
                                    </select>
                                    <div class="text-danger">@error('education_level'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="education_field">Education Field</label><br/>
                                    <input type="text" name="education_field" class="form-control" id="education_field"
                                           placeholder="Enter Education Field "
                                           value="{{ old('education_field')}}">
                                    <div class="text-danger">@error('education_field'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label for="educational_institute">Education Institute</label><br/>
                                    <input type="text" name="educational_institute" class="form-control"
                                           id="educational_institute"
                                           value="{{ old('educational_institute')}}"
                                           placeholder="Enter Education Institute ">
                                    <div class="text-danger">@error('educational_institute'){{ $message }}@enderror</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Working Information</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label for="position_id">Position</label><br/>
                                    <select name="position_id" class="form-control" id="position_id">
                                        <option selected="selected" value>Select</option>
                                        @foreach ($positions as $position)
                                        <option value="{{ $position->id }}">{{ ucfirst($position->title) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">@error('position_id'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="salary">Salary</label><br/>
                                    <input type="text" name="salary" class="form-control"
                                           id="salary" value="{{ old('salary') }}"
                                           placeholder="Enter Salary" >
                                    <div class="text-danger">@error('salary'){{ $message }}@enderror
                                    </div>
                                </div>

                            </div>
                            <div class="bootstrap-timepicker">
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <label for="working_time_start">Working Time Start</label>
                                        <div class="input-group time" id="working_time_start"
                                             data-target-input="nearest">
                                            <input type="text" name="working_time_start" id="working_time_start"
                                                   class="form-control datetimepicker-input"
                                                   data-target="#working_time_start" value="09:00 AM" />
                                            <div class="input-group-append" data-target="#working_time_start"
                                                 data-toggle="datetimepicker" data-start="09:00 AM">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                            <div class="text-danger">@error('working_time_start'){{ $message }}@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="working_time_end">Working Time End</label><br/>
                                        <div class="input-group date" id="working_time_end" data-target-input="nearest">
                                            <input type="text" name="working_time_end" id="working_time_end"
                                                   class="form-control datetimepicker-input"
                                                   value="06:00 PM"
                                                   data-target="#working_time_end"/>
                                            <div class="input-group-append" data-target="#working_time_end"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                            <div class="text-danger">@error('working_time_end'){{ $message }}@enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <label for="break_time_start">Break Time Start</label><br/>
                                        <div class="input-group date" id="break_time_start" data-target-input="nearest">
                                            <input type="text" name="break_time_start" id="break_time_start"
                                                   value="12:00 PM"
                                                   class="form-control datetimepicker-input"
                                                   data-target="#break_time_start"/>
                                            <div class="input-group-append" data-target="#break_time_start"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                            <div class="text-danger">@error('break_time_start'){{ $message }}@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="break_time_end">Break Time End</label><br/>
                                        <div class="input-group date" id="break_time_end" data-target-input="nearest">
                                            <input type="text" name="break_time_end" id="break_time_end"
                                                   value="01:00 PM"
                                                   class="form-control datetimepicker-input"
                                                   data-target="#break_time_end"/>
                                            <div class="input-group-append" data-target="#break_time_end"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                            <div class="text-danger">@error('break_time_end'){{ $message }}@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <label for="working_hours">Working Hour</label><br/>
                                        <input type="text" name="working_hours" class="form-control"
                                               id="working_hours"
                                               placeholder="Enter Working Hour"
                                               value="{{ old('working_hours',9)}}">
                                        <div class="text-danger">@error('working_hour'){{ $message }}@enderror</div>

                                    </div>
                                    <div class="col-sm-6">
                                        <label for="visa_number">Income Tax</label><br/>
                                        <input type="text" name="income_tax" class="form-control"
                                               id="visa_number" value="5" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-2 text-center">
                    <button type="submit" class="btn btn-default">Cancel</button>
                    <span class="mr-3"></span>
                    <button type="submit" class="btn btn-info">{{$title}}</button>
                </div>
            </div>
        </form>
    </div>
</section>
@stop

@section('extras')
<script>
    $(function() {
        //Date range picker
        $('#date_of_birth,#visa_expire_date').datetimepicker({
            format: 'Y/M/D'
        });

        $('#working_time_start,#working_time_end,#break_time_start,#break_time_end').datetimepicker({
            format: 'LT'
        });

        $("#firstname").on('keypress keydown keyup', function(){
            let rawFirstname = $(this).val();
            let prefix = $("#username_prefix").val();
            let firstname = rawFirstname.replace(/[^a-zA-Z0-9]/gm,'-').replace(/\-+/g, '-');
            $("#username").val(prefix + firstname);
        });
    });
</script>
@stop
