<?php

namespace App\Providers;

use App\Models\Access\Role;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         View::composer('*', function ($view) {
            $view->with('rolesInput', Role::all());
            $view->with('productCategoriesInput', \App\Models\Shop\ProductCategory::all());
            $view->with('mediaInput', \App\Models\Files\Media::all());

        });
    }
}
