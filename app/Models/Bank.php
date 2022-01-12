<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "bank";
    protected $fillable = ['client_id', 'bank_name', 'bank_details', 'date_of_transaction', 'opening_balance',
        'closing_balance'];

    public function client()
    {
        return $this->hasOne(Client::class, 'client_id', 'id');
    }
}
