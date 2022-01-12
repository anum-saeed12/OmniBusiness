@extends('layouts.panel')

@section('breadcrumbs')
    <br/>
@stop

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ asset('dist/img/user9-512x512.png')}}"
                                 alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center">{{ $employee->data->firstname }} {{ $employee->data->lastname }}</h3>
                        <p class="text-muted text-center">{{ ucwords($employee->user_role) }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            @if($employee->data->products)
                                <li class="list-group-item">
                                    <b>Total Product</b> <a class="float-right">{{ $employees->data->products }} <small><a href="#" class="ml-3">View all</a></small></a>
                                </li>
                            @endif
                            @if($employee->sales->total)
                                <li class="list-group-item">
                                    <b>Total Sales</b> <p class="float-right m-0">{{ $employee->sales->total }} <small><a href="{{ route('sale.list.manager') }}?employee={{ $employee->data->id }}" class="ml-3">View all</a></small></p>
                                </li>
                            @endif
                                @if($employee->purchases->total)
                                    <li class="list-group-item">
                                        <b>Total Purchases</b> <p class="float-right m-0">{{ $employee->purchases->total }} <small><a href="{{ route('purchase.list.manager') }}?employee={{ $employee->data->id }}" class="ml-3">View all</a></small></p>
                                    </li>
                                @endif
                            @if(!empty($employee->invoices->total))
                                <li class="list-group-item">
                                    <b>Invoices</b> <p class="float-right m-0">{{ $employee->invoices->total }} <small><a href="{{ route('invoice.list.manager') }}?employee={{ $employee->data->id }}" class="ml-3">View all</a></small></p>
                                </li>
                            @endif
                            @if(!empty($employee->quotations->total))
                                <li class="list-group-item">
                                    <b>Quotations</b> <p class="float-right m-0">{{ $employee->quotations->total }} <small><a href="{{ route('quotation.list.manager') }}?employee={{ $employee->data->id }}" class="ml-3">View all</a></small></p>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About Me</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-at mr-1"></i> Personal Email</strong>

                        <p class="text-muted">
                            {{ $employee->data->personal_email }}
                        </p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                        <p class="text-muted">{{ $employee->data->location }}</p>

                        <hr>

                        <strong><i class="fas fa-mobile-alt mr-1"></i> Mobile Number</strong>

                        <p class="text-muted">{{ strtoupper(substr($employee->data->mobile_no,0,4))}}-{{ strtoupper(substr($employee->data->mobile_no,4,7))}}</p>

                        <hr>

                        <strong><i class="fas fa-pencil-alt"></i> Nic Number</strong>

                        <p class="text-muted">{{ strtoupper(substr($employee->data->nic_no,0,5))}}-{{ strtoupper(substr($employee->data->nic_no,5,7))}}-{{ strtoupper(substr($employee->data->nic_no,7,1))}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Today's Activity</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#profile" data-toggle="tab">Update Profile</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#sale" data-toggle="tab">Sale Overview</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#purchase" data-toggle="tab">Purchase Overview</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">
                                <div class="timeline timeline-inverse">
                                    <div>
                                        <i class="fas fa-dolly bg-info"></i>
                                        @if(count($sales))
                                            @foreach($sales as $sale)
                                                <div class="timeline-item mb-2">
                                                    <span class="time"><i class="far fa-clock"></i>   {{ $sale->created_at->diffForHumans(\Carbon\Carbon::now()) }}</span>
                                                    <h3 class="timeline-header"><b><a href="{{ route('sale.view.manager',$sale->id) }}">New Sale Order</a></b> generated</h3>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="timeline-item mb-2">
                                                <h3 class="timeline-header bg-warning"><b>No Sale Order Today </b></h3>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <i class="fas fa-truck-loading bg-info"></i>
                                        @if(count($purchases))
                                            @foreach($purchases as $purchase)
                                                <div class="timeline-item mb-2">
                                                    <span class="time"><i class="far fa-clock"></i>   {{ $purchase->created_at->diffForHumans(\Carbon\Carbon::now()) }}</span>
                                                    <h3 class="timeline-header"><b><a href="{{ route('purchase.view.manager',$purchase->id) }}">New Purchase Order</a></b> generated</h3>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="timeline-item mb-2">
                                                <h3 class="timeline-header bg-warning"><b>No Purchase Order Today </b></h3>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <i class="fas fa-file-invoice-dollar bg-info"></i>
                                        @if(count($quotations))
                                            @foreach($quotations as $quotation)
                                                <div class="timeline-item mb-2">
                                                    <span class="time"><i class="far fa-clock"></i>   {{ $quotation->created_at->diffForHumans(\Carbon\Carbon::now()) }}</span>
                                                    <h3 class="timeline-header"><b><a href="{{ route('quotation.view.manager',$quotation->id) }}">New Quotation </a></b> generated</h3>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="timeline-item mb-2">
                                                <h3 class="timeline-header bg-warning"><b>No Quotation Today </b></h3>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <i class="fas fa-receipt bg-info"></i>
                                        @if(count($invoices))
                                            @foreach($invoices as $invoice)
                                                <div class="timeline-item mb-2">
                                                    <span class="time"><i class="far fa-clock"></i>   {{ $invoice->created_at->diffForHumans(\Carbon\Carbon::now()) }}</span>
                                                    <h3 class="timeline-header"><b><a href="{{ route('invoice.view.manager',$invoice->id) }}">New Invoice</a></b> generated</h3>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="timeline-item mb-2">
                                                <h3 class="timeline-header bg-warning"><b>No Invoice Today </b></h3>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <i class="far fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="profile">
                                <form class="form-horizontal" action="{{ route('employee.update.manager',$employee->employee_id) }}" method="post">
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
                                                                   placeholder="First Name" value="{{ ucfirst($employee->employee->firstname)}}">
                                                            <div class="text-danger">@error('firstname'){{ $message }}@enderror</div>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <label for="lastname">Last Name</label><br/>
                                                            <input type="text" name="lastname" class="form-control" id="lastname"
                                                                   placeholder="Last Name" value="{{ ucfirst($employee->employee->lastname)}}">
                                                            <div class="text-danger">@error('lastname'){{ $message }}@enderror</div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col-sm-6">
                                                            <label for="gender">Gender</label><br/>
                                                            <select name="gender" class="form-control" id="gender">
                                                                <option value="male" {{ $employee->employee->gender == 'male' ? '
                                            selected="selected"' : '' }}>Male
                                                                </option>
                                                                <option value="female" {{ $employee->employee->gender == 'female' ? '
                                            selected="selected"' : '' }}>Female
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="nationality">Nationality</label><br/>
                                                            <select name="nationality" class="form-control" id="nationality">
                                                                <option value="pakistani" {{ $employee->employee->nationality == 'pakistani' ? '
                                            selected="selected"' : '' }}>Pakistan
                                                                </option>
                                                                <option value="british" {{ $employee->employee->nationality == 'british' ? '
                                            selected="selected"' : '' }}>British
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6">
                                                            <label for="birth_place">Birth Place</label><br/>
                                                            <select name="birth_place" class="form-control" id="birth_place">
                                                                <option value="pakistan">Pakistan</option>
                                                                <option value="uk">United Kingdom</option>
                                                                <option value="usa">United States</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <label for="birth_country">Birth Country</label><br/>
                                                            <select name="birth_country" class="form-control" id="birth_country">
                                                                <option value="pakistani">Pakistan</option>
                                                                <option value="british">British</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6">
                                                            <label for="martial_status">Martial Status</label><br/>
                                                            <select name="martial_status" class="form-control" id="martial_status">
                                                                <option value="single" {{ $employee->employee->martial_status == 'single' ? '
                                            selected="selected"' : '' }}>Single
                                                                </option>
                                                                <option value="married" {{ $employee->employee->martial_status == 'married' ? '
                                            selected="selected"' : '' }}>Married
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <label for="children">Children</label><br/>
                                                            <input type="text" name="children" class="form-control"
                                                                   id="children" value="{{ $employee->employee->children}}">
                                                            <div class="text-danger">@error('children'){{ $message }}@enderror</div>
                                                        </div>

                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="mobile_no">Mobile Number</label><br/>
                                                            <input type="text" name="mobile_no" class="form-control"
                                                                   id="mobile_no" value="{{ $employee->employee->mobile_no}}"
                                                                   placeholder="Example: 03211234567">
                                                            <div class="text-danger">@error('mobile_no'){{ $message }}@enderror</div>
                                                        </div>

                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="emergency_contact">Emergency Contact Number</label><br/>
                                                            <input type="text" name="emergency_contact"
                                                                   value="{{ $employee->employee->emergency_contact}}" class="form-control"
                                                                   id="emergency_contact" placeholder="Example: 03211234567">
                                                            <div class="text-danger">@error('emergency_contact'){{ $message }}@enderror
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="birth_date">Date of Birth</label><br/>
                                                            <div class="input-group date" id="birth_date" data-target-input="nearest">
                                                                <input type="text" id="date_of_birth" name="birth_date"
                                                                       class="form-control datetimepicker-input"
                                                                       data-target="#date_of_birth" placeholder="yyyy/mm/dd"
                                                                       value="{{ $employee->employee->birth_date}}"
                                                                       data-toggle="datetimepicker"/>
                                                                <div class="input-group-append" data-target="#date_of_birth"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                            <div class="text-danger">@error('birth_date'){{ $message }}@enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">

                                                        <div class="col-sm-12">
                                                            <label for="personal_email">Personal Email</label><br/>
                                                            <input type="email" name="personal_email" class="form-control"
                                                                   id="personal_email" value="{{ $employee->employee->personal_email}}"
                                                                   placeholder="Enter Personal Email">
                                                            <div class="text-danger">@error('personal_email'){{ $message }}@enderror
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">

                                                        <div class="col-sm-12">
                                                            <label for="bank_account_no">Account Number</label><br/>
                                                            <input type="text" name="bank_account_no"
                                                                   value="{{ $employee->employee->bank_account_no}}"
                                                                   class="form-control"
                                                                   id="bank_account_no" placeholder="Enter Account Number without -"
                                                            >
                                                            <div class="text-danger">@error('bank_account_no'){{ $message }}@enderror
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="nic_no">NIC Number</label><br/>
                                                            <input type="text" name="nic_no" value="{{ $employee->employee->nic_no}}"
                                                                   class="form-control"
                                                                   id="nic_no" placeholder="Enter Nic No. without -">
                                                            <div class="text-danger">@error('nic_no'){{ $message }}@enderror</div>

                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="location">Location</label><br/>
                                                            <input type="text" name="location" class="form-control" id="location"
                                                                   value="{{ ucfirst($employee->employee->location)}}"
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
                                                                   id="username" value="{{ $employee->username}}"
                                                                   placeholder="Enter User Name">
                                                            <div class="text-danger">@error('username'){{ $message }}@enderror</div>

                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="password">Password</label><br/>
                                                            <input type="password" name="password" class="form-control"
                                                                   id="password"
                                                                   placeholder="Enter Password">
                                                            <div class="text-danger">@error('password'){{ $message }}@enderror</div>

                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="email">Company Email</label><br/>
                                                            <input type="email" name="email" class="form-control"
                                                                   id="email" value="{{ $employee->email}}"
                                                                   placeholder="Enter Company Email">
                                                            <div class="text-danger">@error('email'){{ $message }}@enderror</div>

                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="user_role">User role</label><br/>
                                                            <select name="user_role" class="form-control" id="user_role">
                                                                <option value="employee" {{ $employee->user_role == 'employee' ? 'selected="selected"' : '' }}>Employee
                                                                </option>
                                                                <option value="accountant" {{ $employee->user_role == 'accountant' ? 'selected="selected"' : '' }}>Accountant
                                                                </option>
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
                                                                <option value="matriculation" {{ $employee->employee->education_level ==
                                            'matriculation' ? ' selected="selected"' : '' }}>Matriculation
                                                                </option>
                                                                <option value="intermediate" {{ $employee->employee->education_level ==
                                            'intermediate' ? ' selected="selected"' : '' }}>Intermediate
                                                                </option>
                                                                <option value="bachelor's" {{ $employee->employee->education_level ==
                                            'bachelor\'s' ? ' selected="selected"' : '' }}>Bachelor's
                                                                </option>
                                                                <option value="master's" {{ $employee->employee->education_level == 'master\'s' ?
                                            ' selected="selected"' : '' }}>Master's
                                                                </option>
                                                                <option value="doctorate" {{ $employee->employee->education_level == 'doctorate'
                                            ? ' selected="selected"' : '' }}>Doctorate
                                                                </option>
                                                                <option value="nothing" {{ $employee->employee->education_level == 'nothing' ? '
                                            selected="selected"' : '' }}>Nothing
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="education_field">Education Field</label><br/>
                                                            <input type="text" name="education_field" class="form-control" id="education_field"
                                                                   placeholder="Enter Education Field "
                                                                   value="{{ ucfirst($employee->employee->education_field)}}">
                                                            <div class="text-danger">@error('education_field'){{ $message }}@enderror
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col-sm-12">
                                                            <label for="educational_institute">Education Institute</label><br/>
                                                            <input type="text" name="educational_institute" class="form-control"
                                                                   id="educational_institute"
                                                                   value="{{ ucfirst($employee->employee->educational_institute)}}"
                                                                   placeholder="Enter Education Institute ">
                                                            <div class="text-danger">@error('educational_institute'){{ $message }}@enderror

                                                            </div>
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
                                                                @foreach ($positions as $position)
                                                                    <option value="{{ $position->id }}" {{ $position->id ==
                                            $employee->employee->position_id ? ' selected="selected" ' : '' }}> {{
                                            ucfirst( $position->title) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="salary">Salary</label><br/>
                                                            <input type="text" name="salary" class="form-control"
                                                                   id="salary" value="{{ $employee->employee->salary}}"
                                                                   placeholder="Enter Salary">
                                                            <div class="text-danger">@error('salary'){{ $message }}@enderror</div>
                                                        </div>

                                                    </div>
                                                    <div class="bootstrap-timepicker">
                                                        <div class="row mb-2">
                                                            <div class="col-sm-6">
                                                                <label for="working_time_start">Working Time Start</label><br/>
                                                                <div class="input-group time" id="working_time_start"
                                                                     data-target-input="nearest">
                                                                    <input type="text" name="working_time_start" id="working_time_start"
                                                                           class="form-control datetimepicker-input"
                                                                           data-target="#working_time_start"
                                                                           value="{{ $employee->employee->working_time_start }}"/>
                                                                    <div class="input-group-append" data-target="#working_time_start"
                                                                         data-toggle="datetimepicker" data-start="09:00 AM">
                                                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                                    </div>
                                                                    <div class="text-danger">@error('working_time_start'){{ $message
                                                }}@enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <label for="working_time_end">Working Time End</label><br/>
                                                                <div class="input-group date" id="working_time_end" data-target-input="nearest">
                                                                    <input type="text" name="working_time_end" id="working_time_end"
                                                                           class="form-control datetimepicker-input"
                                                                           value="{{ $employee->employee->working_time_end }}"
                                                                           data-target="#working_time_end"/>
                                                                    <div class="input-group-append" data-target="#working_time_end"
                                                                         data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                                    </div>
                                                                    <div class="text-danger">@error('working_time_end'){{ $message
                                                }}@enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-6">
                                                                <label for="break_time_start">Break Time Start</label><br/>
                                                                <div class="input-group date" id="break_time_start" data-target-input="nearest">
                                                                    <input type="text" name="break_time_start" id="break_time_start"
                                                                           value="{{ $employee->employee->break_time_start }}"
                                                                           class="form-control datetimepicker-input"
                                                                           data-target="#break_time_start"/>
                                                                    <div class="input-group-append" data-target="#break_time_start"
                                                                         data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                                    </div>
                                                                    <div class="text-danger">@error('break_time_start'){{ $message
                                                }}@enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <label for="break_time_end">Break Time End</label><br/>
                                                                <div class="input-group date" id="break_time_end" data-target-input="nearest">
                                                                    <input type="text" name="break_time_end" id="break_time_end"
                                                                           value="{{ $employee->employee->break_time_end }}"
                                                                           class="form-control datetimepicker-input"
                                                                           data-target="#break_time_end"/>
                                                                    <div class="input-group-append" data-target="#break_time_end"
                                                                         data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                                    </div>
                                                                    <div class="text-danger">@error('break_time_end'){{ $message
                                                }}@enderror
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
                                                                       value="{{ $employee->employee->working_hours}}">
                                                                <div class="text-danger">@error('working_hour'){{ $message }}@enderror
                                                                </div>

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
                                    <div class="form-group row">
                                        <div class="col-12 mb-2 text-center">
                                            <button type="submit" class="btn btn-default">Cancel</button>
                                            <span class="mr-3"></span>
                                            <button type="submit" class="btn btn-info">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="sale">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-tools">
                                            <ul class="nav nav-pills ml-auto">
                                                <li class="nav-item"><a class="nav-link active" href="#sales-today" data-toggle="tab">Today</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#sales-this-month" data-toggle="tab">This Month</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#sales-this-year" data-toggle="tab">This Year</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content p-0">
                                            <!-- Morris chart - Sales -->
                                            @if(isset($today_employee_sales))
                                                <div class="chart tab-pane active" id="sales-today">
                                                    @if(count($today_employee_sales) > 0)
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Time</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($today_employee_sales as $today_employee_sale)
                                                                            @php
                                                                                $today_employee_sale = isset($today_employee_sale)?$today_employee_sale:'';
                                                                                $todayTimes = isset($todayTimes)?$todayTimes."'".\Carbon\Carbon::parse($today_employee_sale->creation_date)->format('h:i a')."',":"'".\Carbon\Carbon::parse($today_employee_sale->creation_date)->format('h:i a')."',";
                                                                                $todayData = isset($todayData)?$todayData."{$today_employee_sale->total}," :"{$today_employee_sale->total}," ;
                                                                                $todayColors = isset($todayColors)?$todayColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisTodaysSale = isset($thisTodaysSale)?
                                                                                $thisTodaysSale+$today_employee_sale->total:
                                                                                $today_employee_sale->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{\Carbon\Carbon::parse($today_employee_sale->creation_date)->format('h:i a')}}</td>
                                                                                <td>{{ number_format($today_employee_sale->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h4>Total Amount = Rs.{!! number_format($thisTodaysSale) !!}</h4>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-md-4"  style="position:relative;min-height:200px;">
                                                                <canvas id="todaysSaleGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>\
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No sale for today
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(isset($month_employee_sales))
                                                <div class="chart tab-pane" id="sales-this-month">
                                                    @if(count($month_employee_sales) > 0)
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Date</th>
                                                                            <th>Sales</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($month_employee_sales as $month_employee_sale)
                                                                            @php
                                                                                $month_employee_sale = isset($month_employee_sale)?$month_employee_sale:'';
                                                                                $thisMonthsDates = isset($thisMonthsDates)?$thisMonthsDates."'{$month_employee_sale->creation_date}',":"'{$month_employee_sale->creation_date}',";
                                                                                $thisMonthsData = isset($thisMonthsData)?$thisMonthsData.$month_employee_sale->total.',':$month_employee_sale->total.',';
                                                                                $thisMonthsColors = isset($thisMonthsColors)?$thisMonthsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisMonthsSale = isset($thisMonthsSale)?
                                                                                $thisMonthsSale+$month_employee_sale->total:
                                                                                $month_employee_sale->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $month_employee_sale->creation_date }}</td>
                                                                                <td>{{ $month_employee_sale->counter }}</td>
                                                                                <td>{{ number_format($month_employee_sale->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h4>Total Amount = Rs.{!! number_format($thisMonthsSale) !!}</h4>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-12" style="position:relative;min-height:200px;">
                                                                <canvas id="thisMonthsSaleGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No sale for this month
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(isset($year_employee_sales))
                                                <div class="chart tab-pane" id="sales-this-year">
                                                    @if(count($year_employee_sales) > 0)
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Date</th>
                                                                            <th>Sales</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($year_employee_sales as $year_employee_sale)
                                                                            @php
                                                                                $year_employee_sale = isset($year_employee_sale)?$year_employee_sale:'';
                                                                                $thisYearsDates = isset($thisYearsDates)?$thisYearsDates."'{$year_employee_sale->creation_date}',":"'{$year_employee_sale->creation_date}',";
                                                                                $thisYearsData = isset($thisYearsData)?$thisYearsData.$year_employee_sale->total.',':$year_employee_sale->total.',';
                                                                                $thisYearsColors = isset($thisYearsColors)?$thisYearsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisYearsSale = isset($thisYearsSale)?
                                                                                $thisYearsSale+$year_employee_sale->total:
                                                                                $year_employee_sale->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $year_employee_sale->creation_date }}</td>
                                                                                <td>{{ $year_employee_sale->counter }}</td>
                                                                                <td>{{ number_format($year_employee_sale->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h4>Total Amount = Rs.{!! number_format($thisYearsSale) !!}</h4>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-12" style="position:relative;min-height:200px;">
                                                                <canvas id="thisYearSaleGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No sales for this year
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="purchase">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-tools">
                                            <ul class="nav nav-pills ml-auto">
                                                <li class="nav-item"><a class="nav-link active" href="#purchases-today" data-toggle="tab">Today</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#purchases-this-month" data-toggle="tab">This Month</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#purchases-this-year" data-toggle="tab">This Year</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content p-0">
                                            <!-- Morris chart - purchases -->
                                            @if(isset($today_purchases))
                                                <div class="chart tab-pane active" id="purchases-today">
                                                    @if(count($today_purchases) > 0)
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Time</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($today_purchases as $today_purchase)
                                                                            @php
                                                                                $today_purchase = isset($today_purchase)?$today_purchase:'';
                                                                                $todayTimes = isset($todayTimes)?$todayTimes."'".\Carbon\Carbon::parse($today_purchase->creation_date)->format('h:i a')."',":"'".\Carbon\Carbon::parse($today_purchase->creation_date)->format('h:i a')."',";
                                                                                $todayData = isset($todayData)?$todayData."{$today_purchase->total}," :"{$today_purchase->total}," ;
                                                                                $todayColors = isset($todayColors)?$todayColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisTodaysPurchase= isset($thisTodaysPurchase)?
                                                                                $thisTodaysPurchase+$today_purchase->total:
                                                                                $today_purchase->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{\Carbon\Carbon::parse($today_purchase->creation_date)->format('h:i a')}}</td>
                                                                                <td>{{ number_format($today_purchase->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h3>Total Amount = Rs.{!! number_format($thisTodaysPurchase) !!}</h3>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-12"  style="position:relative;min-height:200px;">
                                                                <canvas id="todaysGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No purchase for today
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(isset($month_purchases))
                                                <div class="chart tab-pane" id="purchases-this-month">
                                                    @if(count($month_purchases) > 0)
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Date</th>
                                                                            <th>Purchases</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($month_purchases as $month_purchase)
                                                                            @php
                                                                                $month_purchase = isset($month_purchase)?$month_purchase:'';
                                                                                $thisMonthsDates = isset($thisMonthsDates)?$thisMonthsDates."'{$month_purchase->creation_date}',":"'{$month_purchase->creation_date}',";
                                                                                $thisMonthsData = isset($thisMonthsData)?$thisMonthsData.$month_purchase->total.',':$month_purchase->total.',';
                                                                                $thisMonthsColors = isset($thisMonthsColors)?$thisMonthsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisMonthsPurchase= isset($thisMonthsPurchase)?
                                                                                $thisMonthsPurchase+$month_purchase->total:
                                                                                $month_purchase->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $month_purchase->creation_date }}</td>
                                                                                <td>{{ $month_purchase->counter }}</td>
                                                                                <td>{{ number_format($month_purchase->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h3>Total Amount = Rs.{!! number_format($thisMonthsPurchase) !!}</h3>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-12" style="position:relative;min-height:200px;">
                                                                <canvas id="thisMonthsGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>

                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No purchase for this month
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(isset($year_purchases))
                                                <div class="chart tab-pane" id="purchases-this-year">
                                                    @if(count($year_purchases) > 0)
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Date</th>
                                                                            <th>Purchases</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($year_purchases as $year_purchase)
                                                                            @php
                                                                                $year_purchase = isset($year_purchase)?$year_purchase:'';
                                                                                $thisYearsDates = isset($thisYearsDates)?$thisYearsDates."'{$year_purchase->creation_date}',":"'{$year_purchase->creation_date}',";
                                                                                $thisYearsData = isset($thisYearsData)?$thisYearsData.$year_purchase->total.',':$year_purchase->total.',';
                                                                                $thisYearsColors = isset($thisYearsColors)?$thisYearsColors."'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                                                                                $thisYearsPurchase= isset($thisYearsPurchase)?
                                                                                $thisYearsPurchase+$year_purchase->total:
                                                                                $year_purchase->total;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $year_purchase->creation_date }}</td>
                                                                                <td>{{ $year_purchase->counter }}</td>
                                                                                <td>{{ number_format($year_purchase->total) }} {{ $currency }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <h3>Total Amount = Rs.{!! number_format($thisYearsPurchase) !!}</h3>
                                                                </div>
                                                            </div>  </div>
                                                            <div class="col-12" style="position:relative;min-height:200px;">
                                                                <canvas id="thisYearsGraph" height="100%" style="height: 100%;"></canvas>
                                                            </div>
                                                    @else
                                                        <div class="alert alert-light pt-5 pb-5 mt-4 mb-4 text-center text-muted">
                                                            No purchases for this year
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
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
    <script>
        $(function () {
            $('body').on('click', '[data-toggle="modal"]', function () {
                window.location.href = $(this).data('href');
            });
            @if(isset($today_purchases) && count($today_purchases)>0)
            // Donut Chart
            let todaysPurchaseGraph = $('#todaysGraph').get(0).getContext('2d'),
                todayPurchaseData = {
                    labels: [{!! $todayTimes !!}],
                    datasets: [{
                        data: [{!! $todayData !!}],
                        backgroundColor : [{!! $todayColors !!}],
                    }]
                };
            let todaysPurchasePieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let todaysPurchasePieChart = new Chart(todaysPurchaseGraph,{type: 'pie',data: todayPurchaseData,options: todaysPurchasePieOptions});
            @endif

            @if(isset($month_purchases) && count($month_purchases)>0)
            // Donut Chart
            let thisMonthsPurchaseGraph = $('#thisMonthsGraph').get(0).getContext('2d'),
                thisMonthsPurchaseData = {
                    labels: [{!! $thisMonthsDates !!}],
                    datasets: [{
                        data: [{!! $thisMonthsData !!}],
                        backgroundColor : [{!! $thisMonthsColors !!}],
                    }]
                };
            let thisMonthsPieOptions = {legend: {display: true,position: 'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let thisMonthsPieChart = new Chart(thisMonthsPurchaseGraph,{type: 'doughnut',data: thisMonthsPurchaseData,options: thisMonthsPieOptions});
            @endif

            @if(isset($year_purchases) && count($year_purchases)>0)
            let yearGraphType = 'bar';
            // Donut Chart
            let thisYearsGraph = $('#thisYearsGraph').get(0).getContext('2d'),
                thisYearsData = {
                    labels: [{!! $thisYearsDates !!}],
                    datasets: [{
                        data: [{!! $thisYearsData !!}],
                        backgroundColor : [{!! $thisYearsColors !!}],
                    }]
                };
            if (yearGraphType === 'pie' || yearGraphType === 'doughnut') {
                let thisYearsPieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
                // You can switch between pie and doughnut using the method below.
                let thisYearsPieChart = new Chart(thisYearsGraph,{type: 'doughnut',data: thisYearsData,options: thisYearsPieOptions});
            } else if (yearGraphType === 'bar') {
                let thisYearsBarOptions = {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                };
                // You can switch between pie and doughnut using the method below.
                let thisYearsBarChart = new Chart(thisYearsGraph, {
                    type: 'line',
                    data: {
                        labels: [{!! $thisYearsDates !!}],
                        datasets: [{
                            label: 'Monthly data',
                            data: [{!! $thisYearsData !!}],
                            borderColor: 'rgb(111,193,97)',
                            backgroundColor: 'rgba(111,193,97,0.8)',
                            stack: 'combined',
                            type: 'bar'
                        }]
                    },
                    options: thisYearsBarOptions,
                    scales: {
                        y: {
                            stacked: true
                        }
                    }
                });
            }
            @endif
        });
    </script>
    <script>
        $(function () {
            $('body').on('click', '[data-toggle="modal"]', function () {
                window.location.href = $(this).data('href');
            });
            @if(isset($today_employee_sales) && count($today_employee_sales)>0)
            // Donut Chart
            let todaysSaleGraph = $('#todaysSaleGraph').get(0).getContext('2d'),
                todaySaleData = {
                    labels: [{!! $todayTimes !!}],
                    datasets: [{
                        data: [{!! $todayData !!}],
                        backgroundColor : [{!! $todayColors !!}],
                    }]
                };
            let todaysSalePieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let todaysSalePieChart = new Chart(todaysSaleGraph,{type: 'pie',data: todaySaleData,options: todaysSalePieOptions});
            @endif

            @if(isset($month_employee_sales) && count($month_employee_sales)>0)
            // Donut Chart
            let thisMonthsSaleGraph = $('#thisMonthsSaleGraph').get(0).getContext('2d'),
                thisMonthsSaleData = {
                    labels: [{!! $thisMonthsDates !!}],
                    datasets: [{
                        data: [{!! $thisMonthsData !!}],
                        backgroundColor : [{!! $thisMonthsColors !!}],
                    }]
                };
            let thisMonthsSalePieOptions = {legend: {display: true,position: 'bottom'},maintainAspectRatio: false,responsive: true};
            // You can switch between pie and doughnut using the method below.
            let thisMonthsSalePieChart = new Chart(thisMonthsSaleGraph,{type: 'doughnut',data: thisMonthsSaleData,options: thisMonthsSalePieOptions});
            @endif

            @if(isset($year_employee_sales) && count($year_employee_sales)>0)
            let yearSaleGraphType = 'bar';
            // Donut Chart
            let thisYearSaleGraph = $('#thisYearSaleGraph').get(0).getContext('2d'),
                thisYearSaleData = {
                    labels: [{!! $thisYearsDates !!}],
                    datasets: [{
                        data: [{!! $thisYearsData !!}],
                        backgroundColor : [{!! $thisYearsColors !!}],
                    }]
                };
            if (yearSaleGraphType === 'pie' || yearSaleGraphType === 'doughnut') {
                let thisYearSalePieOptions = {legend: {display: true,position:'bottom'},maintainAspectRatio: false,responsive: true};
                // You can switch between pie and doughnut using the method below.
                let thisYearsPieChart = new Chart(thisYearSaleGraph,{type: 'doughnut',data: thisYearSaleData,options: thisYearSalePieOptions});
            } else if (yearSaleGraphType === 'bar') {
                let thisYearSaleBarOptions = {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                };
                // You can switch between pie and doughnut using the method below.
                let thisYearSaleBarChart = new Chart(thisYearSaleGraph, {
                    type: 'line',
                    data: {
                        labels: [{!! $thisYearsDates !!}],
                        datasets: [{
                            label: 'Monthly data',
                            data: [{!! $thisYearsData !!}],
                            borderColor: 'rgb(111,193,97)',
                            backgroundColor: 'rgba(111,193,97,0.8)',
                            stack: 'combined',
                            type: 'bar'
                        }]
                    },
                    options: thisYearSaleBarOptions,
                    scales: {
                        y: {
                            stacked: true
                        }
                    }
                });
            }
            @endif
        });
    </script>
@stop
