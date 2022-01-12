<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice=Invoice::all()->where('client_id',Auth::user()->client_id);
        $invoice->count() || $this->error('No Invoice found!!');

        return response($invoice,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_supplier_and_buyer' => 'required',
            'total_amount'               => 'required|numeric'
            ]);

        $data                 =    $request->all();
        $data['client_id']    = Auth::user()->client_id;
        $invoice              = new Invoice($data);
        $invoice->save() || $this->error("Invoice is not inserted!");

        $response = [
            'message'  => 'Invoice added successfully',
            'invoice'  => $invoice
        ];
        return response($response,201);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice=Invoice::find($id);
        $invoice|| $this->error('Invoice not found');

        $this->validate($request, [
            'product_supplier_and_buyer' => 'sometimes|required',
            'total_amount'               => 'sometimes|required|numeric'
        ]);

        $request->input('product_supplier_and_buyer')   &&  $invoice->product_supplier_and_buyer    = $request->input('product_supplier_and_buyer');
        $request->input('total_amount')                 &&  $invoice->total_amount                  = $request->input('total_amount');

        $invoice->updated_by = Auth::user()->client_id;
        $invoice->save() || $this->error("Invoice is not updated!");

        $response=[
            'message'  => 'Invoice updated successfully',
            'invoice'  => $invoice

        ];
        return response($response,200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $invoice=Invoice::find($id);
        $invoice || $this->error('Invoice not found');
        $invoice->delete();
        return response(['message','Invoice deleted successfully'],200);
    }
}
