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
    Route::get('/stock/choosecolor/{id}', 'WarehouseProductController@allColors');
    Route::post('/stock/receipt', 'WarehouseProductController@enterReceipt');
    //CRUD de gestão do Stock
    Route::get('/stock/list', 'WarehouseProductController@index');
    Route::get('/stock/list/historic/{id}', 'WarehouseProductController@returnHistoric');
    Route::get('/stock/create', 'WarehouseProductController@create');
    Route::post('/stock/create', 'WarehouseProductController@store');
    Route::get('/stock/edit/{id}', 'WarehouseProductController@edit');
    Route::post('/stock/update/{id}', 'WarehouseProductController@update');
    Route::get('/stock/delete/{id}', 'WarehouseProductController@destroy');
    //Stock Request History
    Route::get('/stock/request/history', 'StockRequestController@index');


//SampleArticles
    Route::get('/samples/list', 'SampleArticleController@index');
    Route::get('/samples/create', 'SampleArticleController@create');
    Route::post('/samples/create', 'SampleArticleController@store');
    Route::get('/samples/edit/{id}', 'SampleArticleController@edit');
    Route::post('/samples/update/{id}', 'SampleArticleController@update');
    Route::get('/samples/updatewirespecs/{id}', 'SampleArticleController@updateWireSpecs');
    Route::get('/samples/delete/{id}', 'SampleArticleController@destroy');
    Route::get('/samples/getForDuplicate/{id}', 'SampleArticleController@duplicate');

//Quotations
    Route::get('/quotation/list', 'QuotationV2Controller@index');
    Route::get('/quotation/create/', 'QuotationV2Controller@create');
    Route::post('/quotation/create', 'QuotationV2Controller@store');
    Route::get('/quotation/edit/{id}', 'QuotationV2Controller@edit');
    Route::post('/quotation/update/{id}', 'QuotationV2Controller@update');
    Route::get('/quotation/delete/{id}', 'QuotationV2Controller@destroy');

//Emails
    Route::get('/email/list', 'EmailController@index');
    Route::get('/email/create/{stock_id?}', 'EmailController@create');
    Route::post('/email/send', 'EmailController@send');

//Orders
    Route::get('/orders/list', 'OrderController@index');
    Route::get('/orders/create', 'OrderController@create');
    Route::get('/orders/getSampleArticleId/{id}', 'OrderController@getSampleArticleId');
    Route::post('/orders/create', 'OrderController@store');
    Route::get('/orders/edit/{id}', 'OrderController@edit');
    Route::post('/orders/update/{id}', 'OrderController@update');
    Route::get('/orders/delete/{id}', 'OrderController@destroy');

//Orders Production
    Route::get('/order/production/{id}', 'OrderProductionController@list');
    Route::get('/order/production/insert/{id}/{id_user?}', 'OrderProductionController@create');
    Route::post('/order/production/update/{id}', 'OrderProductionController@update');
    Route::get('/to/subtract/{id}', 'OrderProductionController@toSubtract');
    Route::get('/order/ended/{id}', 'OrderProductionController@orderEnded');
    Route::post('/order/ended/save/image', 'OrderProductionController@saveImageFinishedOrder');


//Suppliers
    Route::get('/suppliers/list', 'SupplierController@index');
    Route::get('/suppliers/create', 'SupplierController@create');
    Route::post('/suppliers/create', 'SupplierController@store');
    Route::get('/suppliers/edit/{id}', 'SupplierController@edit');
    Route::post('/suppliers/update/{id}', 'SupplierController@update');
    Route::get('/suppliers/delete/{id}', 'SupplierController@destroy');

//Clients
    Route::get('/clients/list', 'ClientController@index');
    Route::get('/clients/create', 'ClientController@create');
    Route::post('/clients/create', 'ClientController@store');
    Route::get('/clients/edit/{id}', 'ClientController@edit');
    Route::post('/clients/update/{id}', 'ClientController@update');
    Route::get('/clients/delete/{id}', 'ClientController@destroy');

//Emails
    Route::get('/email/manage', 'OrderProductionController@toSubtract');

//Orders Production
    Route::get('/orders/production/list', 'OrderProductionController@index');

//Stats
    Route::get('/stats', 'StatisticsController@index');
    Route::post('/stats/update', 'StatisticsController@update');

});