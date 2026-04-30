<?php
require_once __DIR__ . '/components/sidebar.php';
$mainStorageReal = realpath('../mainStorage');
$games = [];
$cacheFile = __DIR__ . '/cache/game-covers.json';
$coverCache = is_file($cacheFile) ? json_decode((string)file_get_contents($cacheFile), true) : [];
if (!is_array($coverCache)) { $coverCache = []; }

function fetchSteamCover(string $term): ?string {
    $url = 'https://store.steampowered.com/api/storesearch/?l=english&cc=us&term=' . rawurlencode($term);
    $ctx = stream_context_create(['http' => ['timeout' => 2, 'header' => "User-Agent: LocalLoot/1.0\r\n"]]);
    $json = @file_get_contents($url, false, $ctx);
    if ($json === false) return null;
    $data = json_decode($json, true);
    if (!isset($data['items'][0]['id'])) return null;
    $appId = (int)$data['items'][0]['id'];
    return $appId > 0 ? 'https://cdn.akamai.steamstatic.com/steam/apps/' . $appId . '/header.jpg' : null;
}

if ($mainStorageReal !== false) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($mainStorageReal, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $fileInfo) {
        if (!$fileInfo->isFile() || strtolower($fileInfo->getExtension()) !== 'exe') continue;
        $absoluteExePath = $fileInfo->getPathname();
        $relativeExePath = ltrim(str_replace('\\', '/', substr($absoluteExePath, strlen($mainStorageReal))), '/');
        $relativeFolderPath = ltrim(str_replace('\\', '/', substr($fileInfo->getPath(), strlen($mainStorageReal))), '/');
        $folderSegments = array_values(array_filter(explode('/', $relativeFolderPath), 'strlen'));
        $folderQuery = 'file-browser.php';
        if (count($folderSegments) > 0) { $params=[]; foreach ($folderSegments as $i=>$seg) {$params[]='Pfad'.$i.'='.rawurlencode($seg);} $folderQuery .= '?' . implode('&',$params); }

        $gameName = $fileInfo->getBasename('.exe');
        $cacheKey = strtolower($gameName);
        if (!array_key_exists($cacheKey, $coverCache)) { $coverCache[$cacheKey] = fetchSteamCover($gameName); }
        $iconPath = $coverCache[$cacheKey] ?: '../media/file.svg';

        $games[] = ['name'=>$gameName,'exe'=>$relativeExePath,'folder'=>$relativeFolderPath ?: 'mainStorage','folderUrl'=>$folderQuery,'icon'=>$iconPath];
    }
}
file_put_contents($cacheFile, json_encode($coverCache, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
usort($games, fn($a,$b)=>strcasecmp($a['name'],$b['name']));
?>
<!doctype html><html lang="de"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>LocalLoot - Game Library</title>
<style>:root{--bg:#f5f8ff;--card:#fff;--text:#0f2142;--muted:#6a7c9d;--border:#dce7ff;--accent:#1f6fff}*{box-sizing:border-box}body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:var(--bg);color:var(--text)}.app-shell{display:grid;grid-template-columns:300px 1fr;min-height:100vh}.sidebar{background:#fff;border-right:1px solid #e2e9f7;padding:22px}.side-link{display:block;padding:12px 14px;border-radius:10px;color:#2e4468;text-decoration:none;font-weight:600;margin:4px 0}.side-link.active{background:#edf3ff;color:#1f6fff}.brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit}.brand-logo{width:44px;height:44px;border-radius:10px;background:#e8f0ff;padding:5px;object-fit:contain}.wrap{padding:22px}.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px}.game{display:block;background:var(--card);border:1px solid var(--border);border-radius:14px;padding:14px;text-decoration:none;color:inherit}.game img{width:100%;aspect-ratio:460/215;object-fit:cover;border-radius:8px;margin-bottom:10px}.meta{font-size:.88rem;color:var(--muted)}</style></head>
<body><div class="app-shell"><?php renderSidebar('games'); ?><main class="wrap"><h1>🎮 Game Library</h1><p class="meta">Automatisch erkannte Spiele aus <strong>mainStorage</strong>. Klick öffnet den Zielordner.</p><?php if(!$games): ?><p>Keine .exe-Dateien gefunden.</p><?php else: ?><div class="grid"><?php foreach($games as $game): ?><a class="game" href="<?php echo htmlspecialchars($game['folderUrl'], ENT_QUOTES); ?>"><img src="<?php echo htmlspecialchars($game['icon'], ENT_QUOTES); ?>" alt="Game Cover"><div><strong><?php echo htmlspecialchars($game['name'], ENT_QUOTES); ?></strong></div><div class="meta"><?php echo htmlspecialchars($game['folder'], ENT_QUOTES); ?></div></a><?php endforeach; ?></div><?php endif; ?></main></div></body></html>
