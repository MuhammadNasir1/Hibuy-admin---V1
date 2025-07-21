<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';
    protected $primaryKey = 'faq_id';
    protected $fillable = [
        'question',
        'answer',
        'faq_category',
        'is_active',
    ];
    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(product_category::class, 'faq_category', 'id');
    }
}
