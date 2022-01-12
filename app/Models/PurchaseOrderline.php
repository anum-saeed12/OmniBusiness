<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderline extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'purchase_order_line';
    protected $fillable = ['product_id', 'purchase_id', 'quantity', 'unit_price', 'total_price',
        'unit', 'purchase_due_at', 'purchase_order_at'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'id', 'purchase_id');
    }

}
