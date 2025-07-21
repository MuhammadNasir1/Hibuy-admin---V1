<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnOrder extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'returns';

    // Primary key
    protected $primaryKey = 'return_id';

    // Fields you can mass assign
    protected $fillable = [
        'order_id',
        'user_id',
        'return_items',
        'return_total',
        'return_delivery_fee',
        'return_grand_total',
        'return_status',
        'return_reason',
        'return_recieve_option',
        'return_address',
        'return_note',
    ];

    // Cast JSON field to array automatically
    protected $casts = [
        'return_items' => 'array',
    ];
}
