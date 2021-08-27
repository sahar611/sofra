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






});