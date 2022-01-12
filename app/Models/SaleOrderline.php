<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleOrderline extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sale_order_line';
    protected $fillable = ['product_id', 'sale_id', 'quantity', 'unit_price', 'total_price'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function sale()
    {
        return $this->hasOne(Sale::class, 'id', 'sale_id');
    }

}
