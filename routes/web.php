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
    Route::get('/stock/receipt', 'WarehouseProductController@receipt');
    Route::post('/stock/receipt', 'WarehouseProductController@enterReceipt');
    Route::get('/stock/list', 'WarehouseProductController@index');
    Route::get('/stock/create', 'WarehouseProductController@create');
    Route::post('/stock/create', 'WarehouseProductController@store');
    Route::get('/stock/edit/{id}', 'WarehouseProductController@edit');
    Route::post('/stock/update/{id}', 'WarehouseProductController@update');
    Route::get('/stock/delete/{id}', 'WarehouseProductController@destroy');


//SampleArticles
    Route::get('/samples/list', 'SampleArticleController@index');
    Route::get('/samples/create', 'SampleArticleController@create');
    Route::post('/samples/create', 'SampleArticleController@store');
    Route::get('/samples/edit/{id}', 'SampleArticleController@edit');
    Route::post('/samples/update/{id}', 'SampleArticleController@update');
    Route::get('/samples/updatewirespecs/{id}', 'SampleArticleController@updateWireSpecs');
    Route::get('/samples/delete/{id}', 'SampleArticleController@destroy');

});