<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartOfAccounting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "charts_of_accounting";
    protected $fillable = ['transaction_type', 'account_type', 'amount', 'invoice_id', 'transaction'];

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'invoice_id', 'id');
    }

    public static function add($transaction_type='sale', $account_type, $amount, $sale_id, $transaction)
    {
        $insert = new self();
        $insert->transaction_type = $transaction_type;
        $insert->account_type = $account_type;
        $insert->amount = $amount;
        $insert->invoice = $sale_id;
        $insert->transaction = $transaction=='debit'?'debit':'credit';
        $insert->save();

        return $insert;
    }

    public static function record($data,$tax_included=false)
    {
        $data = (Object) $data;
        $tax_included = isset($data->tax_included) && $data->tax_included == true;
        $tax = $data->total * ($data->tax / 100);
        $amount = $tax_included == true ? $data->total : $data->total + $tax;
        $revenue = $tax_included == false ? $data->total : $data->total - $tax;
        $records = [];

        if ($data->type == 'sale') {
            $records = [
                ['transaction_type' => $data->type, 'account_type' => 'Receivable',      'amount' => $amount,  'invoice_id' => $data->invoice_id, 'transaction' => 'credit'],
                ['transaction_type' => $data->type, 'account_type' => 'SalesTaxPayable', 'amount' => $tax,     'invoice_id' => $data->invoice_id, 'transaction' => 'debit'],
                ['transaction_type' => $data->type, 'account_type' => 'Revenue',         'amount' => $revenue, 'invoice_id' => $data->invoice_id, 'transaction' => 'credit'],
            ];
        }

        if ($data->type == 'purchase') {
            /*$records = [
                ['transaction_type' => $data->type, 'account_type' => 'Receivable', $amount, $data->invoice_id, 'credit'],
                ['transaction_type' => $data->type, 'account_type' => 'SalesTaxPayable', $tax, $data->invoice_id, 'debit'],
                ['transaction_type' => $data->type, 'account_type' => 'Revenue', $revenue, $data->invoice_id, 'credit'],
            ];*/
        }
        self::insert($records);
    }
}
