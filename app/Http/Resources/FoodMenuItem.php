<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MenuItems;
use App\Models\Category;


class FoodMenuItem extends JsonResource{
    
    public function toArray($request){
        return [
             'id' => $this->id,
             'menu' =>  $this->getMenuProps(),
             'menu_item_name' => $this->menu_item_name,
             'menu_item_desc' => $this->menu_item_desc,
             'menu_item_price' => $this->menu_item_price,
             'menu_item_image' => $this->menu_item_image,
             'rating' => $this->rating,
             'categories_id' => $this->getCategoriesData(),
             'sort_order' => $this->sort_order
        ];
    }

    public function getMenuProps($menu_id=""){
         if($menu_id!== null) return (['menu_id'=> $this->menu_id,'menu_name' =>MenuItems::find($this->id)->menu->menu_name]);
         else return null;
    }

    public function getCategoriesData($categories_id=""){
        if($this->categories_id !== null){
            $categories_id =  json_decode($this->categories_id);
              $catName = [];
            //Call the Category db to get the names of particular id
             foreach($categories_id as $key=> $value){
                 $catName[] =  Category::find($value)->category_name;
             }
            return $catName;
        
        }
        
    }
}