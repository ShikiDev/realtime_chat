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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/','ChatController@index')->name('index');
//Route::get('/getMessage','ChatController@sendMessage')->name('sendMessage');
Route::post('/sendMessage','ChatController@sendMessage')->name('sendMessage');

Auth::routes();
