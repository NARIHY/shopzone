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

class ProcessCreateMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $localPath; // chemin relatif sur disk 'local' (ex: uploads/temp/....)
    public string $title;
    public ?int $userId;

    /**
     * @param string $localPath path returned by ->store(..., 'local')
     * @param string $title
     * @param int|null $userId optional
     */
    public function __construct(string $localPath, string $title, ?int $userId = null)
    {
        $this->localPath = $localPath;
        $this->title = $title;
        $this->userId = $userId;
    }

    public function handle(LoggerInterface $logger = null)
    {
        $localDisk = 'local';
        $targetDisk = 'public';

        // Construire destination (tu peux adapter la structure)
        $dateDir = date('Y-m-d'); // plus stable que D-M-Y
        $destinationDir = 'media' . DIRECTORY_SEPARATOR . $dateDir;

        // récupérer le chemin absolu du fichier local
        $localFullPath = Storage::disk($localDisk)->path($this->localPath);

        // générer un nom de fichier unique pour éviter collisions
        $originalName = basename($this->localPath);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $uniqueName = Str::slug($base) . '_' . time() . '_' . Str::random(6) . ($ext ? '.' . $ext : '');

        $targetPath = $destinationDir . DIRECTORY_SEPARATOR . $uniqueName;

        DB::beginTransaction();

        try {
            if (!file_exists($localFullPath)) {
                throw new Exception("Local file not found: {$localFullPath}");
            }

            // copy using stream to avoid memory spikes
            $stream = fopen($localFullPath, 'rb');
            if ($stream === false) {
                throw new Exception("Failed to open local file for streaming: {$localFullPath}");
            }

            $written = Storage::disk($targetDisk)->put($targetPath, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }

            if ($written === false) {
                throw new Exception("Failed to write file to target disk ({$targetDisk}) at {$targetPath}");
            }

            // create db record
            $media = Media::create([
                'title'         => $this->title,
                'path'          => $targetPath,
                'disk'          => $targetDisk,
                'mime_type'     => mime_content_type($localFullPath) ?: null,
                'size'          => filesize($localFullPath) ?: null,
                'original_name' => $originalName,
                'user_id'       => $this->userId,
            ]);

            DB::commit();

            // cleanup local temp
            Storage::disk($localDisk)->delete($this->localPath);
        } catch (Exception $e) {
            DB::rollBack();

            // remove partially written file on target if exists
            try {
                if (Storage::disk($targetDisk)->exists($targetPath)) {
                    Storage::disk($targetDisk)->delete($targetPath);
                }
            } catch (Exception $inner) {
                // ignore but log
                if ($logger) {
                    $logger->warning("Failed to clean target file after error: " . $inner->getMessage());
                }
            }

            // log then rethrow so the job can be retried or failed properly
            if ($logger) {
                $logger->error("ProcessCreateMediaJob failed: " . $e->getMessage(), [
                    'localPath' => $this->localPath,
                    'targetPath' => $targetPath,
                ]);
            }

            throw $e;
        }
    }
}
