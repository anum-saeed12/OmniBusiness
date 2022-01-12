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
        </div>
    </section>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if(session()->has('success'))
                        <div class="callout callout-success" style="color:green">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="callout callout-danger" style="color:red">
                            {{ session()->get('error') }}
                        </div>
                    @endif

                    <div class="card">
                        <div class="row mb-3 mt-3 ml-3">
                            <div class="col-md-6">
                                <form action="" method="GET" id="perPage">
                                    <label for="perPageCount">Show</label>
                                    <select id="perPageCount" name="count" onchange="$('#perPage').submit();"
                                            class="input-select mx-2">
                                        <option value="15"{{ request('count')=='15'?' selected':'' }}>15 rows</option>
                                        <option value="25"{{ request('count')=='25'?' selected':'' }}>25 rows</option>
                                        <option value="50"{{ request('count')=='50'?' selected':'' }}>50 rows</option>
                                        <option value="100"{{ request('count')=='100'?' selected':'' }}>100 rows</option>
                                    </select>
                                </form>
                            </div>
                            <div class="row offset-md-2 col-md-4">
                                <div class="col-sm-8">
                                    <form method="Get" action="">
                                        <div class="input-group">
                                            <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Search" class="form-control"
                                                   aria-label="Search">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-4">
                                    <a type="submit" class="btn btn-success" href="{{ route("employee.add.manager") }}"><i class="fa fa-plus-circle mr-2"></i> New</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th class="pl-0">Full Name</th>
                                    <th class="pl-0">Email</th>
                                    <th class="pl-0">Position</th>
                                    <th class="pl-0">Department</th>
                                    <th class="pl-0">Mobile No.</th>
                                    <th class="pl-0">Gender</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>
                                            <a href="{{ route("employee.view.manager", $employee->employee_id) }}">{{ $employee->username }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route("employee.view.manager", $employee->employee_id) }}">{{ ucfirst($employee->employee->firstname) }} {{ ucfirst($employee->employee->lastname) }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route("employee.view.manager", $employee->employee_id) }}">{{ $employee->email }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route("employee.view.manager", $employee->employee_id) }}">{{ ucwords($employee->employee->position->title) }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route("employee.view.manager", $employee->employee_id) }}">{{ ucwords($employee->employee->position->department->name) }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route("employee.view.manager", $employee->employee_id) }}">{{ $employee->employee->mobile_no }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route("employee.view.manager", $employee->employee_id) }}">{{ ucfirst($employee->employee->gender)}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        {!! $employees->appends($_GET)->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('extras')
    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@stop


