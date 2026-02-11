<?php

namespace App\Services\Image;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use App\Models\Files\Media;

class ImageProcessor
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function toWebp(Media $media, int $quality = 80): ?Media
    {
        // sécurité
        if ($media->mime_type === 'image/webp') {
            return $media;
        }

        if (! $media->exists()) {
            return null;
        }

        $disk = $media->disk;

        /**
         * IMPORTANT :
         * Laravel Storage utilise toujours "/"
         * On normalise pour éviter les "\" Windows en base
         */
        $oldPath = ltrim(str_replace('\\', '/', $media->path), '/');

        // Chemin absolu OS (Windows/Linux)
        $absoluteOldPath = Storage::disk($disk)->path(
            str_replace('/', DIRECTORY_SEPARATOR, $oldPath)
        );

        // Lire image
        $image = $this->imageManager->read($absoluteOldPath);

        // Dossier + nom fichier
        $dirname  = pathinfo($oldPath, PATHINFO_DIRNAME);
        $filename = pathinfo($oldPath, PATHINFO_FILENAME);

        // Construire nouveau path (toujours "/" pour Laravel)
        $newPath = ($dirname !== '.' ? $dirname.'/' : '') . $filename.'.webp';

        // Encoder webp
        $webpContent = $image->toWebp($quality);

        // Sauvegarder via Storage (PAS DIRECTORY_SEPARATOR ici)
        Storage::disk($disk)->put($newPath, (string) $webpContent);

        // Supprimer ancien fichier
        Storage::disk($disk)->delete($oldPath);

        // Update media existant
        $media->update([
            'path' => $newPath,
            'mime_type' => 'image/webp',
            'size' => Storage::disk($disk)->size($newPath),
            'is_webp' => true,
            'title' => basename($newPath),
            'original_name' => basename($newPath),
        ]);

        return $media->fresh();
    }
}
