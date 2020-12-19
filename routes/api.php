<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/login','App\Http\Controllers\ApiController@accessToken');

Route::group(['middleware' => ['web','auth:api']], function() {

    Route::post('/room/','App\Http\Controllers\ApiController@storeRoom');
    Route::get('/room/','App\Http\Controllers\ApiController@getRooms');
    Route::get('/room/{room_number}','App\Http\Controllers\ApiController@showRoom');
    Route::put('/room/{room_number}','App\Http\Controllers\ApiController@updateRoom');
    Route::delete('/room/{room_number}','App\Http\Controllers\ApiController@destroyRoom');

    Route::post('/customer/','App\Http\Controllers\ApiController@storeCustomer');
    Route::get('/customer/','App\Http\Controllers\ApiController@getCustomers');
    Route::get('/customer/{customer_number}','App\Http\Controllers\ApiController@showCustomer');
    Route::put('/customer/{customer_number}','App\Http\Controllers\ApiController@updateCustomer');
    Route::delete('/customer/{customer_number}','App\Http\Controllers\ApiController@destroyCustomer');

    Route::post('/booking/','App\Http\Controllers\ApiController@storeBooking');
    Route::get('/booking/','App\Http\Controllers\ApiController@getBookings');
    Route::get('/booking/{booking_number}','App\Http\Controllers\ApiController@showBooking');
    Route::put('/booking/{booking_number}','App\Http\Controllers\ApiController@updateBooking');
    Route::delete('/booking/{booking_number}','App\Http\Controllers\ApiController@destroyBooking');

    Route::post('/payment/','App\Http\Controllers\ApiController@storePayment');
    Route::get('/payment/','App\Http\Controllers\ApiController@getPayments');
    Route::get('/get-due-payment/{customer_id}','App\Http\Controllers\ApiController@getDuePayment');
    Route::get('/payment/{payment_number}','App\Http\Controllers\ApiController@showPayment');
    Route::put('/payment/{payment_number}','App\Http\Controllers\ApiController@updatePayment');
    Route::delete('/payment/{payment_number}','App\Http\Controllers\ApiController@destroyPayment');


    Route::get('/checkout/{customer_id}','App\Http\Controllers\ApiController@checkout');



});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
