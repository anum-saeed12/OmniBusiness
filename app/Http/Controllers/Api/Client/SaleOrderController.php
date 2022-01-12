<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleOrderline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class SaleOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client_id = Auth::user()->client_id;
        $sale = $this->all
            ?
            Sale::where('client_id', $client_id)->orderBy('id', 'DESC')->with('items')->get()
            :
            Sale::where('client_id', $client_id)->where('employee_id', null)->orderBy('id', 'DESC')->with('items')->get();

        $sale->count() || $this->error('No sale order available');

        return response($sale,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*foreach($items as $item) {
            echo $item->product_id."\n";
        }
        return false;
        //return response(json_decode($request->items));*/

        $this->validate($request, [
            #'invoice_id'    => 'required|exists:App/Models/Invoice,id',
            #'quotation_id'  => 'required|exists:App/Models/Quotation,id',
            'invoice_id'    => 'required',
            'quotation_id'  => 'required',
            'buyer_name'    => 'required',
            'total_amount'  => 'required|numeric',
            'items'         => 'required|json'
        ]);

        // Fetch the items from the request
        $order_items = json_decode($request->input('items'));

        // Check and verify the products
        foreach ($order_items as $key => $product)
        {
            // Fetch the product from the database
            $fetch_product = Product::where('client_id',Auth::user()->client_id)
                ->where('id',$product->product_id)
                ->first();

            // Checks if the product exists
            if (!$fetch_product) {
                return response([
                    'message' => "Product ID {$product->product_id} does not exist"
                ], 403);
            }

            // Checks if product is in stock
            if ($fetch_product->in_stock < intval($product->quantity)) {
                return response([
                    'message' => "{$fetch_product->name} does not have {$product->quantity} items in stock",
                    'product' => $fetch_product
                ], 403);
            }

            $order_items[$key]->db = $fetch_product;
        }

        // If the products were verified, create the sale
        $sale_data  =  $request->all();
        unset($sale_data['items']);
        $sale_data['client_id'] = Auth::user()->client_id;
        $sale_data['tax_value'] = fetchSetting('gst'); // Fetch gst from settings table
        $sale_data['sale']      = Uuid::uuid4()->getHex();
        $new_sale               = new Sale($sale_data);
        $new_sale->save() || $this->error('Sale was not inserted');

        $sale_id = $new_sale->id;
        // Empty array for total items saved
        $items_saved = [];
        // Update the items
        foreach ($order_items as $product) {
            // Update the stock of the product
            $stock_remaining = intval($product->db->in_stock) - intval($product->quantity);
            Product::where('id', $product->product_id)
                ->update(['in_stock' => $stock_remaining]);

            $new_item['sale_id']     = $sale_id;
            $new_item['product_id']  = $product->product_id;
            $new_item['quantity']    = $product->quantity;
            $new_item['unit_price']  = $product->unit_price;
            $new_item['total_price'] = $product->total_price;
            $new_item['unit']        = $product->unit;
            $sale                    = new SaleOrderline($new_item);
            $sale->save() || $this->error('Sale Order not inserted');

            $items_saved[] = $sale; // Append the new item in the items array
        }

        $new_sale->items = $items_saved;

        $response = [
            'message'   => 'Sale Order inserted',
            'sale'     => $new_sale
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $sale = Sale::find($id);
        $sale || $this->error('No Sale Order available');
        $items = SaleOrderline::where('sale_id',$id)->delete();
        $sale->delete();
        return response(['message','Sale Order deleted successfully'],200);
    }
}
