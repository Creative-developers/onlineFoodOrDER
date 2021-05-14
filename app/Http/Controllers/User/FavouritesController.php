<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Favourites as FavouriteResource;
use Illuminate\Support\Facades\Auth;

use App\Models\Favourites;

class FavouritesController extends Controller
{
    public function markFoodItemFavourite($id){
       $favourite =  Favourites::create([
           'user_id' => Auth::id(),
           'menu_item_id' =>  $id
       ]);
       
       $data =  new FavouriteResource($favourite);
       
       return response()->json(['data' => $favourite, 'isFavouritedMarked' => 1, 'message' => 'Favourited Food added successfully!']);
    }
}
