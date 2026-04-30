<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lan Cloud</title>
    <style>
        :root {
            --bg: #040a1f;
            --bg-soft: #07143a;
            --panel: rgba(8, 20, 52, 0.82);
            --panel-soft: rgba(15, 36, 78, 0.9);
            --panel-bright: rgba(20, 58, 122, 0.62);
            --border: #1a7fd4;
            --border-soft: rgba(89, 189, 255, 0.45);
            --text: #e7f4ff;
            --muted: #8fc8ee;
            --accent: #1ec7ff;
            --accent-soft: #1578ff;
            --warn: #ffd447;
            --glow: 0 0 0 1px rgba(30, 199, 255, 0.35), 0 0 20px rgba(30, 199, 255, 0.16);
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Inter", "Segoe UI", system-ui, Arial, sans-serif; background: radial-gradient(circle at 18% 12%, #0d2f6c 0%, var(--bg-soft) 30%, var(--bg) 65%, #01040f 100%); color: var(--text); min-height:100vh; line-height:1.45; }
        #content { max-width: 1200px; margin: 0 auto; padding: 24px; }
        .topbar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; margin-bottom: 16px; background: linear-gradient(90deg, rgba(7,24,62,.95), rgba(5,16,42,.85)); border: 1px solid var(--border-soft); box-shadow: var(--glow); padding: 14px 16px; border-radius: 14px; }
        h1 { margin:0; font-size: 1.65rem; text-transform: uppercase; letter-spacing: .04em; text-shadow: 0 0 10px rgba(34, 198, 255, .28); font-weight:700; }
        .toolbar { display:flex; gap:10px; align-items:center; }
        .input, .btn { border:1px solid var(--border-soft); background: linear-gradient(180deg, rgba(18, 48, 96, 0.95), rgba(7, 23, 56, 0.95)); color: #eef8ff; border-radius: 10px; padding: 10px 12px; box-shadow: inset 0 0 12px rgba(31, 112, 183, 0.3); font-size:.95rem; font-weight:600; }
        .input::placeholder { color:#c6e7ff; opacity:.95; }
        .btn { cursor:pointer; }
        .btn:hover { border-color: var(--accent); box-shadow: 0 0 14px rgba(30, 199, 255, 0.35); }
        #uploadWrapper { border: 2px dashed var(--border-soft); border-radius: 16px; background: linear-gradient(160deg, rgba(6, 20, 55, .95), rgba(9, 29, 72, .75)); padding: 24px; text-align: center; margin-bottom: 16px; transition: .2s ease; box-shadow: var(--glow); }
        #uploadWrapper .helper { color: var(--muted); }
        #uploadFile { width: 100%; height: 48px; opacity: 0; cursor: pointer; position:absolute; inset:0; }
        .uploadInputWrap { position:relative; height: 48px; margin-top: 10px; }
        .uploadButtonFake { height:48px; border-radius:10px; display:grid; place-items:center; background: var(--panel-soft); border:1px solid var(--border-soft); }
        #breadcrumb { margin: 12px 0; color: var(--muted); }
        #breadcrumb a { color: #c7d2fe; text-decoration:none; }
        .section-title { margin: 18px 0 8px; color: #c4e9ff; font-size: 1rem; font-weight:700; letter-spacing:.02em; }
        .grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(150px,1fr)); gap: 12px; }
        .grid.list { display:flex; flex-direction:column; }
        .grid.list .card { flex-direction:row; justify-content:flex-start; align-items:center; min-height:64px; gap:12px; }
        .grid.list .name { text-align:left; }
        .card { background: linear-gradient(160deg, var(--panel) 0%, var(--panel-soft) 100%); border:1px solid var(--border-soft); border-radius: 14px; padding: 10px; min-height: 130px; display:flex; flex-direction:column; align-items:center; justify-content:space-between; transition:.2s ease; position:relative; box-shadow: inset 0 0 30px rgba(14, 57, 121, 0.25); }
        .card:hover { transform: translateY(-2px); border-color:#56d9ff; box-shadow: var(--glow); }
        .card img { width:56px; height:56px; object-fit:contain; }
        .card:focus { outline:2px solid var(--accent); outline-offset:2px; }
        .name { font-size: .9rem; text-align:center; overflow-wrap:anywhere; font-weight:600; color:#eaf5ff; text-shadow: 0 0 6px rgba(10, 39, 78, .45); }
        .folder-card { min-height: 100px; }
        .folder-card.drag-over { border-color: #61e4ff; box-shadow: 0 0 0 2px rgba(97,228,255,.35), 0 0 22px rgba(30,199,255,.35); }
        .dropdown-content { display:none; position:absolute; top:8px; right:8px; background:#040f2a; border:1px solid var(--border-soft); border-radius:8px; overflow:hidden; z-index:2; }
        .dropdown-content a { display:block; color:var(--text); text-decoration:none; padding:8px 10px; font-size:.85rem; }
        .dropdown-content a:hover { background:#1e293b; }
        .show { display:block; }
        #leftSidebar { position: fixed; right: 24px; bottom: 24px; }
        #leftSidebar img { width: 54px; height:54px; cursor:pointer; background: linear-gradient(180deg, var(--accent), var(--accent-soft)); border-radius:50%; padding: 12px; box-shadow: 0 0 18px rgba(30,199,255,.55); }
        #leftSidebar img:hover { filter: brightness(1.08); }
        .folder-card img, .card img { filter: brightness(0) saturate(100%) invert(73%) sepia(39%) saturate(1592%) hue-rotate(165deg) brightness(103%) contrast(102%); }
        .card img[alt='File icon'][src*='/mainStorage/'] { filter: none; }
        .modal { display:none; position:fixed; inset:0; background: rgba(0,0,0,.45); z-index: 9999; }
        .modal-content { width:min(420px,90%); margin: 15vh auto; background: #e7f4ff; color:#09203f; border-radius: 14px; padding: 16px; border:1px solid #62c7ff; }
        .modal-content input { width:100%; padding:10px; margin-bottom: 10px; }
        #noContent { text-align:center; color:var(--muted); margin-top: 24px; }
        .toast { position:fixed; left:50%; transform:translateX(-50%); bottom:20px; background:#07173e; border:1px solid var(--border-soft); color:var(--text); padding:10px 14px; border-radius:10px; display:none; z-index:10; box-shadow: var(--glow); }
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
<div id='leftSidebar'><img src='../media/folder-plus.svg' id="myBtn"></div>
<div id="myModal" class="modal"><div class="modal-content"><span class="close" style="float:right;cursor:pointer;">&times;</span><p>Neuer Ordner</p><form action="createFolder.php" method='post'><input type="text" pattern="[^|,/:?*\\]+" value="unbenannter Ordner" name="folderName" required><input type="hidden" name="path" value="<?php echo $path; ?>"><input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="currentUrl"><button class="btn" type="submit">Erstellen</button><button class="btn" id="cancelNewFolder" type="button">Abbrechen</button></form></div></div>
<div id="content">
    <div class="topbar"><h1>◉ Lan Cloud Matrix</h1><div class="toolbar"><button class="btn" onclick="history.back()">⟵ Zurück</button><input id="searchInput" class="input" placeholder="Dateien/Ordner suchen" oninput="filterEntriesDebounced()"><select id="sortSelect" class="input" onchange="sortEntries()"><option value="nameAsc">Name A-Z</option><option value="nameDesc">Name Z-A</option></select><button id="viewToggle" class="btn" onclick="toggleView()" aria-pressed="false">☰ List View</button><button id="copyPathBtn" class="btn" onclick="copyCurrentPath()">⎘ Pfad kopieren</button></div></div>
    <div id="uploadWrapper"><div class="helper">Drag & drop files here or click to browse.</div><div id="uploadStatus" class="helper"></div><form method="post" action="uploadFiles.php" enctype="multipart/form-data"><div class="uploadInputWrap"><div class="uploadButtonFake">Choose files</div><input id="uploadFile" type="file" onchange="changeText(this);" name="files[]" multiple></div><input type="hidden" value="<?php echo $path; ?>" name="path"><input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="currentUrl"></form></div>

    <div id="breadcrumb"><span id="currentPathText"><?php
        if(isset($_GET["Pfad0"])){
            for($i=0;$i<$cntPath;$i++){
                error_reporting(E_ERROR | E_PARSE);
                $removeFrom = explode("&",$_SERVER['REQUEST_URI']);
                $postionInUrl = strpos($_SERVER['REQUEST_URI'], $removeFrom[$i+1]);
                $pathDeletePart = "&".substr($_SERVER['REQUEST_URI'], $postionInUrl);
                $pathBack = str_replace($pathDeletePart, "", $_SERVER['REQUEST_URI']);
                $pfadName = "Pfad".$i;
                if($i <= 0){ print "<a href='index.php'>mainStorage</a>"; }
                print " → <a href='".$pathBack."'>".$_GET[$pfadName]."</a>";
            }
        }else{ print $path; }
    ?></span></div>

    <?php
    $scanned_directory = array_values(array_diff(scandir($path), array('..', '.')));
    if(count($scanned_directory) === 0){ print "<p id='noContent'>Dieser Ordner ist leer</p>"; }

    print "<p class='section-title'>Folders</p><div class='grid'>";
    foreach($scanned_directory as $entry){
        if(strpos($entry, ".") === false){
            echo "<div class='card folder-card' tabindex='0' role='link' aria-label='Ordner ".$entry."' data-folder-name=\"".htmlspecialchars($entry, ENT_QUOTES)."\" data-href='".$_SERVER['REQUEST_URI'].($cntPath == 0 ? "?" : "&")."Pfad".$cntPath."=".$entry."' data-name='".$entry."' oncontextmenu='openDropdownFolder(this)' onclick='openFolderCard(event, this)' onkeydown='openFolderCardByKey(event, this)'>";
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

            echo "<div class='card' tabindex='0' role='button' aria-label='Datei ".$entry."' data-file-name=\"".htmlspecialchars($entry, ENT_QUOTES)."\" data-name='".$entry."' oncontextmenu='openDropdown(this)' onclick='previewFile(\"".$entry."\", \"".$media."\", \"".$path."/".$entry."\")'><img loading='lazy' src='".$media."' alt='File icon'>";
            echo '<div class="dropdown-content">';
            echo '<a href="'.$path.'/'.$entry.'" download>Download</a>';
            echo '<form action="deleteFile.php" method="post" style="margin:0;">';
            echo '<input type="hidden" name="path" value="'.$path.'"><input type="hidden" name="fileName" value="'.$entry.'"><input type="hidden" name="currentUrl" value="'.$_SERVER['REQUEST_URI'].'"><input type="hidden" name="type" value="file"><a onclick="this.parentNode.submit();">Delete</a>';
            echo '</form></div><div class="name">'.$entry.'</div></div>';
        }
    }
    print "</div>";
    ?>
</div>
<div id="toast" class="toast" role="status" aria-live="polite"></div>
<div id="previewModal" class="modal"><div class="modal-content"><span class="close" id="closePreview" style="float:right;cursor:pointer;">&times;</span><div id="previewBody"></div><div id="previewActions" style="margin-top:12px; display:flex; gap:8px; flex-wrap:wrap;"></div></div></div>
<script>
    var modal = document.getElementById("myModal");
    document.getElementById("myBtn").onclick = function() { modal.style.display = "block"; };
    document.getElementById("cancelNewFolder").onclick = function() { modal.style.display = "none"; };
    document.getElementsByClassName("close")[0].onclick = function() { modal.style.display = "none"; };
    window.addEventListener('click', function(event) {
        if (event.target == modal) modal.style.display = "none";
        if (!event.target.matches('.card, .card *')) {
            document.querySelectorAll('.dropdown-content').forEach(d => d.classList.remove('show'));
        }
    });
    function openDropdown(x) { const m=x.querySelector('.dropdown-content'); if(m) m.classList.toggle('show'); }
    function openDropdownFolder(x) { const m=x.querySelector('.dropdown-content'); if(m) m.classList.toggle('show'); }
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
    function previewFile(fileName, filePath, downloadPath){
        const modal = document.getElementById("previewModal");
        const body = document.getElementById("previewBody");
        const actions = document.getElementById("previewActions");
        if(/\.(png|jpg|jpeg|gif|svg)$/i.test(fileName)){
            body.innerHTML = `<p><strong>${fileName}</strong></p><img src="${filePath}" style="max-width:100%;max-height:60vh;">`;
        } else {
            body.innerHTML = `<p><strong>${fileName}</strong></p><p>Keine Vorschau für diesen Dateityp.</p>`;
        }
        actions.innerHTML = `
            <a class="btn" href="${downloadPath}" download>Download</a>
            <button class="btn" type="button" onclick="deleteFromPreview('${fileName.replace(/'/g, "\\'")}')">Delete</button>
        `;
        modal.style.display = "block";
    }
    function moveFileToFolder(fileName, targetFolder){
        if(!fileName || !targetFolder) return;
        const form = document.createElement('form');
        form.method = 'post';
        form.action = 'moveFile.php';
        form.innerHTML = `
            <input type="hidden" name="path" value="<?php echo htmlspecialchars($path, ENT_QUOTES); ?>">
            <input type="hidden" name="fileName" value="${fileName}">
            <input type="hidden" name="targetFolder" value="${targetFolder}">
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
    document.getElementById("closePreview").onclick = function(){ document.getElementById("previewModal").style.display = "none"; };
    document.getElementById("previewModal").addEventListener('click', function(event){
        if(event.target === this){ this.style.display = "none"; }
    });
    document.addEventListener("DOMContentLoaded", function(){
        preventBrowserDropNavigation();
        setupDropUpload();
        if(localStorage.getItem("lancloud_view_list") === "1"){
            document.querySelectorAll(".grid").forEach(g => g.classList.add("list"));
            const btn=document.getElementById("viewToggle");
            btn.setAttribute("aria-pressed","true");
            btn.innerText="◫ Grid View";
        }
        document.querySelectorAll(".card[data-file-name]").forEach(fileCard => {
            fileCard.setAttribute("draggable", "true");
            fileCard.addEventListener("dragstart", (event) => {
                event.dataTransfer.setData("text/plain", fileCard.dataset.fileName);
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
                if (fileName && targetFolder) moveFileToFolder(fileName, targetFolder);
            });
        });
    });
</script>
</body>
</html>
