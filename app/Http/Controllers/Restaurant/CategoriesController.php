<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Menu as MenuResource;

use App\Models\Menu;
use App\Models\Category;
use App\Models\MenuItems;
use Symfony\Polyfill\Ctype\Ctype;

class CategoriesController extends Controller
{
    public function store(Request $request){

            $validator = \Validator::make($request->all(), ['category_name' => 'required|string|min:3']);
            //Validating the fields
            if ($validator->fails()) {
               return response()->json($validator->errors(), 422);
            }
            //if none erros occurs save the data in db 

    //   return $request->all();
            
            $store = Category::create([
                'category_name'   => $request->category_name,
                'category_desc'  => $request->category_desc,
                'sort_order'     => $request->sort_order
            ]);

            return response()->json(['data' => $store], 200);
    }

    public function show(Request $request, $id = ''){
        //Get only row that contains {id}
       if($id){
          $data =  Category::where('id', $id)->orderBy('sort_order', 'asc')->get();
         // $data = MenuResource::collection($menu);
          return response()->json(['data' => $data], 200);
       }else{
           //Get all the rows from the db
          $query =  Category::orderBy('sort_order', 'asc');
          
          //for specifying the limit for eg /api/menu/get?limit=2 for getting only top 2 rows from the db
          if($request->has('limit')){
              $query->limit($request->get('limit'));
          }
         $data =  $query->get();
          
        //  $data = MenuResource::collection($menu);
          return response()->json(['data' => $data], 200);
       }
    }
    
    public function update(Request $request, $id){
        $validator = \Validator::make($request->all(), ['category_name' => 'required|string|min:3']);
        //Validating the fields
        if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
        }
        
        $category = Category::find($id);
        if($category){
            $category->category_name = $request->category_name;
            $category->category_desc = $request->category_desc;
            $category->sort_order =  $request->sort_order;
            $category->save();
            return response()->json(['data' => $category, 'isUpdated' => 1], 200);
        }else{
            return response()->json(['error' => 'Invalid Request'], 400);
        }

    }
   
  

    public function destroy($id){
        if($id){
            $category = Category::where('id', $id)->first();
           // return $category;
             if(!$category)  return response()->json(['error' => 'Invalid Request'], 400);  
             $category->delete();
             
            $categories_id = [];

             //Update the mennu Items which contains category id and remove the category Id from that column
            $data = MenuItems::where('categories_id', 'like' , "%{$id}%")->select('categories_id','id')->get();
            foreach($data as $key=> $value){
              foreach(json_decode($value->categories_id) as $cate_id){
                  if($cate_id !== $id){
                      $categories_id[] =  $cate_id;
                  }
              }
              if($categories_id){
                $categories_id =  json_encode($categories_id); 
                MenuItems::where('id',$value->id)->update(['categories_id' => $categories_id]);
             }else{
                MenuItems::where('id',$value->id)->update(['categories_id' => NULL]);
             }
            
            }

            return response()->json(['data' => $category, 'isDeleted' => 1]);
        }
    }

}
 