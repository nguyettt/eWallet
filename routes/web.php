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

Route::get('/logout', 'Auth\LoginController@logout');

Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/check', function () {
    Mail::send('mail', [], function ($m) {
        $m->from('test@gmail.com', 'test');
        $m->to('kuro.keita94@gmail.com', 'kurokeita')->subject('test subject');
    });
});

Route::middleware('verified')->group(function () {

    Route::get('/dashboard', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', 'UserController@show')->name('show');
        Route::get('/edit', 'UserController@edit')->name('edit');
        Route::put('/edit', 'UserController@update')->name('update');
        Route::get('/password', 'UserController@password')->name('password');
        Route::put('/password', 'UserController@passwordUpdate')->name('passwordUpdate');
        Route::delete('/', 'UserController@destroy')->name('delete');
    });

    Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
        Route::get('/create', 'WalletController@create')->name('create');
        Route::post('/', 'WalletController@store')->name('store');
        Route::get('/{id}', 'WalletController@show')->name('show');
        Route::get('/{id}/edit', 'WalletController@edit')->name('edit');
        Route::get('/{id}/restore', 'WalletController@restore')->name('restore');
        Route::put('/{id}', 'WalletController@update')->name('update');
        Route::delete('/{id}', 'WalletController@destroy')->name('delete');
        Route::post('/getbalance', 'WalletController@getBalance')->name('getBalance');
    });

    Route::group(['prefix' => 'cat', 'as' => 'cat.'], function () {
        Route::get('/', 'CategoryController@index')->name('index');
        Route::get('create', 'CategoryController@create')->name('create');
        Route::post('/', 'CategoryController@store')->name('store');
        Route::get('/{id}', 'CategoryController@show')->name('show');
        Route::get('/{id}/edit', 'CategoryController@edit')->name('edit');
        Route::get('/{id}/restore', 'CategoryController@restore')->name('restore');
        Route::put('/{id}', 'CategoryController@update')->name('update');
        Route::delete('/{id}', 'CategoryController@destroy')->name('delete');
    });

    Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {
        Route::get('/', 'TransactionController@index')->name('index');
        Route::get('create', 'TransactionController@create')->name('create');
        Route::post('/', 'TransactionController@store')->name('store');
        Route::get('/{id}', 'TransactionController@show')->name('show');
        Route::get('/{id}/edit', 'TransactionController@edit')->name('edit');
        Route::put('/{id}', 'TransactionController@update')->name('update');
        Route::delete('/{id}', 'TransactionController@destroy')->name('delete');
        Route::post('/search', 'TransactionController@search')->name('search');
    });

    Route::post('/export', 'ExportController@export')->name('export');
    
    Route::post('/exportJSON', 'ExportController@exportJSON')->name('exportJSON');

    Route::get('/export/{id}/{name}', 'ExportController@download')->name('download');

    Route::get('/test', 'TestController@test')->name('test');

});
