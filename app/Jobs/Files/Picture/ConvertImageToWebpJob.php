<?php

namespace App\Jobs\Files\Picture;

use App\Models\Files\Media;
use App\Services\Image\ImageProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertImageToWebpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Media $media
    ) {}

    public function handle()
    {
        Media::where('mime_type', 'like', 'image/%')
            ->where('mime_type', '!=', 'image/webp')
            ->chunkById(100, function ($medias) {
                foreach ($medias as $media) {
                    ConvertImageToWebpJob::dispatch($media);
                }
            });
    }

}