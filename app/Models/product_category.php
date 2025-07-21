<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product_category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'image',
        'category_type',
        'sub_categories',
    ];
    public $timestamps = true;

}
