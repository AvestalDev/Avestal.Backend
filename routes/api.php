<?php

//API Панели администратора
use App\Http\Controllers\Admin\OrderController;


//API Аунтефикации
use App\Http\Controllers\Auth\AuthController;


//Расширения
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

Route::group(['namespace' => 'Auth'], function () {
    Route::post('/signup', [AuthController::class, 'create']);
    Route::post('/auth', [AuthController::class, 'login']);
    Route::post('/code', [AuthController::class, 'code']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::group(['namespace' => 'Auth', 'middleware' => 'jwt'], function () {
    Route::get('/logout', [AuthController::class, 'logout']);
});


Route::group(['namespace' => 'Admin', 'middleware' => 'jwt'], function () {

    Route::post('/order', [OrderController::class, 'set']);
    Route::get('/orders', [OrderController::class, 'getAll']);
    Route::get('/order/{id}', [OrderController::class, 'get']);
    Route::put('/order/{id}', [OrderController::class, 'update']);
    Route::delete('/order', [OrderController::class, 'delete']);

});
