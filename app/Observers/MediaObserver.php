<?php

namespace App\Observers;

use App\Models\Files\Media;
use Illuminate\Support\Facades\Cache;

class MediaObserver
{
    /**
     * Création d’un média
     */
    public function created(Media $media): void
    {
        Cache::forget('media_input');
    }

    /**
     * Mise à jour d’un média
     */
    public function updated(Media $media): void
    {
        Cache::forget('media_input');
    }

    /**
     * Suppression d’un média
     */
    public function deleted(Media $media): void
    {
        Cache::forget('media_input');
    }

    /**
     * Restauration (si SoftDeletes)
     */
    public function restored(Media $media): void
    {
        Cache::forget('media_input');
    }

    /**
     * Suppression définitive (si SoftDeletes)
     */
    public function forceDeleted(Media $media): void
    {
        Cache::forget('media_input');
    }
}
