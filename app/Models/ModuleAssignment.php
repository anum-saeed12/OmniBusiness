<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleAssignment extends Model
{
    use HasFactory;

    protected $table = "module_assignment";
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;
}
