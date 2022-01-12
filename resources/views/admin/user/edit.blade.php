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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Home</a></li>
                        <li class="breadcrumb-item">User</li>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <form class="form-horizontal" action="{{ route('user.update.admin' , $users[0]->id) }}" method="POST">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="email">Email</label><br/>
                                    <input type="email" name="email" class="form-control" id="email"
                                           placeholder="Enter Email" value="{{ $users[0]->email }}">
                                    <div class="text-danger">@error('email'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="username">Username</label><br/>
                                    <input type="text" name="username" class="form-control" id="username"
                                           placeholder="Enter Username" value="{{ $users[0]->username }}">
                                    <div class="text-danger">@error('username'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="password">Password</label><br/>
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="Enter Password">
                                    <div class="text-danger">@error('password'){{ $message }}@enderror</div>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="user_role">Privilege Level</label><br/>
                                    <select name="user_role" class="form-control" id="user_role">
                                        <option value="admin" {{ $users[0]->user_role == 'admin' ? ' selected="selected"' :
                                            '' }}>Admin
                                        </option>
                                        <option value="client" {{ $users[0]->user_role == 'client' ? ' selected="selected"'
                                            : '' }}>Client
                                        </option>
                                        <option value="manager" {{ $users[0]->user_role == 'manager' ? '
                                            selected="selected"' : '' }}>Manager
                                        </option>
                                        <option value="accountant" {{ $users[0]->user_role == 'accountant' ? '
                                            selected="selected"' : '' }}>Accountant
                                        </option>
                                        <option value="employee" {{ $users[0]->user_role == 'employee' ? '
                                            selected="selected"' : '' }}>Employee
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3 text-center">
                                <button type="submit" class="btn btn-default">Cancel</button>
                                <span class="mr-3"></span>
                                <button type="submit" class="btn btn-info">{{$title}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
