<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('Auth.login');
});


// Route::middleware(['custom_auth'])->group(function () {
Route::get('/', function () {
    return view('layout');
});

Route::get('products', function () {
    return view('pages.products');
});
// });
