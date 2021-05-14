<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourites extends Model
{
    protected $fillable = [
         'id',
         'user_id',
         'menu_id'
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function Food(){
        return $this->belongsTo('App\Menu');
    }
}
