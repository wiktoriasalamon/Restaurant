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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/chat', 'ChatsController@index');
Route::get('messages', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');
Route::get('/table', 'TableController@index')->name('table.index');
Route::get('/dish', 'DishController@index')->name('dish.index');
Route::get('/menu', 'DishController@menu')->name('menu');
Route::get('/dishCategory', 'DishCategoryController@index')->name('dishCategory.index');