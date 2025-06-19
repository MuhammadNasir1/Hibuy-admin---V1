<?php

namespace App\Models;

use App\Models\User;
use App\Models\Products;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
     protected $primaryKey = 'inquiry_id'; 
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
