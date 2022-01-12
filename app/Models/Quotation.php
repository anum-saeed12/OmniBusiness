<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'quotations';
    protected $fillable = ['client_id', 'employee_id', 'company',
        'quotation_type', 'gst', 'total_amount', 'rejected_at', 'accepted_at','original_amount'];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function vendor()
    {
        return $this->hasOne(Company::class, 'name', 'company');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class,'quotation_id','id')->with('product');
    }

    public function products()
    {
        return $this->hasMany(QuotationItem::class,'quotation_id','id')->with('product');
    }

}
