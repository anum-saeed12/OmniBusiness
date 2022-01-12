<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::all();
        $product->count() || $this->error('No product is available');
        return response($product,200);
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
            'name'      => 'required',
            'unit'      => 'required|in:lb,g,kg,ton,ml,lt,pc',
            'in_stock'  => 'required|numeric',
            'client_id' => 'required|exists:App\Models\Client,id'
        ]);
        $data               = $request->all();
        $data['client_id']  = $request->client_id;
        $product            = new Product($data);
        $product->save() || $this->error("Product is not inserted!");

        $response=[
            'message' => 'Product added successfully',
            'product' =>  $product
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
        $product = Product::find($id);

        $this->validate($request, [
            'name'      => 'sometimes|required',
            'unit'      => 'sometimes|required|in:lb,g,kg,ton,ml,lt,pc',
            'in_stock'  => 'sometimes|required|numeric'
        ]);

        $request->input('name')       &&   $product->name      = $request->input('name');
        $request->input('unit')       &&   $product->unit      = $request->input('unit');
        $request->input('in_stock')   &&   $product->in_stock  = $request->input('in_stock');
        $product->save() || $this->error("Product is not updated!");

        $response=[
            'message' => 'Product updated successfully',
            'product' =>  $product
        ];
        return response($response,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $product=Product::find($id);
        $product || $this->error('Product is not available');
        $product->delete();
        return response(['message','Product deleted successfully'],200);
    }
}
