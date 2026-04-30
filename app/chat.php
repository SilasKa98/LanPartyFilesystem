<!doctype html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LocalLoot Chat</title>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:#f4f8ff;color:#0b1b3a}
    .wrap{max-width:980px;margin:0 auto;padding:18px}
    .top{display:flex;justify-content:space-between;align-items:center;background:#fff;border:1px solid #dbe7ff;border-radius:14px;padding:12px 14px}
    .brand{display:flex;gap:10px;align-items:center;text-decoration:none;color:inherit}
    .brand img{width:42px;height:42px;object-fit:contain;border-radius:10px;background:#e8f0ff;padding:5px}
    #chatBox{height:60vh;overflow:auto;background:#fff;border:1px solid #dbe7ff;border-radius:14px;padding:12px;margin-top:14px}
    .m{padding:8px 10px;border-bottom:1px solid #eef3ff}.meta{font-size:.78rem;color:#5f7193}
    form{display:flex;gap:10px;margin-top:12px}
    input,button{padding:11px 12px;border-radius:10px;border:1px solid #cfdfff}
    #text{flex:1}button{background:#1f6fff;color:#fff;border-color:#1f6fff;cursor:pointer}
    #nameGate{position:fixed;inset:0;background:rgba(0,0,0,.45);display:grid;place-items:center}
    .gateCard{background:#fff;padding:20px;border-radius:14px;width:min(420px,92vw)}
  </style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <a class="brand" href="index.php"><img src="../media/LocalLoot_logo.png" alt="LocalLoot Logo"><div><strong>LocalLoot Chat</strong><div style="font-size:.85rem;color:#5f7193">Gemeinsamer Chat-Raum</div></div></a>
    <a href="file-browser.php">📁 Dateien</a>
  </div>
  <div id="chatBox"></div>
  <form id="chatForm">
    <input id="text" maxlength="600" placeholder="Nachricht schreiben..." required>
    <button type="submit">Senden</button>
  </form>
</div>
<div id="nameGate"><div class="gateCard"><h3>Willkommen im Chat</h3><p>Bitte zuerst deinen Namen eingeben.</p><input id="nameInput" maxlength="40" placeholder="Dein Name"><button id="nameBtn" style="margin-top:8px;width:100%">Chat öffnen</button></div></div>
<script>
const box=document.getElementById('chatBox');
let username=sessionStorage.getItem('chat_username')||'';
function fmt(ts){return new Date(ts*1000).toLocaleString('de-DE');}
function esc(s){return s.replace(/[&<>\"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));}
async function load(){
  const r=await fetch('chat_api.php?action=list');
  const d=await r.json();
  box.innerHTML=(d.messages||[]).map(m=>`<div class="m"><div class="meta"><strong>${esc(m.user)}</strong> • ${fmt(m.ts)}</div><div>${esc(m.text)}</div></div>`).join('');
  box.scrollTop=box.scrollHeight;
}
if(username){document.getElementById('nameGate').style.display='none'; load();}
setInterval(()=>{ if(username) load(); }, 2000);
document.getElementById('nameBtn').onclick=()=>{ const n=document.getElementById('nameInput').value.trim(); if(!n) return; username=n; sessionStorage.setItem('chat_username',n); document.getElementById('nameGate').style.display='none'; load(); };
document.getElementById('chatForm').onsubmit=async(e)=>{e.preventDefault(); if(!username) return; const t=document.getElementById('text'); const text=t.value.trim(); if(!text) return; await fetch('chat_api.php?action=send',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({user:username,text})}); t.value=''; load();};
</script>
</body>
</html>
