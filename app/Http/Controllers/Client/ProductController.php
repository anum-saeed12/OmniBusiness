<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#MODELS
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryAssign;
use App\Models\PurchaseOrderline;
use App\Models\SaleOrderline;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('client_id' , Auth::user()->client_id)
            ->with('category')
            ->orderBy('id', 'DESC')
            ->paginate($this->count);

        $data = [
            'title'       =>'View Products',
            'user'        => Auth::user(),
            'products'    => $products,
        ];
        return view('product.view',$data);
    }

    public function view($id)
    {
        if (!Product::find($id)) return redirect(route('product.list.client'))->with('error', 'Product not found');
        $product = Product::where('client_id' , Auth::user()->client_id)->where('id', $id)->with('category')->first();

        # Checks if the employee exists and the client owns the product
        if (!$product) return redirect(route('product.list.client'))->with('error', 'Product not found');

        $user = Auth::user();
            //SALES
        $year_sales = SaleOrderline::select(
            DB::raw('SUM(total_price) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%M, %Y') as creation_date")
        )
            ->where('created_at', '>=', Carbon::today()->firstOfYear())
            ->where('product_id', $id)
            ->groupBy('creation_date')
            ->orderBy('created_at','ASC');
        $month_sales = SaleOrderline::select(
            DB::raw('SUM(total_price) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%d %M, %Y') as creation_date")
        )->where('created_at', '>=', Carbon::today()->firstOfMonth())
            ->where('product_id', $id)
            ->groupBy('creation_date')
            ->orderBy('created_at', 'ASC');
        $today_sales = SaleOrderline::select(
            'total_price as total',
            'created_at as creation_date'
        )->where('created_at', '>=', Carbon::today())
            ->where('product_id', $id);

                //PURCHASE
        $year_purchase = PurchaseOrderline::select(
            DB::raw('SUM(total_price) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%M, %Y') as creation_date")
        )
            ->where('created_at', '>=', Carbon::today()->firstOfYear())
            ->where('product_id', $id)
            ->groupBy('creation_date')
            ->orderBy('created_at','ASC');
        $month_purchase = PurchaseOrderline::select(
            DB::raw('SUM(total_price) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%d %M, %Y') as creation_date")
        )->where('created_at', '>=', Carbon::today()->firstOfMonth())
            ->where('product_id', $id)
            ->groupBy('creation_date')
            ->orderBy('created_at', 'ASC');
        $today_purchase = PurchaseOrderline::select(
            'total_price as total',
            'created_at as creation_date'
        )->where('created_at', '>=', Carbon::today())
            ->where('product_id', $id);
        $data = [
            'title'                 => "{$product->name}",
            'base_url'              => env('APP_URL', 'http://omnibiz.local'),
            'user'                  => Auth::user(),
            'product'               => $product,
            'year_employee_sales'   => $year_sales->get(),
            'month_employee_sales'  => $month_sales->get(),
            'today_employee_sales'  => $today_sales->get(),
            'year_purchases'        => $year_purchase->get(),
            'month_purchases'       => $month_purchase->get(),
            'today_purchases'       => $today_purchase->get(),
            'currency'              => 'PKR',
            'client'                => Client::find($user->client_id),
            'gst'                   => fetchSetting('gst')
        ];
        return view('product.individual', $data);
    }

    public function add()
    {
        $category = ProductCategory::where('client_id', Auth::user()->client_id)->get();
        $data = [
            'title'    => 'Add Product',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
            'category' => $category
        ];
        return view('product.add', $data);
    }

    public function edit($id)
    {
        $category = ProductCategory::where('client_id', Auth::user()->client_id)
                                    ->get();
        $product  = Product::where('client_id', Auth::user()->client_id)
                            ->where('id',$id)
                            ->with('category')
                            ->first();
        $data = [
            'title'    => 'Update Product',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
            'category' => $category,
            'product'  => $product
        ];
        return view('product.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:App\Models\Product,name,NULL,NULL,client_id,' . Auth::user()->client_id,
            'unit'       => 'required|in:lb,g,kg,ton,ml,lt,pc',
            'in_stock'   => 'required|numeric',
            'unit_price'  => 'required|numeric',
            'category_id'=> 'required'
        ],
            [
                'in_stock.required'    => 'The stock field is required.',
                'category_id.required' => 'The category field is required.',
            ]);
        $data               = $request->all();
        $data['client_id']  = Auth::user()->client_id;
        $product            = new Product($data);
        $product->save();

        $data                = $request->all();
        $data['category_id'] = $request->category_id;
        $data['product_id']  = $product->id;
        $category            = new ProductCategoryAssign($data);
        $category->save();

        return redirect(
            route('product.list.client')
        )->with('success', 'Product was added successfully!');
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name'        => "required|unique:App\Models\Product,name,{$id},id,client_id," . Auth::user()->client_id,
            'unit'        => 'required|in:lb,g,kg,ton,ml,lt,pc',
            'in_stock'    => 'required|numeric',
            'unit_price'  => 'required|numeric',
            'category_id' => 'required'
        ],
            [
                'in_stock.required'    => 'The stock field is required.',
                'category_id.required' => 'The category field is required.',
            ]);


        $product  = Product::where('client_id', Auth::user()->client_id)
            ->where('id',$id)
            ->with('category')
            ->first();

        $category = ProductCategoryAssign::where('product_id',$id)
            ->first();

        $request->input('name')          &&   $product->name          = $request->input('name');
        $request->input('unit')          &&   $product->unit          = $request->input('unit');
        $request->input('in_stock')      &&   $product->in_stock      = $request->input('in_stock');
        $request->input('unit_price')    &&   $product->unit_price    = $request->input('unit_price');
        $request->input('category_id')   &&   $category->category_id  = $request->input('category_id');
        $product->save();
        $category->save();


        return redirect(
            route('product.list.client')
        )->with('success', 'Product updated successfully!');
    }

    public function delete($id)
    {
        $product = Product::find($id)->delete();
        return redirect(
        route('product.list.client'))->with('success', 'Product deleted successfully!');
    }
}
