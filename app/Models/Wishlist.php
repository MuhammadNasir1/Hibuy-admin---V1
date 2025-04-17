<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';
    protected $primaryKey = 'wishlist_id';
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    // public function product()
    // {
    //     return $this->belongsTo(Products::class, 'product_id');
    // }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id')
            ->select([
                'product_id',
                'store_id',
                'product_name',
                'product_brand',
                'product_category',
                'product_subcategory',
                'product_price',
                'product_discount',
                'product_discounted_price',
                'product_images'
            ]);
    }


}
