<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Sale;
use App\Models\SaleOrderline;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SaleOrderController extends Controller
{
    public function index(Request $request)
    {
        $client_id = Auth::user()->client_id;
        $sales = $this->all
            ?
            Sale::select('sale_orders.*')
                ->join('employees','employees.id','=','sale_orders.employee_id')
                ->where('sale_orders.client_id', $client_id)
                ->where('sale_orders.employee_id', Auth::user()->employee_id)
                ->where('sale_orders.due_date','!=',null)
                ->where('sale_orders.quotation_id','!=',null)
                ->orderBy('sale_orders.id', 'DESC')
                ->whereNull('employees.deleted_at')
                ->with('items')
                ->with('quotation')
            :
            Sale::select('sale_orders.*')
                ->join('employees','employees.id','=','sale_orders.employee_id')
                ->where('sale_orders.client_id', $client_id)
                ->where('sale_orders.due_date','!=',null)
                ->where('sale_orders.quotation_id','!=',null)
                ->where('sale_orders.employee_id', null)
                ->whereNull('employees.deleted_at')
                ->orderBy('id', 'DESC')
                ->with('items')
                ->with('quotation');

        // Checks if the user is filtering the results
        if($request->filters) {
            # Expected parameters
            # 1. from_date
            # 2. end_date
            # 3. amount_min
            # 4. amount_max
            # 5. find

            if ($request->dates) {
                $request->validate([
                    'dates' => ['nullable','regex:/^(\d{2}\/){2}\d{4}\s\-\s(\d{2}\/){2}\d{4}$/']
                ]);
                $dates = $request->dates;
                list($start, $stop) = explode(' - ', $dates);
                #fro = '25/08/2017';
                $start = Carbon::createFromFormat('d/m/Y', $start);
                $stop = Carbon::createFromFormat('d/m/Y', $stop);
                # Add date filters to the query
                $sales->where("sale_orders.created_at", ">=", $start)->where("sale_orders.created_at", "<=", $stop);
            }
            if ($request->amount_min) {
                $request->validate([
                    'amount_min' => 'nullable|numeric'
                ]);
                $amount_min = $request->amount_min;
                # Add min-amount filters to the query
                empty($amount_min) || $sales->where("sale_orders.total_amount", ">=", floatval($amount_min));
            }
            if ($request->amount_max) {
                $request->validate([
                    'amount_max' => 'nullable|numeric'
                ]);
                $amount_max = $request->amount_max;
                # Add max-amount filters to the query
                empty($amount_max) || $sales->where("sale_orders.total_amount", "<=", floatval($amount_max));
            }
            if ($request->find) {
                $find = $request->find;
                # Add company-name-finder filters to the query
                empty($find) || $sales->where("sale_orders.buyer_name", "LIKE", "%{$find}%");
            }
            // Process code....
        }

        # Checks for filters
        $this->employee_id && $sales->where('sale_orders.employee_id', $this->employee_id);

        $sales = $sales->paginate($this->count);

        $data = [
            'title'      => 'View Sale',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'sales'      => $sales,
            'pos'        => $this->pos($request),
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => fetchSetting('gst')
        ];

        return view('sale.view', $data);
    }

    public function pos(Request $request)
    {
        $client_id = Auth::user()->client_id;
        $employee_id = Auth::user()->employee_id;
        $sales = $this->all
            ?
            Sale::where('client_id', $client_id)->where('employee_id', $employee_id)
                ->orderBy('id', 'DESC')
                ->where('due_date',null)
                ->where('quotation_id',null)
                ->with('items')
            :
            Sale::where('client_id', $client_id)->where('employee_id', $employee_id)
                ->where('employee_id', null)
                ->orderBy('id', 'DESC')
                ->where('due_date',null)
                ->where('quotation_id',null)
                ->with('items');

        # Checks for filters
        $this->employee_id && $sales->where('employee_id', $this->employee_id);

        $poss = $sales->paginate($this->count);
        return $poss;
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

    public function add()
    {
        $quotations = Quotation::where('client_id', Auth::user()->client_id)
            ->where('quotation_type','sent')
            ->whereNull('rejected_at')
            ->get();
        $client = Client::find(Auth::user()->client_id);
        $data = [
            'title'      => 'Add Sale Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'quotations' => $quotations,
            'client'     => $client,
            'currency'   => 'Rs.',
            'gst'        => fetchSetting('gst')
        ];
        return view('sale.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required',
            'due_date'     => 'required'
        ],
            [
                'quotation_id.required' => 'The quotation field is required.'
            ]);

        $due_date = Carbon::createFromTimeStamp(strtotime($request->due_date))->format('Y-m-d');
        $quotation = Quotation::where('client_id', Auth::user()->client_id)
            ->where('id', $request->quotation_id)
            ->first();
        $new_sale = $request->all();
        $new_sale['sale']          = Uuid::uuid4()->getHex();
        $new_sale['client_id']     = Auth::user()->client_id;
        $new_sale['quotation_id']  = $request->quotation_id;
        $new_sale['buyer_name']    = $quotation->company;
        $new_sale['tax_value']     = $quotation->gst;
        $new_sale['total_amount']  = $quotation->total_amount;
        $new_sale['due_date']      = $due_date;
        $new_sale['employee_id']   = Auth::user()->employee_id;

        $new_sale = new Sale($new_sale);
        $new_sale->save();
        $sale_id = $new_sale->id;
        $quotation_items = QuotationItem::where('quotation_id', $request->quotation_id)
            ->get();
        $sale_item = [];
        foreach ($quotation_items as $item) {
            $sale_item = [
                'product_id' => $item->product_id,
                'sale_id'    => $sale_id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price'=> $item->total_price
            ];
            $sale_item = new SaleOrderline($sale_item);
            $sale_item->save() ;
            $inventory = Product::find($item->product_id);
            $inventory->in_stock = $inventory->in_stock - $item->quantity;
            $inventory->save();
        }
        $invoice = $request->all();
        $invoice['product_supplier_and_buyer'] = $quotation->company;
        $invoice['total_amount']               = $quotation->total_amount;
        $invoice['client_id']                  = Auth::user()->client_id;
        $invoice['employee_id']                = Auth::user()->employee_id;

        $invoices = new Invoice($invoice);
        $invoices->save();
        $invoice_id = $invoices->id;
        $sale_items = SaleOrderline::where('sale_id', $new_sale->id)
            ->get();
        $invoice_item = [];
        foreach ($sale_items as $item) {
            $invoice_item = [
                'product_id' => $item->product_id,
                'invoice_id' => $invoice_id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price'=> $item->total_price
            ];
            $invoice_item = new InvoiceItem($invoice_item);
            $invoice_item->save() ;
        }
        $new_sale['invoice_id']  = $invoice_id;
        $new_sale->save();

        return redirect(
            route('sale.list.employee',$invoice_id)
        );
    }

    public function view($id) {
        $client_id = Auth::user()->client_id;
        $sale = Sale::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('vendor')
            ->with('quotation')
            ->first();
        $sale->creation = Carbon::createFromTimeStamp(strtotime($sale->created_at))->format('d-M-Y');
        $sale->due_date_formatted = empty($sale->due_date)?null:Carbon::createFromTimeStamp(strtotime($sale->due_date))->format('d-M-Y');
        $sale->quotation->accepted_at_formatted = empty($sale->quotation->accepted_at)?null:Carbon::createFromTimeStamp(strtotime($sale->quotation->accepted_at))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($sale->total_amount * ($gst/100));
        $subtotal= $sale->total_amount;

        $data = [
            'title'      => 'Sale Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'sales'      => $sale,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax
        ];
        return view('sale.item', $data);
    }

    public function posview($id) {
        $client_id = Auth::user()->client_id;
        $sale = Sale::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->first();
        $sale->creation = Carbon::createFromTimeStamp(strtotime($sale->created_at))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($sale->total_amount * ($gst/100));
        $subtotal= $sale->total_amount;

        $data = [
            'title'      => 'Sale Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'sales'      => $sale,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax
        ];
        return view('sale.positem', $data);
    }

    public function posinvoice($id) {
        $client_id = Auth::user()->client_id;
        $invoice = Invoice::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('sale')
            ->first();

        $invoice->creation = Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y');
        $invoice->sale->due_date_formatted = empty($invoice->sale->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->sale->due_date))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($invoice->total_amount * ($gst/100));
        $subtotal= $invoice->total_amount;
        $data = [
            'title'      => 'Sale Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'invoice'    => $invoice,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax
        ];
        $date = "Invoice-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('sale.pos-pdf-invoice', $data);
        return $pdf->download($date);
    }

    public function printposInvoice($id)
    {
        $client_id = Auth::user()->client_id;
        $invoice = Invoice::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('sale')
            ->first();

        $invoice->creation = Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y');
        $invoice->sale->due_date_formatted = empty($invoice->sale->due_date) ? null : Carbon::createFromTimeStamp(strtotime($invoice->sale->due_date))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($invoice->total_amount * ($gst / 100));
        $subtotal = $invoice->total_amount;
        $data = [
            'title' => 'Sale Order',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user' => Auth::user(),
            'invoice' => $invoice,
            'currency' => 'Rs.',
            'client' => Client::find($client_id),
            'gst' => $gst,
            'subtotal' => $subtotal,
            'tax' => $tax
        ];
        return view('sale.pos-print-invoice', $data);
    }

    public function invoice($id) {
        $client_id = Auth::user()->client_id;
        $invoice = Invoice::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('vendor')
            ->with('sale')
            ->first();

        $invoice->creation = Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y');
        $invoice->sale->due_date_formatted = empty($invoice->sale->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->sale->due_date))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($invoice->total_amount * ($gst/100));
        $subtotal= $invoice->total_amount;
        $data = [
            'title'      => 'Sale Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'invoice'    => $invoice,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax
        ];
        return view('sale.pdf-invoice', $data);
    }

    public function printInvoice($id) {
        $client_id = Auth::user()->client_id;
        $invoice = Invoice::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('vendor')
            ->with('sale')
            ->first();

        $invoice->creation = Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y');
        $invoice->sale->due_date_formatted = empty($invoice->sale->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->sale->due_date))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($invoice->total_amount * ($gst/100));
        $subtotal= $invoice->total_amount;
        $data = [
            'title'      => 'Sale Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'invoice'    => $invoice,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax
        ];
        return view('sale.print-invoice', $data);
    }

    public function point()
    {
        $client = Client::find(Auth::user()->client_id);
        $data = [
            'title'      => 'Add Point of Sale',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'client'     => $client,
            'currency'   => 'Rs.',
            'gst'        => fetchSetting('gst')
        ];
        return view('sale.pointsale', $data);
    }

    public function pointsale(Request $request)
    {
        $request->validate([
            'buyer_name'       => 'required',
            'products'         => 'required|array'
        ]);
        $total = 0;
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
            $total += floatval($product['price']) * floatval($product['quantity']);
            $index++;
        }

        $sale                  = $request->only('buyer_name');
        $sale['sale']          = Uuid::uuid4()->getHex();
        $sale['client_id']     = Auth::user()->client_id;
        $sale['employee_id']   = Auth::user()->employee_id;
        $sale['tax_value']     = fetchSetting('gst'); // Fetch gst from settings table
        $sale['total_amount']  = $total;
        $sale['buyer_name']    = $request->buyer_name;
        $new_sale              = new Sale($sale);
        $new_sale->save();
        $sale_id = $new_sale->id;
        // Empty array for total items saved
        $items_saved = [];
        foreach ($order_items as $product) {
            // Update the stock of the product

            $stock_remaining = intval($product->db->in_stock) - intval($product->quantity);
            $updated = Product::where('id', $product->id)
                ->update(['in_stock' => $stock_remaining]);

            $new_item['sale_id']     = $sale_id;
            $new_item['product_id']  = $product->id;
            $new_item['quantity']    = $product->quantity;
            $new_item['unit_price']  = $product->price;
            $new_item['total_price'] = floatval($product->quantity) * floatval($product->price);
            $new_item['unit']        = $product->db->unit;
            $sale_item               = new SaleOrderline($new_item);
            $sale_item->save();

            $items_saved[$index] = $sale_item; // Append the new item in the items array
            $items_saved[$index]->updated = $updated;
        }
        $sale_item->items = $items_saved;

        $invoice = $request->all();
        $invoice['product_supplier_and_buyer'] = $request->buyer_name;
        $invoice['total_amount']               = $total;
        $invoice['client_id']                  = Auth::user()->client_id;
        $invoice['employee_id']                = Auth::user()->employee_id;

        $invoices = new Invoice($invoice);
        $invoices->save();
        $invoice_id = $invoices->id;
        $sale_items = SaleOrderline::where('sale_id', $new_sale->id)
            ->get();
        $invoice_item = [];
        foreach ($sale_items as $item) {
            $invoice_item = [
                'product_id' => $item->product_id,
                'invoice_id' => $invoice_id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price'=> $item->total_price
            ];
            $invoice_item = new InvoiceItem($invoice_item);
            $invoice_item->save() ;
        }
        $new_sale['invoice_id']  = $invoice_id;
        $new_sale->save();

        return redirect(
            route('sale.list.employee')
        )->with('success', 'Point of Sale was added successfully!');
    }
}
