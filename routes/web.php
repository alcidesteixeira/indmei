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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
//Roles
    Route::get('/roles/list', 'RoleController@index');
    Route::get('/roles/create', 'RoleController@create');
    Route::post('/roles/create', 'RoleController@store');
    Route::get('/roles/edit/{id}', 'RoleController@edit');
    Route::post('/roles/update/{id}', 'RoleController@update');
    Route::get('/roles/delete/{id}', 'RoleController@destroy');

    Route::get('/roles/attribute', 'RoleController@attributeRoles');
    Route::get('/roles/attribute/edit/{id}', 'RoleController@editAttributeRoles');
    Route::post('/roles/attribute/edit/{id}', 'RoleController@storeAttributeRoles');
    Route::get('/roles/attribute/delete/{id}', 'RoleController@deleteAttributeRoles');

//WarehouseProducts
    //Entrada de stock em armazém através de uma fatura
    Route::get('/stock/receipt', 'WarehouseProductController@receipt');
    Route::post('/stock/receipt', 'WarehouseProductController@enterReceipt');
    //CRUD de gestão do Stock
    Route::get('/stock/list', 'WarehouseProductController@index');
    Route::get('/stock/list/historic/{id}', 'WarehouseProductController@returnHistoric');
    Route::get('/stock/create', 'WarehouseProductController@create');
    Route::post('/stock/create', 'WarehouseProductController@store');
    Route::get('/stock/edit/{id}', 'WarehouseProductController@edit');
    Route::post('/stock/update/{id}', 'WarehouseProductController@update');
    Route::get('/stock/delete/{id}', 'WarehouseProductController@destroy');
    //Stock Request
    Route::get('/stock/request', 'WarehouseProductController@requestStock');
    Route::post('/stock/request', 'WarehouseProductController@storeRequestedStock');
    Route::get('/stock/request/history', 'WarehouseProductController@StockRequestedHistory');


//SampleArticles
    Route::get('/samples/list', 'SampleArticleController@index');
    Route::get('/samples/create', 'SampleArticleController@create');
    Route::post('/samples/create', 'SampleArticleController@store');
    Route::get('/samples/edit/{id}', 'SampleArticleController@edit');
    Route::post('/samples/update/{id}', 'SampleArticleController@update');
    Route::get('/samples/updatewirespecs/{id}', 'SampleArticleController@updateWireSpecs');
    Route::get('/samples/delete/{id}', 'SampleArticleController@destroy');

//Quotations
    Route::get('/quotation/list', 'QuotationController@index');
    Route::get('/quotation/create', 'QuotationController@create');
    Route::post('/quotation/create', 'QuotationController@store');
    Route::get('/quotation/edit/{id}', 'QuotationController@edit');
    Route::post('/quotation/update/{id}', 'QuotationController@update');
    Route::get('/quotation/delete/{id}', 'QuotationController@destroy');

//Orders
    Route::get('/orders/list', 'OrderController@index');
    Route::get('/orders/create', 'OrderController@create');
    Route::post('/orders/create', 'OrderController@store');
    Route::get('/orders/edit/{id}', 'OrderController@edit');
    Route::post('/orders/update/{id}', 'OrderController@update');
    Route::get('/orders/delete/{id}', 'OrderController@destroy');

//Suppliers
    Route::get('/suppliers/list', 'SupplierController@index');
    Route::get('/suppliers/create', 'SupplierController@create');
    Route::post('/suppliers/create', 'SupplierController@store');
    Route::get('/suppliers/edit/{id}', 'SupplierController@edit');
    Route::post('/suppliers/update/{id}', 'SupplierController@update');
    Route::get('/suppliers/delete/{id}', 'SupplierController@destroy');

});