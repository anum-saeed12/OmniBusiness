<?php

namespace App\Http\Controllers\Common;

use App\Imports\ProductsImport;
use App\Models\ImportedProducts;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryAssign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    private $user_role;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user_role = Auth::user()->user_role;
    }

    public function importproduct(Request $request)
    {
        $request->validate([
            'productFile' => 'file'
        ]);
        $file = $this->uploadFile($request->file('productsFile'));
        $productImport = new ProductsImport();
        # Start Excel Upload
        $imported = Excel::import($productImport, $file);

        $batch_id = $productImport->batch_id;

        return redirect(route("product.review.{$this->user_role}", $batch_id));
    }

    public function uploadFile($file)
    {
        $filename  = date('Ymdhis')."Imported".ucfirst($file->getClientOriginalName());
        $directory = "products";
        $private_path = $file->storeAs("public/{$directory}",$filename);
        $filepath = "{$directory}/{$filename}";
        return Storage::disk('local')->path($private_path);
        #return "/product_imports/{$filename}";
    }

    public function viewImport($batch_id)
    {
        $imported_data = ImportedProducts::where('batch_id', $batch_id);
        $imported_data->firstOrFail();
        $data = [
            'title'         => 'View Import Data ',
            'user'          => Auth::user(),
            'imported_data' => $imported_data->paginate($this->count),
            'batch_id'      => $batch_id
        ];
        return view('product.import',$data);
    }

    public function approve(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:App\Models\Importedproduct,batch_id',
        ]);
        $imported_products = ImportedProducts::where('batch_id', $request->batch_id)->get();

        $client_id = Auth::user()->client_id;

        $categories = [];
        $brands = [];
        $products = [];

        $data_to_be_inserted = [];
        $data_to_be_updated = [];

        $updated = [];

        $created_categories = [];
        $created_brands = [];

        $existing_products = Product::where('client_id',Auth::id())->get();
        foreach ($existing_products as $_product) $products[$_product->product_name] = $_product->id;


        $existing_categories = ProductCategory::all();
        foreach ($existing_categories as $_category) $categories[$_category->category_title] = $_category->id;

        foreach($imported_products as $product) {
            $product_name = $product->name;
            $product_exists = isset($products[$product_name]);
            $category_exists = isset($categories[$product->category_title]);

            # 1st step:
            # Create a new category if category doesnt exist
            if (isset($categories[$product->category_title])) {
                $category_id = $categories[$product->category_title];
            } else {
                $category_id = ProductCategory::add($product->category_title);
            }
            $category_exists || $created_categories[] = $category_id;
            $category_exists || $categories[$product->category_title] = $category_id;

            if (!$product_exists) {

                # Checks if the product is in relation with the category and the brand
                $exists = Product::select('id')
                    ->where('name', $product->name)
                    ->where('client_id', $client_id)
                    ->first();
                if (!$exists) {
                    $new_product = new Product();
                    $new_product->client_id = $client_id;
                    $new_product->name = $product->name;
                    $new_product->unit = $product->unit;
                    $new_product->in_stock = $product->in_stock;
                    $new_product->unit_price = $product->unit_price;
                    $new_product->save();
                    # Create new category link
                    $new_category_assignment = new ProductCategoryAssign();
                    $new_category_assignment->category_id = $category_id;
                    $new_category_assignment->product_id = $new_product->id;
                    $new_category_assignment->save();

                    continue;
                }
                # If the product exists, fetch the product
                $data_to_update = Product::where('name', $product_name)->where('client_id', $client_id);
                # Update the existing product
                $updated[] = $data_to_update->update([
                    'unit' => $product->unit,
                    'in_stock' => $product->in_stock,
                    'unit_price' => $product->unit_price,
                ]);
            }
        }

        # Update the products
        foreach ($data_to_be_updated as $updatable_product)
        {
            $data_to_update = Product::where('name', $updatable_product->product_name)
                ->where('client_id', $client_id);
            unset($updatable_product['name']);
            $updated[] = $data_to_update->update($updatable_product);
        }

        # Remove all category links to products

        $update_batch = ImportedProducts::where('batch_id', $request->batch_id)->update(['imported' => 1]);

        $data = [
            'created' => [
                'products' => $data_to_be_inserted,
                'created_categories' => $created_categories,
                'created_brands' => $created_brands
            ],
            'updated' => $updated
        ];

        return redirect(route("product.list.{$this->user_role}"))->with('success', 'Import successful!')->with('stats', $data);
    }

    /*public function exportproduct()
    {
        $filename = "products_" . date('M_d_Y') . ".xlsx";
        return Excel::download(new productsExport(), $filename);
    }*/
}
