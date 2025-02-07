<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/signup', function () {
    return view('signup');
});
Route::get('/layout', function () {
    return view('layout.layout');
});
Route::get('/sidebar', function () {
    return view('sidebar');
});
Route::get('/seller', function () {
    return view('seller/dashboard');
});
Route::get('/seller-product', function () {
    return view('seller/product');
});
Route::get('/seller-packages', function () {
    return view('seller/packages');
});
Route::get('/seller-orders', function () {
    return view('seller/orders');
});
Route::get('/admin', function () {
    return view('admin/dashboard');
});
Route::get('/admin/products', function () {
    return view('admin/product');
});
Route::get('/admin/packages', function () {
    return view('admin/packages');
});
Route::get('/admin/orders', function () {
    return view('seller/orders');
});
Route::get('/admin/orders', function () {
    return view('admin/orders');
});
Route::get('/admin/returns', function () {
    return view('admin/returns');
});
Route::get('/admin/kyc', function () {
    return view('admin/kyc');
});
Route::get('/dextop/dextop1', function () {
    return view('dextop/dextop1');
});
Route::get('/seller-returns', function () {
    return view('seller/returns');
});
Route::get('/seller-queries', function () {
    return view('seller/queries');
});
Route::get('/seller-notifications', function () {
    return view('seller/notifications');
});
Route::get('/seller-store', function () {
    return view('seller/my_store');
});
Route::get('/seller-setting', function () {
    return view('seller/setting');
});
Route::get('/seller-otherproduct', function () {
    return view('seller/other_products');
});
Route::get('/seller-purchases', function () {
    return view('seller/purchases');
});
Route::get('/seller-inquiries', function () {
    return view('seller/inquiries');
});
Route::get('/orders', function () {
    return view('admin/orders');
});
Route::get('/returns', function () {
    return view('admin/returns');
});
Route::get('/admin-credit', function () {
    return view('admin/credit');
});
Route::get('/hibuy-products', function () {
    return view('admin/hibuy_products');
});
Route::get('/admin-queries', function () {
    return view('admin/queries');
});
Route::get('/admin-notifications', function () {
    return view('admin/notifications');
});
Route::get('/admin-setting', function () {
    return view('admin/setting');
});
Route::get('/admin-store', function () {
    return view('admin/hibuy_store');
});
