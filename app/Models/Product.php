<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "products";
    protected $fillable = ['client_id', 'name', 'unit', 'in_stock','unit_price'];

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
    public function category()
    {
        return $this->hasOne(ProductCategoryAssign::class, 'product_id', 'id')->with('category');
    }
}
