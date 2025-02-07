<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $table = 'seller';
    protected $primaryKey = 'seller_id';
    protected $fillable = [
        'user_id',
        'seller_type',
        'personal_info',
        'store_info',
        'documents_info',
        'bank_info',
        'business_info',
    ];
}
