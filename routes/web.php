<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KYCController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\CheckSellerKyc;
use App\Http\Middleware\CheckSellerKycApproved;

Route::get('/Login', function () {
    return view('Auth.login');
})->name("login");

Route::post('login', [AuthController::class, 'login']);
Route::get('/signup/{role?}', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::middleware(['custom_auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware([CheckSellerKycApproved::class])->group(function () {
        // KYC ROUTES
        Route::controller(KYCController::class)->group(function () {
            Route::get('/Kyc-profile', 'kycView')->name('kycView');
        });
        Route::get('/profile-detail', [UserController::class, 'profileDetail'])->name('ProfileDetail');
        Route::get('/create-profile', function () {
            return view('Auth.CreateProfile');
        })->name("CreateProfile");
        Route::post('/submit-profile', [UserController::class, 'KYC_Authentication'])->name('KYC_Authentication');
    });


    Route::middleware([CheckSellerKyc::class])->group(function () {

        Route::get('/create-store', function () {
            return view('Auth.CreateStore');
        })->name("CreateStore");
        Route::post('upload-images', [ProductsController::class, 'getFileName'])->name('upload.images');

        Route::post('/submit-product', [ProductsController::class, 'storeProduct'])->name('product.store');
        Route::get('/', function () {
            return view('pages.dashboard');
        })->name('dashboard');

        //Product Routes
        Route::controller(ProductsController::class)->group(function () {
            Route::GET('/products', 'showAllProducts')->name('products');
            Route::GET('/delete-product/{id}', 'deleteProduct');
            Route::GET('/view-product/{id}', 'viewProductDetails');
            Route::GET('/other-seller-product', 'getOtherSellerProduct')->name('other-seller-product');
            Route::POST('/update-product-status', 'updateStatus');
        });

        // KYC APPROVE
        Route::controller(KYCController::class)->group(function () {
            Route::get('/KYC', 'kycData')->name('KYC_auth');
            Route::get('/KYC-data/{id}', 'kycDataSelect')->name('kycDataSelect');
            Route::post('/approve-kyc', 'approveKyc')->name('approveKyc');
        });

        // Order Group
        Route::controller(OrderController::class)->group(function () {
            Route::GET('/Orders', 'GetOrders')->name('allorders');
            Route::GET('/view-order/{Order_id}', 'GetOrderWithProducts');
        });


        Route::POST('editStoreProfile', [StoreController::class, 'editStoreProfile'])->name('editStoreProfile');
        Route::GET('view-store/{userId}', [StoreController::class, 'GetStoreInfo']);

        Route::get('/PackagesOffer', function () {
            return view('pages.PackagesOffer');
        })->name('PackagesOffer');

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


        Route::GET('/product/add/{editid?}', [ProductsController::class, 'getProductwithCategories'])->name('product.add');
        Route::GET('/get-subcategories/{category_id}', [ProductsController::class, 'getSubCategories']);

        Route::view('/PurchaseProducts', 'seller.PurchaseProducts')->name('PurchaseProducts');
        Route::view('/Purchases', 'seller.Purchases')->name('savePurchases');
        Route::view('/BoostProducts', 'seller.BoostProducts')->name('BoostProducts');
        Route::view('/Inquiries', 'seller.inquiries')->name('inquirieslist');
        Route::view('/FreelancerProfile', 'admin.FreelancerProfile')->name('FreelancerProfile');
        Route::view('/SellerProfile', 'admin.SellerProfile')->name('SellerProfile');
        Route::view('/BuyerProfile', 'admin.BuyerProfile')->name('BuyerProfile');
        // Route::view('/mystore', 'seller.Store')->name('mystore');
        Route::GET('/mystore', [StoreController::class, 'getStoreDetails'])->name('getStoreDetails');
    });
});
