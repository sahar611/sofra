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
Route::group(['prefix'=>'client','namespace'=>'client'],function(){
Route::post('register', 'MainController@register');
Route::post('login','MainController@login');
Route::post('reset-password','MainController@resetPassword');
Route::post('save-password','MainController@savePassword');

/////////////////////////// client routes apis with auth/////////////
Route::group(['middleware'=>'auth:client'],function(){
    Route::post('profile','AuthController@profile');
    Route::post('add-token', 'AuthController@addToken');
    Route::post('new-order', 'AuthController@newOrder');
    Route::get('current-order', 'AuthController@currentOrders');//status="pending"
    Route::post('confirm-order', 'AuthController@confirmOrder');//status="confirmid"
    Route::post('declined-order', 'AuthController@decliendOrder');//status="declined"
    Route::get('all-orders', 'AuthController@allOrders');//status="confirmid,decliend"
    Route::post('review', 'AuthController@review');



});

});
/////////////////////////////////////// restaurant routes apis///////////////
Route::group(['prefix'=>'restaurant','namespace'=>'restaurant'],function(){
    Route::post('register', 'MainController@register');
    Route::post('login','MainController@login');
    Route::post('reset-password','MainController@resetPassword');
    Route::post('save-password','MainController@savePassword');
    /////////////////////////// restaurant routes apis with auth/////////////
    Route::group(['middleware'=>'auth:restaurant'],function(){
        Route::post('profile','AuthController@profile');
        Route::post('add-token', 'AuthController@addToken');
       //////////////////Product Apis///////////////////////////////////
        Route::get('all-products', 'ProductController@allProducts');
        Route::post('add-product', 'ProductController@addProduct');
        Route::post('edit-product', 'ProductController@editProduct');
        Route::post('delete-product', 'ProductController@deleteProduct');
     //////////////////Offer Apis///////////////////////////////////
        Route::get('all-offers', 'OfferController@allOffer');
        Route::post('add-offer', 'OfferController@addOffer');
        Route::post('edit-offer', 'OfferController@editOffer');
        Route::post('delete-offer', 'OfferController@deleteOffer');
     //////////////////Order Apis///////////////////////////////////
        Route::get('current-orders', 'OrderController@currentOrders');
        Route::get('pervious-orders', 'OrderController@perviousOrders');
        Route::post('accept-order', 'OrderController@acceptOrder');//status="accept"
        Route::post('cancelled-order', 'OrderController@cancelledOrder');//status="cancelled"
        Route::post('confirm-order', 'OrderController@confirmOrder');//status="confirmid"

    
    
    });
    
    });
});