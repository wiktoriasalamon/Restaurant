<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::name('api.')->namespace('API')->group(function () {
//    Route::get('/table', 'ApiTableController@index')->name('table.index');
//    Route::get('/dish', 'ApiDishController@index')->name('dish.index');
//    Route::get('/dishCategory', 'ApiDishCategoryController@index')->name('dishCategory.index');
//
//
//    Route::name('reservation.')->prefix('reservation')->group(function () {
//
//        Route::post('/store-as-citizen', 'ApiReservationController@storeAsCitizen')->name('storeAsCitizen');
//        Route::post('/store-as-worker', 'ApiReservationController@storeAsWorker')->name('storeAsWorker');
//        Route::get('/show/{id}', 'ApiReservationController@fetchReservation')->name('show');
//        Route::get('', 'ApiReservationController@customerIndex')->name('customerIndex');
//        Route::delete('/{id}', 'ApiReservationController@delete')->name('delete');
//    });
//
//});

Route::middleware('jwt.verify')->name('api.')->group(function () {

});