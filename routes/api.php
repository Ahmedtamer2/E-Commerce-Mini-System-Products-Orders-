<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});


Route::middleware('auth:api')->group(function () {
    // Products (protected or public? here protected - you can move GET product to public)
    Route::get('/products', [ProductController::class,'index']);
    Route::get('/product/{id}', [ProductController::class,'show']);
    Route::post('/products', [ProductController::class,'store']);
    Route::put('/products/{id}', [ProductController::class,'update']);
    Route::delete('/products/{id}', [ProductController::class,'destroy']);

    // Cart endpoints (user cart)
    Route::get('/cart', [CartController::class,'index']);
    Route::post('/cart', [CartController::class,'add']); 
    Route::delete('/cart/{id}', [CartController::class,'remove']);
    Route::delete('/cart', [CartController::class,'clear']); 

    // Orders
    Route::post('/orders', [OrderController::class,'store']);
    Route::get('/orders', [OrderController::class,'index']); 
    Route::get('/orders/{id}', [OrderController::class,'show']);
});