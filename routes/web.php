<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


//user routes
Route::middleware(['auth', 'userMiddleware'])->group(function () {
    Route::get('dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::get('/', [UserController::class, 'home'])->name('welcome');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

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
    Route::get('/order/{id}', [CartController::class, 'order'])->name('order.index');
});
//admin routes
Route::middleware(['auth', 'adminMiddleware'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::resource('/admin/products', ProductController::class);
    Route::post('/products/{id}/delete-image', [ProductController::class, 'deleteImage'])->name('products.deleteImage');

    Route::resource('/brands', BrandController::class);

    Route::resource('/categories', CategoryController::class);

    Route::resource('/tags', TagController::class);

    // Route chỉnh sửa danh mục
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');

    // Route cập nhật danh mục
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
});
