<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'invoice_items';
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'unit_price', 'total_price'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }
}
