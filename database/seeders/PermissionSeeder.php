<?php

namespace Database\Seeders;

use App\Models\Access\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = config('permissions.actions');

        // Récupérer tous les modèles du dossier App/Models
        $modelPath = app_path('Models');
        $models = collect(File::allFiles($modelPath))
            ->map(fn($file) => pathinfo($file->getFilename(), PATHINFO_FILENAME))
            ->filter(fn($name) => !in_array($name, ['User', 'BaseModel'])) // optionnel
            ->values();

        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permissionName = strtolower("$model.$action");

                Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['description' => "Ability to $action on $model"]
                );
            }
        }

        $this->command->info('Dynamic permissions generated successfully.');
    }
}
