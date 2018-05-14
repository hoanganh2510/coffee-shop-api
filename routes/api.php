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
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('signup', 'Jwt\AuthController@signup');

Route::post('/auth/login', 'Jwt\AuthController@login');

Route::group(['middleware' => 'jwt.auth'], function () {

});
Route::apiResource('categories', 'CategoryController');

Route::apiResource('shops', 'ShopController');

Route::apiResource('products', 'ProductController');

Route::apiResource('customers', 'CustomerController');

Route::apiResource('coupons', 'CouponController');

Route::apiResource('orders', 'OrderController');

Route::put('orders/{id}/transaction', 'OrderController@transaction');

Route::get('distance', 'OrderController@getDistance');