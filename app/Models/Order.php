<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'user_id',
        'tracking_id',
        'order_items',
        'total',
        'delivery_fee',
        'grand_total',
        'customer_name',
        'phone',
        'address',
        'second_phone',
        'status',
        'order_date',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
