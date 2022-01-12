@if(!empty($client->client->subscription))
    <div class="omni-filters">
        <h2 class="mb-2">Actions <i class="fa fa-cog"></i></h2>
        <form action="" method="post">
            <div class="row pt-2">
                <div class="col-md-6 text-center text-md-left">
                    {{--<input name="receipt" type="file"
                       class="form-control-file"
                       required="required">--}}
                    <span class="text-sm">
                        {!!
                            !empty($client->client->subscription[count($client->client->subscription)-1]->next_payment_date)
                            ?
                            '<span class="c-tt" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="Payment date: '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->client->subscription[count($client->client->subscription)-1]->next_payment_date)->toFormattedDateString().'"><i class="fa fa-file-invoice-dollar"></i> Due In '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->client->subscription[count($client->client->subscription)-1]->next_payment_date)->diffInDays().' days</span>'
                            :
                            "Full paid"
                        !!}
                    </span>
                    <span class="text-sm mr-2 ml-2" style="color:#ccc;">|</span>
                    <span class="text-sm">
                        Membership validity:
                        @if(!empty($client->client->subscription[count($client->client->subscription)-1]->membership_start))
                            <b>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $client->client->subscription[count($client->client->subscription)-1]->membership_start)->format('d-M-Y') }}</b>
                            to
                            <b>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $client->client->subscription[count($client->client->subscription)-1]->membership_end)->format('d-M-Y') }}</b>
                        @else
                            <b>Pending activation</b>
                        @endif
                    </span>
                </div>
                <div class="col-md-6 text-center text-md-right">
                {{--<div class="col">--}}
                    {{--<button type="submit" class="btn btn-primary btn-sm mr-2">Submit Receipt</button>--}}
                    <a class="btn btn-link border-primary btn-sm mr-2" href="{{ url($client->client->subscription[count($client->client->subscription)-1]->receipt) }}" title="View submitted receipt" target="_blank"><i class="fas fa-file-pdf mr-1" aria-hidden="false"></i> View Old receipt</a>
                    @if($client->client->active == 1)
                        <a class="btn btn-danger btn-sm c-tt" data-toggle="tooltip" data-placement="top" title="Client is currently enabled" href="{{ route('client.status.admin', [$client->client_id, 'deactivate']) }}"><i class="fas fa-user-slash" aria-hidden="false"></i> Disable client</a>
                    @endif
                    @if($client->client->active == 0)
                        {{--<a class="btn btn-success btn-sm c-tt" data-toggle="tooltip" data-placement="top" title="Client is currently disabled" href="{{ route('client.status.admin', [$client->client_id, 'activate']) }}"><i class="fas fa-user-alt" aria-hidden="false"></i> Enable client</a>--}}
                        @foreach($client->client->subscription as $sub)
                            @if($sub->approved!=2) @continue @endif
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
                                                    <label for="amount{{$client->client->id}}" class="text-left d-block">Confirm the amount in the receipt</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">Rs.</span>
                                                        </div>
                                                        <input type="text" class="form-control d-block" name="amount" id="amount{{$client->client->id}}" placeholder="Example: 1500" required/>
                                                    </div>
                                                    <button class="btn btn-success btn-block mt-3" type="submit">Confirm &amp; activate</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="clientRequestDisapproveModal{{$client->client->id}}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-body text-center" style="white-space:normal;">
                                                <h3>Disapprove client</h3>
                                                <p class="font-italic text-sm text-left">
                                                    You are about to disapprove the client,
                                                    please review the receipt submitted by
                                                    the client before disapproving his request
                                                    to avoid any problems.
                                                </p>
                                                <form action="{{ route('client.disapprove.admin', $client->client_id) }}" method="post" class="text-center">
                                                    @csrf
                                                    <label for="description" class="text-left d-block">Reason for disapproval</label>
                                                    <textarea name="description" id="description" class="form-control" cols="30" rows="10" placeholder="Example: The receipt was invalid."></textarea>
                                                    <button class="btn btn-warning btn-block mt-3" type="submit">Submit & Disapprove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="btn btn-success btn-sm" href="#" data-toggle="modal" data-target="#clientActivateModal{{$client->client->id}}"><i class="fas fa-user-alt" aria-hidden="false"></i> Approve &amp; Activate Client</a>
                                <a class="btn btn-danger btn-sm ml-2" href="#" data-toggle="modal" data-target="#clientRequestDisapproveModal{{$client->client->id}}"><i class="fas fa-user-slash" aria-hidden="false"></i> Disapprove Payment</a>
                                {{--<a class="btn btn-success btn-sm c-tt" data-toggle="tooltip" data-placement="top" title="Client is currently disabled" href="{{ route('client.status.admin', [$client->client_id, 'activate']) }}"><i class="fas fa-user-alt" aria-hidden="false"></i> Enable client</a>--}}
                                @php $normal_activation = false @endphp
                                @break
                            @else
                                @php $normal_activation = true @endphp
                            @endif
                        @endforeach
                        @if(isset($normal_activation)&&$normal_activation==true)<a class="btn btn-success btn-sm c-tt" data-toggle="tooltip" data-placement="top" title="Client is currently disabled" href="{{ route('client.status.admin', [$client->client_id, 'activate']) }}"><i class="fas fa-user-alt" aria-hidden="false"></i> Enable client</a>@endif
                    @endif
                </div>
            </div>
        </form>
    </div>
@endif
