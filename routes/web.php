<?php

use App\Http\Controllers\Api\apiStoreController;
use App\Http\Controllers\CreditRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KYCController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\CheckSellerKyc;
use App\Http\Middleware\CheckSellerKycApproved;
use App\Http\Middleware\FetchNotifications;

Route::get('/Login', function () {
    return view('Auth.login');
})->name("login");
Route::get('/forgot_password', function () {
    return view('Auth.forgotPassword');
})->name("forgot_Password");


Route::post('login', [AuthController::class, 'login']);
Route::post('/forgot/password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
Route::get('/forgot/password/{token}', [AuthController::class, 'showLinkForm'])->name('forgot.password.Link');
Route::post('/reset/password/email', [AuthController::class, 'resetPassword'])->name('reset.password');
Route::get('/signup/{role?}', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::middleware(['custom_auth'])->group(function () {
    Route::middleware([FetchNotifications::class])->group(function () {
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
            Route::GET('/', [AuthController::class, 'dashboard'])->name('dashboard');
            // Route::get('/', function () {
            //     return view('pages.dashboard');
            // })->name('dashboard');

            //Product Routes
            Route::controller(ProductsController::class)->group(function () {
                Route::GET('/products', 'showAllProducts')->name('products');
                Route::GET('/HibuyProduct', 'showHibuyProducts')->name('hibuy_product');
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
                Route::post('/reject-kyc', 'rejectKyc')->name('rejectKyc');
            });
            // Notification
            Route::controller(NotificationController::class)->group(function () {
                Route::post('/send-notification', 'insert')->name('send-notification');
                Route::get('/Notifications', 'show')->name('notifications');
                Route::get('/delete-notification/{id}', 'delete')->name('del_notification');
            });

            // Order Group
            Route::controller(OrderController::class)->group(function () {
                Route::GET('/Orders', 'GetOrders')->name('allorders');
                Route::GET('/view-order/{Order_id}', 'GetOrderWithProducts');
                Route::post('/orders/update-status', [OrderController::class, 'updateOrderStatus'])->name('orders.update.status');
            });


            Route::POST('editStoreProfile', [StoreController::class, 'editStoreProfile'])->name('editStoreProfile');
            Route::GET('view-store/{userId}', [StoreController::class, 'GetStoreInfo']);
            Route::GET('/Queries', [StoreController::class, 'getQuery'])->name("queries");
            Route::POST('/query-data/{id}', [StoreController::class, 'updateQuery'])->name("query.update");

            Route::GET('/HelpCenter', [UserController::class, 'HelpCenter'])->name("HelpCenterQuestions");

            Route::post('/faq-category/store', [UserController::class, 'storeFaqCategory'])->name('faq-category.store');

            Route::post('/faqs/store', [UserController::class, 'storeFaqs'])->name('faq.store');
            Route::get('/faqs/delete/{id}', [UserController::class, 'deleteFaqs'])->name('faq.delete');
            Route::get('/faqs/edit/{faq_id}', [UserController::class, 'getFaq'])->name('faq.edit');

            Route::GET('/faq-category/view', [UserController::class, 'viewFaqCategory'])->name('faq-category.view');

            Route::GET('/SellerManagement', [UserController::class, 'sellerManagement'])->name("manage_seller");
            Route::GET('/FreelancersManagement', [UserController::class, 'freelancerManagement'])->name("manage_freelancer");
            Route::GET('/FreelancerProfile/{id}', [SellerController::class, 'getSellerDetail'])->name("FreelancerProfile");
            Route::GET('/BuyersManagement', [UserController::class, 'getBuyerData'])->name("manage_buyer");
            Route::GET('/BuyerProfile/{id}', [UserController::class, 'getBuyerDetails'])->name("BuyerProfile");

            Route::get('/PackagesOffer', function () {
                return view('pages.PackagesOffer');
            })->name('PackagesOffer');

            Route::get('/ReturnOrders', function () {
                return view('pages.ReturnOrders');
            })->name('return_orders');

            Route::get('/CreditRequest', function () {
                return view('pages.CreditRequest');
            })->name('credit_request');


            // Route::get('/Promotions', function () {
            //     return view('admin.Promotions');
            // })->name('promotion_list');




            // Route::get('/Settings', function () {
            //     return view('pages.Settings');
            // })->name('editsettings');

            Route::post('/ProductCategory', [ProductsController::class, 'categories'])->name('productCategory');
            Route::get('/ProductCategory', [ProductsController::class, 'showcat'])->name('addProductCategory');
            Route::get('/fetch-category/{id}', [ProductsController::class, 'fetchCategory']);
            Route::get('/deleteProductCategory/{id}', [ProductsController::class, 'deleteCategory']);
            Route::get('/ProductCategory/getforupdate/{id}', [ProductsController::class, 'getForUpdate'])->name('getforupdate');
            Route::post('/ProductCategory/update/{id}', [ProductsController::class, 'update']);
            Route::GET('/SellerProfile/{sellerId}', [SellerController::class, 'getSellerDetail'])->name('SellerProfile');
            Route::get('/refresh-csrf-token', function () {
                return response()->json(['csrf_token' => csrf_token()]);
            });


            Route::GET('/product/add/{editid?}', [ProductsController::class, 'getProductwithCategories'])->name('product.add');
            Route::GET('/get-subcategories/{category_id}', [ProductsController::class, 'getSubCategories']);

            Route::view('/PurchaseProducts', 'seller.PurchaseProducts')->name('PurchaseProducts');
            Route::view('/all-notifications', 'pages.AllNotifications')->name('allNotifications');
            Route::view('/Purchases', 'seller.Purchases')->name('savePurchases');
            Route::view('/Inquiries', 'seller.Inquiries')->name('inquirieslist');
            // Route::view('/BuyerProfile', 'admin.BuyerProfile')->name('BuyerProfile');
            Route::GET('/mystore', [StoreController::class, 'getStoreDetails'])->name('getStoreDetails');
            Route::post('/save-transaction-image', [SellerController::class, 'saveTransactionImage']);
            Route::get('/promotions', [SellerController::class, 'showPromotions'])->name('promotions');
            Route::post('/update-package-status', [SellerController::class, 'updatePackageStatus'])->name('update.package.status');
            Route::get('/BoostProducts', [SellerController::class, 'BoostStatus'])->name('BoostProducts');
            Route::post('/product/boost/{id}', [ProductsController::class, 'boost'])->name('product.boost');
            Route::post('/send-inquiry', [SellerController::class, 'store']);
            Route::get('/seller/purchases', [SellerController::class, 'purchases'])->name('seller.purchases');
            Route::get('/seller/inquiries', [SellerController::class, 'inquiries'])->name('seller.inquiries');
            Route::post('/save-Inquiry-image', [SellerController::class, 'saveInquiryImage'])->name('save.inquiry.image');
            Route::get('/inquiry-details/{id}', [SellerController::class, 'purchasesWithProductDetails']);
            Route::post('/update-inquiry-status', [SellerController::class, 'updateInquiryStatus'])->name('update.inquiry.status');

            // Credit request
            Route::post('/credit-request', [CreditRequestController::class, 'store'])->name('credit-request.store');
            Route::get('/credit_request', [CreditRequestController::class, 'index'])->name('credit-requests');
            Route::get('/credit-request/{id}', [CreditRequestController::class, 'show'])->name('credit-request.show');
            Route::post('/credit/update-status', [CreditRequestController::class, 'updateStatus'])->name('credit.updateStatus');


            Route::GET('/Settings', [UserController::class, 'settings'])->name("settings");
            Route::POST('/updatePersonalInfo', [UserController::class, 'updatePersonalInfo'])->name("updatePersonalInfo");
            Route::POST('/updateUserPassword', [UserController::class, 'updateUserPassword'])->name("updateUserPassword");
        });
    });
});
