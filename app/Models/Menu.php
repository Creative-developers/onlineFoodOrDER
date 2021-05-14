<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $table = 'menus';
    protected $fillable = [
        'id',
        'menu_name',
        'sort_order',
    ];

    public function menuItems(){
       
        return  $this->hasMany('App\Models\MenuItems')->orderBy('sort_order', 'asc');
   
    }

}
