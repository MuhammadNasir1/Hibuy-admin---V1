<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditRequest extends Model
{
    protected $table = 'credit_request';
    protected $primaryKey = 'credit_id';
    protected $fillable = [
        'credit_id',
        'user_id',
        'amount',
        'reason',
        'request_status',
        'credit_use',
    ];
}
