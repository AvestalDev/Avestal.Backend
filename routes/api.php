<?php

//API
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ResponseController;
use App\Http\Controllers\Admin\UserController;
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
    Route::delete('/order/{id}', [OrderController::class, 'delete']);

    Route::get('/user', [UserController::class, 'get']);
    Route::get('/clients', [ClientController::class, 'getAll']);
    Route::get('/client/{id}', [ClientController::class, 'get']);
    Route::put('/client/{id}', [ClientController::class, 'update']);
    Route::delete('/client/{id}', [ClientController::class, 'delete']);

    Route::post('/response', [ResponseController::class, 'set']);
    Route::put('/response/{id}', [ResponseController::class, 'update']);
    Route::delete('/response/{id}', [ResponseController::class, 'delete']);

    Route::post('/category', [CategoryController::class, 'set']);
    Route::get('/categories', [CategoryController::class, 'getAll']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'delete']);

    Route::post('/subcategory', [OrderController::class, 'set']);
    Route::get('/subcategories', [OrderController::class, 'getAll']);
    Route::put('/subcategory/{id}', [OrderController::class, 'update']);
    Route::delete('/subcategory/{id}', [OrderController::class, 'delete']);
});
