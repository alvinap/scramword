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
Route::get('/play', 'PlayController@index')->name('play');
Route::post('/play/post', 'PlayController@post')->name('play/post');
Route::get('/detail/{param}', 'DetailController@index')->name('detail');
Route::get('/best', 'BestController@index')->name('best');
