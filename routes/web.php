<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductManController;
use App\Http\Controllers\UserController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

//Customer segment use
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CouponController;
//

// Authentication routes
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::post('/register', [AuthController::class, 'store']);
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

// Dashboard routes
Route::get('/admin/admin_dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/customer/dashboard', [AuthController::class, 'custDashboard'])->name('customer.dashboard');

// Public-facing shop routes
Route::controller(ClientController::class)->group(function () {
    Route::get('/', 'index')->name('clientHome');
    Route::get('/products', 'products')->name('clientProducts');
    Route::get('/product/{id}', 'productDetail')->name('clientProductDetail'); // Naka-ID na
    Route::get('/categories', 'category')->name('clientCategory');
    Route::get('/category/{id}', 'categoryProducts')->name('clientCategoryProducts'); // Naka-ID na
    Route::get('/about', 'about')->name('clientAbout');
    Route::post('/search', 'searchProduct')->name('clientSearch');
});

// Cart actions (these remain public for guests to build their cart)
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'carts')->name('clientCart');
    Route::post('/cart/add', 'addToCart')->name('clientAddToCart');
    Route::post('/cart/update', 'updateCart')->name('clientUpdateCart');
    Route::post('/cart/delete', 'deleteCart')->name('clientDeleteCart');
});

//admin routes products
Route::get('/admin/products', [ProductManController::class, 'index'])->name('admin.product_management');
Route::get('/admin/product/create', [ProductManController::class, 'create'])->name('admin.product_create');
Route::post('/admin/product/store', [ProductManController::class, 'store'])->name('admin.product_store');
Route::get('/admin/products/{id}/edit', [ProductManController::class, 'edit'])->name('admin.product_edit');
Route::put('/admin/products/{id}', [ProductManController::class, 'update'])->name('admin.product_update');
Route::delete('/admin/products/{id}', [ProductManController::class, 'destroy'])->name('admin.product_destroy');

//admin routes category
Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category_management');
Route::get('/admin/category/create', [CategoryController::class, 'create'])->name('admin.category_create');
Route::post('/admin/category/store', [CategoryController::class, 'store'])->name('admin.category_store');
Route::get('/admin/category/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category_edit');
Route::put('/admin/category/{id}', [CategoryController::class, 'update'])->name('admin.category_update');
Route::delete('/admin/category/{id}', [CategoryController::class, 'destroy'])->name('admin.category_destroy');

//admin routes orders
Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders_management');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders_destroy');

//Customer routes
Route::get('/products/{id}', [ProductController::class, 'show1']);//error
Route::post('/cart/add', [CartController::class, 'add']);//on-test
Route::get('/cart', [CartController::class, 'index']);//on-test
Route::post('/checkout', [OrderController::class, 'checkoutC']);//on-test
Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);//on-test
Route::get('/order/{id}', [OrderController::class, 'showC']);//on-test
Route::get('/orders/history', [OrderController::class, 'historyC']);//on-test
Route::post('/products/{id}/review', [ReviewController::class, 'store']);//error
Route::post('/coupon/apply', [CouponController::class, 'apply']);//error