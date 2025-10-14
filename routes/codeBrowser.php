<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS as MiddlewareValidateSessionWithWorkOS;

Route::middleware(['auth', MiddlewareValidateSessionWithWorkOS::class])
    ->match(['get', 'post'], '/code-browser', function (Request $request) {
        $basePath = base_path();
        $path = $request->get('path', $basePath);

        // Sécurité : bloquer tout accès hors du projet
        $realPath = realpath($path);
        if (!$realPath || !str_starts_with($realPath, realpath($basePath))) {
            abort(403, 'Accès refusé');
        }

        // Sauvegarde du fichier + git (POST)
        if ($request->isMethod('post')) {
            $newContent = $request->get('content', '');
            File::put($realPath, $newContent);

            $commitMsg = trim($request->get('commit_message', ''));
            if ($commitMsg === '') {
                $commitMsg = 'Edit ' . ltrim(str_replace($basePath, '', $realPath), DIRECTORY_SEPARATOR);
            }

            $gitOutputs = [];
            try {
                // Commandes git exécutées dans le répertoire du projet (basePath)
                $commands = [
                    ['git', 'add', '.'],
                    ['git', 'commit', '-m', $commitMsg],
                    ['git', 'push', 'origin', 'main'],
                ];

                foreach ($commands as $cmd) {
                    $process = new Process($cmd, $basePath);
                    $process->setTimeout(120); // 2 minutes max par commande
                    $process->run();

                    $gitOutputs[] = [
                        'command' => implode(' ', array_map('escapeshellarg', $cmd)),
                        'exit' => $process->getExitCode(),
                        'output' => $process->getOutput(),
                        'error' => $process->getErrorOutput(),
                    ];

                    // si commit renvoie 1 parce qu'il n'y a rien à commit, on continue
                    // mais on capture l'output pour le front
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Fichier sauvegardé avec succès !',
                    'git' => $gitOutputs,
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'opération Git : ' . $e->getMessage(),
                    'git' => $gitOutputs,
                ], 500);
            }
        }

        // Si c’est un dossier → explorer (on renvoie name + chemin complet)
        if (is_dir($realPath)) {
            $dirs = collect(File::directories($realPath))
                ->map(fn($d) => ['name' => basename($d), 'path' => $realPath . DIRECTORY_SEPARATOR . basename($d)])
                ->values();
            $files = collect(File::files($realPath))
                ->map(fn($f) => ['name' => basename($f), 'path' => $realPath . DIRECTORY_SEPARATOR . basename($f)])
                ->values();

            return view('code.codeBrowser', compact('dirs', 'files', 'path', 'basePath'));
        }

        // Si c’est un fichier → afficher le contenu
        $content = File::get($realPath);
        return view('code.codeBrowser', compact('content', 'path', 'basePath'));
    });

