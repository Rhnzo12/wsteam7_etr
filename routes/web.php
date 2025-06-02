<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductManController;
use App\Http\Controllers\CartCustController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

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

Route::get('/cartcust', [CartCustController::class, 'carts'])->name('customerCart');

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/checkout', [OrderController::class, 'showCheckOut'])->name('customerCheckout');
// Route::get('/order-history', [OrderController::class, 'orderHistory'])->name('order.history');
Route::get('/order-history', [OrderController::class, 'orderHistory'])->name('customer.orderHistory');



Route::get('/products1', [CustomerController::class, 'products'])->name('customerProducts');
Route::get('/product1/{id}', [CustomerController::class, 'productDetail'])->name('customerProductDetail');
Route::get('/categories1', [CustomerController::class, 'category'])->name('customerCategory');
Route::get('/categories1/{id}', [CustomerController::class, 'categoryProducts'])->name('customerCategoryProducts');
Route::get('/about1', [CustomerController::class, 'about'])->name('customerAbout');
Route::post('/search1', [CustomerController::class, 'searchProduct'])->name('customerSearch');

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

//profile routes
Route::get('/profile', [CustomerController::class, 'showProfile'])->name('customer.profile');
Route::get('/profile/edit', [CustomerController::class, 'editProfile'])->name('customer.profile.edit');
Route::post('/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');