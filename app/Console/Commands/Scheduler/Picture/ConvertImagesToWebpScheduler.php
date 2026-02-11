<?php

namespace App\Console\Commands\Scheduler\Picture;

use Illuminate\Console\Command;
use App\Models\Files\Media;
use App\Services\Image\ImageProcessor;

class ConvertImagesToWebpScheduler extends Command
{
    protected $signature = 'app:convert-images-to-webp-scheduler';
    protected $description = 'CONVERT IMAGES TO WEBP FORMAT SCHEDULER';

    public function handle(ImageProcessor $imageProcessor)
    {
        $count = 0;

        $this->info('Début conversion images...');

        Media::images()
            ->where('is_webp', false)
            ->chunkById(100, function ($medias) use (&$count, $imageProcessor) {

                foreach ($medias as $media) {

                    try {
                        $result = $imageProcessor->toWebp($media);

                        if ($result) {
                            $count++;
                        }

                    } catch (\Throwable $e) {
                        $this->error("Erreur media ID {$media->id} : ".$e->getMessage());
                    }
                }
            });

        $this->info("Images converties : {$count}");
    }
}
