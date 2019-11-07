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
Route::get('/menu-admin', 'DishController@adminMenu')->name('menu.admin');
Route::get('/dish/edit/{id}', 'DishController@edit')->name('dish.edit');
Route::get('/dishCategory', 'DishCategoryController@index')->name('dishCategory.index');
Route::resource('reservation', 'ReservationController');
Route::get('/reservation-user', 'ReservationController@indexUser')->name('reservation.indexUser');

Route::name('api.')->prefix('api')->namespace('API')->middleware('auth')->group(function () {
    Route::get('/table', 'ApiTableController@index')->name('table.index');
    Route::delete('/table/{table}', 'ApiTableController@delete')->name('table.delete');
    Route::get('/dish', 'ApiDishController@index')->name('dish.index');
    Route::delete('/dish/{dish}', 'ApiDishController@delete')->name('dish.delete');
    Route::get('/dishCategory', 'ApiDishCategoryController@index')->name('dishCategory.index');
    Route::delete('/dishCategory/{dishCategory}', 'ApiDishCategoryController@delete')->name('dishCategory.delete');


    Route::name('reservation.')->prefix('reservation')->group(function () {

        Route::post('/store-as-citizen', 'ApiReservationController@storeAsCitizen')->name('storeAsCitizen');
        Route::post('/store-as-worker', 'ApiReservationController@storeAsWorker')->name('storeAsWorker');
        Route::get('/show/{id}', 'ApiReservationController@fetchReservation')->name('show');
        Route::get('', 'ApiReservationController@customerIndex')->name('customerIndex');
        Route::delete('/{id}', 'ApiReservationController@delete')->name('delete');
    });

});