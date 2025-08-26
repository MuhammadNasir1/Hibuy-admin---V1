<?php

namespace App\Models;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;

class Previlige extends Model
{
     protected $table = 'previliges';
    protected $primaryKey = 'previlige_id';
    protected $fillable = ['user_id', 'menu_id', 'can_view', 'can_add', 'can_edit', 'can_delete'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }
}
