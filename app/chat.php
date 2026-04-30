<!doctype html>
<html lang="de">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LocalLoot Chat</title>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:#f4f8ff;color:#0b1b3a}
    .wrap{max-width:980px;margin:0 auto;padding:18px}
    .top{display:flex;justify-content:space-between;align-items:center;background:#fff;border:1px solid #dbe7ff;border-radius:14px;padding:12px 14px}
    .brand{display:flex;gap:10px;align-items:center;text-decoration:none;color:inherit}
    .brand img{width:42px;height:42px;object-fit:contain;border-radius:10px;background:#e8f0ff;padding:5px}
    #chatBox{height:60vh;overflow:auto;background:#fff;border:1px solid #dbe7ff;border-radius:14px;padding:12px;margin-top:14px}
    .m{padding:8px 10px;border-bottom:1px solid #eef3ff}
    .meta{font-size:.78rem;color:#5f7193}
    form{display:flex;gap:10px;margin-top:12px}
    input,button{padding:11px 12px;border-radius:10px;border:1px solid #cfdfff}
    #text{flex:1}
    button{background:#1f6fff;color:#fff;border-color:#1f6fff;cursor:pointer}
    button.secondary{background:#fff;color:#1f6fff}
    #joinGate{position:fixed;inset:0;background:rgba(0,0,0,.45);display:grid;place-items:center}
    .gateCard{background:#fff;padding:20px;border-radius:14px;width:min(760px,94vw)}
    .row{display:flex;gap:8px;margin-top:8px}
    .muted{font-size:.88rem;color:#5f7193}
    .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;margin-top:14px}
    .roomCard{background:#f8fbff;border:1px solid #dbe7ff;border-radius:12px;padding:12px;display:flex;justify-content:space-between;align-items:center;gap:8px}
    .hidden{display:none}
  </style>
</head>
<body><div class="wrap"><div class="top"><a class="brand" href="index.php"><img src="../media/LocalLoot_logo.png" alt="LocalLoot Logo"><div><strong>LocalLoot Chat</strong><div id="roomLabel" style="font-size:.85rem;color:#5f7193">Noch kein Raum gewählt</div></div></a><a href="file-browser.php">📁 Dateien</a></div><div id="chatBox"></div><form id="chatForm"><input id="text" maxlength="600" placeholder="Nachricht schreiben..." required><button type="submit">Senden</button><button id="backToRoomsBtn" type="button" class="secondary">Zur Raumauswahl</button></form></div>
<div id="joinGate"><div class="gateCard"><h3>Chat beitreten</h3>
  <div id="nameStep">
    <p class="muted">Gib zuerst deinen Namen ein.</p>
    <div class="row"><input id="nameInput" maxlength="40" placeholder="Dein Name" style="flex:1"><button id="continueBtn" type="button">Weiter</button></div>
  </div>
  <div id="roomStep" class="hidden">
    <p class="muted">Wähle einen offenen Chat aus der Grid-Ansicht oder erstelle einen neuen.</p>
    <div class="row"><input id="newRoomInput" maxlength="60" placeholder="Neuen Chat-Namen eingeben" style="flex:1"><button id="createRoomBtn" type="button">Neuen Chat erstellen</button></div>
    <div id="roomGrid" class="grid"></div>
    <div class="row"><button id="backToNameBtn" type="button" class="secondary">Zurück zur Namenseingabe</button></div>
  </div>
</div></div>
<script>
const box=document.getElementById('chatBox'); let username=''; let room='';
function fmt(ts){return new Date(ts*1000).toLocaleString('de-DE');}
function esc(s){return s.replace(/[&<>\"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));}
function showNameStep(){document.getElementById('nameStep').classList.remove('hidden');document.getElementById('roomStep').classList.add('hidden');}
function showRoomStep(){document.getElementById('nameStep').classList.add('hidden');document.getElementById('roomStep').classList.remove('hidden');}
function leaveRoom(){room='';sessionStorage.removeItem('chat_room');document.getElementById('roomLabel').textContent='Noch kein Raum gewählt';document.getElementById('joinGate').style.display='grid';box.innerHTML='';showRoomStep();loadRooms();}
async function loadRooms(){
  const r=await fetch('chat_api.php?action=listRooms');
  const d=await r.json();
  const grid=document.getElementById('roomGrid');
  grid.innerHTML='';
  const rooms=d.rooms||[];
  if(!rooms.length){grid.innerHTML='<div class="muted">Noch keine offenen Chats vorhanden.</div>';return;}
  rooms.forEach(n=>{const card=document.createElement('div');card.className='roomCard';card.innerHTML=`<strong>${esc(n)}</strong><button type="button">Beitreten</button>`;card.querySelector('button').onclick=()=>openChat(n);grid.appendChild(card);});
}
async function load(){ if(!room) return; const r=await fetch('chat_api.php?action=list&room='+encodeURIComponent(room)); const d=await r.json(); box.innerHTML=(d.messages||[]).map(m=>`<div class="m"><div class="meta"><strong>${esc(m.user)}</strong> • ${fmt(m.ts)}</div><div>${esc(m.text)}</div></div>`).join(''); box.scrollTop=box.scrollHeight; document.getElementById('roomLabel').textContent='Raum: '+room; }
function openChat(chosenRoom){ if(!username||!chosenRoom) return; room=chosenRoom; sessionStorage.setItem('chat_username',username); sessionStorage.setItem('chat_room',room); document.getElementById('joinGate').style.display='none'; load(); }
setInterval(()=>{ if(username&&room) load(); },2000);
document.getElementById('continueBtn').onclick=()=>{const n=document.getElementById('nameInput').value.trim();if(!n)return;username=n;sessionStorage.setItem('chat_username',username);showRoomStep();loadRooms();};
document.getElementById('backToNameBtn').onclick=()=>{showNameStep();};
document.getElementById('createRoomBtn').onclick=async()=>{ const rname=document.getElementById('newRoomInput').value.trim(); if(!rname||!username) return; await fetch('chat_api.php?action=createRoom&room='+encodeURIComponent(rname)); await loadRooms(); openChat(rname); };
document.getElementById('backToRoomsBtn').onclick=()=>leaveRoom();
document.getElementById('chatForm').onsubmit=async(e)=>{e.preventDefault(); if(!username||!room) return; const t=document.getElementById('text'); const text=t.value.trim(); if(!text) return; await fetch('chat_api.php?action=send&room='+encodeURIComponent(room),{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({user:username,text})}); t.value=''; load();};
(async()=>{const u=sessionStorage.getItem('chat_username')||''; const r=sessionStorage.getItem('chat_room')||''; if(u){username=u;document.getElementById('nameInput').value=u;showRoomStep();await loadRooms();} else {showNameStep();} if(u&&r){openChat(r);} })();
</script></body></html>
