<!doctype html>
<html lang="de">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LocalLoot Chat</title>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:#f4f8ff;color:#0b1b3a}
    .wrap{width:100%;padding:0}.app-shell{display:grid;grid-template-columns:300px 1fr;min-height:100vh}.chat-main{padding:18px}
    .layout{display:block}
    .panel{background:#fff;border:1px solid #dbe7ff;border-radius:14px;padding:12px}.sidebar{border-right:1px solid #e2e9f7;border-radius:0;border-left:0;border-top:0;border-bottom:0;min-height:100vh}
    .sidebar-brand{display:flex;gap:10px;align-items:center;text-decoration:none;color:inherit;margin-bottom:14px}
    .sidebar-brand img{width:44px;height:44px;border-radius:10px;background:#e8f0ff;padding:5px;object-fit:contain}
    .module-links{margin-bottom:12px}
    .side-link{display:block;padding:10px 12px;border-radius:10px;color:#2e4468;text-decoration:none;font-weight:600;margin:4px 0}
    .side-link.active{background:#edf3ff;color:#1f6fff}
    .muted{font-size:.88rem;color:#5f7193}
    .row{display:flex;gap:8px;flex-wrap:wrap}
    input,button{padding:11px 12px;border-radius:10px;border:1px solid #cfdfff}
    button{background:#1f6fff;color:#fff;border-color:#1f6fff;cursor:pointer}
    button.secondary{background:#fff;color:#1f6fff}
    .rooms{margin-top:12px;display:flex;flex-direction:column;gap:8px;max-height:46vh;overflow:auto}
    .roomItem{display:flex;gap:6px}.roomBtn{flex:1;text-align:left;background:#f8fbff;color:#0b1b3a;border:1px solid #dbe7ff}.deleteRoomBtn{background:#fff;color:#d22;border:1px solid #f0b3b3;padding:8px 10px;border-radius:10px}
    .roomBtn.active{border-color:#1f6fff;background:#eaf2ff}
    .new-room-wrap{display:grid;gap:8px}
    .password-wrap{display:none;width:100%}
    .password-wrap input{width:100%}
    .switch{position:relative;display:inline-block;width:48px;height:28px}
    .switch input{opacity:0;width:0;height:0}
    .slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:#c9d7ef;transition:.2s;border-radius:999px}
    .slider:before{position:absolute;content:"";height:22px;width:22px;left:3px;top:3px;background:white;transition:.2s;border-radius:50%}
    .switch input:checked + .slider{background:#1f6fff}
    .switch input:checked + .slider:before{transform:translateX(20px)}
    #chatBox{height:64vh;overflow:auto;background:#fdfefe;border:1px solid #dbe7ff;border-radius:12px;padding:10px}
    .m{padding:8px 10px;border-bottom:1px solid #eef3ff}.meta{font-size:.78rem;color:#5f7193}
    #chatForm{display:flex;gap:8px;margin-top:10px;align-items:center}
    #text{flex:1}.emojiWrap{position:relative}
    #emojiPanel{position:absolute;bottom:46px;right:0;background:#fff;border:1px solid #dbe7ff;border-radius:10px;padding:8px;display:none;grid-template-columns:repeat(8,1fr);gap:6px;width:280px;box-shadow:0 8px 25px rgba(0,0,0,.12)}
    .emoji{cursor:pointer;background:#fff;border:1px solid #eef3ff;border-radius:8px;padding:6px;text-align:center}
    #nameGate{position:fixed;inset:0;background:rgba(0,0,0,.45);display:grid;place-items:center}.gateCard{background:#fff;padding:20px;border-radius:14px;width:min(460px,94vw)}
    @media (max-width:900px){.app-shell{grid-template-columns:1fr}.sidebar{min-height:auto;border-right:0}.chat-main{padding:12px}}
  </style>
</head>
<body>
<div class="wrap"><div class="app-shell">
    <aside class="panel sidebar">
      <a class="sidebar-brand" href="index.php"><img src="../media/LocalLoot_logo.png" alt="LocalLoot Logo"><div><strong style="font-size:1.2rem">LocalLoot</strong><div class="muted">Local file & game sharing</div></div></a>
      <div class="module-links"><a class="side-link" href="file-browser.php">📁 Files</a><a class="side-link" href="#">🎮 Game Library</a><a class="side-link active" href="chat.php">💬 Chat</a><a class="side-link" href="#">🖥️ Server Status</a></div>
      <h3 style="margin:8px 0 8px">Chats</h3>
      <div class="row" style="align-items:center;justify-content:space-between;margin-bottom:8px"><p class="muted" style="margin:0">Hallo <strong id="userLabel">-</strong></p><button id="editNameBtn" type="button" class="secondary">Name ändern</button></div>
      <div class="new-room-wrap"><div class="row"><input id="newRoomInput" maxlength="60" placeholder="Neuen Chat-Namen" style="flex:1"><button id="createRoomBtn" type="button">Starten</button></div><div class="row"><label class="muted" style="display:flex;align-items:center;gap:8px"><span>Passwortschutz</span><span class="switch"><input id="protectToggle" type="checkbox"><span class="slider"></span></span></label></div><div class="password-wrap" id="passwordWrap"><input id="newRoomPassword" type="password" maxlength="64" placeholder="Chat-Passwort" style="max-width:220px"></div></div>
      <div id="rooms" class="rooms"></div>
    </aside>

    <main class="chat-main"><section class="panel">
      <div class="muted" id="roomLabel" style="margin-bottom:6px">Kein Raum ausgewählt</div>
      <div id="chatBox"><div class="muted">Bitte links einen Chat auswählen oder erstellen.</div></div>
      <form id="chatForm"><input id="text" maxlength="600" placeholder="Nachricht schreiben..." required><div class="emojiWrap"><button id="emojiToggle" type="button" class="secondary">😊</button><div id="emojiPanel"></div></div><button type="submit">Senden</button></form>
    </section></main>
  </div></div>
<div id="nameGate"><div class="gateCard"><h3>Willkommen im Chat</h3><p class="muted">Bitte gib deinen Namen ein, um fortzufahren.</p><div class="row"><input id="nameInput" maxlength="40" placeholder="Dein Name" style="flex:1"><button id="continueBtn" type="button">Weiter</button></div></div></div>
<audio id="notifAudio" preload="auto"><source src="data:audio/wav;base64,UklGRlQAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YTAAAAAAAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA" type="audio/wav"></audio>
<script src="chat_notifier.js"></script>
<script>


const box=document.getElementById('chatBox');
const roomsEl=document.getElementById('rooms');
const emojiList=['😀','😁','😂','🤣','😊','😍','🥳','😎','🤝','👍','👏','🔥','💡','✅','🎉','🚀','🙌','😅','🤔','😇','😴','😭','😡','❤️','💙','💚','🧡','💬','📁','🛠️','🌟','🍀'];
let username=''; let room='';
let visitedRooms=[];
let roomLastTs={};
let notificationsEnabled=false;
let audioUnlocked=false;
let roomPasswords={};

function fmt(ts){return new Date(ts*1000).toLocaleString('de-DE');}
function esc(s){return s.replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));}
function renderEmojiPanel(){const p=document.getElementById('emojiPanel');p.innerHTML='';emojiList.forEach(e=>{const b=document.createElement('button');b.type='button';b.className='emoji';b.textContent=e;b.onclick=()=>{const t=document.getElementById('text');t.value+=e;t.focus();};p.appendChild(b);});}
function persistState(){sessionStorage.setItem('chat_visited_rooms',JSON.stringify(visitedRooms));sessionStorage.setItem('chat_room_last_ts',JSON.stringify(roomLastTs));sessionStorage.setItem('chat_room_passwords',JSON.stringify(roomPasswords));localStorage.setItem('chat_visited_rooms',JSON.stringify(visitedRooms));localStorage.setItem('chat_room_last_ts',JSON.stringify(roomLastTs));localStorage.setItem('chat_room_passwords',JSON.stringify(roomPasswords));}
function markRoomVisited(name){if(!visitedRooms.includes(name)){visitedRooms.push(name);}persistState();}
function playNotificationTone(){
  const audio=document.getElementById('notifAudio');
  if(!audio){return;}
  audio.currentTime=0;
  const playPromise=audio.play();
  if(playPromise && typeof playPromise.catch==='function'){playPromise.catch(()=>{});}
}
function notify(message){
  if(notificationsEnabled && Notification.permission==='granted'){
    new Notification(message.title,{body:message.body});
  }
  playNotificationTone();
}

async function loadRooms(){
  const r=await fetch('chat_api.php?action=listRooms');
  const d=await r.json();
  const rooms=d.rooms||[];
  roomsEl.innerHTML='';
  if(!rooms.length){roomsEl.innerHTML='<div class="muted">Noch keine Chats vorhanden.</div>';return;}
  rooms.forEach(item=>{const name=item.name||'';const isProtected=!!item.protected;const owner=item.owner||'';const wrap=document.createElement('div');wrap.className='roomItem';const b=document.createElement('button');b.type='button';b.className='roomBtn'+(name===room?' active':'');b.textContent=(isProtected?'🔒 ':'')+name;b.onclick=()=>openChat(name,isProtected);wrap.appendChild(b);if(username&&owner===username){const del=document.createElement('button');del.type='button';del.className='deleteRoomBtn';del.textContent='🗑';del.title='Chat löschen';del.onclick=()=>deleteRoom(name);wrap.appendChild(del);}roomsEl.appendChild(wrap);});
}

async function fetchLatestTs(roomName){
  const pw=roomPasswords[roomName]||'';
  const r=await fetch('chat_api.php?action=list&room='+encodeURIComponent(roomName)+'&password='+encodeURIComponent(pw));
  const d=await r.json();
  const messages=d.messages||[];
  if(!messages.length){return null;}
  return messages[messages.length-1];
}

async function load(){
  if(!room){return;}
  const pw=roomPasswords[room]||'';
  const r=await fetch('chat_api.php?action=list&room='+encodeURIComponent(room)+'&password='+encodeURIComponent(pw));
  if(!r.ok){
    room='';
    sessionStorage.removeItem('chat_room');
    localStorage.removeItem('chat_room');
    document.getElementById('roomLabel').textContent='Kein Raum ausgewählt';
    box.innerHTML='<div class="muted">Dieser Chat wurde gelöscht oder ist nicht mehr verfügbar.</div>';
    await loadRooms();
    return;
  }
  const d=await r.json();
  const messages=d.messages||[];
  box.innerHTML=messages.map(m=>`<div class="m"><div class="meta"><strong>${esc(m.user)}</strong> • ${fmt(m.ts)}</div><div>${esc(m.text)}</div></div>`).join('') || '<div class="muted">Noch keine Nachrichten.</div>';
  box.scrollTop=box.scrollHeight;
  document.getElementById('roomLabel').textContent='Raum: '+room;
  if(messages.length){roomLastTs[room]=messages[messages.length-1].ts||0;persistState();}
  unlockAudio();
  await loadRooms();
}

async function openChat(chosenRoom,isProtected=false){
  if(!username||!chosenRoom){return;}
  if(isProtected){
    const existing=roomPasswords[chosenRoom]||'';
    const entered=existing || prompt('Passwort für Chat '+chosenRoom+' eingeben:') || '';
    if(!entered){return;}
    const check=await fetch('chat_api.php?action=checkRoomAccess&room='+encodeURIComponent(chosenRoom)+'&password='+encodeURIComponent(entered));
    if(!check.ok){alert('Falsches Passwort.');return;}
    roomPasswords[chosenRoom]=entered;
    persistState();
  }
  room=chosenRoom;
  sessionStorage.setItem('chat_room',room);localStorage.setItem('chat_room',room);
  markRoomVisited(room);
  await load();
}

async function pollVisitedRooms(){
  if(!username || !visitedRooms.length){return;}
  for(const roomName of visitedRooms){
    if(roomName===room){continue;}
    const latestMsg=await fetchLatestTs(roomName);
    const latest=(latestMsg&&latestMsg.ts)||0;
    const previous=roomLastTs[roomName]||0;
    if(latest>previous && previous>0){
      roomLastTs[roomName]=latest;
      persistState();
      notify({title:'Neues im Chat '+roomName, body:(latestMsg.user||'Unbekannt')+': '+(latestMsg.text||'')});
    } else if(previous===0 && latest>0){
      roomLastTs[roomName]=latest;
      persistState();
    }
  }
}

function unlockAudio(){
  if(audioUnlocked){return;}
  const audio=document.getElementById('notifAudio');
  if(!audio){return;}
  audio.volume=0;
  const p=audio.play();
  if(p && typeof p.then==='function'){p.then(()=>{audio.pause();audio.currentTime=0;audio.volume=1;audioUnlocked=true;}).catch(()=>{});}
}

document.getElementById('continueBtn').onclick=async()=>{
  const n=document.getElementById('nameInput').value.trim();
  if(!n){return;}
  username=n;
  document.getElementById('userLabel').textContent=username;
  sessionStorage.setItem('chat_username',username);localStorage.setItem('chat_username',username);
  document.getElementById('nameGate').style.display='none';
  if('Notification' in window){
    const p=await Notification.requestPermission();
    notificationsEnabled=(p==='granted');
  }
  await loadRooms();
};

document.getElementById('editNameBtn').onclick=()=>{
  document.getElementById('nameInput').value=username;
  document.getElementById('nameGate').style.display='grid';
};


async function deleteRoom(roomName){
  if(!confirm('Diesen Chat wirklich löschen?')){return;}
  const res=await fetch('chat_api.php?action=deleteRoom&room='+encodeURIComponent(roomName)+'&requester='+encodeURIComponent(username));
  if(!res.ok){alert('Löschen nicht erlaubt. Nur der Ersteller darf löschen.');return;}
  if(room===roomName){room='';document.getElementById('roomLabel').textContent='Kein Raum ausgewählt';box.innerHTML='<div class="muted">Bitte links einen Chat auswählen oder erstellen.</div>';}
  await loadRooms();
}

document.getElementById('createRoomBtn').onclick=async()=>{
  const rname=document.getElementById('newRoomInput').value.trim();
  if(!rname||!username){return;}
  const protect=document.getElementById('protectToggle').checked;
  const newPw=document.getElementById('newRoomPassword').value.trim();
  await fetch('chat_api.php?action=createRoom&room='+encodeURIComponent(rname)+'&protect='+(protect?'1':'0')+'&newPassword='+encodeURIComponent(newPw)+'&creator='+encodeURIComponent(username));
  document.getElementById('newRoomInput').value='';
  await loadRooms();
  openChat(rname);
};

document.getElementById('chatForm').onsubmit=async(e)=>{
  e.preventDefault();
  if(!username||!room){return;}
  const t=document.getElementById('text'); const text=t.value.trim();
  if(!text){return;}
  const pw=roomPasswords[room]||'';
  await fetch('chat_api.php?action=send&room='+encodeURIComponent(room)+'&password='+encodeURIComponent(pw),{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({user:username,text})});
  t.value='';
  load();
};

document.getElementById('emojiToggle').onclick=()=>{
  const p=document.getElementById('emojiPanel');
  p.style.display=(p.style.display==='grid'?'none':'grid');
};

document.addEventListener('click',(e)=>{
  const panel=document.getElementById('emojiPanel');
  const toggle=document.getElementById('emojiToggle');
  if(!panel.contains(e.target) && e.target!==toggle){panel.style.display='none';}
});

setInterval(()=>{if(username&&room){load();}if(username){pollVisitedRooms();}},2500);
document.addEventListener('visibilitychange',()=>{if(username){pollVisitedRooms();}});
(async()=>{
  renderEmojiPanel();
  visitedRooms=JSON.parse(localStorage.getItem('chat_visited_rooms')||sessionStorage.getItem('chat_visited_rooms')||'[]');
  roomLastTs=JSON.parse(localStorage.getItem('chat_room_last_ts')||sessionStorage.getItem('chat_room_last_ts')||'{}');
  roomPasswords=JSON.parse(localStorage.getItem('chat_room_passwords')||sessionStorage.getItem('chat_room_passwords')||'{}');
  const u=localStorage.getItem('chat_username')||sessionStorage.getItem('chat_username')||'';
  const r=localStorage.getItem('chat_room')||sessionStorage.getItem('chat_room')||'';
  if('Notification' in window){notificationsEnabled=(Notification.permission==='granted');}
  if(u){
    username=u;
    document.getElementById('nameInput').value=u;
    document.getElementById('userLabel').textContent=u;
    document.getElementById('nameGate').style.display='none';
    await loadRooms();
    if(r){openChat(r);} 
  }
})();
document.getElementById('protectToggle').addEventListener('change',(e)=>{document.getElementById('passwordWrap').style.display=e.target.checked?'block':'none';if(!e.target.checked){document.getElementById('newRoomPassword').value='';}});


</script>
</body></html>
