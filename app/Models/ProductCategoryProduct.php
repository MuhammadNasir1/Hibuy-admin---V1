<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryProduct extends Model
{
    // Table name (Laravel will guess wrong because it's not plural)
    protected $table = 'product_category_product';

    // Primary key
    protected $primaryKey = 'product_subcategoryId';  // as you named it

    // Allow mass assignment
    protected $fillable = [
        'product_id',
        'category_id',
        'category_level',
    ];

    public $timestamps = true;


    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id');
    }
}
