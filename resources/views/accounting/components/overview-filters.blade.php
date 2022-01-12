<div class="omni-filters">
    <h2>Show data</h2>
    <form action="{{ route('purchase.overview.client') }}" method="get" id="_showAsForm">
        <input type="hidden" name="filters" value="go"/>
        <div class="row">
            <div class="col">
                <div class="form-check form-check-inline pt-2">
                    <input name="show_as" id="_purchase" value="purchase" class="mr-2 form-check-input purchase-filter-trigger" type="radio"{!! request('show_as')=='purchase'?' checked':'' !!}{!! request('show_as')?'':' checked' !!}>
                    <label class="form-check-label" for="_purchase">Date wise</label>
                </div>
            </div>
            <div class="col">
                <div class="form-check form-check-inline pt-2">
                    <input name="show_as" id="_employee" value="employee" class="mr-2 form-check-input purchase-filter-trigger" type="radio"{!! request('show_as')=='employee'?' checked':'' !!}>
                    <label class="form-check-label" for="_employee">Employee wise</label>
                </div>
            </div>
            <div class="col">
                <div class="form-check form-check-inline pt-2">
                    <input name="show_as" id="_product" value="product" class="mr-2 form-check-input purchase-filter-trigger" type="radio"{!! request('show_as')=='product'?' checked':'' !!}>
                    <label class="form-check-label" for="_product">Product wise</label>
                </div>
            </div>
            <div class="col">
                <div class="form-check form-check-inline pt-2">
                    <input name="show_as" id="_vendor" value="vendor" class="mr-2 form-check-input purchase-filter-trigger" type="radio"{!! request('show_as')=='vendor'?' checked':'' !!}>
                    <label class="form-check-label" for="_vendor">Vendor wise</label>
                </div>
            </div>
        </div>
    </form>
</div>

@section('filters.js')
<script type="text/javascript">
    $('.purchase-filter-trigger').change(function(){
        $('#_showAsForm').submit();
    });
</script>
@endsection
