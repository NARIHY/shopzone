<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Explorateur de code Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Fira Code', monospace;
            background: #1e1e1e;
            color: #ddd;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: #252526;
            border-right: 1px solid #333;
            padding: 15px;
            overflow-y: auto;
        }
        .sidebar h2 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #7dd3fc;
        }
        .sidebar ul {
            list-style: none;
            padding-left: 0;
        }
        .sidebar li {
            margin: 5px 0;
        }
        .sidebar a {
            color: #9cdcfe;
            text-decoration: none;
            display: block;
            padding: 5px 8px;
            border-radius: 5px;
            transition: 0.2s;
        }
        .sidebar a:hover {
            background: #333;
        }
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #1e1e1e;
        }
        .path {
            background: #2d2d2d;
            padding: 10px 20px;
            border-bottom: 1px solid #444;
            color: #9cdcfe;
            font-size: 14px;
        }
        .editor-container {
            flex: 1;
            position: relative;
        }
        #editor {
            width: 100%;
            height: 100%;
            font-size: 14px;
        }
        .toolbar {
            padding: 10px;
            background: #2d2d2d;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #444;
        }
        .toolbar button, .toolbar a {
            background: #007acc;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .toolbar button:hover, .toolbar a:hover {
            background: #005fa3;
        }
        .alert {
            color: #22c55e;
            padding: 8px 12px;
            background: #064e3b;
            border-radius: 4px;
            margin-left: 10px;
        }
    </style>

    <!-- Ace Editor CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.0/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.0/theme-dracula.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.0/mode-php.min.js"></script>
</head>
<body>

    {{-- Barre latérale gauche : arborescence --}}
    <div class="sidebar">
        <h2>📁 {{ str_replace($basePath, '', $path) ?: '/' }}</h2>

        @if(isset($dirs))
            <ul>
                @foreach($dirs as $dir)
                    <li>
                        <a href="?path={{ urlencode($path . DIRECTORY_SEPARATOR . basename($dir)) }}">📂 {{ basename($dir) }}</a>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(isset($files))
            <h2>📄 Fichiers</h2>
            <ul>
                @foreach($files as $file)
                    <li>
                        <a href="?path={{ urlencode($path . DIRECTORY_SEPARATOR . basename($file)) }}">📄 {{ basename($file) }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Zone principale droite --}}
    <div class="main">
        <div class="path">
            Chemin : <span>{{ $path }}</span>
        </div>

        @if(isset($content))
            <div class="editor-container">
                <div id="editor">{{ $content }}</div>
            </div>

            <div class="toolbar">
                <div>
                    <button id="saveBtn">💾 Sauvegarder</button>
                    <a href="?path={{ urlencode(dirname($path)) }}">⬅ Retour</a>
                </div>
                <div id="status"></div>
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

    saveBtn.addEventListener("click", async () => {
        const content = editor.getValue();
        status.innerHTML = "💾 Sauvegarde...";
        const response = await fetch("?path={{ urlencode($path) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ content })
        });

        const result = await response.json();
        if (result.success) {
            status.innerHTML = `<span class='alert'>✅ ${result.message}</span>`;
        } else {
            status.innerHTML = "<span style='color:red;'>❌ Erreur de sauvegarde</span>";
        }
        setTimeout(() => status.innerHTML = "", 3000);
    });
@endif
</script>

</body>
</html>
