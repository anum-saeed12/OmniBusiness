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
                        <li class="breadcrumb-item">Department</li>
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
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="POST" action="{{ route('department.store.admin') }}">
                    @csrf
                    <div class="row">
                        <div class="col-8">
                            <input type="text" name="name" class="form-control" id="name" placeholder="New Department Name">
                        </div>
                        <button type="submit" class="btn btn-success toastrDefaultSuccess"><i
                                class="fa fa-plus-circle mr-2"></i> Create
                        </button>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="col-12 text-danger">@error('name'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
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
                        <div class="row offset-md-3 col-md-3">
                            <form method="Get" action="">
                                <div class="input-group">
                                    <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Search" class="form-control"
                                           aria-label="Search">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit"><i
                                                class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-compact">
                            <thead>
                            <tr>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($departments as $department)
                            <tr>
                                <td>{{ ucfirst($department->name) }}</td>
                                <td class="text-right p-0">
                                    <a class="bg-primary list-btn" title="Edit" href="{{ route('department.edit.admin', $department->id) }}"><i class="fas fa-tools" aria-hidden="false"></i></a>
                                    <a class="bg-danger list-btn" title="Delete" href="{{ route('department.delete.admin', $department->id) }}"><i class="fas fa-trash-alt" aria-hidden="false"></i></a>

                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse">
                    {!! $departments->appends($_GET)->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('extras')
    @if(session()->has('success'))
    <script>
        $(function(){
            toastr.success("{{ session()->get('success') }}");
        });
    </script>
    @endif

    @if(session()->has('error'))
    <script>
        $(function(){
            toastr.error("{{ session()->get('error') }}");
        });
    </script>
    @endif
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

