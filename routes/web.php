<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;


Route::get('/Login', function () {
    return view('Auth.login');
});

// Route::middleware(['custom_auth'])->group(function () {
Route::get('/', function () {
    return view('layout');
})->name('dashboard');

Route::controller(ProductsController::class)->group(function () {
    Route::get('/products', 'index')->name('products');
});


Route::get('/PackagesOffer', function () {
    return view('pages.PackagesOffer');
})->name('PackagesOffer');

Route::get('/KYC', function () {
    return view('admin.KYC');
})->name('KYC_auth');

Route::get('/Orders', function () {
    return view('pages.Orders');
})->name('allorders');

Route::get('/ReturnOrders', function () {
    return view('pages.ReturnOrders');
})->name('return_orders');

Route::get('/SellerManagement', function () {
    return view('admin.SellerManagement');
})->name('manage_seller');

Route::get('/BuyersManagement', function () {
    return view('admin.BuyersManagement');
})->name('manage_buyer');

Route::get('/FreelancersManagement', function () {
    return view('admin.FreelancersManagement');
})->name('manage_freelancer');

Route::get('/CreditRequest', function () {
    return view('pages.CreditRequest');
})->name('credit_request');

Route::get('/HibuyProduct', function () {
    return view('admin.HibuyProduct');
})->name('hibuy_product');

Route::get('/Promotions', function () {
    return view('admin.Promotions');
})->name('promotion_list');

Route::get('/Queries', function () {
    return view('pages.Queries');
})->name('queries');

Route::get('/Notifications', function () {
    return view('pages.Notifications');
})->name('notifications');

Route::get('/Settings', function () {
    return view('pages.Settings');
})->name('editsettings');
