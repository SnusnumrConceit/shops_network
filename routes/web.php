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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', function () {
    return view('app');
});

Route::group(['prefix' => 'contracts'], function () {
    Route::get('/', 'ContractController@store');
    Route::post('/create', 'ContractController@create');
    Route::get('/edit/{id}', 'ContractController@edit')
    ->where('id', '[0-9]+');
    Route::post('/update/{id}', 'ContractController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'ContractController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'ContractController@form_info');
    Route::get('/search', 'ContractController@search');
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'ProductController@store');
    Route::post('/create', 'ProductController@create');
    Route::get('/edit/{id}', 'ProductController@edit')
        ->where('id', '[0-9]+');
    Route::post('/update/{id}', 'ProductController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'ProductController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/search', 'ProductController@search');
});

Route::group(['prefix' => 'providers'], function () {
    Route::get('/', 'ProviderController@store');
    Route::post('/create', 'ProviderController@create');
    Route::get('/edit/{id}', 'ProviderController@edit')
        ->where('id', '[0-9]+');
    Route::post('/update/{id}', 'ProviderController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'ProviderController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'ProviderController@form_info');
    Route::get('/search', 'ProviderController@search');
    Route::get('/products/{id}', 'ProviderController@getProducts')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => 'shops'], function () {
    Route::get('/', 'ShopController@store');
    Route::post('/create', 'ShopController@create');
    Route::get('/edit/{id}', 'ShopController@edit')
        ->where('id', '[0-9]+');
    Route::post('/update/{id}', 'ShopController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'ShopController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/search', 'ShopController@search');
});

Route::group(['prefix' => 'users', 'is' => 'admin'], function () {
    Route::get('/', 'UserController@store');
    Route::post('/create', 'UserController@create');
    Route::get('/edit/{id}', 'UserController@edit')
        ->where('id', '[0-9]+');
    Route::post('/update/{id}', 'UserController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'UserController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'UserController@form_info');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout');
Route::get('/auth', 'UserController@getUser');
