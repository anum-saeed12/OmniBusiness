<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\ProductCategoryAssign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorys = ProductCategory::where('client_id', Auth::user()->client_id)
            ->orderBy('id','DESC')
            ->paginate($this->count);
        $data = [
            'title'       => 'Category',
            'user'        => Auth::user(),
            'categorys'   => $categorys,
            'i'           => 1
        ];
        return view('product.category.view',$data);
    }
    public function add()
    {
        $data = [
            'title' => 'Add Product Category',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user' => Auth::user()
        ];
        return view('product.category.add', $data);
    }

    public function edit()
    {
        $data = [
            'title'    => 'Update Product Category',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user()
        ];
        return view('product.category.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required'
        ]);

        $exist=ProductCategory::where([
            ['client_id', Auth::user()->client_id],
            ['title', $request->title]
        ])->get();
        if($exist)
        {
            return redirect(
                route('category.list.manager')
            )->with('success', 'Category already exists!');
        }
        $data               = $request->all();
        $data['client_id']  = Auth::user()->client_id;
        $product            = new ProductCategory($data);
        $product->save() || $this->error("Product Category is not inserted!");

        return redirect(
            route('product.list.manager')
        )->with('success', 'Category was added successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $product=ProductCategory::find($id)->delete();
        return redirect(
            route('category.list.manager')
        )->with('success', 'Category deleted successfully!');
    }
}
