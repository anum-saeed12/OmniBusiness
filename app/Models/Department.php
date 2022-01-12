<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "departments";
    protected $fillable = ['name'];

    public function position()
    {
        return $this->hasMany(JobPosition::class,'department_id','id');
    }

    public function positions()
    {
        return $this->hasMany(JobPosition::class, 'department_id', 'id');
    }

}
