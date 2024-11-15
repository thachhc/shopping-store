<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CodeSizeController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


//user routes
Route::middleware(['auth', 'userMiddleware'])->group(function () {

    Route::get('dashboard', [UserController::class, 'index'])->name('dashboard');

});

//admin routes
Route::middleware(['auth', 'adminMiddleware'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

    Route::resource('/admin/products', ProductController::class);
    Route::resource('/brands', BrandController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/tags', TagController::class);
    Route::post('/products/{id}/delete-image', [ProductController::class, 'deleteImage'])->name('products.deleteImage');

    Route::get('/codesizes/create', [CodeSizeController::class, 'create'])->name('codesizes.create');

    // Route để lưu size và quantity
Route::post('/codesizes/store', [CodeSizeController::class, 'store'])->name('codesizes.store');
Route::get('codesizes/{id}/edit', [CodeSizeController::class, 'edit'])->name('codesizes.edit');
Route::put('codesizes/{id}', [CodeSizeController::class, 'update'])->name('codesizes.update');
Route::get('/codesizes/editByProduct/{product_id}', [CodeSizeController::class, 'editByProduct'])->name('codesizes.editByProduct');
Route::put('/codesizes/updateByProduct/{product_id}', [CodeSizeController::class, 'updateByProduct'])->name('codesizes.updateByProduct');

// Route chỉnh sửa danh mục
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');

// Route cập nhật danh mục
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

});
