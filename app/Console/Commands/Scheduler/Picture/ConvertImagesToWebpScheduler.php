<?php

namespace App\Console\Commands\Scheduler\Picture;

use Illuminate\Console\Command;
use App\Models\Files\Media;
use App\Jobs\Files\Picture\ConvertImageToWebpJob;

class ConvertImagesToWebpScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:convert-images-to-webp-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Media::where('mime_type', 'like', 'image/%')
        ->where('mime_type', '!=', 'image/webp')
        ->chunkById(100, function ($medias) {
            foreach ($medias as $media) {
                ConvertImageToWebpJob::dispatch($media)
                    ->onQueue('images');
            }
        });
    }
}
