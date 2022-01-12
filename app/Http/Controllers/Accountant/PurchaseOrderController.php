<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrderline;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $client_id = Auth::user()->client_id;
        $purchases = $this->all
            ?
            Purchase::select('purchase_orders.*')
                ->leftJoin('employees','employees.id','=','purchase_orders.employee_id')
                ->where('purchase_orders.client_id', $client_id)
                ->whereNull('employees.deleted_at')
                ->orderBy('purchase_orders.id', 'DESC')
                ->groupBy('purchase_orders.id')
                ->with('items')
                ->with('quotation')
            :
            Purchase::select('purchase_orders.*')
                ->leftJoin('employees','employees.id','=','purchase_orders.employee_id')
                ->where('purchase_orders.client_id', $client_id)
                ->where('purchase_orders.employee_id', null)
                ->whereNull('employees.deleted_at')
                ->orderBy('purchase_orders.id', 'DESC')
                ->groupBy('purchase_orders.id')
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

    public function view($id) {
        $client_id = Auth::user()->client_id;
        $purchase = Purchase::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('quotation')
            ->with('vendor')
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
        return view('accountant.purchase.item', $data);
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
        $date = "Invoice-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('accountant.purchase.pdf-invoice', $data);
        return $pdf->download($date);
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
        return view('accountant.purchase.print-invoice', $data);
    }

    public function delete($id)
    {
        $purchase = Purchase::find($id);
        $items = PurchaseOrderline::where('purchase_id',$id)->delete();
        $purchase->delete();
        return redirect()->back()->with('success', 'Purchase order has been deleted');
    }
}
