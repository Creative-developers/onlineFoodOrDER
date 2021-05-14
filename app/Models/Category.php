<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'id',
        'category_name',
        'category_desc',
        'sort_order'
    ];

    public function menItemss(){
        return $this->hasMany('App\Models\MenuItems')->orderBy('sort_order','asc');
    }

    // public function restaurant(){
    //     return $this->belongsTo('App\Restaurant');
    // }

    // public function category(){
    //     return $this->belongsTo('App\Category');
    // }

    // public function favourite(){
    //     return $this->hasMany('App\Favourites');
    // }
}
