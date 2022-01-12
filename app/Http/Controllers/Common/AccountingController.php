<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Ledger;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    /**
     * Ledgers
     * @param Request $request
     * @return Mixed
     */
    public function ledger(Request $request)
    {
        $ledger_records = [];
        $select = [
            "ledgers.user_id",
            "ledgers.client_id",
            "ledgers.transaction_id",
            "ledgers.account_type",
            "ledgers.ledger_entry_type",
            "ledgers.ledger_amount",
            "ledgers.created_at",
            DB::raw("(CASE
                WHEN transactions.transaction_type = 'purchase'
                THEN purchase_orders.supplier_name
                ELSE sale_orders.buyer_name
            END) as affiliate_name"),
        ];
        $ledger_records = Ledger::select($select)
            ->leftJoin('transactions','transactions.id','=','ledgers.transaction_id')
            ->leftJoin('sale_orders','sale_orders.invoice_id','=','transactions.invoice_id')
            ->leftJoin('purchase_orders','purchase_orders.invoice_id','=','transactions.invoice_id')
            ->where('ledgers.client_id',Auth::user()->client_id)
            ->orderBy('ledgers.created_at','ASC');
        # Get total credit
        $ledger_credit = Ledger::select([
                DB::raw('SUM(ledgers.ledger_amount) as credit')
            ])
            ->leftJoin('transactions','transactions.id','=','ledgers.transaction_id')
            ->leftJoin('sale_orders','sale_orders.invoice_id','=','transactions.invoice_id')
            ->leftJoin('purchase_orders','purchase_orders.invoice_id','=','transactions.invoice_id')
            ->where('ledgers.client_id',Auth::user()->client_id)
            ->where('ledgers.ledger_entry_type','credit')
            ->groupBy('ledgers.ledger_entry_type')
            ->orderBy('ledgers.created_at','ASC');
        # Get total debit
        $ledger_debit = Ledger::select([
                DB::raw('SUM(ledgers.ledger_amount) as debit')
            ])
            ->leftJoin('transactions','transactions.id','=','ledgers.transaction_id')
            ->leftJoin('sale_orders','sale_orders.invoice_id','=','transactions.invoice_id')
            ->leftJoin('purchase_orders','purchase_orders.invoice_id','=','transactions.invoice_id')
            ->where('ledgers.client_id',Auth::user()->client_id)
            ->where('ledgers.ledger_entry_type','debit')
            ->groupBy('ledgers.ledger_entry_type')
            ->orderBy('ledgers.created_at','ASC');

        # Apply account type filter
        if ($request->has('account') && !empty($request->account)) $ledger_records = $ledger_records->where('ledgers.account_type',$request->input('account'));
        if ($request->has('account') && !empty($request->account)) $ledger_credit = $ledger_credit->where('ledgers.account_type',$request->input('account'));
        if ($request->has('account') && !empty($request->account)) $ledger_debit = $ledger_debit->where('ledgers.account_type',$request->input('account'));
        # Apply start date filter
        $start_date = ($request->has('start') && !empty($request->start))?Carbon::createFromDate($request->input('start'))->startOfDay():'';
        if ($request->has('start') && !empty($request->start)) $ledger_records = $ledger_records->where('ledgers.created_at',">=",$start_date);
        if ($request->has('start') && !empty($request->start)) $ledger_credit = $ledger_credit->where('ledgers.created_at',">=",$start_date);
        if ($request->has('start') && !empty($request->start)) $ledger_debit = $ledger_debit->where('ledgers.created_at',">=",$start_date);
        # Apply end date filter
        $end_date = ($request->has('end') && !empty($request->end))?Carbon::createFromDate($request->input('end'))->endOfDay():'';
        if ($request->has('end') && !empty($request->end)) $ledger_records = $ledger_records->where('ledgers.created_at',"<=",$end_date);
        if ($request->has('end') && !empty($request->end)) $ledger_credit = $ledger_credit->where('ledgers.created_at',"<=",$end_date);
        if ($request->has('end') && !empty($request->end)) $ledger_debit = $ledger_debit->where('ledgers.created_at',"<=",$end_date);

        $ledger_records = $ledger_records->paginate($this->count);
        $ledger_credit = $ledger_credit->first();
        $ledger_debit = $ledger_debit->first();

        $account_types = Ledger::select(DB::raw('DISTINCT account_type as name'))->orderBy('account_type','ASC')->get();

        $ledger_credit = isset($ledger_credit->credit)?floatval($ledger_credit->credit):floatval(0);
        $ledger_debit = isset($ledger_debit->debit)?floatval($ledger_debit->debit):floatval(0);

        $client = Client::find(Auth::user()->client_id);

        $data = [
            'title'   => "{$client->name} Ledger Records",
            'user'    => Auth::user(),
            'account_types' => $account_types,
            'total_credit' => $ledger_credit,
            'total_debit' => $ledger_debit,
            'ledger_records' => $ledger_records,
            'client' => $client,
            'currency' => $request->currency?$request->currency:'PKR'
        ];
        return view('accounting.ledger',$data);
    }

    /**
     * Balance Sheet
     * @param Request $request
     * @return Mixed
     */
    public function balanceSheet(Request $request)
    {
        $select_inventory = [
            DB::raw("(
                SUM(purchase_order_line.unit_price * purchase_order_line.quantity) -
                SUM(sale_order_line.unit_price * sale_order_line.quantity)
            ) as value")
        ];
        # Calculate inventory
        $inventory = Product::select($select_inventory)
            ->leftJoin('purchase_order_line','purchase_order_line.product_id','=','products.id')
            ->leftJoin('sale_order_line','sale_order_line.product_id','=','products.id')
            ->where('products.client_id',Auth::user()->client_id)
            ->groupBy('products.client_id');

        # Get total credit
        $ledger_credit = Ledger::select(DB::raw('SUM(ledgers.ledger_amount) as credit'))
            ->leftJoin('transactions','transactions.id','=','ledgers.transaction_id')
            ->leftJoin('sale_orders','sale_orders.invoice_id','=','transactions.invoice_id')
            ->leftJoin('purchase_orders','purchase_orders.invoice_id','=','transactions.invoice_id')
            ->where('ledgers.client_id',Auth::user()->client_id)
            ->where('ledgers.ledger_entry_type','credit')
            ->groupBy('ledgers.ledger_entry_type')
            ->orderBy('ledgers.created_at','ASC');

        # Get total debit
        $ledger_debit = Ledger::select(DB::raw('SUM(ledgers.ledger_amount) as debit'))
            ->leftJoin('transactions','transactions.id','=','ledgers.transaction_id')
            ->leftJoin('sale_orders','sale_orders.invoice_id','=','transactions.invoice_id')
            ->leftJoin('purchase_orders','purchase_orders.invoice_id','=','transactions.invoice_id')
            ->where('ledgers.client_id',Auth::user()->client_id)
            ->where('ledgers.ledger_entry_type','debit')
            ->groupBy('ledgers.ledger_entry_type')
            ->orderBy('ledgers.created_at','ASC');


        # Apply start date filter
        $start_date = ($request->has('start') && !empty($request->start))?Carbon::createFromDate($request->input('start'))->startOfDay():'';
        if ($request->has('start') && !empty($request->start)) {
            $inventory = $inventory
                ->where('purchase_order_line.created_at',">=",$start_date)
                ->where('sale_order_line.created_at',">=",$start_date);
            $ledger_credit = $ledger_credit->where('ledgers.created_at',">=",$start_date);
            $ledger_debit = $ledger_debit->where('ledgers.created_at',">=",$start_date);
        } else {
            $inventory = $inventory
                ->where('purchase_order_line.created_at',">=",Carbon::now()->firstOfYear())
                ->where('sale_order_line.created_at',">=",Carbon::now()->firstOfYear());
            $ledger_credit = $ledger_credit->where('ledgers.created_at',">=",Carbon::now()->firstOfYear());
            $ledger_debit = $ledger_debit->where('ledgers.created_at',">=",Carbon::now()->firstOfYear());
        }


        # Apply end date filter
        $end_date = ($request->has('end') && !empty($request->end))?Carbon::createFromDate($request->input('end'))->endOfDay():'';
        if ($request->has('end') && !empty($request->end)) {
            $inventory = $inventory
                ->where('purchase_order_line.created_at',"<=",$end_date)
                ->where('sale_order_line.created_at',"<=",$end_date);
            $ledger_credit = $ledger_credit->where('ledgers.created_at',"<=",$end_date);
            $ledger_debit = $ledger_debit->where('ledgers.created_at',"<=",$end_date);
        }

        $inventory = $inventory->first();
        $ledger_credit = $ledger_credit->first();
        $ledger_debit = $ledger_debit->first();
        $ledger_credit = isset($ledger_credit->credit)?floatval($ledger_credit->credit):floatval(0);
        if (!isset($inventory->value)) $inventory = (Object) ['value' => 0];
        $ledger_debit = isset($ledger_debit->debit)?floatval($ledger_debit->debit):floatval(0);

        $client = Client::find(Auth::user()->client_id);

        $data = [
            'title'   => "{$client->name} Balance Sheet",
            'user'    => Auth::user(),
            'inventory' => $inventory,
            'ledger_debit' => $ledger_debit,
            'ledger_credit' => $ledger_credit,
            'client' => $client,
            'currency' => $request->currency?$request->currency:'PKR'
        ];
        return view('accounting.balance-sheet',$data);
    }

    /**
     * Income Statement
     * @param Request $request
     * @return Mixed
     */
    public function incomeStatement(Request $request)
    {
        $select_inventory = [
            'sale_order_line.product_id as sale_product_id',
            'sale_order_line.quantity as sale_quantity',
            'sale_order_line.unit_price as sale_unit_price',
            'purchase_order_line.product_id as purchase_product_id',
            'purchase_order_line.quantity as purchase_quantity',
            'purchase_order_line.unit_price as purchase_unit_price',
            /*DB::raw("SUM(
                sale_order_line.quantity * purchase_order_line.unit_price
            ) as cogs")*/
        ];
        # Calculate inventory
        $inventory = Product::select($select_inventory)
            ->join('purchase_order_line','purchase_order_line.product_id','=','products.id')
            ->join('sale_order_line','sale_order_line.product_id','=','products.id')
            ->where('products.client_id',Auth::user()->client_id)
            ->groupBy('products.client_id');
        # Calculate sales
        $revenue = Sale::select(DB::raw("SUM(total_amount) as amount"))
            ->where('sale_orders.client_id', Auth::user()->client_id)
            ->groupBy('sale_orders.client_id');

        # Get total credit
        $ledger_credit = Ledger::select(DB::raw('SUM(ledgers.ledger_amount) as credit'))
            ->leftJoin('transactions','transactions.id','=','ledgers.transaction_id')
            ->leftJoin('sale_orders','sale_orders.invoice_id','=','transactions.invoice_id')
            ->leftJoin('purchase_orders','purchase_orders.invoice_id','=','transactions.invoice_id')
            ->where('ledgers.client_id',Auth::user()->client_id)
            ->where('ledgers.ledger_entry_type','credit')
            ->groupBy('ledgers.ledger_entry_type')
            ->orderBy('ledgers.created_at','ASC');

        # Get total debit
        $ledger_debit = Ledger::select(DB::raw('SUM(ledgers.ledger_amount) as debit'))
            ->leftJoin('transactions','transactions.id','=','ledgers.transaction_id')
            ->leftJoin('sale_orders','sale_orders.invoice_id','=','transactions.invoice_id')
            ->leftJoin('purchase_orders','purchase_orders.invoice_id','=','transactions.invoice_id')
            ->where('ledgers.client_id',Auth::user()->client_id)
            ->where('ledgers.ledger_entry_type','debit')
            ->groupBy('ledgers.ledger_entry_type')
            ->orderBy('ledgers.created_at','ASC');


        # Apply start date filter
        $start_date = ($request->has('start') && !empty($request->start))?Carbon::createFromDate($request->input('start'))->startOfDay():'';
        if ($request->has('start') && !empty($request->start)) {
            $inventory = $inventory
                ->where('purchase_order_line.created_at',">=",$start_date)
                ->where('sale_order_line.created_at',">=",$start_date);
            $ledger_credit = $ledger_credit->where('ledgers.created_at',">=",$start_date);
            $ledger_debit = $ledger_debit->where('ledgers.created_at',">=",$start_date);
            $revenue = $revenue->where('sale_orders.created_at',">=",$start_date);
        } else {
            $inventory = $inventory
                ->where('purchase_order_line.created_at',">=",Carbon::now()->firstOfYear())
                ->where('sale_order_line.created_at',">=",Carbon::now()->firstOfYear());
            $ledger_credit = $ledger_credit->where('ledgers.created_at',">=",Carbon::now()->firstOfYear());
            $ledger_debit = $ledger_debit->where('ledgers.created_at',">=",Carbon::now()->firstOfYear());
            $revenue = $revenue->where('sale_orders.created_at',">=",Carbon::now()->firstOfYear());
        }


        # Apply end date filter
        $end_date = ($request->has('end') && !empty($request->end))?Carbon::createFromDate($request->input('end'))->endOfDay():'';
        if ($request->has('end') && !empty($request->end)) {
            $inventory = $inventory
                ->where('purchase_order_line.created_at',"<=",$end_date)
                ->where('sale_order_line.created_at',"<=",$end_date);
            $ledger_credit = $ledger_credit->where('ledgers.created_at',"<=",$end_date);
            $ledger_debit = $ledger_debit->where('ledgers.created_at',"<=",$end_date);
            $revenue = $revenue->where('sale_orders.created_at',"<=",$end_date);
        }
        # Process the products
        #$inventory = $inventory->first();
        $items = $inventory->get();
        $inventory = (Object) ['cogs' => 0];
        if ($items->count() > 0) {
            foreach($items as $item) {
                $inventory->cogs += abs($item->sale_quantity * $item->purchase_unit_price);
            }
        }

        $ledger_credit = $ledger_credit->first();
        $ledger_debit = $ledger_debit->first();
        $revenue = $revenue->first();
        if (!isset($inventory->cogs)) $inventory = (Object) ['cogs' => 0];
        if (!isset($revenue->amount)) $revenue = (Object) ['amount' => 0];
        $ledger_credit = isset($ledger_credit->credit)?floatval($ledger_credit->credit):floatval(0);
        $ledger_debit = isset($ledger_debit->debit)?floatval($ledger_debit->debit):floatval(0);

        $client = Client::find(Auth::user()->client_id);

        $data = [
            'title'   => "{$client->name} Income Statement",
            'user'    => Auth::user(),
            'inventory' => $inventory,
            'ledger_debit' => $ledger_debit,
            'ledger_credit' => $ledger_credit,
            'revenue' => $revenue,
            'client' => $client,
            'currency' => $request->currency?$request->currency:'PKR'
        ];
        return view('accounting.income-statement',$data);
    }
}
