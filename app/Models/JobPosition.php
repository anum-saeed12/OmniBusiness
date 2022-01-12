<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosition extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "job_positions";
    protected $fillable = ['title', 'department_id'];

    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
}
