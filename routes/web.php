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


//Roles
Route::get('/roles/list', 'RoleController@index');
Route::get('/roles/create', 'RoleController@create');
Route::post('/roles/create', 'RoleController@store');
Route::get('/roles/edit/{id}', 'RoleController@edit');
Route::post('/roles/update/{id}', 'RoleController@update');
Route::get('/roles/delete/{id}', 'RoleController@destroy');
