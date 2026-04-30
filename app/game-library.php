<?php
$mainStorageReal = realpath('../mainStorage');
$games = [];

if ($mainStorageReal !== false) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($mainStorageReal, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $fileInfo) {
        if (!$fileInfo->isFile()) {
            continue;
        }

        if (strtolower($fileInfo->getExtension()) !== 'exe') {
            continue;
        }

        $absoluteExePath = $fileInfo->getPathname();
        $relativeExePath = ltrim(str_replace('\\', '/', substr($absoluteExePath, strlen($mainStorageReal))), '/');
        $relativeFolderPath = ltrim(str_replace('\\', '/', substr($fileInfo->getPath(), strlen($mainStorageReal))), '/');
        $folderSegments = array_values(array_filter(explode('/', $relativeFolderPath), 'strlen'));

        $folderQuery = 'file-browser.php';
        if (count($folderSegments) > 0) {
            $params = [];
            foreach ($folderSegments as $index => $segment) {
                $params[] = 'Pfad' . $index . '=' . rawurlencode($segment);
            }
            $folderQuery .= '?' . implode('&', $params);
        }

        $iconPath = '../media/file.svg';
        $baseName = pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME);
        $folderDiskPath = $fileInfo->getPath();
        $candidateIcons = [
            $folderDiskPath . DIRECTORY_SEPARATOR . $baseName . '.png',
            $folderDiskPath . DIRECTORY_SEPARATOR . $baseName . '.ico'
        ];
        foreach ($candidateIcons as $candidateIcon) {
            if (is_file($candidateIcon)) {
                $relativeIcon = ltrim(str_replace('\\', '/', substr($candidateIcon, strlen(realpath('..')))), '/');
                $iconPath = '../' . $relativeIcon;
                break;
            }
        }

        $games[] = [
            'name' => $fileInfo->getBasename('.exe'),
            'exe' => $relativeExePath,
            'folder' => $relativeFolderPath !== '' ? $relativeFolderPath : 'mainStorage',
            'folderUrl' => $folderQuery,
            'icon' => $iconPath
        ];
    }
}

usort($games, fn($a, $b) => strcasecmp($a['name'], $b['name']));
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LocalLoot - Game Library</title>
  <style>
    :root{--bg:#f5f8ff;--card:#fff;--text:#0f2142;--muted:#6a7c9d;--border:#dce7ff;--accent:#1f6fff}
    *{box-sizing:border-box}
    body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:var(--bg);color:var(--text)}
    .wrap{max-width:1200px;margin:0 auto;padding:22px}
    .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
    .nav{display:flex;gap:8px;flex-wrap:wrap}
    .nav a{padding:10px 14px;border:1px solid var(--border);border-radius:10px;background:#fff;color:#21406f;text-decoration:none;font-weight:600}
    .nav a.active{border-color:var(--accent);color:var(--accent)}
    .intro{margin:18px 0 14px;color:var(--muted)}
    .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px}
    .game{display:block;background:var(--card);border:1px solid var(--border);border-radius:14px;padding:14px;text-decoration:none;color:inherit}
    .game:hover{border-color:var(--accent)}
    .game img{width:64px;height:64px;object-fit:contain;margin-bottom:10px}
    .name{font-weight:700;margin-bottom:6px}
    .meta{font-size:.88rem;color:var(--muted);word-break:break-word}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="top">
      <h1>🎮 Game Library</h1>
      <nav class="nav">
        <a href="file-browser.php">📁 Files</a>
        <a href="game-library.php" class="active">🎮 Game Library</a>
        <a href="chat.php">💬 Chat</a>
        <a href="tools.php">🧰 Tools</a>
      </nav>
    </div>
    <p class="intro">Automatisch erkannte Spiele aus <strong>mainStorage</strong> (.exe). Klick auf ein Spiel öffnet den Zielordner im File Browser.</p>

    <?php if (count($games) === 0): ?>
      <p>Keine .exe-Dateien in mainStorage gefunden.</p>
    <?php else: ?>
      <div class="grid">
        <?php foreach ($games as $game): ?>
          <a class="game" href="<?php echo htmlspecialchars($game['folderUrl'], ENT_QUOTES); ?>">
            <img src="<?php echo htmlspecialchars($game['icon'], ENT_QUOTES); ?>" alt="Game Icon">
            <div class="name"><?php echo htmlspecialchars($game['name'], ENT_QUOTES); ?></div>
            <div class="meta">Ordner: <?php echo htmlspecialchars($game['folder'], ENT_QUOTES); ?></div>
            <div class="meta">EXE: <?php echo htmlspecialchars($game['exe'], ENT_QUOTES); ?></div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
