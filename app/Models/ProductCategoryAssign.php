<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProductCategoryAssign extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "product_categories_assigned";
    protected $fillable = ['category_id', 'product_id', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }
    public function product()
   {
       return $this->hasMany(Product::class, 'id', 'product_id');
   }

    public function products()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

}
