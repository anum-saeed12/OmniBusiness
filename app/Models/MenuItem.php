<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory;
    protected $table = "menu_items";
    public $timestamps = false;

    public function parent()
    {
        return $this->belongsTo(self::class,'parent','id');
    }

    public function children()
    {
        return $this->hasMany(self::class,'id','parent');
    }



}
