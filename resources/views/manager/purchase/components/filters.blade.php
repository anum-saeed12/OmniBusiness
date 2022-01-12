<div class="omni-filters">
    <h2>Filters</h2>
    <form action="" method="get">
        <input type="hidden" name="filters" value="go"/>
        <div class="row">
            <div class="col">
                <label for="from_date" class="normal">Date from</label>
                <div class="input-group input-group-sm date" data-target-input="nearest">
                    <input type="text" id="from_date" name="from_date"
                           class="form-control datetimepicker-input"
                           data-target="#from_date" placeholder="From date"
                           data-toggle="datetimepicker" autocomplete="off" aria-autocomplete="off"/>
                    <div class="input-group-append" data-target="#from_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <label for="end_date" class="normal">Date to</label>
                <div class="input-group input-group-sm date" data-target-input="nearest">
                    <input type="text" id="end_date" name="end_date"
                           class="form-control datetimepicker-input"
                           data-target="#end_date" placeholder="To date"
                           data-toggle="datetimepicker" autocomplete="off" aria-autocomplete="off"/>
                    <div class="input-group-append" data-target="#end_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <label for="amount_min" class="normal">Amount Min</label>
                <div class="input-group input-group-sm">
                    <input type="number" id="amount_min" name="amount_min" class="form-control" placeholder="Sale min amount" autocomplete="off" aria-autocomplete="off"/>
                    <div class="input-group-append">
                        <div class="input-group-text">{{ isset($currency)?$currency:'PKR' }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <label for="amount_max" class="normal">Amount Max</label>
                <div class="input-group input-group-sm">
                    <input type="number" id="amount_max" name="amount_max" class="form-control" placeholder="Sale max amount" autocomplete="off" aria-autocomplete="off"/>
                    <div class="input-group-append">
                        <div class="input-group-text">{{ isset($currency)?$currency:'PKR' }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <label for="find" class="normal">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" id="amount_max" name="amount_max" class="form-control" placeholder="Sale max amount" autocomplete="off" aria-autocomplete="off"/>
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
                    <button type="reset" class="btn btn-default btn-sm btn-block">Clear filters</button>
                </div>
            </div>
        </div>
    </form>
</div>
