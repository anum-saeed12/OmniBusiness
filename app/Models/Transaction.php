<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = [
        'client_id',
        'user_id',
        'invoice_id',
        'transaction_type',
        'transaction_date',
        'description',
        'created_at',
    ];
}
