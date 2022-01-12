<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "companies";
    protected $fillable = ['name', 'client_id', 'address_1', 'address_2', 'phone_num', 'personal_email'];

    public function client()
    {
        return $this->hasOne(Client::class, 'client_id', 'id');
    }

}
