<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Purchase extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'purchase_orders';
    protected $fillable = ['client_id','purchase' , 'employee_id', 'invoice_id', 'quotation_id',
        'supplier_name', 'tax_value', 'total_amount', 'updated_by'];

    protected static function booted()
    {
        static::updated(function ($purchase) {
            # Add tax
            $tax = floatval($purchase->tax_value)>0?$purchase->tax_value/100:1;
            $tax_amount = $purchase->total_amount * $tax;
            # Create a transaction
            $transaction = new Transaction([
                'user_id' => Auth::id(),
                'client_id' => $purchase->client_id,
                'invoice_id' => $purchase->invoice_id,
                'transaction_type' => 'purchase',
                'created_at' => $purchase->created_at,
            ]);
            $transaction->save();

            # Add to ledger
            # For sale
            $purchase_ledger = new Ledger([
                'user_id' => Auth::id(),
                'client_id' => $purchase->client_id,
                'account_type' => 'Purchase',
                'transaction_id' => $transaction->id,
                'ledger_entry_type' => 'debit',
                'ledger_amount' => $purchase->total_amount,
                'created_at' => $purchase->created_at,
            ]);
            $purchase_ledger->save();
            # Tax
            $tax_ledger = new Ledger([
                'user_id' => Auth::id(),
                'client_id' => $purchase->client_id,
                'account_type' => 'Purchase Tax Payable',
                'transaction_id' => $transaction->id,
                'ledger_entry_type' => 'credit',
                'ledger_amount' => $tax_amount,
                'created_at' => $purchase->created_at,
            ]);
            $tax_ledger->save();
            # Cash Receivable
            $cash_ledger = new Ledger([
                'user_id' => Auth::id(),
                'client_id' => $purchase->client_id,
                'account_type' => 'Cash',
                'transaction_id' => $transaction->id,
                'ledger_entry_type' => 'credit',
                'ledger_amount' => $purchase->total_amount + $tax_amount,
                'created_at' => $purchase->created_at,
            ]);
            $cash_ledger->save();
        });
        /*static::saving(function ($sale) {
            $tax = fetchSetting('gst') / 100;
            $tax_amount = $sale->total_amount * $tax;
            $sale->total_amount = $sale->total_amount + $tax_amount;
        });*/
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'id', 'quotation_id');
    }

    public function vendor()
    {
        return $this->hasOne(Company::class, 'name', 'supplier_name');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderline::class, 'purchase_id', 'id')->with('product');
    }

    public function products()
    {
        return $this->hasMany(PurchaseOrderline::class, 'purchase_id', 'id')->with('product');
    }


    public static function fetchPurchasesBy($basis='date', $type=false)
    {
        $array = [
            'purchase' => 'fetchPurchasesByDate',
            'date' => 'fetchPurchasesByDate',
            'employee' => 'fetchPurchasesByEmployee',
            'product' => 'fetchPurchasesByProduct',
            'vendor' => 'fetchPurchasesByVendor'
        ];
        $function = $array[$basis];
        return self::{$function}(Auth::user(), $type);
    }

    public static function fetchPurchasesByDate($user, $type=false)
    {
        $purchase_orders = (new self())->getTable();
        $purchase_order_line = (new PurchaseOrderline())->getTable();
        $results = [];
        $concatenated = [];

        $label = '';

        if ($type == 'year') {
            $results = self::select(
                DB::raw("SUM(total_amount) as total"),
                DB::raw("COUNT(*) as counter"),
                DB::raw("DATE_FORMAT(created_at,'%M, %Y') as creation_date")
            )->where("created_at", ">=", Carbon::today()->firstOfYear())
                ->where("client_id", $user->client_id)
                ->groupBy("creation_date")
                ->orderBy("created_at", "ASC")
                ->get();

            $concatenated = [
                'total' => '',
                'colors' => '',
                'counter' => '',
                'creation_date' => ''
            ];
            foreach($results as $k => $i) {
                $concatenated['total'] .= $k>0?",'{$i->total}'":"'{$i->total}'";
                $concatenated['colors'] .= $k>0?"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                $concatenated['counter'] .= $k>0?",'{$i->counter}'":"'{$i->counter}'";
                $concatenated['creation_date'] .= $k>0?",'{$i->creation_date}'":"'{$i->creation_date}'";
            }

            $label = 'Purchases for this Year';
        }

        if ($type == 'month') {
            $results = self::select(
                DB::raw("SUM(total_amount) as total"),
                DB::raw("COUNT(*) as counter"),
                DB::raw("DATE_FORMAT(created_at,'%d %M, %Y') as creation_date")
            )->where("created_at", ">=", Carbon::today()->firstOfMonth())
                ->where("client_id", $user->client_id)
                ->groupBy("creation_date")
                ->orderBy("created_at", "ASC")
                ->get();

            $concatenated = [
                'total' => '',
                'colors' => '',
                'counter' => '',
                'creation_date' => ''
            ];
            foreach($results as $k => $i) {
                $concatenated['total'] .= $k>0?",'{$i->total}'":"'{$i->total}'";
                $concatenated['colors'] .= $k>0?"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                $concatenated['counter'] .= $k>0?",'{$i->counter}'":"'{$i->counter}'";
                $concatenated['creation_date'] .= $k>0?",'{$i->creation_date}'":"'{$i->creation_date}'";
            }

            $label = 'Purchases for this Month';
        }

        if ($type == 'week') {
            $results = self::select(
                DB::raw("SUM(total_amount) as total"),
                DB::raw("COUNT(*) as counter"),
                DB::raw("DATE_FORMAT(created_at,'%M, %Y') as creation_date")
            )
                ->where("created_at", ">=", Carbon::today()->subWeek(7))
                ->where("client_id", $user->client_id)
                ->groupBy("creation_date")
                ->orderBy("created_at", "ASC")
                ->get();

            $concatenated = [
                'total' => '',
                'colors' => '',
                'counter' => '',
                'creation_date' => ''
            ];
            foreach($results as $k => $i) {
                $concatenated['total'] .= $k>0?",'{$i->total}'":"'{$i->total}'";
                $concatenated['colors'] .= $k>0?"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                $concatenated['counter'] .= $k>0?",'{$i->counter}'":"'{$i->counter}'";
                $concatenated['creation_date'] .= $k>0?",'{$i->creation_date}'":"'{$i->creation_date}'";
            }
        }

        if ($type == 'day') {
            $results = self::select(
                "total_amount as total",
                "created_at as creation_date"
            )->where("created_at", ">=", Carbon::today())
                ->where("client_id", $user->client_id)
                ->get();

            $concatenated = [
                'total' => '',
                'colors' => '',
                'creation_date' => ''
            ];
            foreach($results as $k => $i) {
                $concatenated['total'] .= $k>0?",'{$i->total}'":"'{$i->total}'";
                $concatenated['colors'] .= $k>0?"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                $concatenated['creation_date'] .= $k>0?",'{$i->creation_date}'":"'{$i->creation_date}'";
            }

            $label = 'Purchases for Today';
        }

        if ($type == 'day') $type = 'todays';
        return [
            'results' => $results,
            'data' => $concatenated,
            'graph' => self::generateSimpleGraph("#{$type}Graph", $concatenated['creation_date'], $concatenated, $label)
        ];
    }

    public static function fetchPurchasesByEmployee($user, $type=false)
    {
        $purchase_orders = (new self())->getTable();
        $purchase_order_line = (new PurchaseOrderline())->getTable();
        $employees = (new Employee())->getTable();
        $results = [];
        $concatenated = [];

        if ($type == 'year') {
            $results = self::select(
                DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                DB::raw("{$employees}.id as employee_id"),
                DB::raw("CONCAT({$employees}.firstname,' ',{$employees}.lastname) as fullname"),
                DB::raw("COUNT(*) as counter"),
                DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y') as creation_date")
            )->join($employees, "{$employees}.id", "=", "{$purchase_orders}.employee_id")
                ->where("{$purchase_orders}.created_at", ">=", Carbon::today()->firstOfYear())
                ->where("{$purchase_orders}.client_id", $user->client_id)
                ->groupBy(
                    "{$purchase_orders}.employee_id",
                    DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y')")
                )
                ->orderBy("{$purchase_orders}.created_at", "DESC")
                ->get();

            $employee_ids = [];
            foreach($results as $i) {
                $employee_ids[] = $i->employee_id;
            }
            $employee_ids = array_unique($employee_ids);

            # Calculating the dates
            $employee_data = [];
            $_months = '';
            $_added_month = [];
            #foreach ($period as $date) {
            foreach($employee_ids as $employee_id) {
                $employee_info = Employee::find($employee_id);
                $period_from = Carbon::today()->firstOfYear();
                $period_to = Carbon::today()->addHours(23)->addMinutes(59)->addSeconds(59);
                $period = CarbonPeriod::create($period_from, $period_to);
                $done = [];
                $i = 0;
                $_counter = [];
                foreach ($period as $date) {
                    if(in_array($date->format('Y-m'), $done)) continue;
                    $done[] = $date->format('Y-m');
                    $month_start = Carbon::createFromFormat('Y-m-d H:i:s', $date)->firstOfMonth();
                    $month_end = Carbon::createFromFormat('Y-m-d H:i:s', $date)->lastOfMonth()->addHours(23)->addMinutes(59)->addSeconds(59);
                    $month = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F, Y');
                    if (!in_array($month, $_added_month)) {
                        $_months .= "'{$month}',";
                        $_added_month[] = $month;
                    }
                    $purchase_data = self::select(
                        DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                        DB::raw("{$employees}.id as employee_id"),
                        DB::raw("CONCAT({$employees}.firstname,' ',{$employees}.lastname) as fullname"),
                        DB::raw("COUNT(*) as counter"),
                        DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y') as creation_date")
                    )->join($employees, "{$employees}.id", "=", "{$purchase_orders}.employee_id")
                        ->where("{$purchase_orders}.created_at", ">=", $month_start)
                        ->where("{$purchase_orders}.created_at", "<=", $month_end)
                        ->where("{$purchase_orders}.client_id", $user->client_id)
                        ->where("{$purchase_orders}.employee_id", $employee_id)
                        ->groupBy(
                            DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y')")
                        )
                        ->orderBy("{$purchase_orders}.created_at", "DESC")
                        ->first();

                    $counter = isset($purchase_data->counter)?$purchase_data->counter:0;
                    $_counter[$employee_id] = isset($_counter[$employee_id])?$_counter[$employee_id].",{$counter}":$counter;
                    $total = isset($purchase_data->total)?$purchase_data->total:0;
                    $_total[$employee_id] = isset($_total[$employee_id])?$_total[$employee_id].",{$total}":$total;
                }
                $employee_data[] = [
                    'set_label' => "{$employee_info->firstname} {$employee_info->lastname}",
                    'counter' => $_counter[$employee_info->id],
                    'data' => $_total[$employee_info->id]
                ];
            }
            $labels = trim($_months, ",");
            $concatenated = [
                'main_label' => $labels,
                'data' => $employee_data
            ];
        }

        if ($type == 'month') {
            $results = self::select(
                DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                DB::raw("CONCAT({$employees}.firstname,' ',{$employees}.lastname) as fullname"),
                DB::raw("COUNT(*) as counter"),
                DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%d %M, %Y') as creation_date")
            )->join($employees, "{$employees}.id", "=", "{$purchase_orders}.employee_id")
                ->where("{$purchase_orders}.created_at", ">=", Carbon::today()->firstOfMonth())
                ->where("{$purchase_orders}.client_id", $user->client_id)
                ->groupBy(
                    "{$purchase_orders}.employee_id",
                    DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%d %M, %Y')")
                )
                ->orderBy("{$purchase_orders}.created_at", "DESC")
                ->get();

            $concatenated = [
                'data' => '',
                'colors' => '',
                'counter' => '',
                'set_label' => '',
                'main_label' => ''
            ];
            foreach($results as $k => $i) {
                $concatenated['data'] .= $k>0?",'{$i->total}'":"'{$i->total}'";
                $concatenated['colors'] .= $k>0?"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                $concatenated['counter'] .= $k>0?",'{$i->counter}'":"'{$i->counter}'";
                $concatenated['set_label'] .= $k>0?",'{$i->fullname}'":"'{$i->fullname}'";
                $concatenated['main_label'] .= $k>0?",'{$i->creation_date}'":"'{$i->creation_date}'";
            }
        }

        if ($type == 'day') {
            $results = self::select(
                DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                DB::raw("CONCAT({$employees}.firstname,' ',{$employees}.lastname) as fullname"),
                DB::raw("COUNT(*) as counter"),
                DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y') as creation_date")
            )->join($employees, "{$employees}.id", "=", "{$purchase_orders}.employee_id")
                ->where("{$purchase_orders}.created_at", ">=", Carbon::today())
                ->where("{$purchase_orders}.client_id", $user->client_id)
                ->groupBy(
                    "{$purchase_orders}.employee_id",
                    DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y')")
                )
                ->orderBy("{$purchase_orders}.created_at", "DESC")
                ->get();

            $employee_ids = [];
            foreach($results as $i) {
                $employee_ids[] = $i->employee_id;
            }
            $employee_ids = array_unique($employee_ids);

            # Calculating the dates
            $employee_data = [];
            $_months = '';
            $_added_month = [];
            foreach($employee_ids as $employee_id) {
                $employee_info = Employee::find($employee_id);
                if (!$employee_info) break;
                $period_from = Carbon::today()->startOfDay();
                $period_to = Carbon::today()->endOfDay();
                $period = CarbonPeriod::create($period_from, $period_to);
                $done = [];
                $i = 0;
                $_counter = [];
                foreach ($period as $date) {
                    if(in_array($date->format('Y-m'), $done)) continue;
                    $done[] = $date->format('Y-m');
                    $month_start = Carbon::createFromFormat('Y-m-d H:i:s', $date)->firstOfMonth();
                    $month_end = Carbon::createFromFormat('Y-m-d H:i:s', $date)->lastOfMonth()->addHours(23)->addMinutes(59)->addSeconds(59);
                    $month = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F, Y');
                    if (!in_array($month, $_added_month)) {
                        $_months .= "'{$month}',";
                        $_added_month[] = $month;
                    }
                    $purchase_data = self::select(
                        DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                        DB::raw("{$employees}.id as employee_id"),
                        DB::raw("CONCAT({$employees}.firstname,' ',{$employees}.lastname) as fullname"),
                        DB::raw("COUNT(*) as counter"),
                        DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y') as creation_date")
                    )->join($employees, "{$employees}.id", "=", "{$purchase_orders}.employee_id")
                        ->where("{$purchase_orders}.created_at", ">=", $month_start)
                        ->where("{$purchase_orders}.created_at", "<=", $month_end)
                        ->where("{$purchase_orders}.client_id", $user->client_id)
                        ->where("{$purchase_orders}.employee_id", $employee_id)
                        ->groupBy(
                            DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y')")
                        )
                        ->orderBy("{$purchase_orders}.created_at", "DESC")
                        ->first();

                    $counter = isset($purchase_data->counter)?$purchase_data->counter:0;
                    $_counter[$employee_id] = isset($_counter[$employee_id])?$_counter[$employee_id].",{$counter}":$counter;
                    $total = isset($purchase_data->total)?$purchase_data->total:0;
                    $_total[$employee_id] = isset($_total[$employee_id])?$_total[$employee_id].",{$total}":$total;
                }
                $employee_data[] = [
                    'set_label' => isset($employee_info->firstname)?"{$employee_info->firstname} {$employee_info->lastname}":'',
                    'counter' => isset($employee_info->id)?$_counter[$employee_info->id]:'',
                    'data' => isset($employee_info->id)?$_total[$employee_info->id]:''
                ];
            }
            $labels = trim($_months, ",");
            $concatenated = [
                'main_label' => $labels,
                'data' => $employee_data
            ];
        }

        if ($type=='day') $type = 'todays';

        return [
            'results' => $results,
            'data' => $concatenated,
            'graph' => self::generateGraph("#{$type}Graph", $concatenated['main_label'], $concatenated['data'])
        ];
    }

    public static function fetchPurchasesByProduct($user, $type=false)
    {
        $purchase_orders = (new self())->getTable();
        $purchase_order_line = (new PurchaseOrderline())->getTable();
        $products = (new Product())->getTable();
        $results = [];
        $concatenated = [];

        if ($type == 'year') {
            $results = self::select(
                DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                DB::raw("{$purchase_order_line}.product_id as product_id"),
                DB::raw("{$products}.name as product_name"),
                DB::raw("COUNT({$purchase_orders}.id) as counter"),
                DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y') as creation_date")
            )->rightJoin($purchase_order_line, "{$purchase_order_line}.purchase_id", "=", "purchase_orders.id")
                ->leftJoin($products, "{$products}.id", "=", "{$purchase_order_line}.product_id")
                ->where("{$purchase_orders}.created_at", ">=", Carbon::today()->firstOfYear())
                ->where("{$purchase_orders}.client_id", $user->client_id)
                ->groupBy(
                    "{$purchase_order_line}.product_id",
                    DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y')")
                )
                ->orderBy("{$purchase_orders}.created_at", 'ASC')
                ->get();

            $product_ids = [];
            foreach($results as $i) {
                $product_ids[] = $i->product_id;
            }
            $product_ids = array_merge(array_unique($product_ids));
            # Calculating the dates
            $product_data = [];
            $_months = '';
            $_added_month = [];

            foreach($product_ids as $product_id) {
                $product_info = Product::find($product_id);
                $period_from = Carbon::today()->firstOfYear();
                $period_to = Carbon::today()->addHours(23)->addMinutes(59)->addSeconds(59);
                $period = CarbonPeriod::create($period_from, $period_to);
                $months = [];
                $done = [];
                $i = 0;
                $_counter = [];
                foreach ($period as $date) {
                    if(in_array($date->format('Y-m'), $done)) continue;
                    $done[] = $date->format('Y-m');
                    $start = Carbon::createFromFormat('Y-m-d H:i:s', $date)->firstOfMonth();
                    $end = Carbon::createFromFormat('Y-m-d H:i:s', $date)->lastOfMonth()->addHours(23)->addMinutes(59)->addSeconds(59);
                    $month = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F, Y');
                    if (!in_array($month, $_added_month)) {
                        $_months .= "'{$month}',";
                        $_added_month[] = $month;
                    }
                    $_results = self::select(
                        DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                        DB::raw("{$purchase_order_line}.product_id as product_id"),
                        DB::raw("{$products}.name as product_name"),
                        DB::raw("COUNT({$purchase_orders}.id) as counter"),
                        DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y') as creation_date")
                    )->rightJoin($purchase_order_line, "{$purchase_order_line}.purchase_id", "=", "purchase_orders.id")
                        ->leftJoin($products, "{$products}.id", "=", "{$purchase_order_line}.product_id")
                        ->where("{$purchase_orders}.created_at", ">=", $start)
                        ->where("{$purchase_orders}.created_at", "<=", $end)
                        ->where("{$purchase_orders}.client_id", $user->client_id)
                        ->where("{$purchase_order_line}.product_id", $product_id)
                        ->groupBy(
                            DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y')")
                        )
                        ->orderBy("{$purchase_orders}.created_at", 'ASC')
                        ->first();

                    $counter = isset($_results->counter)?$_results->counter:0;
                    $_counter[$product_id] = isset($_counter[$product_id])?$_counter[$product_id].",{$counter}":$counter;
                    $total = isset($_results->total)?$_results->total:0;
                    $_total[$product_id] = isset($_total[$product_id])?$_total[$product_id].",{$total}":$total;
                }
                $product_data[] = [
                    'set_label' => $product_info->name,
                    'counter' => $_counter[$product_info->id],
                    'data' => $_total[$product_info->id]
                ];
                $i++;
            }
            $labels = trim($_months, ",");
            $concatenated = [
                'main_label' => $labels,
                'data' => $product_data
            ];
        }

        if ($type == 'month') {
            $results = self::select(
                DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                DB::raw("{$purchase_order_line}.product_id as product_id"),
                DB::raw("COUNT({$purchase_orders}.id) as counter"),
                DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%d %M, %Y') as creation_date")
            )->rightJoin($purchase_order_line, "{$purchase_order_line}.purchase_id", "=", "{$purchase_orders}.id")
                ->where("{$purchase_orders}.created_at", ">=", Carbon::today()->firstOfMonth())
                ->where("{$purchase_orders}.client_id", $user->client_id)
                ->groupBy(
                    "{$purchase_order_line}.product_id",
                    DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%d %M, %Y')")
                )
                ->orderBy("{$purchase_orders}.created_at", 'ASC')
                ->get();

            $product_ids = [];
            foreach($results as $i) {
                $product_ids[] = $i->product_id;
            }
            $product_ids = array_merge(array_unique($product_ids));

            /*$concatenated = [
                'total' => '',
                'colors' => '',
                'counter' => '',
                'product_id' => '',
                'creation_date' => ''
            ];
            foreach($results as $k => $i) {
                $concatenated['total'] .= $k>0?",'{$i->total}'":"'{$i->total}'";
                $concatenated['colors'] .= $k>0?"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',":"'rgb(".mt_rand(90,230).','.mt_rand(90,230).','.mt_rand(90,230).")',";
                $concatenated['counter'] .= $k>0?",'{$i->counter}'":"'{$i->counter}'";
                $concatenated['product_id'] .= $k>0?",'{$i->product_id}'":"'{$i->product_id}'";
                $concatenated['creation_date'] .= $k>0?",'{$i->creation_date}'":"'{$i->creation_date}'";
            }*/

            # Calculating the dates
            $product_data = [];
            $_months = '';
            $_added_month = [];

            foreach($product_ids as $product_id) {
                $product_info = Product::find($product_id);
                $period_from = Carbon::today()->firstOfMonth();
                $period_to = Carbon::today()->addHours(23)->addMinutes(59)->addSeconds(59);
                $period = CarbonPeriod::create($period_from, $period_to);
                $months = [];
                $done = [];
                $i = 0;
                $_counter = [];
                foreach ($period as $date) {
                    if(in_array($date->format('m-d'), $done)) continue;
                    $done[] = $date->format('m-d');
                    $start = Carbon::createFromFormat('Y-m-d H:i:s', $date)->firstOfMonth();
                    $end = Carbon::createFromFormat('Y-m-d H:i:s', $date)->lastOfMonth()->addHours(23)->addMinutes(59)->addSeconds(59);
                    $month = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d F');
                    if (!in_array($month, $_added_month)) {
                        $_months .= "'{$month}',";
                        $_added_month[] = $month;
                    }
                    $_results = self::select(
                        DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                        DB::raw("{$purchase_order_line}.product_id as product_id"),
                        DB::raw("{$products}.name as product_name"),
                        DB::raw("COUNT({$purchase_orders}.id) as counter"),
                        DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%d %M') as creation_date")
                    )->rightJoin($purchase_order_line, "{$purchase_order_line}.purchase_id", "=", "purchase_orders.id")
                        ->leftJoin($products, "{$products}.id", "=", "{$purchase_order_line}.product_id")
                        ->where("{$purchase_orders}.created_at", ">=", $start)
                        ->where("{$purchase_orders}.created_at", "<=", $end)
                        ->where("{$purchase_orders}.client_id", $user->client_id)
                        ->where("{$purchase_order_line}.product_id", $product_id)
                        ->groupBy(
                            DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%d %M')")
                        )
                        ->orderBy("{$purchase_orders}.created_at", 'ASC')
                        ->first();

                    $counter = isset($_results->counter)?$_results->counter:0;
                    $_counter[$product_id] = isset($_counter[$product_id])?$_counter[$product_id].",{$counter}":$counter;
                    $total = isset($_results->total)?$_results->total:0;
                    $_total[$product_id] = isset($_total[$product_id])?$_total[$product_id].",{$total}":$total;
                }
                $product_data[] = [
                    'set_label' => $product_info->name,
                    'counter' => $_counter[$product_info->id],
                    'data' => $_total[$product_info->id]
                ];
                $i++;
            }
            $labels = trim($_months, ",");
            $concatenated = [
                'main_label' => $labels,
                'data' => $product_data
            ];
        }

        # if ($type == 'week') {}

        if ($type == 'day') {
            $results = self::select(
                DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                DB::raw("{$purchase_order_line}.product_id as product_id"),
                DB::raw("COUNT({$purchase_orders}.id) as counter"),
                DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%d %M, %Y') as creation_date")
            )->rightJoin($purchase_order_line, "{$purchase_order_line}.purchase_id", "=", "{$purchase_orders}.id")
                ->where("{$purchase_orders}.created_at", ">=", Carbon::today())
                ->where("{$purchase_orders}.client_id", $user->client_id)
                ->groupBy("{$purchase_order_line}.product_id")
                ->orderBy("{$purchase_orders}.created_at", 'ASC')
                ->get();

            $product_ids = [];
            foreach($results as $i) {
                $product_ids[] = $i->product_id;
            }
            $product_ids = array_merge(array_unique($product_ids));

            # Calculating the dates
            $product_data = [];
            $_months = '';
            $_added_month = [];

            foreach($product_ids as $product_id) {
                $product_info = Product::find($product_id);
                $period_from = Carbon::today()->startOfDay();
                $period_to = Carbon::today()->endOfDay();
                $period = CarbonPeriod::create($period_from, $period_to);
                $months = [];
                $done = [];
                $i = 0;
                $_counter = [];
                foreach ($period as $date) {
                    if(in_array($date->format('Y-m-d H:i:s'), $done)) continue;
                    $done[] = $date->format('Y-m-d H:i:s');
                    $start = Carbon::createFromFormat('Y-m-d H:i:s', $date)->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d H:i:s', $date)->endOfDay();
                    $month = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i');
                    if (!in_array($month, $_added_month)) {
                        $_months .= "'{$month}',";
                        $_added_month[] = $month;
                    }
                    $_results = self::select(
                        DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                        DB::raw("{$purchase_order_line}.product_id as product_id"),
                        DB::raw("{$products}.name as product_name"),
                        DB::raw("COUNT({$purchase_orders}.id) as counter"),
                        DB::raw("{$purchase_orders}.created_at as creation_date")
                    )->rightJoin($purchase_order_line, "{$purchase_order_line}.purchase_id", "=", "purchase_orders.id")
                        ->leftJoin($products, "{$products}.id", "=", "{$purchase_order_line}.product_id")
                        ->where("{$purchase_orders}.created_at", ">=", $start)
                        ->where("{$purchase_orders}.created_at", "<=", $end)
                        ->where("{$purchase_orders}.client_id", $user->client_id)
                        ->where("{$purchase_order_line}.product_id", $product_id)
                        ->groupBy(
                            DB::raw("{$purchase_orders}.created_at")
                        )
                        ->orderBy("{$purchase_orders}.created_at", 'ASC')
                        ->first();

                    $counter = isset($_results->counter)?$_results->counter:0;
                    $_counter[$product_id] = isset($_counter[$product_id])?$_counter[$product_id].",{$counter}":$counter;
                    $total = isset($_results->total)?$_results->total:0;
                    $_total[$product_id] = isset($_total[$product_id])?$_total[$product_id].",{$total}":$total;
                }
                $product_data[] = [
                    'set_label' => $product_info->name,
                    'counter' => $_counter[$product_info->id],
                    'data' => $_total[$product_info->id]
                ];
                $i++;
            }


            $labels = trim($_months, ",");
            $concatenated = [
                'main_label' => $labels,
                'data' => $product_data
            ];
        }

        if ($type=='day') $type='todays';

        return [
            'results' => $results,
            'data' => $concatenated,
            'graph' => self::generateGraph("#{$type}Graph", $concatenated['main_label'], $concatenated['data'])
        ];
    }

    public static function fetchpurchasesByVendor($user, $type=false)
    {
        $purchase_orders = (new self())->getTable();
        $purchase_order_line = (new purchaseOrderline())->getTable();
        $companies = (new Company())->getTable();
        $results = [];
        $concatenated = [];

        if ($type == 'year') {
            $results = self::select(
                DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                DB::raw("{$companies}.name as company"),
                DB::raw("{$companies}.id as company_id"),
                DB::raw("COUNT({$purchase_orders}.id) as counter"),
                DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y') as creation_date")
            )
                ->rightJoin($companies, "{$companies}.name", "=", "{$purchase_orders}.supplier_name")
                ->where("{$purchase_orders}.created_at", ">=", Carbon::today()->firstOfYear())
                ->where("{$purchase_orders}.client_id", $user->client_id)
                ->groupBy("{$companies}.name")
                ->orderBy("{$purchase_orders}.created_at", 'ASC')
                ->get();

            $company_ids = [];
            foreach($results as $i) {
                $company_ids[] = $i->company_id;
            }
            $company_ids = array_merge(array_unique($company_ids));
            # Calculating the dates
            $product_data = [];
            $_months = '';
            $_added_month = [];

            foreach($company_ids as $company_id) {
                $company_info = Company::find($company_id);
                $period_from = Carbon::today()->firstOfYear();
                $period_to = Carbon::today()->addHours(23)->addMinutes(59)->addSeconds(59);
                $period = CarbonPeriod::create($period_from, $period_to);
                $months = [];
                $done = [];
                $i = 0;
                $_counter = [];
                foreach ($period as $date) {
                    if(in_array($date->format('Y-m'), $done)) continue;
                    $done[] = $date->format('Y-m');
                    $month_start = Carbon::createFromFormat('Y-m-d H:i:s', $date)->firstOfMonth();
                    $month_end = Carbon::createFromFormat('Y-m-d H:i:s', $date)->lastOfMonth()->addHours(23)->addMinutes(59)->addSeconds(59);
                    $month = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F, Y');
                    if (!in_array($month, $_added_month)) {
                        $_months .= "'{$month}',";
                        $_added_month[] = $month;
                    }
                    $_results = self::select(
                        DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                        DB::raw("{$companies}.name as name"),
                        DB::raw("COUNT({$purchase_orders}.id) as counter"),
                        DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%M, %Y') as creation_date")
                    )
                        ->rightJoin($companies, "{$companies}.name", "=", "{$purchase_orders}.supplier_name")
                        ->where("{$purchase_orders}.created_at", ">=", $month_start)
                        ->where("{$purchase_orders}.created_at", "<=", $month_end)
                        ->where("{$purchase_orders}.client_id", $user->client_id)
                        ->where("{$companies}.id", $company_id)
                        ->groupBy("{$companies}.name")
                        ->orderBy("{$purchase_orders}.created_at", 'ASC')
                        ->first();

                    $counter = isset($_results->counter)?$_results->counter:0;
                    $_counter[$company_id] = isset($_counter[$company_id])?$_counter[$company_id].",{$counter}":$counter;
                    $total = isset($_results->total)?$_results->total:0;
                    $_total[$company_id] = isset($_total[$company_id])?$_total[$company_id].",{$total}":$total;
                }
                $product_data[] = [
                    'set_label' => $company_info->name,
                    'counter' => $_counter[$company_info->id],
                    'data' => $_total[$company_info->id]
                ];
                $i++;
            }
            $labels = trim($_months, ",");
            $concatenated = [
                'main_label' => $labels,
                'data' => $product_data,
            ];
        }

        if ($type == 'month') {
            $results = self::select(
                DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                DB::raw("{$companies}.name as company"),
                DB::raw("COUNT({$purchase_orders}.id) as counter"),
                DB::raw("DATE_FORMAT({$purchase_orders}.created_at,'%d %M, %Y') as creation_date")
            )
                ->rightJoin($companies, "{$companies}.name", "=", "{$purchase_orders}.supplier_name")
                ->where("{$purchase_orders}.created_at", ">=", Carbon::today()->firstOfMonth())
                ->where("{$purchase_orders}.client_id", $user->client_id)
                ->groupBy("{$companies}.name")
                ->orderBy("{$purchase_orders}.created_at", 'ASC')
                ->get();
        }

        if ($type == 'day') {
            $results = self::select(
                DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                DB::raw("{$companies}.name as company"),
                DB::raw("{$companies}.id as company_id"),
                DB::raw("COUNT({$purchase_orders}.id) as counter"),
                DB::raw("{$purchase_orders}.created_at,'%M, %Y' as creation_date")
            )
                ->rightJoin($companies, "{$companies}.name", "=", "{$purchase_orders}.supplier_name")
                ->where("{$purchase_orders}.created_at", ">=", Carbon::today())
                ->where("{$purchase_orders}.client_id", $user->client_id)
                ->groupBy("{$companies}.name")
                ->orderBy("{$purchase_orders}.created_at", 'ASC')
                ->get();

            $company_ids = [];
            foreach($results as $i) {
                $company_ids[] = $i->company_id;
            }
            $company_ids = array_merge(array_unique($company_ids));
            # Calculating the dates
            $product_data = [];
            $_months = '';
            $_added_month = [];

            foreach($company_ids as $company_id) {
                $company_info = Company::find($company_id);
                $period_from = Carbon::today()->startOfDay();
                $period_to = Carbon::today()->endOfDay();
                $period = CarbonPeriod::create($period_from, $period_to);
                $months = [];
                $done = [];
                $i = 0;
                $_counter = [];
                foreach ($period as $date) {
                    if(in_array($date->format('Y-m'), $done)) continue;
                    $done[] = $date->format('Y-m');
                    $month_start = Carbon::createFromFormat('Y-m-d H:i:s', $date)->firstOfMonth();
                    $month_end = Carbon::createFromFormat('Y-m-d H:i:s', $date)->lastOfMonth()->addHours(23)->addMinutes(59)->addSeconds(59);
                    $month = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F, Y');
                    if (!in_array($month, $_added_month)) {
                        $_months .= "'{$month}',";
                        $_added_month[] = $month;
                    }
                    $_results = self::select(
                        DB::raw("SUM({$purchase_orders}.total_amount) as total"),
                        DB::raw("{$companies}.name as name"),
                        DB::raw("COUNT({$purchase_orders}.id) as counter"),
                        DB::raw("{$purchase_orders}.created_at as creation_date")
                    )
                        ->rightJoin($companies, "{$companies}.name", "=", "{$purchase_orders}.supplier_name")
                        ->where("{$purchase_orders}.created_at", ">=", $month_start)
                        ->where("{$purchase_orders}.created_at", "<=", $month_end)
                        ->where("{$purchase_orders}.client_id", $user->client_id)
                        ->where("{$companies}.id", $company_id)
                        ->groupBy("{$companies}.name")
                        ->orderBy("{$purchase_orders}.created_at", 'ASC')
                        ->first();

                    $counter = isset($_results->counter)?$_results->counter:0;
                    $_counter[$company_id] = isset($_counter[$company_id])?$_counter[$company_id].",{$counter}":$counter;
                    $total = isset($_results->total)?$_results->total:0;
                    $_total[$company_id] = isset($_total[$company_id])?$_total[$company_id].",{$total}":$total;
                }
                $product_data[] = [
                    'set_label' => $company_info->name,
                    'counter' => $_counter[$company_info->id],
                    'data' => $_total[$company_info->id]
                ];
                $i++;
            }
            $labels = trim($_months, ",");
            $concatenated = [
                'main_label' => $labels,
                'data' => $product_data,
            ];
        }

        if ($type == 'day') $type = 'todays';

        return [
            'results' => $results,
            'data' => $concatenated,
            'graph' => self::generateGraph("#{$type}Graph", $concatenated['main_label'], $concatenated['data'])
        ];
    }

    public static function generateGraph($target, $labels, $datasets=[], $graph_name=false)
    {
        $graph_name || $graph_name = Uuid::uuid4()->getHex();
        # Initiate variables
        $set = [];
        # Process datasets
        if (empty($datasets)) return '';
        #dd($datasets);
        foreach($datasets as $dataset){
            $var = Uuid::uuid4()->getHex();
            $randomName = substr($var,0,mt_rand(0,9));
            $red = mt_rand(rand(0,120),180);
            $green = mt_rand(rand(50,120),180);
            $blue = mt_rand(rand(80,90),180);
            $set[] = "
            {
                label: '{$dataset['set_label']}',
                data: [{$dataset['data']}],
                borderColor: 'rgb({$red},{$green},{$blue})',
                backgroundColor: 'rgba({$red},{$green},{$blue},0.8)',
                type: 'bar'
            }
            ";
        }

        $datasets = implode(",",array_filter($set));

        $graph = "
        let target{$graph_name} = $('{$target}').get(0).getContext('2d');

        let graph{$graph_name} = new Chart(target{$graph_name}, {
            type: 'bar',
            data: {
                labels: [{$labels}],
                datasets: [{$datasets}]
            },
            options: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                maintainAspectRatio: false,
                responsive: true,
            },
            scales: {y: {stacked: true}}
        });
        ";

        return self::reformat($graph);
    }

    public static function generateSimpleGraph($target, $labels, $dataset, $graph_name='Monthly purchases')
    {
        $graph_name || $graph_name = Uuid::uuid4()->getHex();

        $gname = Uuid::uuid4()->getHex();

        $red = mt_rand(rand(0,120),180);
        $green = mt_rand(rand(50,120),180);
        $blue = mt_rand(rand(80,90),180);

        $graph = "
        let target{$gname} = $('{$target}').get(0).getContext('2d');

        let graph{$gname} = new Chart(target{$gname}, {
            type: 'bar',
            data: {
                labels: [{$labels}],
                datasets: [
                    {
                        label: '{$graph_name}',
                        data: [{$dataset['total']}],
                        borderColor: 'rgb({$red},{$green},{$blue})',
                backgroundColor: 'rgba({$red},{$green},{$blue},0.8)',
                        type: 'bar'
                    }
                ]
            },
            options: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                maintainAspectRatio: false,
                responsive: true,
            },
            scales: {y: {stacked: true}}
        });
        ";

        return self::reformat($graph);
    }

    private static function reformat($str)
    {
        return preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", $str));
    }

}
