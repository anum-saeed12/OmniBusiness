<div class="omni-filters">
    <h2>Filters</h2>
    <form action="{{ isset($action)?route($action):route('ledger.index.' . auth()->user()->user_role) }}" method="get">
        <input type="hidden" name="filters" value="go"/>
        @if(request()->has('count'))<input type="hidden" name="count" value="{{ request('count') }}">@endif
        <div class="row">
            <div class="col">
                <label for="start" class="normal">Starting</label>
                <div class="input-group input-group-sm date" data-target-input="nearest">
                    <input type="text" id="start" name="start"
                           class="form-control datetimepicker-input date" placeholder="Beginning"
                           autocomplete="off" aria-autocomplete="off" value="{{ request('start',isset($start)?$start:'') }}"/>
                    <div class="input-group-append" data-target="#start"
                         data-toggle="daterangepicker" onclick="$('#start').focus()">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <label for="end" class="normal">Ending</label>
                <div class="input-group input-group-sm date" data-target-input="nearest">
                    <input type="text" id="end" name="end"
                           class="form-control datetimepicker-input date" placeholder="Today"
                           autocomplete="off" aria-autocomplete="off" value="{{ request('end') }}"/>
                    <div class="input-group-append" data-target="#end"
                         data-toggle="daterangepicker" onclick="$('#end').focus()">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            @if(!isset($exclude)||!in_array('account-type',$exclude))
                <div class="col">
                    <label for="account" class="normal">Account Type:</label>
                    <div class="input-group input-group-sm">
                        <select id="account" class="form-control form-control-sm mb-3" style="width:auto;" name="account">
                            <option value="">All accounts</option>
                            @foreach($account_types as $account)
                                <option value="{{ $account->name }}"{{ request('account')==$account->name?' selected':'' }}>{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-sm-2">
                <label class="normal">&nbsp;</label>
                <div class="input-group input-group-sm">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">Apply filters</button>
                </div>
            </div>
            <div class="col-sm-2">
                <label class="normal">&nbsp;</label>
                <div class="input-group input-group-sm">
                    <a href="{{ isset($action)?route($action):route('ledger.index.' . auth()->user()->user_role) }}" class="btn btn-default btn-sm btn-block">Clear filters</a>
                </div>
            </div>
        </div>
    </form>
</div>
