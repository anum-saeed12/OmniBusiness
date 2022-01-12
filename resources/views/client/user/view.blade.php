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
            <div class="col-10 offset-1">
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
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User role</th>
                                <th width="30"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $item)
                            <tr>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->user_role}}</td>
                                @if($item->user_role == 'client')
                                <td class="text-right">
                                    <a class="btn btn-info btn-sm disabled"
                                       href="{{ route('user.edit.client', $item->id) }}" >
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm disabled" href="{{ route('user.delete.client', $item->id) }}" disabled="false">
                                        <i class="fas fa-trash">
                                        </i>
                                        Delete
                                    </a>
                                </td>
                                @else
                                <td class="text-right">
                                    <a class="btn btn-info btn-sm"
                                       href="{{ route('user.edit.client', $item->id) }}" >
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="{{ route('user.delete.client', $item->id) }}" >
                                        <i class="fas fa-trash">
                                        </i>
                                        Delete
                                    </a>
                                </td>
                                @endif

                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="d-flex flex-row-reverse">
                    {!! $users->appends($_GET)->links('pagination::bootstrap-4') !!}
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!--/.col -->
    </div>
    <!-- /.container-fluid -->
</section>
@stop

