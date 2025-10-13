<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
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

        // Sauvegarde du fichier
        if ($request->isMethod('post')) {
            $newContent = $request->get('content');
            File::put($realPath, $newContent);
            return response()->json(['success' => true, 'message' => 'Fichier sauvegardé avec succès !']);
        }

        // Si c’est un dossier → explorer
        if (is_dir($realPath)) {
            $dirs = collect(File::directories($realPath))->map(fn($d) => basename($d));
            $files = collect(File::files($realPath))->map(fn($f) => basename($f));
            return view('code.codeBrowser', compact('dirs', 'files', 'path', 'basePath'));
        }

        // Si c’est un fichier → afficher le contenu
        $content = File::get($realPath);
        return view('code.codeBrowser', compact('content', 'path', 'basePath'));
    });
