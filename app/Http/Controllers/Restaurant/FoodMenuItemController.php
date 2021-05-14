<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\FoodMenuItem as FoodMenuItemResource;

use App\Models\MenuItems;
use App\Models\Menu;
use App\Functions\CommonFunctions;

class FoodMenuItemController extends Controller
{

  public function show(Request $request, $id = ''){
    //Get only row that contains {id}

   if($id){
      $menu =  MenuItems::where('id', $id)->orderBy('sort_order', 'asc')->get();
      $data = FoodMenuItemResource::collection($menu);
      return response()->json(['data' => $data], 200);
   }else{
       //Get all the rows from the db
      $query =  MenuItems::orderBy('sort_order', 'asc');
      
      //for specifying the limit for eg /api/menuItem/get?limit=2 for getting only top 2 rows from the db
      if($request->has('limit')){
          $query->limit($request->get('limit'));
      }
     $menu =  $query->get();
      
      $data = FoodMenuItemResource::collection($menu);
      return response()->json(['data' => $data], 200);
   }
}

    public function store(Request $request){

     // return $request->all();
         //$float_num_regex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";
        $validator = \Validator::make($request->all(), 
          [
          'menu_item_name' => 'required|string|min:3',
          'menu_item_price' => 'required|numeric'
          ]);
        //Validating the fields
        if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
        }

     //   check if menu exists in parent menus table if not throw the error
        //return Menu::where('id',$request->menu_id)->count();
        if(Menu::where('id',$request->menu_id)->count()){
      
        
          //if none erros occurs save the data in db         
          $store = MenuItems::create([
              'menu_item_name'   => $request->menu_item_name,
              'menu_id' => $request->menu_id,
              'menu_item_desc'  => $request->menu_item_desc,
              'menu_item_price'  => $request->menu_item_price,
              'rating'  => $request->rating,
              'sort_order'=> $request->sort_order,
              'categories_id' => json_encode($request->categories_id) 
              // This [categories_id] should will come in array data as menu item can contain as many as categories
              // so from the frontend it will would be coming in form of array like
              // "categories_id": ["2","4"]
              // then we will will json encode for storing in db coulmn and on retreving we will
              // decode it 
          ]);

         // $data =  FoodMenuItemResource::collection($store);

          return response()->json(['data' => $store], 200);
        }else{
          return response()->json(['error' => 'Invalid Request'], 400);
        }
   }

   public function update(Request $request, $id){
    $validator = \Validator::make($request->all(), 
    [
      'menu_item_name' => 'required|string|min:3',
      'menu_item_price' => 'required|numeric'
    ]);
    //Validating the fields
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $menuItem = MenuItems::find($id);
    $menu = Menu::where('id',$request->menu_id)->count();
    if($menuItem && $menu){
        $menuItem->menu_item_name = $request->menu_item_name;
        $menuItem->menu_id =  $request->menu_id;
        $menuItem->menu_item_desc =  $request->menu_item_desc;
        $menuItem->menu_item_price =  $request->menu_item_price;
        $menuItem->rating =  $request->rating;
         $menuItem->sort_order = $request->sort_order;
        $menuItem->categories_id = ( $request->categories_id) ? json_encode($request->categories_id) : null;

        $menuItem->save();


        return response()->json(['data' => $menuItem, 'isUpdated' => 1], 200);
    }else{
        return response()->json(['error' => 'Invalid Request'], 400);
    }

}

  // Get Menu Items from category id
  public function getMenuItemsFromCategory($id){
    $data = [];
    $MenuItems = MenuItems::where('categories_id', 'like' , "%{$id}%")->orderBy('sort_order','asc')->get();
    $data =  FoodMenuItemResource::collection($MenuItems);
    
    return response()->json(['data' => $data], 200);

 }

//Search Menu Items


public function searchMenuItem(Request $request){
    $search_query = $request->get('query');
    $data = [];
    if($search_query){
       $MenuItems =  MenuItems::where('menu_item_name', 'like', "%{$search_query}%")->orderBy('sort_order', 'asc')->get();
       $data =  FoodMenuItemResource::collection($MenuItems);
   }

    return response()->json(['data' => $data], 200);

}

//Uploading Menu Item Picture
public function uploadFile(Request $request, $id){
   
  if(!MenuItems::find($id)) {return 'Invalid Request';}

  //return $request; 
    $validator = \Validator::make($request->all(), 
    [
        'file' => 'required'
    ]);
  //Validating the fields
  if ($validator->fails()) {
    return response()->json($validator->errors(), 422);
  }  
   
   $allowed_ext = array('jpg','jpeg','png');

 
  if ($request->hasfile('file')) {
    $file = $request->file('file');

    $file_ext = $file->getClientOriginalExtension();
    if(!in_array($file_ext, $allowed_ext)){
       return response()->json(['data' => 'Extension not allowed'], 422);
    } 
     
    //unlink the old one
    
    CommonFunctions::delete_files(public_path('/media/menu-items/menu-item_'.$id));

    $picfilename = time() . '.' .$file_ext;
  
    $picpath = public_path('/media/menu-items/menu-item_'.$id);
     $file->move($picpath, $picfilename); 

    $upadtedItem = MenuItems::where('id', $id)->update(['menu_item_image'=> $picfilename]);
    if($upadtedItem){
        $menuItemData = MenuItems::where('id',$id)->get();
       return response()->json(['data' => FoodMenuItemResource::collection($menuItemData)], 200);
    }
    else return response()->json(['error' => 'Something went wrong Please try again later!'],503 );
  }

}

//Delet the Menu Item Image
public function deleteMedia($id){
   if($id){
     MenuItems::where('id',$id)->update(['menu_item_image'=> null]);
       //Unlink the media
     CommonFunctions::delete_files(public_path('/media/menu-items/menu-item_'.$id));
     return response()->json(['data'=> 'Image File removed successfully!'],200);
    }
}

public function destroy($id){
    if($id){
        $menuItem = MenuItems::where('id', $id)->first();
        if(!$menuItem)  return response()->json(['error' => 'Invalid Request'], 400);  
         //unlink the file
        if($menuItem->delete()){
          CommonFunctions::delete_files(public_path('/media/menu-items/menu-item_'.$id));
        }

        return response()->json(['data' => $menuItem, 'isDeleted' => 1]);
    }
}
}
