<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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

    public static function add($category_title)
    {
        $new_category = new self();
        $new_category->title = $category_title;
        $new_category->client_id = Auth::id();
        $new_category->save();
        return $new_category->id;
    }

}

