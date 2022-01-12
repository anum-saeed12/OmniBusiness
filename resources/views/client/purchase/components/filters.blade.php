<div class="omni-filters">
    <h2>Filters</h2>
    <form action="{{ route('purchase.list.'.auth()->user()->user_role) }}" method="get">
        <input type="hidden" name="filters" value="go"/>
        <div class="row">
            <div class="col-lg-3 col-md-3 col">
                <label for="date_range" class="normal">Dates</label>
                <div class="input-group input-group-sm date" data-target-input="nearest">
                    <input type="text" id="date_range" name="dates"
                           class="form-control datetimepicker-input" placeholder="From date" autocomplete="off" aria-autocomplete="off" value="{{ request('dates') }}"/>
                    <div class="input-group-append" data-target="#date_range"
                         data-toggle="daterangepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <label for="amount_min" class="normal">Amount Min</label>
                <div class="input-group input-group-sm">
                    <input type="number" id="amount_min" min="0" name="amount_min" class="form-control" placeholder="purchase min amount" autocomplete="off" aria-autocomplete="off"  value="{{ request('amount_min') }}"/>
                    <div class="input-group-append">
                        <div class="input-group-text">{{ isset($currency)?$currency:'PKR' }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <label for="amount_max" class="normal">Amount Max</label>
                <div class="input-group input-group-sm">
                    <input type="number" id="amount_max" min="0" name="amount_max" class="form-control" placeholder="purchase max amount" autocomplete="off" aria-autocomplete="off" value="{{ request('amount_max') }}"/>
                    <div class="input-group-append">
                        <div class="input-group-text">{{ isset($currency)?$currency:'PKR' }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <label for="find" class="normal">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" id="find" name="find" class="form-control" placeholder="Company name" autocomplete="off" aria-autocomplete="off" value="{{ request('find') }}"/>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <label class="normal">&nbsp;</label>
                <div class="input-group input-group-sm">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">Apply filters</button>
                </div>
            </div>
            <div class="col">
                <label class="normal">&nbsp;</label>
                <div class="input-group input-group-sm">
                    <a href="{{ route('purchase.overview.client') }}" class="btn btn-default btn-sm btn-block">Clear filters</a>
                </div>
            </div>
        </div>
    </form>
</div>
