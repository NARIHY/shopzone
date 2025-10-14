<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Explorateur de code Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Fira Code', monospace; background: #1e1e1e; color: #ddd; display: flex; height: 100vh; }
        .sidebar { width: 320px; background: #252526; border-right: 1px solid #333; padding: 15px; overflow-y: auto; }
        .sidebar h2 { font-size: 16px; margin-bottom: 10px; color: #7dd3fc; }
        .sidebar ul { list-style: none; padding-left: 0; }
        .sidebar li { margin: 5px 0; }
        .sidebar a { color: #9cdcfe; text-decoration: none; display: block; padding: 5px 8px; border-radius: 5px; transition: 0.2s; }
        .sidebar a:hover { background: #333; }
        .main { flex: 1; display: flex; flex-direction: column; background: #1e1e1e; }
        .path { background: #2d2d2d; padding: 10px 20px; border-bottom: 1px solid #444; color: #9cdcfe; font-size: 14px; }
        .editor-container { flex: 1; position: relative; }
        #editor { width: 100%; height: 100%; font-size: 14px; }
        .toolbar { padding: 10px; background: #2d2d2d; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #444; }
        .toolbar button, .toolbar a { background: #007acc; border: none; color: white; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .toolbar button:hover, .toolbar a:hover { background: #005fa3; }
        .alert { color: #22c55e; padding: 8px 12px; background: #064e3b; border-radius: 4px; margin-left: 10px; }
        .commit-input { margin-right: 10px; padding: 6px 8px; border-radius: 4px; border: 1px solid #444; background: #0b1220; color: #ddd; min-width: 320px; }
        .git-output { font-size: 12px; color: #cbd5e1; margin-top: 6px; white-space: pre-wrap; }
        .breadcrumb a { color: #9cdcfe; text-decoration: none; margin-right: 6px; }
    </style>

    <!-- Ace Editor CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.0/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.0/theme-dracula.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.0/mode-php.min.js"></script>
</head>
<body>

    <div class="sidebar">
        <h2>📁 {{ str_replace($basePath, '', $path) ?: '/' }}</h2>

        {{-- Parent link --}}
        <div style="margin-bottom:10px;">
            <a href="?path={{ urlencode(dirname($path)) }}">⬅ Remonter</a>
        </div>

        @if(isset($dirs) && $dirs->count())
            <h3 style="color:#9cdcff; margin-top:0;">Dossiers</h3>
            <ul>
                @foreach($dirs as $dir)
                    <li>
                        <a href="?path={{ urlencode($dir['path']) }}">📂 {{ $dir['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(isset($files) && $files->count())
            <h3 style="color:#9cdcff; margin-top:12px;">Fichiers</h3>
            <ul>
                @foreach($files as $file)
                    <li>
                        <a href="?path={{ urlencode($file['path']) }}">📄 {{ $file['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="main">
        <div class="path">
            {{-- Breadcrumb cliquable --}}
            <div class="breadcrumb">
                @php
                    $rel = trim(str_replace($basePath, '', $path), DIRECTORY_SEPARATOR);
                    $parts = $rel === '' ? [] : explode(DIRECTORY_SEPARATOR, $rel);
                    $acc = $basePath;
                @endphp
                <a href="?path={{ urlencode($basePath) }}">/</a>
                @foreach($parts as $i => $part)
                    @php $acc = $acc . DIRECTORY_SEPARATOR . $part; @endphp
                    <span>/</span>
                    <a href="?path={{ urlencode($acc) }}">{{ $part }}</a>
                @endforeach
            </div>
        </div>

        @if(isset($content))
            <div class="editor-container">
                <div id="editor">{{ $content }}</div>
            </div>

            <div class="toolbar">
                <div style="display:flex; align-items:center;">
                    <input id="commitMsg" class="commit-input" placeholder="Message de commit (ex: Correction de bug X)" value="Edit {{ basename($path) }}">
                    <button id="saveBtn">💾 Sauvegarder & Git</button>
                    <a href="?path={{ urlencode(dirname($path)) }}" style="margin-left:8px;">⬅ Retour</a>
                </div>
                <div id="status"></div>
            </div>

            <div style="padding:8px 20px;">
                <div id="gitOutput" class="git-output"></div>
            </div>
        @else
            <div class="editor-container flex items-center justify-center text-gray-400 text-sm p-4">
                <p>Sélectionnez un fichier à gauche pour l’éditer.</p>
            </div>
        @endif
    </div>

<script>
@if(isset($content))
    const editor = ace.edit("editor");
    editor.setTheme("ace/theme/dracula");
    editor.session.setMode("ace/mode/php");
    editor.setShowPrintMargin(false);
    editor.setOptions({ fontSize: "14px", wrap: true });

    const saveBtn = document.getElementById("saveBtn");
    const status = document.getElementById("status");
    const gitOutput = document.getElementById("gitOutput");

    saveBtn.addEventListener("click", async () => {
        const content = editor.getValue();
        const commit_message = document.getElementById('commitMsg').value || '';
        status.innerHTML = "💾 Sauvegarde...";
        gitOutput.innerText = "";

        try {
            const response = await fetch("?path={{ urlencode($path) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ content, commit_message })
            });

            const result = await response.json();
            if (result.success) {
                status.innerHTML = `<span class='alert'>✅ ${result.message}</span>`;

                // afficher les sorties git (lisibles)
                if (result.git && Array.isArray(result.git)) {
                    let txt = '';
                    result.git.forEach(item => {
                        txt += `Commande: ${item.command}\nExit: ${item.exit}\n--- Output ---\n${item.output}\n--- Error ---\n${item.error}\n\n`;
                    });
                    gitOutput.innerText = txt;
                }
            } else {
                status.innerHTML = "<span style='color:red;'>❌ Erreur de sauvegarde</span>";
                if (result.git) gitOutput.innerText = JSON.stringify(result.git, null, 2);
            }
            setTimeout(() => status.innerHTML = "", 4000);
        } catch (err) {
            status.innerHTML = "<span style='color:red;'>❌ Erreur réseau ou serveur</span>";
            gitOutput.innerText = err.toString();
        }
    });
@endif
</script>

</body>
</html>
