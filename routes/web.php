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

Route::get('/menu', 'DishController@menu')->name('menu');
Route::get('/forgot-password', 'UserController@resetPassword')->name('forgotPassword.mail');

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/chat', 'ChatsController@index');
    Route::get('messages', 'ChatsController@fetchMessages');
    Route::post('messages', 'ChatsController@sendMessage');
    Route::get('/table-admin', 'TableController@index')->name('table.index')->middleware('permission:tableIndex');
    Route::get('/table/edit/{id}', 'TableController@edit')->name('table.edit')->middleware('permission:tableEdit');
    Route::get('/table/{id}', 'TableController@show')->name('table.show')->middleware('permission:tableShow');
    Route::get('/dish', 'DishController@index')->name('dish.index')->middleware('permission:dishIndex');
    Route::get('/menu-admin', 'DishController@adminMenu')->name('menu.admin');
    Route::get('/dish/edit/{id}', 'DishController@edit')->name('dish.edit')->middleware('permission:dishEdit');
    Route::get('/dishCategory', 'DishCategoryController@index')->name('dishCategory.index')->middleware('permission:dishCategoryIndex');
    Route::get('/reservation/create', 'ReservationController@create')->name('reservation.create')->middleware('permission:reservationCreate|onlineReservationCreate');
    Route::get('/reservation-user', 'ReservationController@indexUser')->name('reservation.indexUser')->middleware('permission:onlineReservationIndex');
    Route::get('/menu-admin', 'DishController@adminMenu')->name('menu.admin')->middleware('permission:tableIndex');
    Route::get('/dish/edit/{id}', 'DishController@edit')->name('dish.edit')->middleware('permission:dishEdit');
    Route::get('/myAccount', 'UserController@myAccount')->name('user.myAccount');

});

Route::name('api.')->prefix('api')->namespace('API')->middleware('auth')->group(function () {
    Route::get('/table', 'ApiTableController@index')->name('table.index')
        ->middleware('permission:tableIndex');
    Route::get('/table/{table}', 'ApiTableController@load')->name('table.load')
        ->middleware('permission:tableIndex');
    Route::post('/table', 'ApiTableController@store')->name('table.store')
        ->middleware('permission:tableIndex');
    Route::post('/table/update', 'ApiTableController@update')->name('table.update')
        ->middleware('permission:tableIndex');
    Route::delete('/table/{table}', 'ApiTableController@delete')->name('table.delete')
        ->middleware('permission:tableDelete');

    Route::get('/dish', 'ApiDishController@index')->name('dish.index')
        ->middleware('permission:dishIndex');
    Route::get('/dish/{dish}', 'ApiDishController@load')->name('dish.load')
        ->middleware('permission:dishIndex');
    Route::post('/dish', 'ApiDishController@store')->name('dish.store')
        ->middleware('permission:dishIndex');
    Route::post('/dish/update', 'ApiDishController@update')->name('dish.update')
        ->middleware('permission:dishIndex');
    Route::delete('/dish/{dish}', 'ApiDishController@delete')->name('dish.delete')
        ->middleware('permission:dishDelete');

    Route::get('/dishCategory', 'ApiDishCategoryController@index')->name('dishCategory.index')
        ->middleware('permission:dishCategoryIndex');
    Route::post('/dishCategory', 'ApiDishCategoryController@store')->name('dishCategory.store')
        ->middleware('permission:dishCategoryIndex');
    Route::post('/dishCategory/update', 'ApiDishCategoryController@update')->name('dishCategory.update')
        ->middleware('permission:dishCategoryIndex');
    Route::delete('/dishCategory/{dishCategory}', 'ApiDishCategoryController@delete')->name('dishCategory.delete')
        ->middleware('permission:dishCategoryDelete');

    Route::name('reservation.')->prefix('reservation')->group(function () {

        Route::post('/store-as-customer', 'ApiReservationController@storeAsCustomer')->name('storeAsCustomer')->middleware('permission:onlineReservationCreate');
        Route::post('/store-as-worker', 'ApiReservationController@storeAsWorker')->name('storeAsWorker')->middleware('permission:reservationCreate');
        Route::put('/update-as-worker', 'ApiReservationController@updateAsWorker')->name('updateAsWorker')->middleware('permission:reservationEdit');
        Route::get('/show/{id}', 'ApiReservationController@fetchReservation')->name('show')->middleware('permission:reservationShow|onlineReservationShow');
        Route::get('', 'ApiReservationController@customerIndex')->name('customerIndex')->middleware('permission:onlineReservationIndex');
        Route::get('worker-index/{date}', 'ApiReservationController@workerIndex')->name('workerIndex')->middleware('permission:reservationIndex');
        Route::get('/tables/{date}', 'ApiReservationController@fetchTablesByDate')->name('fetchTablesByDate')->middleware('permission:reservationIndex');
        Route::delete('/{id}', 'ApiReservationController@delete')->name('delete')->middleware('permission:reservationDelete|onlineReservationDelete');
    });

    Route::name('order.')->prefix('order')->group(function () {
        Route::get('/tables/{date}', 'ApiOrderController@fetchTablesByDate')->name('fetchTablesByDate')->middleware('permission:orderIndex');
    });


    Route::name('user.')->prefix('user')->group(function () {
        Route::get('/fetch-user/{user}', 'ApiUserController@fetchUser')->name('fetchUser')->middleware('permission:userEdit');
        Route::get('/fetch-customers', 'ApiUserController@fetchCustomers')->name('fetchCustomers')->middleware('permission:customerIndex');
        Route::get('/fetch-workers', 'ApiUserController@fetchWorkers')->name('fetchWorkers')->middleware('permission:userIndex');
        Route::get('/fetch-user-my-account/{user}', 'ApiUserController@changePassword')->name('fetchUserMyAccount')->middleware('myAccount');
        Route::put('/change-password/{user}', 'ApiUserController@changePassword')->name('changePassword')->middleware('permission:userEdit');
        Route::put('/change-password-my-account/{user}', 'ApiUserController@changePassword')->name('changePasswordMyAccount')->middleware('myAccount');
        Route::put('/update-my-account/{user}', 'ApiUserController@update')->name('updateUserMyAccount')->middleware('myAccount');
        Route::put('/update-worker/{user}', 'ApiUserController@update')->name('updateWorker')->middleware('permission:userEdit');
        Route::put('/update-customer/{user}', 'ApiUserController@update')->name('updateCustomer')->middleware('permission:customerEdit');
        Route::post('/store-worker', 'ApiUserController@storeWorker')->name('storeWorker')->middleware('permission:createUser');
        Route::post('/store-customer', 'ApiUserController@storeCustomer')->name('storeCustomer');
        Route::delete('/{id}', 'ApiUserController@destroy')->name('delete')->middleware('permission:userDelete');
        Route::get('/auth-user', 'ApiUserController@myAccount')->name('authenticatedUser');
    });
});