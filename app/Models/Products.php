<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_id',
        'user_id',
        'store_id',
        'product_name',
        'product_description',
        'product_brand',
        'product_category',
        'product_subcategory',
        'purchase_price',
        'product_price',
        'product_discount',
        'product_discounted_price',
        'product_images',
        'product_variation',
        'product_status',
    ];
}
