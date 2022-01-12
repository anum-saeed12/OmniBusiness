<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrderline;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $client_id = Auth::user()->client_id;
        $purchases = $this->all
            ?
            Purchase::select('purchase_orders.*')
                ->join('employees','employees.id','=','purchase_orders.employee_id')
                ->where('purchase_orders.client_id', $client_id)
                ->where('purchase_orders.employee_id', Auth::user()->employee_id)
                ->orderBy('purchase_orders.id', 'DESC')
                ->whereNull('employees.deleted_at')
                ->with('items')
                ->with('quotation')
            :
            Purchase::select('purchase_orders.*')
                ->join('employees','employees.id','=','purchase_orders.employee_id')
                ->where('purchase_orders.client_id', $client_id)
                ->where('purchase_orders.employee_id', Auth::user()->employee_id)
                ->where('purchase_orders.employee_id', null)
                ->whereNull('employees.deleted_at')
                ->orderBy('purchase_orders.id', 'DESC')
                ->with('items')
                ->with('quotation');
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
                $purchases->where("purchase_orders.created_at", ">=", $start)->where("purchase_orders.created_at", "<=", $stop);
            }
            if ($request->amount_min) {
                $request->validate([
                    'amount_min' => 'nullable|numeric'
                ]);
                $amount_min = $request->amount_min;
                # Add min-amount filters to the query
                empty($amount_min) || $purchases->where("purchase_orders.total_amount", ">=", floatval($amount_min));
            }
            if ($request->amount_max) {
                $request->validate([
                    'amount_max' => 'nullable|numeric'
                ]);
                $amount_max = $request->amount_max;
                # Add max-amount filters to the query
                empty($amount_max) || $purchases->where("purchase_orders.total_amount", "<=", floatval($amount_max));
            }
            if ($request->find) {
                $find = $request->find;
                # Add company-name-finder filters to the query
                empty($find) || $purchases->where("purchase_orders.supplier_name", "LIKE", "%{$find}%");
            }
            // Process code....
        }
        # Checks for filters
        $this->employee_id && $purchases->where('purchase_orders.employee_id', $this->employee_id);

        $purchases = $purchases->paginate($this->count);

        #return $purchases;

        $data = [
            'title'      => 'View Purchase Orders',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'purchases'   => $purchases,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => fetchSetting('gst')
        ];

        return view('purchase.view', $data);
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
            ->where('quotation_type','rcvd')
            ->whereNull('rejected_at')
            ->get();
        $client = Client::find(Auth::user()->client_id);
        $data = [
            'title'      => 'Add Purchase Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'quotations' => $quotations,
            'client'     => $client,
            'currency'   => 'Rs.',
            'gst'        => fetchSetting('gst')
        ];
        return view('purchase.add', $data);
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
        $new_purchase = $request->all();
        $new_purchase['purchase']          = Uuid::uuid4()->getHex();
        $new_purchase['client_id']     = Auth::user()->client_id;
        $new_purchase['employee_id']     = Auth::user()->employee_id;
        $new_purchase['quotation_id']  = $request->quotation_id;
        $new_purchase['supplier_name'] = $quotation->company;
        $new_purchase['tax_value']     = $quotation->gst;
        $new_purchase['total_amount']  = $quotation->total_amount;
        $new_sale['original_amount']     = $quotation->original_amount;
        $new_purchase['due_date']      = $due_date;
        $new_purchase = new Purchase($new_purchase);
        $new_purchase->save();
        $purchase_id = $new_purchase->id;
        $quotation_items = QuotationItem::where('quotation_id', $request->quotation_id)->get();
        $purchase_item = [];
        foreach ($quotation_items as $item) {
            $purchase_item = [
                'product_id' => $item->product_id,
                'purchase_id' => $purchase_id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount,
                'original_total_price' => $item->original_total_price,
                'original_unit_price' => $item->original_unit_price,
                'total_price'=> $item->total_price
            ];
            $previous_quantity = intval(Product::find($item->product_id)->in_stock);
            $stock_remaining = $previous_quantity + intval($item->quantity);
            # Update the product's available quantity (in-stock)
            $updated = Product::where('id', $item->product_id)->update(['in_stock' => $stock_remaining]);
            $purchase_item = new PurchaseOrderline($purchase_item);
            $purchase_item->save() ;
        }
        $invoice = $request->all();
        $invoice['product_supplier_and_buyer'] = $quotation->company;
        $invoice['total_amount']               = $quotation->total_amount;
        $invoice['client_id']                  = Auth::user()->client_id;
        $invoice['employee_id']     = Auth::user()->employee_id;

        $invoices = new Invoice($invoice);
        $invoices->save();
        $invoice_id = $invoices->id;
        $purchase_items = PurchaseOrderline::where('purchase_id', $new_purchase->id)
            ->get();
        $invoice_item = [];
        foreach ($purchase_items as $item) {
            $invoice_item = [
                'product_id' => $item->product_id,
                'invoice_id' => $invoice_id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount,
                'original_total_price' => $item->original_total_price,
                'original_unit_price' => $item->original_unit_price,
                'total_price'=> $item->total_price
            ];
            $invoice_item = new InvoiceItem($invoice_item);
            $invoice_item->save() ;
        }
        $new_purchase['invoice_id']  = $invoice_id;
        $new_purchase->save();

        return redirect(
            route('purchase.list.employee',$invoice_id)
        );
    }

    public function view($id) {
        $client_id = Auth::user()->client_id;
        $purchase = Purchase::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('vendor')
            ->with('quotation')
            ->first();

        $purchase->creation = Carbon::createFromTimeStamp(strtotime($purchase->created_at))->format('d-M-Y');
        $purchase->due_date_formatted = empty($purchase->due_date)?null:Carbon::createFromTimeStamp(strtotime($purchase->due_date))->format('d-M-Y');
        $purchase->quotation->accepted_at_formatted = empty($purchase->quotation->accepted_at)?null:Carbon::createFromTimeStamp(strtotime($purchase->quotation->accepted_at))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($purchase->total_amount * ($gst/100));
        $subtotal= $purchase->total_amount;
        $data = [
            'title'      => 'Purchase Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'purchase'   => $purchase,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax
        ];
        return view('purchase.item', $data);
    }

    public function invoice($id) {
        $client_id = Auth::user()->client_id;
        $invoice = Invoice::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('vendor')
            ->with('purchase')
            ->first();

        $invoice->creation = Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y');
        $invoice->purchase->due_date_formatted = empty($invoice->purchase->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->purchase->due_date))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($invoice->total_amount * ($gst/100));
        $subtotal= $invoice->total_amount;
        $data = [
            'title'      => 'Purchase Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'invoice'    => $invoice,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax
        ];
        return view('purchase.pdf-invoice', $data);
    }

    public function printInvoice($id) {
        $client_id = Auth::user()->client_id;
        $invoice = Invoice::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('vendor')
            ->with('purchase')
            ->first();
        #return $invoice;
        $invoice->creation = Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y');
        $invoice->purchase->due_date_formatted = empty($invoice->purchase->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->purchase->due_date))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($invoice->total_amount * ($gst/100));
        $subtotal= $invoice->total_amount;
        $data = [
            'title'      => 'Purchase Order',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'invoice'    => $invoice,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax
        ];
        return view('purchase.print-invoice', $data);
    }
}
