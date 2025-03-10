<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

Route::get('/Login', function () {
    return view('Auth.login');
})->name("login");
Route::get('/signup', function () {
    return view('Auth.signup');
})->name("signup");

Route::post('login', [AuthController::class, 'login']);



Route::middleware(['custom_auth'])->group(function () {

    Route::post('upload-images', [ProductsController::class, 'getFileName'])->name('upload.images');

    Route::post('/submit-product', [ProductsController::class, 'storeProduct'])->name('product.store');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/create-store', function () {
        return view('Auth.CreateStore');
    })->name("CreateStore");
    Route::get('/create-profile', function () {
        return view('Auth.CreateProfile');
    })->name("CreateProfile");
    Route::get('/profile-detail', function () {
        return view('Auth.ProfileDetail');
    })->name("ProfileDetail");

    // Route::middleware(['custom_auth'])->group(function () {
    Route::get('/', function () {
        return view('pages.dashboard');
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

    Route::post('/ProductCategory', [ProductsController::class, 'categories'])->name('productCategory');
    Route::get('/ProductCategory', [ProductsController::class, 'showcat'])->name('addProductCategory');
    Route::get('/fetch-category/{id}', [ProductsController::class, 'fetchCategory']);
    Route::get('/deleteProductCategory/{id}', [ProductsController::class, 'deleteCategory']);
    Route::get('/ProductCategory/getforupdate/{id}', [ProductsController::class, 'getForUpdate'])->name('getforupdate');
    Route::post('/ProductCategory/update/{id}', [ProductsController::class, 'update']);

    // Add Product

    Route::view('/PurchaseProducts', 'seller.PurchaseProducts')->name('PurchaseProducts');

    Route::view('/Purchases', 'seller.Purchases')->name('savePurchases');
    Route::view('/BoostProducts', 'seller.BoostProducts')->name('BoostProducts');
    Route::view('/Inquiries', 'seller.inquiries')->name('inquirieslist');
    Route::view('/FreelancerProfile', 'admin.FreelancerProfile')->name('FreelancerProfile');
    Route::view('/SellerProfile', 'admin.SellerProfile')->name('SellerProfile');
    Route::view('/BuyerProfile', 'admin.BuyerProfile')->name('BuyerProfile');
    // Route::view('/ProductCategory', 'admin.ProductCategory')->name('addProductCategory');
    Route::view('/product/add', 'pages.AddProduct')->name('product.add');
    Route::view('/mystore', 'seller.Store')->name('mystore');
    Route::view('/other-seller-product', 'seller.OtherSeller')->name('other-seller-product');
});
