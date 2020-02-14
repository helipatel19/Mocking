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

Route::group(['middleware' => ['auth'],'prefix' => 'task'], function () {

    Route::get('/', 'TaskController@index');
    Route::get('/create', 'TaskController@create');
    Route::post('/store', 'TaskController@store');
    Route::get('/{task}','TaskController@show');
    Route::put('/update/{task}','TaskController@update');
    Route::put('/delete/{task}', 'TaskController@delete');

});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
