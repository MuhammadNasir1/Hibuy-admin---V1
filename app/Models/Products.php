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
        // 'product_subcategory',
        'purchase_price',
        'product_stock',
        'product_price',
        'product_discount',
        'product_discounted_price',
        'product_images',
        'product_variation',
        'product_status',
        'is_boosted',

        // 👇 Newly added fields
        'weight',
        'length',
        'width',
        'height',
        'vehicle_type_id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function category()
    {
        return $this->belongsTo(product_category::class, 'product_category', 'id')
            ->select(['id', 'name']);
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id', 'id');
    }
}
