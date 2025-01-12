<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\OrderController;
use Illuminate\Container\Attributes\Tag;
use App\Http\Controllers\OrderAdminController;

Route::get('/', [HomepageController::class, 'index'])->name('homepage');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


//user routes
Route::middleware(['auth', 'userMiddleware'])->group(function () {
    Route::get('dashboard', [HomepageController::class, 'index'])->name('dashboard');
    // Route::get('/', [HomepageController::class, 'index'])->name('welcome');
    Route::get('/products', [ProductController::class, 'UserIndex'])->name('browse');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/brand/{brandId}', [ProductController::class, 'productsByBrand'])->name('brand.products');
    Route::get('/category/{categoryId}', [ProductController::class, 'productsByCategory'])->name('productsByCategory');
    
    // Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    // ánh xạ
    Route::get('/user-cart', function () {
        return redirect()->route('cart.index');
    })->name('user.cart');

    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/increase/{id}', [CartController::class, 'increaseQuantity'])->name('cart.increase');
    Route::post('/cart/decrease/{id}', [CartController::class, 'decreaseQuantity'])->name('cart.decrease');

    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::post('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/ordernow', [OrderController::class, 'buyNow'])->name('order.buynow');
    Route::post('/ordernow', [OrderController::class, 'buyNow'])->name('order.buynow');
    Route::post('/order/placenow', action: [OrderController::class, 'placeOrderNow'])->name('order.placenow');

    Route::post('/order/place', action: [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/orderdetails', [OrderController::class, 'show'])->name('order.details');
});

//admin routes
Route::middleware(['auth', 'adminMiddleware'])->group(function () {

    Route::get('/welcome', [HomepageController::class, 'index'])->name('welcome');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::resource('/admin/products', ProductController::class);
    Route::post('/products/{id}/delete-image', [ProductController::class, 'deleteImage'])->name('products.deleteImage');

    Route::resource('/brands', BrandController::class);

    Route::resource('/tags', TagController::class);

    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::resource('/categories', CategoryController::class);

    Route::get('/admin/orders', [OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('/admin/orders/{id}', [OrderAdminController::class, 'show'])->name('orders.show');
    Route::patch('/admin/orders/{id}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders/{order_id}/details', [OrderAdminController::class, 'getProducts']);
    Route::get('/orders/search', [OrderAdminController::class, 'search'])->name('orders.search');
   




});
