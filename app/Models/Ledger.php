<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;
    protected $table = 'ledgers';
    protected $fillable = [
        'user_id',
        'client_id',
        'account_type',
        'transaction_id',
        'ledger_entry_type',
        'ledger_amount',
        'created_at',
    ];
}
