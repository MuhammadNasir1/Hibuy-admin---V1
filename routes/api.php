<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Api\apiAuthController;
use App\Http\Controllers\Api\apiproductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [apiAuthController::class, 'login']);
Route::post('setPassword', [UserController::class, 'setPassword']);


Route::get('getCategories', [apiproductController::class, 'getCategories']);
Route::match(['get', 'post'], 'getProducts', [apiproductController::class, 'getProducts']);
Route::get('getProductsDetail', [apiproductController::class, 'getProductsDetail']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::POST('storeReview', [apiAuthController::class, 'storeReview']);

    Route::get('userdetail', [apiAuthController::class, 'userdetail']);

    Route::POST('toggleWishlist', [apiproductController::class, 'toggleWishlist']);

    Route::post('editProfile', [UserController::class, 'editProfile']);

    Route::post('editStoreProfile', [StoreController::class, 'editStoreProfile']);

    Route::post('KYC_Authentication', [UserController::class, 'KYC_Authentication']);

    Route::post('logout', [apiAuthController::class, 'logout']);
});
