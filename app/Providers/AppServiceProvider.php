<?php

namespace App\Providers;

use App\Models\Access\Role;
use App\Services\CachedData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->singleton('cachedData', function () {
            return new CachedData();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    View::composer('*', function ($view) {
        // Charger et mettre en cache pendant 10 minutes
        $rolesInput = Cache::remember('roles_input', 600, function () {
            return Role::with('groups:id,name')->get(); // limiter les colonnes si possible
        });

        $productCategoriesInput = Cache::remember('product_categories_input', 600, function () {
            return \App\Models\Shop\ProductCategory::with('products:id,name')->get();
        });

        $mediaInput = Cache::remember('media_input', 600, function () {
            return \App\Models\Files\Media::with('products:id,name')->get();
        });

        $view->with([
            'rolesInput' => $rolesInput,
            'productCategoriesInput' => $productCategoriesInput,
            'mediaInput' => $mediaInput,
        ]);
    });
}

}
