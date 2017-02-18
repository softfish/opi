<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'order'], function() {
	Route::post('new', ['as' => 'new-order', 'uses' => '\App\Http\Controllers\OrderController@submitNewOrder']);
	
	Route::get('list', ['as' => 'order-list', 'uses' => '\App\Http\Controllers\OrderController@apiList']);
});

Route::group(['prefix' => 'product'], function() {
	Route::post('new', ['as' => 'new-product', 'uses' => '\App\Http\Controllers\ProductController@apiCreate']);
	Route::get('list', ['as' => 'product-list', 'uses' => '\App\Http\Controllers\ProductController@apiList']);
	Route::post('property/add', ['as' => 'add-new-product-property', 'uses' => '\App\Http\Controllers\ProductController@addNewProperty']);
	Route::get('property/delete/{id}', ['as' => 'remove-product-property', 'uses' => '\App\Http\Controllers\ProductController@removeProperty']);
});

Route::group(['prefix' => 'item'], function() {
    Route::post('new',['as' => 'new-item', 'uses' => '\App\Http\Controllers\ItemController@apiCreates']);
	Route::get('list',['as' => 'item-list', 'uses' => '\App\Http\Controllers\ItemController@apiList']);
	Route::post('update',['as' => 'item-update', 'uses' => '\App\Http\Controllers\ItemController@apiUpdate']);
	Route::get('unlink-order/{id}',['as' => 'item-unlink-order', 'uses' => '\App\Http\Controllers\ItemController@apiRemoveItemFromOrder']);
});