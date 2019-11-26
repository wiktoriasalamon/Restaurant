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
Route::post('api/user/store-customer', 'API\ApiUserController@storeCustomer')->name('storeCustomer');
Route::get('/contact', 'ContactController@index')->name('contact');
Route::get('/order/online', 'OrderController@createOrder')->name('order.create.online');
Route::post('/api/order/online/update', 'API\ApiOrderController@updateOnlineOrder')->name('api.order.updateOnlineOrder');
Route::post('/api/order/online', 'API\ApiOrderController@storeNewOrderOnline')->name('api.order.storeNewOrderOnline');
Route::get('/api/order/show/{token}', 'API\ApiOrderController@loadOrder')->name('api.order.loadOrder');
Route::get('/order-show/{token}', 'OrderController@show')->name('order.show');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/api/user/auth-user', 'API\ApiUserController@myAccount')->name('api.user.authenticatedUser');
Route::delete('/order/delete/{token}', 'API\ApiOrderController@deleteOrder')->name('api.order.delete');

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/table-admin', 'TableController@index')->name('table.index')->middleware('permission:tableIndex');
    Route::get('/table/edit/{id}', 'TableController@edit')->name('table.edit')->middleware('permission:tableEdit');
    Route::get('/table/{id}', 'TableController@show')->name('table.show')->middleware('permission:tableShow');
    Route::get('/table-waiter/{id}', 'TableController@showWaiter')->name('table.showWaiter')->middleware('permission:tableShow');
    Route::get('/dish', 'DishController@index')->name('dish.index')->middleware('permission:dishIndex');
    Route::get('/menu-admin', 'DishController@adminMenu')->name('menu.admin');
    Route::get('/dish/edit/{id}', 'DishController@edit')->name('dish.edit')->middleware('permission:dishEdit');
    Route::get('/dishCategory', 'DishCategoryController@index')->name('dishCategory.index')->middleware('permission:dishCategoryIndex');
    Route::get('/reservation/create', 'ReservationController@create')->name('reservation.create');
    Route::get('/reservation/user-index', 'ReservationController@indexUser')->name('reservation.indexUser')->middleware('permission:onlineReservationIndex');
    Route::get('/reservation/create-user', 'ReservationController@createUser')->name('reservation.createUser')->middleware('permission:reservationCreate|onlineReservationCreate');;
    Route::get('/reservation/index', 'ReservationController@index')->name('reservation.index');
    Route::get('/reservation/user-show/{id}', 'ReservationController@showUser')->name('reservation.showUser')
        ->middleware('permission:onlineReservationShow','myReservation');
    Route::get('/reservation/show/{id}', 'ReservationController@showWaiter')->name('reservation.showWaiter')
        ->middleware('permission:reservationShow');
    Route::get('/menu-admin', 'DishController@adminMenu')->name('menu.admin')->middleware('permission:tableIndex');
    Route::get('/dish/edit/{id}', 'DishController@edit')->name('dish.edit')->middleware('permission:dishEdit');
    Route::get('/dish/create', 'DishController@create')->name('dish.create')->middleware('permission:dishCreate');
    Route::get('/myAccount', 'UserController@myAccount')->name('user.myAccount');
    Route::get('/order/myOrders', 'CustomerMyOrdersController@index')->name('orders.myOrders');

    Route::name('worker.')->prefix('worker')->group(function () {
        Route::get('/create', 'WorkerController@create')->name('create')->middleware('permission:userCreate');
        Route::get('edit/{id}', 'WorkerController@edit')->name('edit')->middleware('permission:userEdit');
        Route::get('index', 'WorkerController@index')->name('index')->middleware('permission:userIndex');
    });
    Route::get('/orders/waiter-index', 'OrderController@index')->name('order.index');
    Route::get('/orders/waiter-create/{tableId}', 'OrderController@createWaiterOrder')->name('order.createWaiter');
    Route::get('/orders/waiter-edit/{token}', 'OrderController@editWaiter')->name('order.editWaiter');
    
    Route::get('/tables/waiter-index', 'TableController@waiterIndex')->name('table.waiterIndex')->middleware('permission:tableIndex');
});

Route::name('api.')->prefix('api')->namespace('API')->middleware('auth')->group(function () {
    //todo refactor na group
//table
    Route::get('/table', 'ApiTableController@index')->name('table.index')
        ->middleware('permission:tableIndex');
    Route::get('/table/{table}', 'ApiTableController@load')->name('table.load')
        ->middleware('permission:tableIndex');
    Route::get('/table/waiter/{table}', 'ApiTableController@loadTableForWaiter')->name('table.loadTableForWaiter')
        ->middleware('permission:tableShow');
    Route::post('/table', 'ApiTableController@store')->name('table.store')
        ->middleware('permission:tableCreate');
    Route::post('/table/update', 'ApiTableController@update')->name('table.update')
        ->middleware('permission:tableEdit');
    Route::delete('/table/{table}', 'ApiTableController@delete')->name('table.delete')
        ->middleware('permission:tableDelete');
    Route::get('/my-tables', 'ApiTableController@myTables')->name('table.myTables')
        ->middleware('permission:tableIndex');
    Route::post('/table/{table}/open', 'ApiTableController@openTable')->name('table.openTable')
        ->middleware('permission:orderEdit');
    Route::post('/table/{table}/close', 'ApiTableController@closeTable')->name('table.closeTable')
        ->middleware('permission:orderEdit');
//dish
    Route::get('/dish', 'ApiDishController@index')->name('dish.index')
        ->middleware('permission:dishIndex');
    Route::get('/dish/{dish}', 'ApiDishController@load')->name('dish.load')
        ->middleware('permission:dishShow');
    Route::post('/dish', 'ApiDishController@store')->name('dish.store')
        ->middleware('permission:tableCreate');
    Route::post('/dish/update', 'ApiDishController@update')->name('dish.update')
        ->middleware('permission:dishEdit');
    Route::delete('/dish/{dish}', 'ApiDishController@delete')->name('dish.delete')
        ->middleware('permission:dishDelete');
//dishCategory
    Route::get('/dishCategory', 'ApiDishCategoryController@index')->name('dishCategory.index')
        ->middleware('permission:dishCategoryIndex');
    Route::post('/dishCategory', 'ApiDishCategoryController@store')->name('dishCategory.store')
        ->middleware('permission:dishCategoryCreate');
    Route::post('/dishCategory/update', 'ApiDishCategoryController@update')->name('dishCategory.update')
        ->middleware('permission:dishCategoryEdit');
    Route::delete('/dishCategory/{dishCategory}', 'ApiDishCategoryController@delete')->name('dishCategory.delete')
        ->middleware('permission:dishCategoryDelete');

//order
    Route::post('/order/status', 'ApiOrderController@changeStatusOrder')->name('order.changeStatusOrder')
        ->middleware('permission:orderEdit');
    Route::get('/order/status-types', 'ApiOrderController@fetchOrderStatusTypes')->name('order.fetchOrderStatusTypes')
        ->middleware('permission:orderIndex');
    Route::get('/order/my-order', 'ApiOrderController@myOrder')->name('order.myOrder')
        ->middleware('permission:userOrderIndex');
    Route::get('/order/customer-index', 'ApiOrderController@customerOrder')->name('order.customerOrder')
        ->middleware('permission:userOrderIndex');
    Route::get('/order/{type}', 'ApiOrderController@orderWithStatus')->name('order.orderWithStatus')
        ->middleware('permission:orderIndex');
    Route::post('/order/worker', 'ApiOrderController@storeNewOrderFromWorker')->name('order.storeNewOrderFromWorker')
        ->middleware('permission:orderCreate');


    Route::post('/order/worker/update', 'ApiOrderController@updateOrderFromWorker')->name('order.updateOrderFromWorker')
        ->middleware('permission:orderEdit');

    Route::name('reservation.')->prefix('reservation')->group(function () {
        Route::post('/store-as-customer', 'ApiReservationController@storeAsCustomer')->name('storeAsCustomer')->middleware('permission:onlineReservationCreate');
        Route::post('/store-as-worker', 'ApiReservationController@storeAsWorker')->name('storeAsWorker')->middleware('permission:reservationCreate');
        Route::put('/update-as-worker', 'ApiReservationController@updateAsWorker')->name('updateAsWorker')->middleware('permission:reservationEdit');
        Route::get('/show/{id}', 'ApiReservationController@fetchReservation')->name('show')->middleware('permission:reservationShow|onlineReservationShow');
        Route::get('/show-user/{id}', 'ApiReservationController@fetchReservation')->name('showUser')->middleware('permission:reservationShow|onlineReservationShow','myReservation');
        Route::get('/customer-index', 'ApiReservationController@customerIndex')->name('customerIndex')->middleware('permission:onlineReservationIndex');
        Route::get('/worker-index/{date}', 'ApiReservationController@workerIndex')->name('workerIndex')->middleware('permission:reservationIndex');
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
        Route::get('/fetch-user-my-account/{user}', 'ApiUserController@fetchUser')->name('fetchUserMyAccount')
            ->middleware('myAccount');
        Route::put('/change-password/{user}', 'ApiUserController@changePassword')->name('changePassword')->middleware
        ('permission:userEdit');
        Route::put('/change-password-my-account/{user}', 'ApiUserController@changePassword')->name('changePasswordMyAccount')->middleware('myAccount');
        Route::put('/update-my-account/{user}', 'ApiUserController@update')->name('updateUserMyAccount')->middleware('myAccount');
        Route::put('/update-worker/{user}', 'ApiUserController@update')->name('updateWorker')->middleware('permission:userEdit');
        Route::put('/update-customer/{user}', 'ApiUserController@update')->name('updateCustomer')->middleware('permission:customerEdit');
        Route::post('/store-worker', 'ApiUserController@storeWorker')->name('storeWorker')->middleware('permission:createUser');
        Route::delete('/{id}', 'ApiUserController@destroy')->name('delete')->middleware('permission:userDelete');

    });
});