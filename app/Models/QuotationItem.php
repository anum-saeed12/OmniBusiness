<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'quotation_items';
    protected $fillable = ['quotation_id', 'product_id', 'quantity', 'unit_price','total_price','original_unit_price','previous_quantity',
                            'original_total_price','discount'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'id', 'quotation_id');
    }

}
