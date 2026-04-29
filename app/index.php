<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lan Cloud</title>
    <style>
        :root {
            --bg: #0f172a;
            --panel: #111827;
            --panel-soft: #1f2937;
            --border: #334155;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --accent: #22c55e;
            --accent-soft: #16a34a;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, system-ui, Arial, sans-serif; background: radial-gradient(circle at top, #1e293b 0%, var(--bg) 45%); color: var(--text); }
        #content { max-width: 1200px; margin: 0 auto; padding: 24px; }
        .topbar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; margin-bottom: 16px; }
        h1 { margin:0; font-size: 1.8rem; }
        .toolbar { display:flex; gap:10px; align-items:center; }
        .input, .btn { border:1px solid var(--border); background: var(--panel); color: var(--text); border-radius: 10px; padding: 10px 12px; }
        .btn { cursor:pointer; }
        .btn:hover { border-color: var(--accent); }
        #uploadWrapper { border: 2px dashed var(--border); border-radius: 16px; background: rgba(17, 24, 39, .9); padding: 24px; text-align: center; margin-bottom: 16px; transition: .2s ease; }
        #uploadWrapper .helper { color: var(--muted); }
        #uploadFile { width: 100%; height: 48px; opacity: 0; cursor: pointer; position:absolute; inset:0; }
        .uploadInputWrap { position:relative; height: 48px; margin-top: 10px; }
        .uploadButtonFake { height:48px; border-radius:10px; display:grid; place-items:center; background: var(--panel-soft); border:1px solid var(--border); }
        #breadcrumb { margin: 12px 0; color: var(--muted); }
        #breadcrumb a { color: #c7d2fe; text-decoration:none; }
        .section-title { margin: 18px 0 8px; color: var(--muted); font-size: .95rem; }
        .grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(150px,1fr)); gap: 12px; }
        .grid.list { display:flex; flex-direction:column; }
        .grid.list .card { flex-direction:row; justify-content:flex-start; align-items:center; min-height:64px; gap:12px; }
        .grid.list .name { text-align:left; }
        .card { background: var(--panel); border:1px solid var(--border); border-radius: 14px; padding: 10px; min-height: 130px; display:flex; flex-direction:column; align-items:center; justify-content:space-between; transition:.2s ease; position:relative; }
        .card:hover { transform: translateY(-2px); border-color:#64748b; }
        .card img { width:56px; height:56px; object-fit:contain; }
        .card:focus { outline:2px solid var(--accent); outline-offset:2px; }
        .name { font-size: .85rem; text-align:center; overflow-wrap:anywhere; }
        .folder-card { min-height: 100px; }
        .dropdown-content { display:none; position:absolute; top:8px; right:8px; background:#0b1220; border:1px solid var(--border); border-radius:8px; overflow:hidden; z-index:2; }
        .dropdown-content a { display:block; color:var(--text); text-decoration:none; padding:8px 10px; font-size:.85rem; }
        .dropdown-content a:hover { background:#1e293b; }
        .show { display:block; }
        #leftSidebar { position: fixed; right: 24px; bottom: 24px; }
        #leftSidebar img { width: 54px; height:54px; cursor:pointer; background: var(--accent); border-radius:50%; padding: 12px; box-shadow: 0 8px 20px rgba(0,0,0,.35); }
        #leftSidebar img:hover { background: var(--accent-soft); }
        .modal { display:none; position:fixed; inset:0; background: rgba(0,0,0,.45); z-index: 9999; }
        .modal-content { width:min(420px,90%); margin: 15vh auto; background: #f8fafc; color:#0f172a; border-radius: 14px; padding: 16px; }
        .modal-content input { width:100%; padding:10px; margin-bottom: 10px; }
        #noContent { text-align:center; color:var(--muted); margin-top: 24px; }
        .toast { position:fixed; left:50%; transform:translateX(-50%); bottom:20px; background:#111827; border:1px solid var(--border); color:var(--text); padding:10px 14px; border-radius:10px; display:none; z-index:10; }
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
        function normalizeField(){ document.getElementById("uploadWrapper").style.borderColor = "#334155"; }
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
            btn.innerText = isList ? 'List View' : 'Grid View';
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
            document.getElementById('copyPathBtn').innerText = 'Copied!';
            setTimeout(()=>document.getElementById('copyPathBtn').innerText='Copy Path',1200);
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
    <div class="topbar"><h1>Lan Cloud</h1><div class="toolbar"><button class="btn" onclick="history.back()">← Zurück</button><input id="searchInput" class="input" placeholder="Search files/folders" oninput="filterEntriesDebounced()"><select id="sortSelect" class="input" onchange="sortEntries()"><option value="nameAsc">Name A-Z</option><option value="nameDesc">Name Z-A</option></select><button id="viewToggle" class="btn" onclick="toggleView()" aria-pressed="false">List View</button><button id="copyPathBtn" class="btn" onclick="copyCurrentPath()">Copy Path</button></div></div>
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
            echo "<div class='card folder-card' tabindex='0' role='link' aria-label='Ordner ".$entry."' data-href='".$_SERVER['REQUEST_URI'].($cntPath == 0 ? "?" : "&")."Pfad".$cntPath."=".$entry."' data-name='".$entry."' oncontextmenu='openDropdownFolder(this)' onclick='openFolderCard(event, this)' onkeydown='openFolderCardByKey(event, this)'>";
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

            echo "<div class='card' tabindex='0' role='button' aria-label='Datei ".$entry."' data-name='".$entry."' oncontextmenu='openDropdown(this)' onclick='previewFile(\"".$entry."\", \"".$media."\")'><img loading='lazy' src='".$media."' alt='File icon'>";
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
<div id="previewModal" class="modal"><div class="modal-content"><span class="close" id="closePreview" style="float:right;cursor:pointer;">&times;</span><div id="previewBody"></div></div></div>
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
    function previewFile(fileName, filePath){
        const modal = document.getElementById("previewModal");
        const body = document.getElementById("previewBody");
        if(/\.(png|jpg|jpeg|gif|svg)$/i.test(fileName)){
            body.innerHTML = `<p><strong>${fileName}</strong></p><img src="${filePath}" style="max-width:100%;max-height:60vh;">`;
        } else {
            body.innerHTML = `<p><strong>${fileName}</strong></p><p>Keine Vorschau für diesen Dateityp.</p>`;
        }
        modal.style.display = "block";
    }
    document.getElementById("closePreview").onclick = function(){ document.getElementById("previewModal").style.display = "none"; };
    document.addEventListener("DOMContentLoaded", function(){
        if(localStorage.getItem("lancloud_view_list") === "1"){
            document.querySelectorAll(".grid").forEach(g => g.classList.add("list"));
            const btn=document.getElementById("viewToggle");
            btn.setAttribute("aria-pressed","true");
            btn.innerText="Grid View";
        }
    });
</script>
</body>
</html>
