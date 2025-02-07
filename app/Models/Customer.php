<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
    'user_id',
    'customer_image',
    'customer_phone',
    'customer_gender',
    'customer_dob',
    'payment_method',
    'customer_addresses',
    ];
    public $timestamps = true;
}   
