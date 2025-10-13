<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'path',
        'disk',
        'mime_type',
        'size',
        'original_name'
    ];

    public function products()
{
    return $this->belongsToMany(\App\Models\Shop\Product::class, 'media_product', 'media_id', 'product_id')
                ->withTimestamps();
}

    /**
     * Retourne l’URL publique du fichier
     */
    public function url(): string
    {
        if ($this->disk === 'public') {
            return asset('storage/' . $this->path);
        }

        throw new \RuntimeException("URL generation is not supported for the disk: {$this->disk}");
    }

    /**
     * Vérifie si le fichier existe dans le disque
     */
    public function exists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    /**
     * Supprime le fichier du disque et la base de données
     */
    public function deleteWithFile(): bool
    {
        if ($this->exists()) {
            Storage::disk($this->disk)->delete($this->path);
        }

        return $this->delete();
    }

    /**
     * Sauvegarde un nouveau fichier et crée un enregistrement en base
     */
    public static function fromUpload($file, string $disk = 'public'): self
    {
        $path = $file->store('', $disk);

        return self::create([
            'path'          => $path,
            'disk'          => $disk,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}
