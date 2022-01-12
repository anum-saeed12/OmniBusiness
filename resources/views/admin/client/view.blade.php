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
                        <li class="breadcrumb-item">Client</li>
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
                            <div class="col-md-6 text-right pr-md-4">
                                <form method="Get" action="" style="display:inline-block;vertical-align:top;" class="mr-2">
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
{{--
                                <a href="{{ route('client.add.admin') }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i> Add New</a>
--}}
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
                                <tr>
                                    <th>Username</th>
                                    <th class="pl-0">Name</th>
                                    <th class="pl-0">Address</th>
                                    <th class="pl-0">Prefix</th>
                                    <th class="pl-0">Landline</th>
                                    <th class="pl-0">Email</th>
                                    <th class="pl-0">Payment Date</th>
                                    <th class="pl-0">Status</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @foreach($clients as $client)
                                    <tr class="{{--{!! empty($client->updated_by)?' bg-success':'' !!}{!! !empty($client->updated_by)&&$client->client->active==0?' bg-danger':'' !!}--}}">
                                        <td>{{ ucfirst($client->username) }}</td>
                                        <td>{{ ucfirst($client->client->name) }}</td>
                                        <td>{{ ucfirst($client->client->address_1) }} {{ $client->client->address_1 }}</td>
                                        <td>{{ $client->client->prefix }}</td>
                                        <td>{{ $client->client->landline }}</td>
                                        <td>{{ $client->client->official_email }}</td>
                                        <td>
                                            @if($client->client->subscription[count($client->client->subscription)-1]->next_payment_date)
                                                <span class="c-tt" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="Payment date: {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->client->subscription[count($client->client->subscription)-1]->next_payment_date)->toFormattedDateString() }}">
                                                    @if($client->client->subscription[count($client->client->subscription)-1]->next_payment_date >= \Carbon\Carbon::now())
                                                        Due In {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->client->subscription[count($client->client->subscription)-1]->next_payment_date)->diffInDays() }} days
                                                    @else
                                                        <span class="text-bold text-danger">
                                                            Due {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->client->subscription[count($client->client->subscription)-1]->next_payment_date)->diffInDays() }} days ago
                                                        </span>
                                                    @endif
                                                </span>
                                            @else
                                                Full paid
                                            @endif
                                        </td>
                                        <td>
                                            @if($client->client->active == 1)
                                                <span class="text-bold text-success"><i class="text-success"></i>Activated</span><br/>
                                            @endif
                                            @if($client->client->active == 0)
                                                <span class="text-bold {!! empty($client->client->updated_by)?'text-primary':'text-danger' !!}"><i class="text-danger"></i>
                                                    {!! empty($client->client->updated_by)?'In-active':'Deactivated' !!}
                                                </span><br/>
                                            @endif
                                        </td>
                                        <td class="text-right p-0">
                                            @if(!empty($client->client->subscription) && !empty($client->client->subscription[count($client->client->subscription)-1]->receipt))
                                                <a class="bg-warning list-btn" href="{{ url($client->client->subscription[count($client->client->subscription)-1]->receipt) }}" title="View submitted receipt" target="_blank"><i class="fas fa-file-pdf" aria-hidden="false"></i></a>
                                            @endif
                                            <a class="bg-primary list-btn" title="Edit"  href="{{ route('client.edit.admin', $client->client_id) }}"><i class="fas fa-tools" aria-hidden="false"></i></a>
                                            <a class="bg-danger list-btn" title="Delete" href="{{ route('client.delete.admin', $client->client_id) }}"><i class="fas fa-trash-alt" aria-hidden="false"></i></a>
                                            @if($client->client->active == 1)
                                                <a class="bg-danger list-btn"  href="{{ route('client.status.admin', [$client->client_id, 'deactivate']) }}"><i class="fas fa-user-alt" aria-hidden="false"></i></a>
                                            @endif
                                            @if($client->client->active == 0)
                                                @foreach($client->client->subscription as $sub)
                                                    @if(empty($sub->membership_start) || empty($sub->membership_end))
                                                        <div class="modal fade" id="clientActivateModal{{$client->client->id}}" tabindex="-1" role="dialog">
                                                            <div class="modal-dialog modal-sm">
                                                                <div class="modal-content">
                                                                    <div class="modal-body text-center" style="white-space:normal;">
                                                                        <h3>Activate client</h3>
                                                                        <p class="font-italic text-sm text-left">
                                                                            You are about to activate the client,
                                                                            please review the receipt submitted by
                                                                            the client before activating him to avoid
                                                                            any problems.
                                                                        </p>
                                                                        <form action="{{ route('client.activate.admin', $client->client_id) }}" method="post" class="text-center">
                                                                            @csrf
                                                                            <label for="amount" class="text-left d-block">Confirm the amount in the receipt</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="basic-addon1">Rs.</span>
                                                                                </div>
                                                                                <input type="text" class="form-control d-block" name="amount" id="amount" placeholder="Example: 1500" required/>
                                                                            </div>
                                                                            <button class="btn btn-success btn-block mt-3" type="submit">Approve &amp; activate</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a class="bg-success list-btn" href="#" data-toggle="modal" data-target="#clientActivateModal{{$client->client->id}}"><i class="fas fa-user-slash" aria-hidden="false"></i></a>
                                                            @php $normal_activation = false @endphp
                                                        @break
                                                    @else
                                                        @php $normal_activation = true @endphp
                                                    @endif
                                                @endforeach
                                                @if(isset($normal_activation)&&$normal_activation==true)<a class="bg-success list-btn"  href="{{ route('client.status.admin', [$client->client_id, 'activate']) }}"><i class="fas fa-user-slash" aria-hidden="false"></i></a>@endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        {!! $clients->appends($_GET)->links('pagination::bootstrap-4') !!}
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
            $('.c-tt').tooltip();
        });
    </script>
@stop
