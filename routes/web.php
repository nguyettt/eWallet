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

    Route::get('/home', 'HomeController@index')->name('home');

    // Route::resource('profile', 'UserController')->only(['show', 'edit', 'update', 'destroy']);

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {

        Route::get('/', 'UserController@show')->name('show');

        Route::get('/edit', 'UserController@edit')->name('edit');

        Route::put('/edit', 'UserController@update')->name('update');

        Route::get('/password', 'UserController@password')->name('password');

        Route::put('/password', 'UserController@passwordUpdate')->name('passwordUpdate');

        Route::delete('/', 'UserController@destroy')->name('delete');

    });

    Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
        
        Route::get('/{id}', 'WalletController@show')->name('show');

        Route::get('/create', 'WalletController@create')->name('create');

        Route::post('/create', 'WalletController@store')->name('store');

        Route::get('/edit', 'WalletController@edit')->name('edit');

        Route::put('/edit', 'WalletController@update')->name('update');

        Route::delete('/{id}', 'WalletController@destroy')->name('delete');

    });

    Route::resource('wallets', 'WalletController');

    Route::get('/test', 'TestController@test');

});
