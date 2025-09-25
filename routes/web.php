<?php

use App\Http\Controllers\Exceptions\ErrorController;
use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::prefix('/')->name('public.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Public\PublicController::class, 'home'])->name('home');
    Route::get('/about', [\App\Http\Controllers\Public\PublicController::class, 'about'])->name('about');

    Route::get('/contact', [\App\Http\Controllers\Public\PublicController::class, 'contact'])->name('contact');
    Route::post('/contact', [\App\Http\Controllers\Public\PublicController::class, 'storeContact'])->name('storeContact');
});

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->name('admin.')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::resource('product-categories', \App\Http\Controllers\Shop\ProductCategoryController::class)->names('product-categories');
    Route::resource('media', \App\Http\Controllers\Files\MediaController::class)->names('media');
});


// ERROR
Route::prefix('/Errors')->name('errors.')->group(function () {
    Route::get('/401', [ErrorController::class, 'error401'])->name('401');
    Route::get('/403', [ErrorController::class, 'error403'])->name('403');
    Route::get('/404', [ErrorController::class, 'error404'])->name('404');
    Route::get('/500', [ErrorController::class, 'error500'])->name('500');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
