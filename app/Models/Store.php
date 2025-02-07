<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';
    protected $primaryKey = 'store_id';
    protected $fillable = [
        'user_id',
        'seller_id',
        'store_info',
        'store_profile_detail',
        'store_status'
    ];
}
