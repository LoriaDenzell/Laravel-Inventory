<?php

use Illuminate\Support\Facades\Route;
//use Spatie\Activitylog\Models\Activity;
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::view('unauthorized', 'unauthorized');
Route::get('/verify', 'Auth\RegisterController@verifyUser')->name('verify.user');

Route::group(['middleware' => ['prevent-back-history']], function() 
{
    Route::get('/home', 'HomeController@index')->name('/home');
    Route::get('/productsSummary', 'HomeController@productsSummary')->name('/productsSummary')->middleware('role:A');
    Route::get('/salesSummary', 'HomeController@salesSummary')->name('/salesSummary')->middleware('role:A');
    Route::get('/expensesSummary', 'HomeController@expensesSummary')->name('/expensesSummary')->middleware('role:A');
});


Route::group(['middleware' => ['user_middleware']], function() //Group middleware for authenticated / logged-in users
{
    Route::get('/logout', 'Auth\LoginController@logout');

    //USER
    Route::resource('user', 'UserController');
    Route::get('user/datatable', 'UserController@datatable')->name('user/datatable')->middleware('role:A');
    Route::get('user/datatableTrash', 'UserController@datatableTrash')->name('user/datatableTrash')->middleware('role:A');
    Route::post('user/undoTrash/{id}', 'UserController@undoTrash')->name('user/undoTrash/{id}')->middleware('role:A');
    Route::get('userActivities/{id}', 'UserController@userActivities')->name('userActivities/{id}');
    Route::get('/password', 'UserController@Password');
    Route::post('user/updatePassword', 'UserController@updatePassword')->name('user.updatePassword');

    //MASTER->PRODUCT
    Route::resource('master/product', 'Master\ProductController')->middleware('role:A');
    Route::get('product/datatable', 'Master\ProductController@datatable')->name('product/datatable')->middleware('role:A');
    Route::get('product/dataTableTrash', 'Master\ProductController@dataTableTrash')->name('product/dataTableTrash')->middleware('role:A');
    Route::post('product/undoTrash/{id}', 'Master\ProductController@undoTrash')->name('product/undoTrash/{id}')->middleware('role:A');
    Route::get('product/activities/{id}', 'Master\ProductController@ProductActivities')->name('product/activities/{id}')->middleware('role:A');
    
    //MASTER->CATEGORY
    Route::resource('master/category', 'Master\CategoryController')->middleware('role:A');
    Route::get('category/datatable', 'Master\CategoryController@datatable')->name('category/datatable')->middleware('role:A');
    Route::get('category/datatableTrash', 'Master\CategoryController@datatableTrash')->name('category/datatableTrash')->middleware('role:A');
    Route::post('category/undoTrash/{id}', 'Master\CategoryController@undoTrash')->name('category/undoTrash/{id}')->middleware('role:A');
    Route::get('master/category/activities/{id}', 'Master\CategoryController@CategoryActivities')->name('master/category/activities/{id}')->middleware('role:A');
    
    //TRANSACTION->PURCHASE
    Route::resource('transaction/purchase-order', 'Transaction\PurchaseController');
    Route::get('browse-product/datatable', 'Master\ProductController@datatable_product')->name('browse-product/datatable');
    Route::get('purchase-order/datatableTrash', 'Transaction\PurchaseController@dataTableTrash')->name('purchase-order/datatableTrash')->middleware('role:A');
    Route::get('purchase-order/datatable', 'Transaction\PurchaseController@datatable')->name('purchase-order/datatable');
    Route::post('transaction/purchase-order/receive/{id}', 'Transaction\PurchaseController@received')->name('transaction/purchase-order/received/{id}');
    Route::get('transaction/purchase-order/print/{id}', 'Transaction\PurchaseController@print')->name('transaction/purchase-order/print/{id}')->middleware('role:A');
    Route::post('purchase-order/undoTrash/{id}', 'Transaction\PurchaseController@undoTrash')->name('purchase-order/undoTrash/{id}')->middleware('role:A');
    
    //TRANSACTION->SALES
    Route::resource('transaction/sales', 'Transaction\SaleController');
    Route::get('transaction/sales/product/popup_media/{id_count}', 'Transaction\SaleController@popup_media_product')->name('transaction/sales/product/popup_media/{id_count}');
    Route::get('sales/datatable', 'Transaction\SaleController@datatable')->name('sales/datatable');
    Route::get('sales/datatableTrash', 'Transaction\SaleController@datatableTrash')->name('sales/datatableTrash')->middleware('role:A');
    Route::get('sales/deactivate/{id}', 'Transaction\SaleController@deactivate')->name('sales/deactivate/{id}')->middleware('role:A');
    Route::post('sales/deleteSalesOrder/{id}', 'Transaction\SaleController@deleteSalesOrder')->name('sales/deleteSalesOrder/{id}')->middleware('role:A');
    Route::get('transaction/sales/print/{id}', 'Transaction\SaleController@print')->name('transaction/sales/print/{id}')->middleware('role:A');
    Route::post('sales/undoTrash/{id}', 'Transaction\SaleController@undoTrash')->name('sales/undoTrash/{id}')->middleware('role:A');
    
    //TRANSACTION->STOCK
    Route::get('transaction/stock', 'Transaction\StockController@index')->name('transaction/stock');
    Route::get('transaction/stock/product/popup_media', 'Transaction\StockController@popup_media_product')->name('transaction/stock/product/popup_media');
    Route::post('transaction/stock', 'Transaction\StockController@update')->name('transaction/update');
    Route::get('stock/report', 'Transaction\StockController@report')->name('stock/report');

    Route::get('RecentActivities', 'HomeController@RecentActivities')->name('RecentActivities');
});