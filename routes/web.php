<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Managing the account;

Route::prefix('/account')->group(function () {
    Route::get('/{user}', [App\Http\Controllers\AccountController::class, 'index'])->name('account.home');
    Route::get('/{user}/edit', [App\Http\Controllers\AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/{user}/update', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');
    Route::delete('/{user}/delete', [App\Http\Controllers\AccountController::class, 'delete'])->name('account.delete');
});


// Managing the products;

Route::prefix('/product')->group(function () {
    Route::get('/{product}', [App\Http\Controllers\ProductsController::class, 'index'])->name('product');
    Route::get('/{product}/edit', [App\Http\Controllers\ProductsController::class, 'edit'])->name('product.edit');
    Route::post('/store', [App\Http\Controllers\ProductsController::class, 'store'])->name('product.store');
    Route::patch('/{product}/{uuid}/update', [App\Http\Controllers\ProductsController::class, 'update'])->name('product.update');
    Route::delete('/delete/{product}', [App\Http\Controllers\AddressesController::class, 'delete'])->name('product.delete');
});

// Managing the photos;

Route::prefix('/photo')->group(function () {
    Route::get('/show', [App\Http\Controllers\PhotoController::class, 'show'])->name('photo.show');
    Route::get('/{photo}/delete', [App\Http\Controllers\PhotoController::class, 'delete'])->name('photo.delete');
});

// Managing the addresses;

Route::prefix('/address')->group(function () {
    Route::post('/store', [App\Http\Controllers\AddressesController::class, 'store'])->name('address.store');
    Route::get('/{address}/edit', [App\Http\Controllers\AddressesController::class, 'edit'])->name('address.edit');
    Route::patch('/{address}/update', [App\Http\Controllers\AddressesController::class, 'update'])->name('address.update');
    Route::delete('/{address}', [App\Http\Controllers\AddressesController::class, 'delete'])->name('address.delete');
});

// Managing the wishlist;

Route::prefix('/favorites')->group(function () {
    Route::post('/store/{product_id}', [App\Http\Controllers\FavoritesController::class, 'store'])->name('favorites.store');
    Route::delete('/remove/{product_id}', [App\Http\Controllers\FavoritesController::class, 'remove'])->name('favorites.remove');
    Route::get('/{user}', [App\Http\Controllers\FavoritesController::class, 'index'])->name('favorites.index');
});

// Managing the shopping carts;

Route::prefix('/cart')->group(function () {
    Route::get('/{user}', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/store/{product_id}', [App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
    Route::delete('/remove/{product_id}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/increment/{product_id}', [App\Http\Controllers\CartController::class, 'increment'])->name('cart.increment');
    Route::patch('/decrement/{product_id}', [App\Http\Controllers\CartController::class, 'decrement'])->name('cart.decrement');
});

// Managing orders;

Route::prefix('/order')->group(function () {
    Route::post('/store/{user}', [App\Http\Controllers\OrdersController::class, 'store'])->name('order.store');
    Route::get('/show', [App\Http\Controllers\OrdersController::class, 'show'])->name('order.show');
    Route::patch('/update/{order}', [App\Http\Controllers\OrdersController::class, 'update'])->name('order.update');
});

// Managing the reviews;

Route::prefix('/reviews')->group(function () {
    Route::post('/store/{product}', [App\Http\Controllers\ReviewsController::class, 'store'])->name('reviews.store');
    Route::patch('/update/{review}', [App\Http\Controllers\ReviewsController::class, 'update'])->name('reviews.update');
    Route::delete('/delete/{review}', [App\Http\Controllers\ReviewsController::class, 'delete'])->name('reviews.delete');

});

// Managing the comments;

Route::prefix('/comments')->group(function () {
    Route::post('/store/{productId}/{reviewId}', [App\Http\Controllers\CommentsController::class, 'store'])->name('comment.store');
    Route::patch('/update/{comment}', [App\Http\Controllers\CommentsController::class, 'update'])->name('comment.update');
    Route::delete('/delete/{comment}', [App\Http\Controllers\CommentsController::class, 'delete'])->name('comment.delete');
});
