<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reviews extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'review_id';
    protected $fillable = ['user_id', 'product_id', 'rating', 'review', 'images'];

    protected $casts = [
        'images' => 'array', // Convert JSON to array automatically
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // Maps user_id in reviews to id in users table
    }


    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id')
            ->select([
                'product_id',
                'product_name',
                'product_images'
            ]);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id')
        ->select([
            'order_id',
            'order_date'
        ]);
    }
}
