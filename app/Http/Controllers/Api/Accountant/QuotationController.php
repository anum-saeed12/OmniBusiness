<?php

namespace App\Http\Controllers\Api\Accountant;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

#models
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Product;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client_id = Auth::user()->client_id;
        $quotations = $this->all
            ?
            Quotation::where('client_id', $client_id)->orderBy('id', 'DESC')->with('items')->get()
            :
            Quotation::where('client_id', $client_id)->where('employee_id', null)->orderBy('id', 'DESC')->with('items')->get();

        $quotations->count() || $this->error('No quotation available');

        return response($quotations, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [

            'quotation_supplier' => 'required',
            'total_amount'       => 'required|numeric',
            'quotation_type'     => 'required|in:rcvd,sent',
            'product_id'         => 'required|exists:App\Models\Product,id',
            'quantity'           => 'required|numeric',
            'unit_price'         => 'required|numeric'
        ]);

        $data                 = $request->all();
        $data['client_id']    = Auth::user()->client_id;
        $data['employee_id']  = Auth::user()->employee_id;
        $data['gst']          = fetchSetting('gst'); // Fetch gst from settings table
        $quotation            = new Quotation($data);
        $quotation->save() || $this->error("Quotation is not inserted!");

        $data                   = $request->all();
        $data['quotation_id']   = $quotation->id;
        $item                   = new QuotationItem($data);
        $item->save() || $this->error("Quotation Item is not inserted!");

        $response = [
            'message'   => 'Quotation added successfully',
            'quotation' => $quotation,
            'item'      => $item
        ];
        return response($response, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quotations = Quotation::find($id)->where('client_id', Auth::user()->client_id)->get();
        $quotations->count() || $this->error('No quotation available');

        $item = QuotationItem::where('quotation_id',$id)->get();
        $item->count() || $this->error('No quotation available');

        $this->validate($request, [

            'quotation_supplier' => 'sometimes|required',
            'total_amount'       => 'sometimes|required|numeric',
            'quotation_type'     => 'sometimes|required|in:rcvd,sent',
            'product_id'         => 'sometimes|required|exists:App\Models\Product,id',
            'quantity'           => 'sometimes|required|numeric',
            'unit_price'         => 'sometimes|required|numeric'
        ]);

        $request->input('quotation_supplier')  &&  $quotations->quotation_supplier   =  $request->input('quotation_supplier');
        $request->input('total_amount')        &&  $quotations->total_amount         =  $request->input('total_amount');
        $request->input('quotation_type')      &&  $quotations->quotation_type       =  $request->input('quotation_type');
        $quotations->updated_by = Auth::user()->employee_id;

        $quotations->save()|| $this->error('Quotation is not updated!');

        $request->input('product_id')          &&  $item->product_id                 =  $request->input('product_id');
        $request->input('quantity')            &&  $item->quantity                   =  $request->input('quantity');
        $request->input('unit_price')          &&  $item->unit_price                 =  $request->input('unit_price');

        $item->save()|| $this->error('Quotation is not updated!');

        $response=[
            'message'    =>'Quotation updated successfully',
            'quotation'  => $quotations,
            'item'       => $item
        ];
        return response($response,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $quotations = Quotation::find($id);
        $quotations || $this->error('No quotation available');
        $items = QuotationItem::where('quotation_id',$id)->first();
        $items->count()|| $this->error('No quotation available');
        $items->delete();
        $quotations->delete();
        return response(['message','Quotation deleted successfully'],200);
    }
    public function reject_at($id)
    {
        $quotations = Quotation::find($id);
        $quotations || $this->error('No quotation available');

        if($quotations->rejected_at != null)
        {
            return response(['message','Quotation already rejected'],200);
        }

        $quotations->updated_by=Auth::user()->employee_id;
        $quotations->rejected_at=Carbon::now();
        $quotations->save() || $this->error('Error in rejecting');

        return response(['message','Quotation Rejected successfully'],200);
    }

    public function accept_at($id)
    {
        $quotations = Quotation::find($id);
        $quotations->count() || $this->error('No quotation available');

        if($quotations->accepted_at != null)
        {
            return response(['message','Quotation already accepted'],200);
        }
        $quotations->updated_by = Auth::user()->employee_id;
        $quotations->accepted_at=Carbon::now();
        $quotations->save() || $this->error('Error in accepting');

        return response($quotations,200);
    }
}
