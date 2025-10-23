<?php

namespace App\Jobs\Files;

use App\Models\Files\Media;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class ProcessUpdateMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $mediaId;
    public ?string $localPath; // si null => pas de nouveau fichier, juste titre
    public string $title;

    public function __construct(int $mediaId, string $title, ?string $localPath = null)
    {
        $this->mediaId = $mediaId;
        $this->title = $title;
        $this->localPath = $localPath;
    }

    public function handle(LoggerInterface $logger = null)
    {
        $localDisk = 'local';
        $targetDisk = 'public';

        $media = Media::find($this->mediaId);
        if (!$media) {
            throw new Exception("Media #{$this->mediaId} not found");
        }

        DB::beginTransaction();
        $newTargetPath = null;
        $oldPath = $media->path;
        try {
            if ($this->localPath) {
                $localFullPath = Storage::disk($localDisk)->path($this->localPath);
                if (!file_exists($localFullPath)) {
                    throw new Exception("Local file not found: {$localFullPath}");
                }

                $dateDir = date('Y-m-d');
                $destinationDir = 'media' . DIRECTORY_SEPARATOR . $dateDir;
                $originalName = basename($this->localPath);
                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $base = pathinfo($originalName, PATHINFO_FILENAME);
                $uniqueName = Str::slug($base) . '_' . time() . '_' . Str::random(6) . ($ext ? '.' . $ext : '');
                $newTargetPath = $destinationDir . DIRECTORY_SEPARATOR . $uniqueName;

                $stream = fopen($localFullPath, 'rb');
                if ($stream === false) {
                    throw new Exception("Failed to open local file for streaming: {$localFullPath}");
                }

                $written = Storage::disk($targetDisk)->put($newTargetPath, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
                if ($written === false) {
                    throw new Exception("Failed to write new file to target disk at {$newTargetPath}");
                }

                // delete old file on target disk if exists
                if ($oldPath && Storage::disk($media->disk)->exists($oldPath)) {
                    Storage::disk($media->disk)->delete($oldPath);
                }

                // update model with new file info
                $media->update([
                    'title'         => $this->title,
                    'path'          => $newTargetPath,
                    'disk'          => $targetDisk,
                    'mime_type'     => mime_content_type($localFullPath) ?: null,
                    'size'          => filesize($localFullPath) ?: null,
                    'original_name' => $originalName,
                ]);

                // cleanup local temp
                Storage::disk($localDisk)->delete($this->localPath);
            } else {
                // pas de nouveau fichier, juste mettre à jour le titre
                $media->update([
                    'title' => $this->title,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            // si on a créé un nouveau fichier target mais tout a échoué, supprimer
            try {
                if ($newTargetPath && Storage::disk($targetDisk)->exists($newTargetPath)) {
                    Storage::disk($targetDisk)->delete($newTargetPath);
                }
            } catch (Exception $inner) {
                if ($logger) {
                    $logger->warning("Failed to cleanup target new file after update error: " . $inner->getMessage());
                }
            }

            if ($logger) {
                $logger->error("ProcessUpdateMediaJob failed: " . $e->getMessage(), [
                    'mediaId' => $this->mediaId,
                    'localPath' => $this->localPath,
                ]);
            }

            throw $e;
        }
    }
}
