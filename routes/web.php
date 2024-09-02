<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Route::get('/', function () {
//     return view('products');
// });

Route::get('/',[ProductController::class, 'products'])->name('products');
