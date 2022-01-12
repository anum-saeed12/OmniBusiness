<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

#models
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Product;
use App\Models\Client;

class QuotationController extends Controller
{
    public function index()
    {
        $client_id = Auth::user()->client_id;
        $quotations = $this->all
            ?
            Quotation::select('quotations.*')
                ->leftJoin('employees','employees.id','=','quotations.employee_id')
                ->where('quotations.client_id', $client_id)
                ->whereNull('employees.deleted_at')
                ->orderBy('quotations.id', 'DESC')
                ->groupBy('quotations.id')
                ->with('items')
                ->with('employee')
            :
            Quotation::select('quotations.*')
                ->leftJoin('employees','employees.id','=','quotations.employee_id')
                ->where('quotations.client_id', $client_id)
                ->where('quotations.employee_id', null)
                ->whereNull('employees.deleted_at')
                ->orderBy('quotations.id', 'DESC')
                ->groupBy('quotations.id')
                ->with('items');

        # Checks for filters
        $this->employee_id && $quotations->where('quotations.employee_id', $this->employee_id);

        $quotations = $quotations->paginate($this->count);

        $data = [
            'title'      => 'Quotations',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'quotations' => $quotations,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => fetchSetting('gst')
        ];
        return view('quotation.view', $data);
    }

    public function search($search) {
        $s = $search;
        $products = Product::where('client_id',Auth::user()->client_id)
            ->where('name', 'LIKE', "%{$s}%")
            ->orWhere('id', $s)
            ->limit(10)
            ->get();
        return response($products, 200);
    }

    public function reject_at($id)
    {
        $quotations = Quotation::find($id);

        if($quotations->rejected_at != null)
        {
            return redirect(route('quotation.view'))
                ->with('error', 'Quotation is already rejected');
        }

        $quotations->updated_by=Auth::user()->client_id;
        $quotations->rejected_at=Carbon::now();
        $quotations->save();

        return redirect()->back()
            ->with('success', 'Quotation rejected successfully');
    }

    public function accept_at($id)
    {
        $quotations = Quotation::find($id);

        if($quotations->accepted_at != null)
        {
            return redirect(route('quotation.item'))
                ->with('error', 'Quotation is already accepted');
        }
        $quotations->updated_by = Auth::user()->client_id;
        $quotations->accepted_at=Carbon::now();
        $quotations->save();

        return redirect()->back()
            ->with('success', 'Quotation accepted successfully');
    }

    public function ajax($id)
    {
        $quotation = Quotation::where('id', $id)
            ->with('items')
            ->with('vendor')
            ->first();
        $quotation || $this->error('Not found');
        $quotation->creation = Carbon::createFromTimeStamp(strtotime($quotation->created_at))->format('d-M-Y');
        $quotation->approval = empty($quotation->accepted_at)?null:Carbon::createFromTimeStamp(strtotime($quotation->accepted_at))->format('d-M-Y');
        $quotation->rejection = empty($quotation->rejected_at)?null:Carbon::createFromTimeStamp(strtotime($quotation->rejected_at))->format('d-M-Y');
        return response($quotation, 200);
    }

    public function ajaxAccept($id)
    {
        $accepted = Quotation::where('id', $id)->update(['accepted_at' => Carbon::now()]);
        if (!$accepted) return response(['message' => 'Quotation not accepted'], 401);
        return response($accepted, 200);
    }

    public function add()
    {
        $data = [
            'title'      => 'Add Quotation',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'currency' => 'Rs.'
        ];
        return view('quotation.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company'          => 'required',
            'quotation_type'   => 'required|in:rcvd,sent',
            'products'         => 'required|array'
        ]);
        $total = 0;
        $original_amount = 0;
        $order_items = [];
        $index = 0;
        foreach ($request->products as $id => $product)
        {
            $order_items[$index] = (Object) $product;
            // Fetch the product from the database
            $fetch_product = Product::where('id', $id)->first();
            // Checks if the product exists
            if (!$fetch_product)
            {
                return redirect()->back()
                    ->with('error', 'Product not found !')
                    ->with('products', $request->products)
                    ->with('not-found-id', $id);
            }
            else
            {
                // Checks if product is in stock
                if ($fetch_product->in_stock < intval($product['quantity']))
                {
                    return redirect()->back()
                        ->with('error', "\"{$fetch_product->name}\" does not have stock of \"{$product->quantity}\"!")
                        ->with('products', $request->products);
                }
            }
            $order_items[$index]->db = $fetch_product;
            #$total += floatval($product['price']) * floatval($product['quantity']);
            $original_amount += floatval($product['quantity']) * floatval($product['price']);
            $total += (floatval($product['quantity']) * floatval($product['price'])) - ((floatval($product['price']) * floatval($product['quantity'])) * (floatval($product['discount'])/100));
            $index++;
        }

        // If the products were verified, create the sale
        $quotation                  = $request->only('company', 'quotation_id', 'due_date','quotation_type');
        $quotation['client_id']     = Auth::user()->client_id;
        $quotation['employee_id']   = Auth::user()->employee_id;
        $quotation['tax_value']     = fetchSetting('gst'); // Fetch gst from settings table
        $quotation['total_amount']  = $total;
        $quotation['original_amount']= $original_amount;
        $new_quotation              = new Quotation($quotation);
        $new_quotation->save();
        $quotation_id = $new_quotation->id;
        // Empty array for total items saved
        $items_saved = [];
        // Update the items
        foreach ($order_items as $product) {
            // Update the stock of the product

            # Checks if the quotation type is "rcvd"; Substract from stock quantity if not "rcvd"
            $previous_quantity = intval($product->db->in_stock);
            $request->quotation_type == 'rcvd' || $stock_remaining = $previous_quantity - intval($product->quantity);
            $request->quotation_type == 'rcvd' || $updated = Product::where('id', $product->id)->update(['in_stock' => $stock_remaining]);

            $new_item['quotation_id']         = $quotation_id;
            $new_item['product_id']           = $product->id;
            $new_item['quantity']             = $product->quantity;
            $new_item['previous_quantity']    = $previous_quantity;
            $new_item['original_unit_price']  = ($product->price / (100 - intval($product->discount))) * 100;
            $new_item['unit_price']           = $product->price;
            $new_item['discount']             = $product->discount;
            $new_item['original_total_price'] = floatval($product->quantity) * floatval($product->price);
            $new_item['total_price']          = (floatval($product->quantity) * floatval($product->price)) - ((floatval($product->price) * floatval($product->quantity)) * (floatval($product->discount)/100));
            $new_item['unit']                 = $product->db->unit;
            $quotation_item                   = new QuotationItem($new_item);
            $quotation_item->save();

            $items_saved[$index] = $quotation_item; // Append the new item in the items array
            !isset($updated) || $items_saved[$index]->updated = $updated;
        }
        $new_quotation->items = $items_saved;

        return redirect(
            route('quotation.add.manager')
        )->with('success', 'Quotation created successfully!')
            ->with('quotation', $new_quotation);
    }

    public function view ( $id ) {
        $client_id = Auth::user()->client_id;
        $quotation = Quotation::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('vendor')
            ->first();

        $quotation->creation = Carbon::createFromTimeStamp(strtotime($quotation->created_at))->format('d-M-Y');
        $quotation->approval = empty($quotation->accepted_at)?null:Carbon::createFromTimeStamp(strtotime($quotation->accepted_at))->format('d-M-Y');
        $quotation->rejection = empty($quotation->rejected_at)?null:Carbon::createFromTimeStamp(strtotime($quotation->rejected_at))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($quotation->total_amount * ($gst/100));
        $data = [
            'title'      => 'Quotations',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'quotation'  => $quotation,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => fetchSetting('gst'),
            'tax'        => $tax
        ];
        return view('quotation.item', $data);
    }
}
