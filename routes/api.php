<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\apiAuthController;
use App\Http\Controllers\Api\apiStoreController;
use App\Http\Controllers\Api\apiproductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::POST('register', [AuthController::class, 'register']);
Route::POST('login', [apiAuthController::class, 'login']);
Route::POST('setPassword', [UserController::class, 'setPassword']);


Route::GET('getCategories', [apiproductController::class, 'getCategories']);
Route::match(['get', 'post'], 'getProducts/{categoryid?}', [apiproductController::class, 'getProducts']);
Route::GET('getProductsDetail', [apiproductController::class, 'getProductsDetail']);
Route::GET('getStoreDetails', [apiStoreController::class, 'getStoreDetails']);
Route::GET('getStoreList', [apiStoreController::class, 'getStoreList']);
Route::GET('searchProducts', [apiproductController::class, 'searchProducts']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::POST('storeReview', [apiAuthController::class, 'storeReview']);

    Route::GET('/GetOrders', [OrderController::class, 'GetOrders']);

    Route::GET('/GetOrderDetail', [OrderController::class, 'GetOrderDetail']);

    Route::POST('/placeOrder', [OrderController::class, 'placeOrder']);

    Route::POST('editProfile', [apiAuthController::class, 'editProfile']);

    Route::GET('userdetail', [apiAuthController::class, 'userdetail']);

    Route::POST('toggleWishlist', [apiproductController::class, 'toggleWishlist']);

    Route::GET('getWishlist', [apiproductController::class, 'getWishlist']);

    Route::POST('storeAddress', [apiAuthController::class, 'storeAddress']);

    Route::POST('DeleteAddress', [apiAuthController::class, 'DeleteAddress']);

    Route::POST('KYC_Authentication', [UserController::class, 'KYC_Authentication']);
    Route::POST('query/add', [apiAuthController::class, 'addQuery']);

    Route::POST('logout', [apiAuthController::class, 'logout']);
});
