<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "product_categories";
    protected $fillable = ['client_id', 'title'];

    public function Client()
    {
        return $this->hasMany(Client::class, 'id', 'client_id');
    }

}

