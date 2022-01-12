<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

#Models
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $client = Auth::user()->client_id;
        $categorys = ProductCategory::select(
            DB::raw("product_categories.id as id"),
            DB::raw("product_categories.title"),
            DB::raw("COUNT(products.id) as total")
        )
            ->leftJoin('product_categories_assigned', 'product_categories_assigned.category_id', '=', 'product_categories.id')
            ->leftJoin("products",function($join) use($client) {
                $join->on("products.id","=","product_categories_assigned.product_id")
                    ->where("products.client_id","=", $client );
            })
            ->where('product_categories.client_id',$client)
            ->groupBy('product_categories.id','product_categories.title')
            ->orderBy('products.id', 'DESC')
            ->paginate($this->count);

        $data = [
            'title'       => 'View Categories',
            'user'        => Auth::user(),
            'categorys'   => $categorys,
        ];
        return view('product.category.view',$data);
    }
    public function add()
    {
        $data = [
            'title'    => 'Add Product Category',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user()
        ];
        return view('product.category.add', $data);
    }

    public function edit($id)
    {
        $client = Auth::user()->client_id;
        $categorys = ProductCategory::select(
            DB::raw("product_categories.id as id"),
            DB::raw("product_categories.title"),
            DB::raw("COUNT(products.id) as total")
        )
            ->leftJoin('product_categories_assigned', 'product_categories_assigned.category_id', '=', 'product_categories.id')
            ->leftJoin("products",function($join) use($client) {
                $join->on("products.id","=","product_categories_assigned.product_id")
                    ->where("products.client_id","=", $client );
            })
            ->where('product_categories.client_id',$client)
            ->groupBy('product_categories.id','product_categories.title')
            ->paginate($this->count);

        $category = ProductCategory::where('client_id',Auth::user()->client_id)
                    ->where('id',$id)
                    ->first();
        $data = [
            'title'    => 'Update Product Category',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
            'category' => $category,
            'categorys'   => $categorys,
        ];
        return view('product.category.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required'
        ]);

        $exist=ProductCategory::where([
            ['client_id', Auth::user()->client_id],
            ['title', $request->title]
        ])->first();
        if($exist)
        {
            return redirect(
                route('category.list.client')
            )->with('success', 'Category already exists!');
        }

        $data               = $request->all();
        $data['client_id']  = Auth::user()->client_id;
        $product            = new ProductCategory($data);
        $product->save();

        return redirect(
            route('category.list.client')
        )->with('success', 'Category was added successfully!');
    }

    public function update(Request $request, $id)
    {
        $product = ProductCategory::where('client_id',Auth::user()->client_id)
                    ->where('id',$id)
                    ->first();

        $request->validate([
            'title'      => "required|unique:App\Models\ProductCategory,title,{$id}",
        ]);

        $exist = ProductCategory::where([
            ['client_id', Auth::user()->client_id],
            ['title', $request->title]
        ])->first();

        $request->input('title')       &&   $product->title      = $request->input('title');
        $product->save();

        return redirect(
            route('category.list.client')
        )->with('success', 'Category was updated successfully!');
    }

    public function delete($id)
    {
        $product = ProductCategory::find($id)->delete();
        return redirect(
            route('category.list.client')
        )->with('success', 'Category deleted successfully!');
    }
}
