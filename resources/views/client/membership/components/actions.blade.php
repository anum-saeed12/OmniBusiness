<div class="omni-filters">
    <h2 class="mb-2 text-warning">Payment done? Upload and submit the receipt</h2>
    <form action="{{ route('subscription.save.client') }}" method="post">
        @csrf
        <input type="hidden" name="type_of_subscription" value="{{ $subscription[0]->type_of_subscription }}"/>
        <div class="row pt-2">
            <div class="col-md-3 text-center text-md-left">
                <input name="receipt" type="file"
                   class="form-control-file"
                   required="required">
            </div>
            <div class="col-md-3 text-center text-md-left"></div>
            <div class="col-md-3 text-center text-md-left">
                <button type="submit" class="btn btn-info btn-block btn-sm">Upload</button>
            </div>
            <div class="col-md-3 text-center text-md-right">
            {{--<div class="col">--}}
                {{--<button type="submit" class="btn btn-primary btn-sm mr-2">Submit Receipt</button>--}}
                <a class="btn btn-link btn-block border-primary btn-sm mr-2" href="#" title="View submitted receipt" target="_blank"><i class="fas fa-file-pdf mr-1" aria-hidden="false"></i> View Old receipt</a>
            </div>
        </div>
    </form>
</div>
