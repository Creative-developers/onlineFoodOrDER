<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model
{
 
    protected $table = 'food_menu_items';
    protected $fillable = [
        'id',
        'menu_id',
        'menu_item_name',
        'menu_item_desc',
        'menu_item_price',
        'rating',
        'sort_order',
        'categories_id',
    ];

    public function menu(){
        return $this->belongsTo('App\Models\Menu','menu_id','id');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
}
