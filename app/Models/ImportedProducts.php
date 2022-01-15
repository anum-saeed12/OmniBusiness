<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedProducts extends Model
{
    use HasFactory;
    protected $table = 'imported_products';
    protected $fillable = ['batch_id', 'client_id', 'name', 'category_title', 'unit', 'in_stock', 'unit_price'];
}
