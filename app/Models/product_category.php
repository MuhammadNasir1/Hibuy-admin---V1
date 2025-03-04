<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product_category extends Model
{
    protected $table = 'product_category';
    protected $primaryKey = 'category_id';
    protected $fillable = [
        'seller_id',
        'store_info',
        'store_profile_detail',
        'store_status'
    ];

}
