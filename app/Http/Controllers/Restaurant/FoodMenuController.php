<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Menu as MenuResource;
use App\Http\Resources\FoodMenuItem as FoodMenuItemResource;

use App\Models\Menu;
use App\Models\MenuItems;
use Exception;
use App\Functions\CommonFunctions;


class FoodMenuController extends Controller
{
    public function store(Request $request){

            $validator = \Validator::make($request->all(), ['menu_name' => 'required|string|min:3']);
            //Validating the fields
            if ($validator->fails()) {
               return response()->json($validator->errors(), 422);
            }
            //if none erros occurs save the data in db 
            
            $store = Menu::create([
                'menu_name'   => $request->menu_name,
                'sort_order'  => $request->sort_order
            ]);

            return response()->json(['data' => $store], 200);
    }

    public function show(Request $request, $id = ''){
        //Get only row that contains {id}
       if($id){
          $menu =  Menu::where('id', $id)->orderBy('sort_order', 'asc')->get();
          $data = MenuResource::collection($menu);
          return response()->json(['data' => $data], 200);
       }else{
           //Get all the rows from the db
          $query =  Menu::orderBy('sort_order', 'asc');
          
          //for specifying the limit for eg /api/menu/get?limit=2 for getting only top 2 rows from the db
          if($request->has('limit')){
              $query->limit($request->get('limit'));
          }
         $menu =  $query->get();
          
          $data = MenuResource::collection($menu);
          return response()->json(['data' => $data], 200);
       }
    }

    public function getMenuItems(Request $request,  $menu_id){
    
        try{

            $menuItem =  Menu::find($menu_id)->menuItems;
     
             $data = FoodMenuItemResource::collection($menuItem);

             return response()->json(['data' => $data], 200);
                   
        }catch(Exception $e){
            return response()->json(['error' => 'Invalid Request'], 400);
        }
    }

    
    public function update(Request $request, $id){
        $validator = \Validator::make($request->all(), ['menu_name' => 'required|string|min:3']);
        //Validating the fields
        if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
        }
        
        $menu = Menu::find($id);
        if($menu){
            $menu->menu_name = $request->menu_name;
            $menu->sort_order =  $request->sort_order;
            $menu->save();
            return response()->json(['data' => $menu, 'isUpdated' => 1], 200);
        }else{
            return response()->json(['error' => 'Invalid Request'], 400);
        }

    }

    public function destroy($id){
        if($id){
            $menu = Menu::where('id', $id)->first();
            if(!$menu)  return response()->json(['error' => 'Invalid Request'], 400); 
              
            foreach((MenuItems::where('menu_id', $id)->get()) as $key => $value ){
                 //unlink the files
                 CommonFunctions::delete_files(public_path('/media/menu-items/menu-item_'.$value->id));    
            }
            //Delte the ,Menu Item
             if(MenuItems::where('menu_id', $id)->delete()){
                   $menu->delete();
             }
            
              return response()->json(['data' => $menu, 'isDeleted' => 1]);
        }
    }

}
 