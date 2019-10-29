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
Route::get('/test/jwt', 'UserController@getAuthenticatedUser')->name('jwt.user');
Route::get('/table', 'API\ApiTableController@index')->name('table2.index');
Route::middleware('jwt.verify')->name('api.')->group(function () {

    Route::get('/dish', 'API\ApiDishController@index')->name('dish.index');
    Route::get('/dishCategory', 'API\ApiDishCategoryController@index')->name('dishCategory.index');


});