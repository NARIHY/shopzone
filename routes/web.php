<?php

use App\Http\Controllers\Access\WorkOSAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Exceptions\ErrorController;
use App\Http\Middleware\Access\AssingUsersGroups;
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
    'web',
    AssingUsersGroups::class,
    ValidateSessionWithWorkOS::class,
])->name('admin.')->prefix('nerkaly/')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('revalis', [DashboardController::class, 'adminDashboard'])->name('adminDashboard');
    Route::get('drive/manager', [\App\Http\Controllers\Files\MediaController::class, 'drive'])->name('media.drive');
    Route::get('contacts', [\App\Http\Controllers\Contact\ContacController::class, 'index'])->name('contact.index');
    
    Route::resource('product-categories', \App\Http\Controllers\Shop\ProductCategoryController::class)->names('product-categories');
    Route::resource('media', \App\Http\Controllers\Files\MediaController::class)->parameters(['media' => 'media'])->names('media');
    Route::resource('roles', \App\Http\Controllers\Access\RoleController::class)->names('roles');
    Route::resource('groups', \App\Http\Controllers\Access\GroupController::class)->names('groups');
    Route::resource('products', \App\Http\Controllers\Shop\ProductController::class)->names('products');
    Route::resource('permissions', \App\Http\Controllers\Access\PermissionController::class)->names('permissions');


    Route::get('utils/verify-user-groups-to-attache-client/v1/userId:{userId}-part56', [\App\Http\Controllers\Access\UtilsUsersController::class, 'verifyUserGroupsToAttacheCLient'])->name('utils.verifyUserGroupsToAttacheClient');

    Route::prefix('workingos/manage-users/group-users/')->name('groupUsers.')->group(function () {
        Route::get('', [\App\Http\Controllers\Access\GroupUserController::class, 'index'])->name('index');
    });
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
require __DIR__.'/codeBrowser.php';
