<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'order'], function(){
    Route::get('/', function(){ return redirect('order/list'); });
    Route::get('list', ['as' => 'web-order-list', 'uses' => 'OrderController@webList']);
    Route::get('view/{id}', 'OrderController@viewOrder');
});

Route::group(['prefix' => 'item'], function(){
    Route::get('/', function(){ return redirect('item/list'); });
    Route::get('list', ['as' => 'web-item-list', 'uses' => 'ItemController@webList']);
    Route::get('view/{id}', 'ItemController@viewItem');
});

Route::group(['prefix' => 'product'], function(){
    Route::get('/', function(){ return redirect('product/list'); });
    Route::get('list', ['as' => 'web-product-list', 'uses' => 'ProductController@webList']);
    Route::get('view/{id}', 'ProductController@viewProduct');
    Route::post('view/{id}', 'ProductController@updateProduct');
    Route::post('addItem', 'ProductController@addProductItems');
    Route::post('new', 'ProductController@addNewProduct');
    Route::get('new', function(){ return redirect('product/list'); });
});