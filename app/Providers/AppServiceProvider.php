<?php

namespace App\Providers;

use App\Models\Access\Group;
use App\Models\Access\Role;
use App\Models\Files\Media;
use App\Models\User;
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
        //Observers
        Role::observe(\App\Observers\RoleObserver::class);
        Media::observe(\App\Observers\MediaObserver::class);
        User::observe(\App\Observers\UserObserver::class);

        // Partage global des données mises en cache pour les formulaires
        View::composer('*', function ($view) {

            // Roles avec groupes (limité aux colonnes nécessaires)
            $rolesInput = Cache::remember('roles_input', 600, function () {
                return Role::with(['groups:id,name'])->get(['id','roleName']);
            });

            // Groups avec roles et users (limité)
            $groupsInput = Cache::remember('groups_input', 600, function () {
                return Group::with([
                    'roles:id,roleName',
                    'users:id,name'
                ])->get(['id','name']);
            });

            // Product Categories avec produits
            $productCategoriesInput = Cache::remember('product_categories_input', 600, function () {
                return \App\Models\Shop\ProductCategory::with([
                    'products:id,name'
                ])->get(['id','name']);
            });

            // Media avec produits liés
            $mediaInput = Cache::remember('media_input', 600, function () {
                return \App\Models\Files\Media::with([
                    'products:id,name'
                ])->get(['id','title']); // ou 'name' selon ta colonne
            });

            // Users avec leurs groupes et rôles
            $usersInput = Cache::remember('users_input', 600, function () {
                return User::with(['groups.roles:id,roleName'])->get(['id','name','email']);
            });

            $view->with(compact(
                'rolesInput',
                'productCategoriesInput',
                'mediaInput',
                'groupsInput',
                'usersInput'
            ));
        });
    }

}
