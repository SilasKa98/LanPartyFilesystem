<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalLoot - File Browser</title>
    <style>
        :root {
            --bg: #f5f8ff;
            --bg-soft: #eef4ff;
            --panel: #ffffff;
            --panel-soft: #ffffff;
            --panel-bright: rgba(20, 58, 122, 0.62);
            --border: #1a7fd4;
            --border-soft: #dfe8fb;
            --text: #0f2142;
            --muted: #667a9a;
            --accent: #1f6fff;
            --accent-soft: #1578ff;
            --warn: #ffd447;
            --glow: 0 0 0 1px rgba(30, 199, 255, 0.35), 0 0 20px rgba(30, 199, 255, 0.16);
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Inter", "Segoe UI", system-ui, Arial, sans-serif; background: var(--bg); color: var(--text); min-height:100vh; line-height:1.45; }
        #content { max-width: 1200px; margin: 0 auto; padding: 24px; }
         .app-shell{display:grid;grid-template-columns:300px 1fr 340px;min-height:100vh;} .sidebar{background:#fff;border-right:1px solid #e2e9f7;padding:22px;} .side-link{display:block;padding:12px 14px;border-radius:10px;color:#2e4468;text-decoration:none;font-weight:600;margin:4px 0;} .side-link.active{background:#edf3ff;color:#1f6fff;} .side-tools{margin-top:16px;display:grid;gap:8px;} .side-tools .input,.side-tools .btn{width:100%;} .rightpanel{background:#fff;border-left:1px solid #e2e9f7;padding:20px;} .detail-card{border:1px solid #e3ebfb;border-radius:16px;padding:16px;position:sticky;top:20px} .meta-row{display:flex;justify-content:space-between;gap:10px;padding:8px 0;border-bottom:1px solid #edf2fc;font-size:.93rem;} .meta-row:last-child{border-bottom:0;} .quick-actions{display:grid;gap:8px;margin-top:14px;} .quick-actions .btn.primary{background:#1f6fff;color:#fff;border-color:#1f6fff;} .stats{display:grid;grid-template-columns:repeat(4,minmax(120px,1fr));gap:12px;margin:12px 0 16px;} .stat{background:#fff;border:1px solid #e2e9f7;border-radius:14px;padding:14px;} .topbar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; margin-bottom: 16px; background: #fff; border: 1px solid #e2e9f7; padding: 14px 16px; border-radius: 14px; }
        .brand { display:flex; align-items:center; gap:10px; text-decoration:none; color:inherit; }
        .brand-logo { width:44px; height:44px; border-radius:10px; background:#e8f0ff; padding:5px; object-fit:contain; }
        h1 { margin:0; font-size: 1.2rem; font-weight:700; }
        .toolbar { display:flex; gap:10px; align-items:center; }
        .input, .btn { border:1px solid #d9e4fa; background:#fff; color:#1b3763; border-radius: 10px; padding: 10px 12px; font-size:.95rem; font-weight:600; }
        .input::placeholder { color:#c6e7ff; opacity:.95; }
        .btn { cursor:pointer; text-align:center; display:inline-flex; align-items:center; justify-content:center; }
        .btn:hover { border-color: var(--accent); }
        #uploadWrapper { border: 1px dashed #cddaf5; border-radius: 16px; background: #fff; padding: 24px; text-align: center; margin-bottom: 16px; transition: .2s ease; box-shadow: var(--glow); }
        #uploadWrapper .helper { color: var(--muted); }
        #uploadFile { width: 100%; height: 48px; opacity: 0; cursor: pointer; position:absolute; inset:0; }
        .uploadInputWrap { position:relative; height: 48px; margin-top: 10px; }
        .uploadButtonFake { height:48px; border-radius:10px; display:grid; place-items:center; background: var(--panel-soft); border:1px solid var(--border-soft); }
        #breadcrumb { margin: 12px 0; color: var(--muted); }
        #breadcrumb a { color:#1b4f9f; text-decoration:none; font-weight:600; }
        #breadcrumb.drag-active { padding: 10px 12px; border: 1px dashed var(--border-soft); border-radius: 10px; background: rgba(10, 32, 70, 0.55); animation: breadcrumbPulse .9s ease-in-out infinite alternate; }
        #breadcrumb.drag-active .crumb-dropzone { display: inline-flex; }
        .crumb-dropzone { display:none; align-items:center; margin-left: 8px; padding: 4px 8px; border-radius: 8px; border:1px solid #b7d5ff; color:#355784; font-size:.82rem; background:#f2f7ff; }
        .crumb-target { padding: 2px 4px; border-radius:6px; transition: .18s ease; color:#1b4f9f; }
        .crumb-target.drag-over { background: rgba(97,228,255,.2); box-shadow: 0 0 0 1px rgba(97,228,255,.4); }
        @keyframes breadcrumbPulse { from { box-shadow: 0 0 0 rgba(97,228,255,.1); } to { box-shadow: 0 0 16px rgba(97,228,255,.28); } }
        .section-title { margin: 18px 0 8px; color: #4b607f; font-size: 1rem; font-weight:700; letter-spacing:.02em; }
        .grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(150px,1fr)); gap: 12px; }
        .grid.list { display:flex; flex-direction:column; }
        .grid.list .card { flex-direction:row; justify-content:flex-start; align-items:center; min-height:64px; gap:12px; }
        .grid.list .name { text-align:left; }
        .card { background: #fff; border:1px solid #e2e9f7; border-radius: 14px; padding: 10px; min-height: 130px; display:flex; flex-direction:column; align-items:center; justify-content:space-between; transition:.2s ease; position:relative; box-shadow: inset 0 0 30px rgba(14, 57, 121, 0.25); }
        .card:hover { transform: translateY(-2px); border-color:#56d9ff; box-shadow: var(--glow); }
        .card img { width:56px; height:56px; object-fit:contain; }
        .card:focus { outline:2px solid var(--accent); outline-offset:2px; }
        .name { font-size: .95rem; text-align:center; overflow-wrap:anywhere; font-weight:600; color:#1a2f53; text-shadow: 0 0 6px rgba(10, 39, 78, .45); }
        .folder-card { min-height: 100px; }
        .folder-card.drag-over { border-color: #61e4ff; box-shadow: 0 0 0 2px rgba(97,228,255,.35), 0 0 22px rgba(30,199,255,.35); }
        .dropdown-content { display:none; position:absolute; top:34px; right:8px; min-width:160px; background:#ffffff; border:1px solid #d6e3fb; border-radius:12px; overflow:hidden; z-index:2500; box-shadow:0 14px 30px rgba(22,62,132,.2); }
        .card-menu-btn { position:absolute; top:8px; right:8px; width:28px; height:28px; border-radius:8px; border:1px solid #d9e4fa; background:#fff; color:#36527c; font-weight:700; cursor:pointer; display:grid; place-items:center; }
        .card-menu-btn:hover { border-color:#1f6fff; color:#1f6fff; }
        .dropdown-content a { display:block; color:#1b3763; text-decoration:none; padding:10px 12px; font-size:.88rem; background:#ffffff; border-bottom:1px solid #edf2fc; }
        .dropdown-content a:hover { background:#eff5ff; }
        .dropdown-content form:last-child a { border-bottom:none; }
        .show { display:block; }
        .search-wrap { position:relative; }
        .search-results { display:none; position:absolute; top: calc(100% + 6px); left:0; right:0; max-height:340px; overflow:auto; background:#ecf7ff; border:1px solid #84cfff; border-radius:10px; z-index:40; padding:8px; color:#071326; }
        .search-results.show { display:block; }
        .search-node { padding:6px 8px; border-radius:6px; cursor:pointer; font-size:.9rem; color:#071326; }
        .search-node:hover { background:#d5edff; }
        #leftSidebar { position: fixed; right: 24px; bottom: 24px; }
        #leftSidebar img { width: 54px; height:54px; cursor:pointer; background: linear-gradient(180deg, var(--accent), var(--accent-soft)); border-radius:50%; padding: 12px; box-shadow: 0 0 18px rgba(30,199,255,.55); }
        #leftSidebar img:hover { filter: brightness(1.08); }
        .folder-card img, .card img { filter: brightness(0) saturate(100%) invert(73%) sepia(39%) saturate(1592%) hue-rotate(165deg) brightness(103%) contrast(102%); }
        .card img[alt='File icon'][src*='/mainStorage/'] { filter: none; }
        .modal { display:none; position:fixed; inset:0; background: rgba(0,0,0,.45); z-index: 9999; }
        .modal-content { width:min(460px,92%); margin: 12vh auto; background:#ffffff; color:#1a2f53; border-radius: 16px; padding: 18px; border:1px solid #d6e3fb; box-shadow:0 20px 50px rgba(17,43,92,.24); }
        .modal-content p { margin:0 0 12px; font-weight:700; color:#1b3763; }
        .modal-content input { width:100%; padding:11px 12px; margin-bottom: 12px; border:1px solid #d9e4fa; border-radius:10px; font:inherit; color:#1b3763; background:#fff; }
        .modal-actions { display:flex; gap:10px; justify-content:flex-end; }
        #noContent { text-align:center; color:var(--muted); margin-top: 24px; }
        .toast { position:fixed; left:50%; transform:translateX(-50%); bottom:20px; background:#ffffff; border:1px solid #d6e3fb; color:#1b3763; padding:10px 14px; border-radius:12px; display:none; z-index:10; box-shadow:0 10px 26px rgba(22,62,132,.18); font-weight:600; }
    </style>
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        function changeText(x){
            var fileSize = x.files[0].size;
            for(let i=1;i<x.files.length;i++){ fileSize += x.files[i].size; }
            var fileSizeMb = (fileSize/1024/1024).toFixed(2) + " MB";
            document.getElementById('uploadStatus').innerHTML = "Uploading " + x.files.length + " file(s) · " + fileSizeMb;
            x.form.submit();
        }
        function highlightField(){ document.getElementById("uploadWrapper").style.borderColor = "#22c55e"; }
        function normalizeField(){ document.getElementById("uploadWrapper").style.borderColor = "rgba(89, 189, 255, 0.45)"; }
        function preventBrowserDropNavigation(){
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                window.addEventListener(eventName, function(event){
                    event.preventDefault();
                    event.stopPropagation();
                }, false);
            });
        }
        function setupDropUpload(){
            const uploadWrapper = document.getElementById('uploadWrapper');
            const fileInput = document.getElementById('uploadFile');
            uploadWrapper.addEventListener('drop', function(event){
                event.preventDefault();
                event.stopPropagation();
                if (event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                    fileInput.files = event.dataTransfer.files;
                    changeText(fileInput);
                }
                normalizeField();
            });
        }
        function filterEntries() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            document.querySelectorAll('.card').forEach(card => {
                const n = card.dataset.name.toLowerCase();
                card.style.display = n.includes(q) ? '' : 'none';
            });
        }
        function debounce(fn, wait) { let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args),wait); }; }
        const filterEntriesDebounced = debounce(filterEntries, 120);
        let searchRequestId = 0;
        async function searchAllPaths(){
            const q = document.getElementById('searchInput').value.trim();
            const box = document.getElementById('searchResults');
            if(q.length < 2){ box.classList.remove('show'); box.innerHTML=''; return; }
            const requestId = ++searchRequestId;
            try {
                const res = await fetch('searchTree.php?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Search request failed');
                const data = await res.json();
                if (requestId !== searchRequestId) return;
                renderSearchResults(Array.isArray(data.results) ? data.results : []);
            } catch (error) {
                box.innerHTML = '<div class="search-node">Suche aktuell nicht verfügbar</div>';
                box.classList.add('show');
            }
        }
        function renderSearchResults(results){
            const box = document.getElementById('searchResults');
            if(!results.length){ box.innerHTML = '<div class=\"search-node\">Keine Treffer</div>'; box.classList.add('show'); return; }
            box.innerHTML = '';
            const buildUrlFromSegments = (segments) => {
                const parts = Array.isArray(segments) ? segments.filter(Boolean) : [];
                if (!parts.length) return 'file-browser.php';
                return 'file-browser.php?' + parts.map((s, i) => 'Pfad' + i + '=' + encodeURIComponent(s)).join('&');
            };
            results.forEach(item => {
                const segments = Array.isArray(item.pathSegments) ? item.pathSegments : [];
                const depth = Math.max(0, segments.length - 1);
                const icon = item.type === 'folder' ? '📁' : '📄';
                const url = buildUrlFromSegments(segments);
                const node = document.createElement('div');
                node.className = 'search-node';
                node.style.paddingLeft = `${8 + depth * 16}px`;
                node.dataset.url = url;
                node.dataset.type = item.type;
                node.dataset.path = item.relativePath;
                node.textContent = `${icon} ${item.relativePath}`;
                box.appendChild(node);
            });
            box.classList.add('show');
        }
        function sortEntries(){
            document.querySelectorAll('.grid').forEach(grid => {
                const cards = [...grid.querySelectorAll('.card')];
                const mode = document.getElementById('sortSelect').value;
                cards.sort((a, b) => {
                    const an = a.dataset.name.toLowerCase();
                    const bn = b.dataset.name.toLowerCase();
                    return mode === 'nameDesc' ? bn.localeCompare(an) : an.localeCompare(bn);
                });
                cards.forEach(c => grid.appendChild(c));
            });
        }
        function toggleView(){
            document.querySelectorAll('.grid').forEach(g => g.classList.toggle('list'));
            const btn = document.getElementById('viewToggle');
            const isList = btn.getAttribute('aria-pressed') === 'true';
            const newState = !isList;
            btn.setAttribute('aria-pressed', String(newState));
            btn.innerText = isList ? '☰ List View' : '◫ Grid View';
            localStorage.setItem('lancloud_view_list', newState ? '1' : '0');
        }
        function showToast(msg){
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.style.display = 'block';
            setTimeout(() => t.style.display = 'none', 1400);
        }
        function copyCurrentPath() {
            const text = document.getElementById('currentPathText').innerText;
            navigator.clipboard.writeText(text);
            showToast('Pfad kopiert');
            document.getElementById('copyPathBtn').innerText = '✓ Kopiert';
            setTimeout(()=>document.getElementById('copyPathBtn').innerText='⎘ Pfad kopieren',1200);
        }
    
</script>
</head>
<body ondragover="highlightField();" ondragleave="normalizeField();">
<?php
$cntPath = substr_count($_SERVER['REQUEST_URI'], "Pfad");
if(isset($_GET["Pfad0"])){
    $path = "../mainStorage/";
    for($i=0;$i<$cntPath;$i++){
        $pfadName = "Pfad".$i;
        $path .= $_GET[$pfadName]."/";
    }
}else{
    $path = "../mainStorage";
}
?>
<div id="myModal" class="modal"><div class="modal-content"><span class="close" style="float:right;cursor:pointer;font-size:1.2rem;color:#4f678c;">&times;</span><p>Neuer Ordner</p><form action="createFolder.php" method='post'><input type="text" pattern="[^|,/:?*\\]+" value="unbenannter Ordner" name="folderName" required><input type="hidden" name="path" value="<?php echo $path; ?>"><input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="currentUrl"><div class="modal-actions"><button class="btn" id="cancelNewFolder" type="button">Abbrechen</button><button class="btn primary" type="submit">Erstellen</button></div></form></div></div>
<div class="app-shell"><aside class="sidebar"><a href="index.php" class="brand" style="margin-bottom:14px"><img src="../media/LocalLoot_logo.png" class="brand-logo" alt="LocalLoot Logo"><div><h1 style="font-size:1.2rem">LocalLoot</h1><div style="color:#6d7f9d;">Local file & game sharing for LAN parties</div></div></a><div style="margin-top:16px;color:#7e8eaa;font-size:.85rem;">MODULES</div><a class="side-link active" href="file-browser.php">📁 Files</a><a class="side-link" href="#">🎮 Game Library</a><a class="side-link" href="chat.php">💬 Chat</a><a class="side-link" href="#">🖥️ Server Status</a><div class="side-tools"><div class="search-wrap"><input id="searchInput" class="input" placeholder="Dateien/Ordner global suchen" oninput="filterEntriesDebounced();searchAllPaths()"><div id="searchResults" class="search-results"></div></div><select id="sortSelect" class="input" onchange="sortEntries()"><option value="nameAsc">Name A-Z</option><option value="nameDesc">Name Z-A</option></select><button id="viewToggle" class="btn" onclick="toggleView()" aria-pressed="false">☰ List View</button><button id="myBtn" class="btn" type="button">+ New Folder</button><button id="copyPathBtn" class="btn" onclick="copyCurrentPath()">⎘ Pfad kopieren</button></div></aside><main><div id="content">
    <div class="topbar"><div class="toolbar"><strong>Shared Files</strong></div></div>
    <div id="uploadWrapper"><div class="helper">Drag & drop files here or click to browse.</div><div id="uploadStatus" class="helper"></div><form method="post" action="uploadFiles.php" enctype="multipart/form-data"><div class="uploadInputWrap"><div class="uploadButtonFake">Choose files</div><input id="uploadFile" type="file" onchange="changeText(this);" name="files[]" multiple></div><input type="hidden" value="<?php echo $path; ?>" name="path"><input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="currentUrl"></form></div>

    <div id="breadcrumb"><span id="currentPathText"><?php
        if(isset($_GET["Pfad0"])){
            $absRoot = realpath("../mainStorage");
            $navPath = $absRoot;
            for($i=0;$i<$cntPath;$i++){
                error_reporting(E_ERROR | E_PARSE);
                $removeFrom = explode("&",$_SERVER['REQUEST_URI']);
                $postionInUrl = strpos($_SERVER['REQUEST_URI'], $removeFrom[$i+1]);
                $pathDeletePart = "&".substr($_SERVER['REQUEST_URI'], $postionInUrl);
                $pathBack = str_replace($pathDeletePart, "", $_SERVER['REQUEST_URI']);
                $pfadName = "Pfad".$i;
                if($i <= 0){ print "<a class='crumb-target' data-path='".htmlspecialchars($absRoot, ENT_QUOTES)."' href='file-browser.php'>mainStorage</a>"; }
                $navPath .= "/".$_GET[$pfadName];
                print " / <a class='crumb-target' data-path='".htmlspecialchars($navPath, ENT_QUOTES)."' href='".$pathBack."'>".$_GET[$pfadName]."</a>";
            }
        }else{ print "<span class='crumb-target' data-path='".htmlspecialchars(realpath($path), ENT_QUOTES)."'>".$path."</span>"; }
    ?></span><span class="crumb-dropzone">⬆ Datei auf Breadcrumb ziehen, um Ebene nach oben zu verschieben</span></div>

    <?php
    $scanned_directory = array_values(array_diff(scandir($path), array('..', '.')));
    if(count($scanned_directory) === 0){ print "<p id='noContent'>Dieser Ordner ist leer</p>"; }
    $folderCount = 0;
    $fileCount = 0;
    foreach($scanned_directory as $entryMeta){ if(strpos($entryMeta, '.') === false){ $folderCount++; } else { $fileCount++; } }
    $currentDisplayPath = isset($_GET['Pfad0']) ? ('Shared Files / '.implode(' / ', array_map(fn($i) => $_GET['Pfad'.$i], range(0, $cntPath-1)))) : 'Shared Files / mainStorage';
    $currentFolderName = basename(rtrim($path, '/'));

    print "<p class='section-title'>Folders</p><div class='grid'>";
    foreach($scanned_directory as $entry){
        if(strpos($entry, ".") === false){
            echo "<div class='card folder-card' tabindex='0' role='link' aria-label='Ordner ".$entry."' data-folder-name=\"".htmlspecialchars($entry, ENT_QUOTES)."\" data-href='".$_SERVER['REQUEST_URI'].($cntPath == 0 ? "?" : "&")."Pfad".$cntPath."=".$entry."' data-name='".$entry."' onclick='openFolderCard(event, this)' onkeydown='openFolderCardByKey(event, this)'>";
            echo "<button type='button' class='card-menu-btn' aria-label='Ordner-Menü' onclick='toggleCardMenu(event, this)'>⋯</button>";
            echo "<img loading='lazy' src='../media/folder-open.svg' alt='Folder'>";
            echo '<div class="dropdown-content">';
            echo '<a href="downloadFolder.php?path='.urlencode($path).'&folder='.urlencode($entry).'">Download ZIP</a>';
            echo '<form action="deleteFile.php" method="post" style="margin:0;">';
            echo '<input type="hidden" name="path" value="'.$path.'"><input type="hidden" name="fileName" value="'.$entry.'"><input type="hidden" name="currentUrl" value="'.$_SERVER['REQUEST_URI'].'"><input type="hidden" name="type" value="folder"><a onclick="this.parentNode.submit();">Delete</a>';
            echo '</form></div>';
            echo "<div class='name'>".$entry."</div></div>";
        }
    }
    print "</div><p class='section-title'>Files</p><div class='grid'>";

    foreach($scanned_directory as $entry){
        if(strpos($entry, ".") !== false){
            if(preg_match('/\.(png|svg|jpg|jpeg|gif)$/i', $entry)){ $media = $path."/".$entry; }
            elseif(stripos($entry, ".pdf") !== false){ $media = "../media/file-pdf.svg"; }
            elseif(stripos($entry, ".txt") !== false){ $media = "../media/file-alt.svg"; }
            elseif(stripos($entry, ".docx") !== false){ $media = "../media/file-word.svg"; }
            elseif(stripos($entry, ".xlsx") !== false){ $media = "../media/file-excel.svg"; }
            elseif(stripos($entry, ".pptx") !== false){ $media = "../media/file-powerpoint.svg"; }
            elseif(stripos($entry, ".zip") !== false){ $media = "../media/folder.svg"; }
            else{ $media = "../media/file.svg"; }

            $fileType = strtoupper(pathinfo($entry, PATHINFO_EXTENSION));
            if($fileType === ''){ $fileType = 'DATEI'; }
            $fileSizeBytes = @filesize($path.'/'.$entry);
            $fileSizeLabel = $fileSizeBytes !== false ? round($fileSizeBytes / 1024 / 1024, 2).' MB' : 'Unbekannt';
            echo "<div class='card' tabindex='0' role='button' aria-label='Datei ".$entry."' data-file-name=\"".htmlspecialchars($entry, ENT_QUOTES)."\" data-name='".$entry."' data-file-type='".htmlspecialchars($fileType, ENT_QUOTES)."' data-file-size='".htmlspecialchars($fileSizeLabel, ENT_QUOTES)."' onclick='previewFile(\"".$entry."\", \"".$media."\", \"".$path."/".$entry."\", \"".$fileType."\", \"".$fileSizeLabel."\")'><button type='button' class='card-menu-btn' aria-label='Datei-Menü' onclick='toggleCardMenu(event, this)'>⋯</button><img loading='lazy' src='".$media."' alt='File icon'>";
            echo '<div class="dropdown-content">';
            echo '<a href="'.$path.'/'.$entry.'" download>Download</a>';
            echo '<form action="deleteFile.php" method="post" style="margin:0;">';
            echo '<input type="hidden" name="path" value="'.$path.'"><input type="hidden" name="fileName" value="'.$entry.'"><input type="hidden" name="currentUrl" value="'.$_SERVER['REQUEST_URI'].'"><input type="hidden" name="type" value="file"><a onclick="this.parentNode.submit();">Delete</a>';
            echo '</form></div><div class="name">'.$entry.'</div></div>';
        }
    }
    print "</div>";
    ?>
</div></main><aside class="rightpanel"><div class="detail-card"><div style="font-size:.85rem;color:#7e8eaa;margin-bottom:8px;">DETAILS</div><div id="detailPreview" style="height:120px;border:1px solid #e7eefc;border-radius:12px;display:grid;place-items:center;margin-bottom:10px;background:#f8fbff;"><img id="detailPreviewImg" src="../media/file.svg" alt="Preview" style="max-height:92px;max-width:92px;"></div><h3 id="detailName" style="margin:0 0 8px;"><?php echo htmlspecialchars($currentFolderName, ENT_QUOTES); ?></h3><p id="detailSubtitle" style="margin:0 0 12px;color:#6c7d98;"><?php echo htmlspecialchars($currentDisplayPath, ENT_QUOTES); ?></p><div class="meta-row" id="rowFolders"><span>Ordner</span><strong id="detailFolders"><?php echo $folderCount; ?></strong></div><div class="meta-row" id="rowFiles"><span>Dateien</span><strong id="detailFiles"><?php echo $fileCount; ?></strong></div><div class="meta-row" id="rowType" style="display:none;"><span>Dateityp</span><strong id="detailType">-</strong></div><div class="meta-row" id="rowSize" style="display:none;"><span>Dateigröße</span><strong id="detailSize">-</strong></div><div class="meta-row"><span>Pfad</span><span id="detailPath"><?php echo htmlspecialchars($path, ENT_QUOTES); ?></span></div><div class="quick-actions"><a id="detailDownload" class="btn primary" href="#">Download</a><button class="btn" type="button" id="detailCopy">Copy Name</button></div></div></aside></div>
<div id="toast" class="toast" role="status" aria-live="polite"></div>
<script>
    var modal = document.getElementById("myModal");
    var openFolderBtn = document.getElementById("myBtn");
    if (openFolderBtn) { openFolderBtn.onclick = function() { modal.style.display = "block"; }; }
    document.getElementById("cancelNewFolder").onclick = function() { modal.style.display = "none"; };
    document.getElementsByClassName("close")[0].onclick = function() { modal.style.display = "none"; };
    window.addEventListener('click', function(event) {
        if (event.target == modal) modal.style.display = "none";
        if (!event.target.closest('.search-wrap')) {
            document.getElementById('searchResults').classList.remove('show');
        }
        if (!event.target.matches('.card, .card *')) {
            document.querySelectorAll('.dropdown-content').forEach(d => d.classList.remove('show'));
        }
    });
    function toggleCardMenu(event, btn) {
        event.preventDefault();
        event.stopPropagation();
        const card = btn.closest('.card');
        if (!card) return;
        const menu = card.querySelector('.dropdown-content');
        document.querySelectorAll('.dropdown-content').forEach(d => { if (d !== menu) d.classList.remove('show'); });
        if (menu) menu.classList.toggle('show');
    }
    function openFolderCard(event, el){
        if (event.target.closest('.dropdown-content')) return;
        window.location.href = el.dataset.href;
    }
    function openFolderCardByKey(event, el){
        if(event.key === 'Enter' || event.key === ' '){
            event.preventDefault();
            window.location.href = el.dataset.href;
        }
    }
    function previewFile(fileName, filePath, downloadPath, fileType = "Datei", fileSize = "Unbekannt") {
        document.getElementById("detailName").textContent = fileName;
        document.getElementById("detailSubtitle").textContent = "Datei ausgewählt";
        document.getElementById("detailPreviewImg").src = filePath;
        const detailDownload = document.getElementById("detailDownload");
        detailDownload.setAttribute("href", downloadPath);
        detailDownload.setAttribute("download", fileName);
        detailDownload.textContent = "Download";
        document.getElementById("detailPath").textContent = downloadPath;
        document.getElementById("detailType").textContent = fileType;
        document.getElementById("detailSize").textContent = fileSize;
        document.getElementById("rowFolders").style.display = "none";
        document.getElementById("rowFiles").style.display = "none";
        document.getElementById("rowType").style.display = "flex";
        document.getElementById("rowSize").style.display = "flex";
    }
    function moveFileToTargetPath(fileName, targetPath){
        if(!fileName || !targetPath) return;
        const form = document.createElement('form');
        form.method = 'post';
        form.action = 'moveFile.php';
        form.innerHTML = `
            <input type="hidden" name="fileName" value="${fileName}">
            <input type="hidden" name="sourcePath" value="<?php echo htmlspecialchars($path, ENT_QUOTES); ?>">
            <input type="hidden" name="targetPath" value="${targetPath}">
            <input type="hidden" name="currentUrl" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES); ?>">
        `;
        document.body.appendChild(form);
        form.submit();
    }
    function deleteFromPreview(fileName){
        const form = document.createElement('form');
        form.method = 'post';
        form.action = 'deleteFile.php';
        form.innerHTML = `
            <input type="hidden" name="path" value="<?php echo htmlspecialchars($path, ENT_QUOTES); ?>">
            <input type="hidden" name="fileName" value="${fileName}">
            <input type="hidden" name="currentUrl" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES); ?>">
            <input type="hidden" name="type" value="file">
        `;
        document.body.appendChild(form);
        form.submit();
    }
    document.addEventListener("DOMContentLoaded", function(){
        updateDetailsPanelForDirectory();
        preventBrowserDropNavigation();
        setupDropUpload();
        if(localStorage.getItem("lancloud_view_list") === "1"){
            document.querySelectorAll(".grid").forEach(g => g.classList.add("list"));
            const btn=document.getElementById("viewToggle");
            btn.setAttribute("aria-pressed","true");
            btn.innerText="◫ Grid View";
        }
        document.querySelectorAll(".card[data-file-name]").forEach(fileCard => {
            fileCard.addEventListener("click", () => {
                previewFile(fileCard.dataset.fileName, fileCard.querySelector("img").getAttribute("src"), "<?php echo htmlspecialchars($path, ENT_QUOTES); ?>/" + fileCard.dataset.fileName, fileCard.dataset.fileType || "Datei", fileCard.dataset.fileSize || "Unbekannt");
            });
            fileCard.setAttribute("draggable", "true");
            fileCard.addEventListener("dragstart", (event) => {
                event.dataTransfer.setData("text/plain", fileCard.dataset.fileName);
                document.getElementById("breadcrumb").classList.add("drag-active");
            });
            fileCard.addEventListener("dragend", () => {
                document.getElementById("breadcrumb").classList.remove("drag-active");
                document.querySelectorAll(".crumb-target").forEach(t => t.classList.remove("drag-over"));
            });
        });
        document.querySelectorAll(".folder-card[data-folder-name]").forEach(folderCard => {
            folderCard.addEventListener("dragover", (event) => {
                event.preventDefault();
                folderCard.classList.add("drag-over");
            });
            folderCard.addEventListener("dragleave", () => folderCard.classList.remove("drag-over"));
            folderCard.addEventListener("drop", (event) => {
                event.preventDefault();
                folderCard.classList.remove("drag-over");
                const fileName = event.dataTransfer.getData("text/plain");
                const targetFolder = folderCard.dataset.folderName;
                if (fileName && targetFolder) {
                    const targetPath = "<?php echo htmlspecialchars($path, ENT_QUOTES); ?>" + "/" + targetFolder;
                    moveFileToTargetPath(fileName, targetPath);
                }
            });
        });
        document.querySelectorAll(".crumb-target").forEach(target => {
            target.addEventListener("dragover", (event) => {
                event.preventDefault();
                target.classList.add("drag-over");
            });
            target.addEventListener("dragleave", () => target.classList.remove("drag-over"));
            target.addEventListener("drop", (event) => {
                event.preventDefault();
                target.classList.remove("drag-over");
                const fileName = event.dataTransfer.getData("text/plain");
                const targetPath = target.dataset.path;
                if (fileName && targetPath) moveFileToTargetPath(fileName, targetPath);
            });
        });
        document.getElementById("searchResults").addEventListener("click", function(event){
            const node = event.target.closest(".search-node");
            if(!node || !node.dataset.url){ return; }
            window.location.href = node.dataset.url;
        });
    });

    function updateDetailsPanelForDirectory(){
        document.getElementById("detailName").textContent = "<?php echo htmlspecialchars($currentFolderName, ENT_QUOTES); ?>";
        document.getElementById("detailSubtitle").textContent = document.getElementById("currentPathText").innerText;
        document.getElementById("detailPreviewImg").src = "../media/folder-open.svg";
        const detailDownload = document.getElementById("detailDownload");
        detailDownload.textContent = "Download ZIP";
        detailDownload.removeAttribute("download");
        detailDownload.setAttribute("href", "downloadFolder.php?path=<?php echo urlencode(dirname(rtrim($path, '/'))); ?>&folder=<?php echo urlencode(basename(rtrim($path, '/'))); ?>");
        document.getElementById("rowFolders").style.display = "flex";
        document.getElementById("rowFiles").style.display = "flex";
        document.getElementById("rowType").style.display = "none";
        document.getElementById("rowSize").style.display = "none";
    }
    document.getElementById("detailCopy").addEventListener("click", function(){ navigator.clipboard.writeText(document.getElementById("detailName").textContent); showToast("Name kopiert"); });

</script>
<script src="chat_notifier.js"></script>
</body>
</html>
