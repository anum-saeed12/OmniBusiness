<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "clients";
    protected $fillable = ['name', 'license', 'website', 'ntn_number', 'overview',
                           'address_1', 'address_2', 'landline', 'mobile', 'official_email','created_by','prefix'];
    public function company()
    {
        return $this->hasMany(Company::class, 'client_id', 'id');
    }

    public function subscription()
    {
        return $this->hasMany(Subscription::class, 'client_id', 'id')->orderBy('id','DESC');
    }
}
