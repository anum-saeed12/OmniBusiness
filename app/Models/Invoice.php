<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'invoices';
    protected $fillable = ['client_id', 'employee_id', 'product_supplier_and_buyer', 'total_amount', 'updated_by'];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id')->with('product');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
    public function sale()
    {
        return $this->hasOne(Sale::class, 'invoice_id', 'id');
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'invoice_id', 'id');
    }

    public function vendor()
    {
        return $this->hasOne(Company::class, 'name', 'buyer_name');
    }
}
