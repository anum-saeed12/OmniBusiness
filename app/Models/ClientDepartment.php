<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDepartment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "client_department";
    protected $fillable = ['client_id', 'department_id'];

    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id')->with('positions');
    }
    public function departments()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}
