<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = "subscription";
    protected $fillable =['client_id', 'next_payment_date', 'last_payment_date', 'last_paid_amount',
                          'last_paid_amount', 'type_of_subscription'];

    public function client()
    {
       return $this->belongsTo(Client::class, 'id', 'client_id');
    }
}
