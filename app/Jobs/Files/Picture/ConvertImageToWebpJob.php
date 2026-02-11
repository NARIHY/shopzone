<?php

namespace App\Jobs\Files\Picture;

use App\Models\Files\Media;
use App\Services\Image\ImageProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ConvertImageToWebpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Media $media
    ) {}

    public function handle(ImageProcessor $imageProcessor)
    {
        $imageProcessor->toWebp($this->media);
    }
}
