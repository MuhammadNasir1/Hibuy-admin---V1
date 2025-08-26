<?php

namespace App\Models;

use App\Models\Privilege;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $primaryKey = 'menu_id';
    protected $fillable = ['menu_name', 'menu_slug', 'menu_description', 'parent_id', 'status', 'can_view', 'can_add', 'can_edit', 'can_delete'];

    public function permissions()
    {
        return $this->hasOne(Privilege::class, 'menu_id', 'menu_id');
    }
}
