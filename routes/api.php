<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['prefix'=>'v1','namespace'=>'Api'],function(){
////////////////// general apis/////////////////////////////////////////
Route::get('cities','MainController@cities');
Route::get('regions','MainController@regions');
Route::get('categories','MainController@categories');
Route::get('restaurants','MainController@restaurants');
Route::get('restaurant','MainController@restaurant');
Route::get('settings','MainController@settings');
Route::get('pages','MainController@pages');
Route::get('products','MainController@products');
Route::get('product','MainController@product');
Route::get('offers','MainController@offers');
Route::get('offer','MainController@offer');
Route::get('reviews','MainController@reviews');
/////////////////////////////////////// client routes apis///////////////
Route::group(['prefix'=>'client'],function(){
Route::post('register', 'ClientController@register');
Route::post('login','ClientController@login');
Route::post('reset-password','ClientController@reset_password');
Route::post('save-password','ClientController@save_password');
/////////////////////////// client routes apis with auth/////////////
Route::group(['middleware'=>'auth:client'],function(){
    Route::post('profile','ClientController@profile');
    Route::post('add-token', 'ClientController@addToken');



});

});
/////////////////////////////////////// restaurant routes apis///////////////
Route::group(['prefix'=>'restaurant'],function(){
    Route::post('register', 'RestaurantController@register');
    Route::post('login','RestaurantController@login');
    Route::post('reset-password','RestaurantController@reset_password');
    Route::post('save-password','RestaurantController@save_password');
    /////////////////////////// restaurant routes apis with auth/////////////
    Route::group(['middleware'=>'auth:restaurant'],function(){
        Route::post('profile','RestaurantController@profile');
        Route::post('add-token', 'ClientController@addToken');
    
    
    
    });
    
    });
});