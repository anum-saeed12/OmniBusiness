<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = ProductCategory::all();
        $product || $this->error('No product category available');
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
            'title'      => 'required',
            'client_id'  => 'required|numeric|exists:App\Models\Client,id'
        ]);
        $data               = $request->all();
        $data['client_id']  = $request->client_id;
        $product            = new ProductCategory($data);
        $product->save() || $this->error("Product Category is not inserted!");

        $response=[
            'message' => 'Product Category added successfully',
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
        $product = ProductCategory::find($id);

        $this->validate($request, [
            'title'      => 'sometimes|required',
        ]);

        $request->input('title')       &&   $product->title      = $request->input('title');
        $product->save() || $this->error("Product Category is not updated!");

        $response=[
            'message' => 'Product Category updated successfully',
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
        $product=ProductCategory::find($id);
        $product || $this->error('Product Category is not available');
        $product->delete();
        return response(['message','Product Category deleted successfully'],200);
    }
}
