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

    public function toWebp(Media $media, int $quality = 80): Media
    {
        $disk = $media->disk;
        $path = $media->path;

        // read image from file system
        $image = $this->imageManager->read(Storage::disk($disk)->path($path));

        $newPath = pathinfo($path, PATHINFO_FILENAME) . '.webp';

        // convert and save modified image in webp format
        $webpContent = $image->toWebp($quality);

        Storage::disk($disk)->put($newPath, (string) $webpContent);

        return Media::create([
            'title'         => $media->title,
            'path'          => $newPath,
            'disk'          => $disk,
            'mime_type'     => 'image/webp',
            'size'          => Storage::disk($disk)->size($newPath),
            'original_name' => basename($newPath),
        ]);
    }
}