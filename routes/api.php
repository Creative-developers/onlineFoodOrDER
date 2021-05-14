<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Restaurant\FoodMenuController;
use App\Http\Controllers\Restaurant\FoodMenuItemController;
use App\Http\Controllers\Restaurant\CategoriesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/menu/get', [FoodMenuController::class,'show']);
Route::get('/menu/get/{id}', [FoodMenuController::class,'show']);
Route::post('/menu/store', [FoodMenuController::class,'store']);
Route::put('/menu/update/{id}',[FoodMenuController::class,'update']);
Route::delete('/menu/destroy/{id}', [FoodMenuController::class,'destroy']);

Route::get('/menu/getMenuItems/{id}',[FoodMenuController::class,'getMenuItems']);

//MenuItems
Route::get('/menuItem/get',[FoodMenuItemController::class, 'show']);
Route::get('/menuItem/get/{id}',[FoodMenuItemController::class, 'show']);
Route::post('/menu/menuItem/store',[FoodMenuItemController::class, 'store']);
Route::put('/menuItem/update/{id}',[FoodMenuItemController::class,'update']);
Route::delete('/menuItem/destroy/{id}', [FoodMenuItemController::class,'destroy']);

//MenuItem Image

Route::post('/menuItem/uploadMedia/{id}', [FoodMenuItemController::class,'uploadFile']);
Route::delete('/menuItem/deleteMedia/{id}', [FoodMenuItemController::class,'deleteMedia']);
//Search Menu Item 
Route::get('/menuItem/search',[FoodMenuItemController::class,'searchMenuItem']);

//Categories endpoints
Route::post('/category/store', [CategoriesController::class, 'store']);
Route::get('/category/get', [CategoriesController::class,'show']);
Route::get('/category/get/{id}', [CategoriesController::class,'show']);
Route::post('/category/store', [CategoriesController::class,'store']);
Route::put('/category/update/{id}',[CategoriesController::class,'update']);
Route::delete('/category/destroy/{id}', [CategoriesController::class,'destroy']);

//Get Menu Items from category

Route::get('/category/getMenuItems/{id}', [FoodMenuItemController::class,'getMenuItemsFromCategory']); 