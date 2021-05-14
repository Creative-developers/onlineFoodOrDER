<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Menu extends JsonResource{
    
    public function toArray($request){
        return [
            'id' => $this->id,
             'menu_name' => $this->menu_name,
             'sort_order' => $this->sort_order
        ];
    }
}