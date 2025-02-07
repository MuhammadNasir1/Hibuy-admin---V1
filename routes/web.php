<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Auth.login');
});
// Route::get('/login', function () {
//     return view('login');
// });
