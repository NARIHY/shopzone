<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Shop\ProductCategory;
use App\Models\Access\Role;
use App\Models\Files\Media;

class CachedData
{
    /**
     * Cache interne en mémoire (par requête)
     */
    protected array $localCache = [];

    /**
     * Récupère les catégories produits
     */
    public function categories(): array
    {
        return $this->remember('categories', 'shopzone_cache_product_categories_input', function () {
            return ProductCategory::pluck('name', 'id')->toArray();
        });
    }

    /**
     * Récupère les rôles
     */
    public function roles(): array
    {
        return $this->remember('roles', 'shopzone_cache_roles_input', function () {
            return Role::pluck('name', 'id')->toArray();
        });
    }

    /**
     * Récupère les médias
     */
    public function media(): array
    {
        return $this->remember('media', 'shopzone_cache_media_input', function () {
            return Media::pluck('name', 'id')->toArray();
        });
    }

    /**
     * Petite méthode utilitaire DRY
     */
    protected function remember(string $localKey, string $cacheKey, \Closure $callback, int $minutes = 1440): array
    {
        // Si déjà en mémoire pour cette requête
        if (isset($this->localCache[$localKey])) {
            return $this->localCache[$localKey];
        }

        // Sinon on vérifie le cache persistant Laravel
        return $this->localCache[$localKey] = Cache::remember($cacheKey, now()->addMinutes($minutes), $callback);
    }
}
