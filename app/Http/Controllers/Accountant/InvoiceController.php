<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;

class InvoiceController extends Controller
{
    public function index()
    {
        $client_id = Auth::user()->client_id;
        $invoices = $this->all
            ?
            Invoice::where('client_id', $client_id)
                ->orderBy('id', 'DESC')
                ->with('items')
            :
            Invoice::where('client_id', $client_id)
                ->where('employee_id', null)
                ->orderBy('id', 'DESC')
                ->with('items');

        # Checks for filters
        $this->employee_id && $invoices->where('employee_id', $this->employee_id);

        $invoices = $invoices->paginate($this->count);

        $data = [
            'title'      => 'View Invoices',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'invoices'   => $invoices,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => fetchSetting('gst')
        ];

        return view('accountant.invoice.view', $data);
    }

    public function view($id) {
        $client_id = Auth::user()->client_id;
        $invoice = Invoice::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('sale')
            ->with('purchase')
            ->with('vendor')
            ->first();
        $invoice->creation = Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y');
        $invoice->due_date_formatted = empty($invoice->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->due_date))->format('d-M-Y');

        if ($invoice->sale) {
            unset($invoice->purchase);
            $invoice->invoice_type = 'sale';
        }
        if ($invoice->purchase) {
            unset($invoice->sale);
            $invoice->invoice_type = 'purchase';
        }

        $gst = fetchSetting('gst');
        $tax = ($invoice->total_amount * ($gst/100));
        $subtotal= $invoice->total_amount;

        $data = [
            'title'      => 'Invoice ',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'invoice'    => $invoice,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax
        ];
        return view('accountant.invoice.item', $data);
    }

    public function invoice($id, Request $request) {
        $request->validate([
            'type' => 'required|in:sale,purchase'
        ]);

        $client_id = Auth::user()->client_id;
        $invoice = Invoice::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('vendor')
            ->with($request->input('type'))
            ->first();


        $invoice->creation = Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y');
        $invoice->sale && $invoice->sale->due_date_formatted = empty($invoice->sale->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->sale->due_date))->format('d-M-Y');
        $invoice->purchase && $invoice->purchase->due_date_formatted = empty($invoice->purchase->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->purchase->due_date))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($invoice->total_amount * ($gst/100));
        $subtotal= $invoice->total_amount;

        $data = [
            'title'      => 'Invoice',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'invoice'    => $invoice,
            'currency'   => 'Rs.',
            'client'     => Client::find($client_id),
            'gst'        => $gst,
            'subtotal'   => $subtotal,
            'tax'        => $tax,
            'invoice_type' => $request->input('type')
        ];
        $date = "Invoice-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('accountant.invoice.pdf-invoice', $data);
        return $pdf->download($date);
    }

    public function printInvoice($id, Request $request) {
        $request->validate([
            'type' => 'required|in:sale,purchase'
        ]);

        $client_id = Auth::user()->client_id;
        $invoice = Invoice::where('client_id', $client_id)
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->with('employee')
            ->with('vendor')
            ->with($request->input('type'))
            ->first();

        $invoice->creation = Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('d-M-Y');
        $invoice->sale && $invoice->sale->due_date_formatted = empty($invoice->sale->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->sale->due_date))->format('d-M-Y');
        $invoice->purchase && $invoice->purchase->due_date_formatted = empty($invoice->purchase->due_date)?null:Carbon::createFromTimeStamp(strtotime($invoice->purchase->due_date))->format('d-M-Y');
        $gst = fetchSetting('gst');
        $tax = ($invoice->total_amount * ($gst/100));
        $subtotal= $invoice->total_amount;
        $data = [
            'title'        => 'Invoice',
            'base_url'     => env('APP_URL', 'http://omnibiz.local'),
            'user'         => Auth::user(),
            'invoice'      => $invoice,
            'currency'     => 'Rs.',
            'client'       => Client::find($client_id),
            'gst'          => $gst,
            'subtotal'     => $subtotal,
            'tax'          => $tax,
            'invoice_type' => $request->input('type')
        ];
        return view('accountant.invoice.print-invoice', $data);
    }

    public function delete($id)
    {
        $invoice = Invoice::find($id);
        $invoice->delete();
        return redirect(
            route('invoice.list.accountant')
        );
    }
}
