<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    /**
     * Création utilisateur
     */
    public function created(User $user): void
    {
        $this->clearCache();
    }

    /**
     * Mise à jour utilisateur
     */
    public function updated(User $user): void
    {
        $this->clearCache();
    }

    /**
     * Suppression utilisateur
     */
    public function deleted(User $user): void
    {
        $this->clearCache();
    }

    /**
     * Restauration (si SoftDeletes)
     */
    public function restored(User $user): void
    {
        $this->clearCache();
    }

    /**
     * Suppression définitive (si SoftDeletes)
     */
    public function forceDeleted(User $user): void
    {
        $this->clearCache();
    }

    /**
     * Invalidation centralisée
     */
    private function clearCache(): void
    {
        Cache::forget('users_input');

        // Dépendances
        Cache::forget('groups_input');
        Cache::forget('roles_input');
    }
}
