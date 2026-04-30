<!doctype html>
<html lang="de">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LocalLoot Chat</title>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:#f4f8ff;color:#0b1b3a}
    .wrap{max-width:1200px;margin:0 auto;padding:18px}
    .top{display:flex;justify-content:space-between;align-items:center;background:#fff;border:1px solid #dbe7ff;border-radius:14px;padding:12px 14px;margin-bottom:14px}
    .brand{display:flex;gap:10px;align-items:center;text-decoration:none;color:inherit}
    .brand img{width:42px;height:42px;object-fit:contain;border-radius:10px;background:#e8f0ff;padding:5px}
    .layout{display:grid;grid-template-columns:320px 1fr;gap:14px;min-height:70vh}
    .panel{background:#fff;border:1px solid #dbe7ff;border-radius:14px;padding:12px}
    .muted{font-size:.88rem;color:#5f7193}
    .row{display:flex;gap:8px}
    input,button{padding:11px 12px;border-radius:10px;border:1px solid #cfdfff}
    button{background:#1f6fff;color:#fff;border-color:#1f6fff;cursor:pointer}
    button.secondary{background:#fff;color:#1f6fff}
    .rooms{margin-top:12px;display:flex;flex-direction:column;gap:8px;max-height:58vh;overflow:auto}
    .roomBtn{width:100%;text-align:left;background:#f8fbff;color:#0b1b3a;border:1px solid #dbe7ff}
    .roomBtn.active{border-color:#1f6fff;background:#eaf2ff}
    #chatBox{height:56vh;overflow:auto;background:#fdfefe;border:1px solid #dbe7ff;border-radius:12px;padding:10px}
    .m{padding:8px 10px;border-bottom:1px solid #eef3ff}
    .meta{font-size:.78rem;color:#5f7193}
    #chatForm{display:flex;gap:8px;margin-top:10px;align-items:center}
    #text{flex:1}
    .emojiWrap{position:relative}
    #emojiPanel{position:absolute;bottom:46px;right:0;background:#fff;border:1px solid #dbe7ff;border-radius:10px;padding:8px;display:none;grid-template-columns:repeat(8,1fr);gap:6px;width:280px;box-shadow:0 8px 25px rgba(0,0,0,.12)}
    .emoji{cursor:pointer;background:#fff;border:1px solid #eef3ff;border-radius:8px;padding:6px;text-align:center}
    #nameGate{position:fixed;inset:0;background:rgba(0,0,0,.45);display:grid;place-items:center}
    .gateCard{background:#fff;padding:20px;border-radius:14px;width:min(460px,94vw)}
    @media (max-width:900px){.layout{grid-template-columns:1fr}}
  </style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <a class="brand" href="index.php"><img src="../media/LocalLoot_logo.png" alt="LocalLoot Logo"><div><strong>LocalLoot Chat</strong><div id="roomLabel" class="muted">Kein Raum ausgewählt</div></div></a>
    <a href="file-browser.php">📁 Dateien</a>
  </div>

  <div class="layout">
    <aside class="panel">
      <h3 style="margin:4px 0 8px">Chats</h3>
      <div class="row" style="align-items:center;justify-content:space-between;margin-bottom:8px"><p class="muted" style="margin:0">Hallo <strong id="userLabel">-</strong></p><button id="editNameBtn" type="button" class="secondary">Name ändern</button></div>
      <div class="row"><input id="newRoomInput" maxlength="60" placeholder="Neuen Chat-Namen" style="flex:1"><button id="createRoomBtn" type="button">Starten</button></div>
      <div id="rooms" class="rooms"></div>
    </aside>

    <section class="panel">
      <div id="chatBox"><div class="muted">Bitte links einen Chat auswählen oder erstellen.</div></div>
      <form id="chatForm">
        <input id="text" maxlength="600" placeholder="Nachricht schreiben..." required>
        <div class="emojiWrap">
          <button id="emojiToggle" type="button" class="secondary">😊</button>
          <div id="emojiPanel"></div>
        </div>
        <button type="submit">Senden</button>
      </form>
    </section>
  </div>
</div>

<div id="nameGate"><div class="gateCard"><h3>Willkommen im Chat</h3><p class="muted">Bitte gib deinen Namen ein, um fortzufahren.</p><div class="row"><input id="nameInput" maxlength="40" placeholder="Dein Name" style="flex:1"><button id="continueBtn" type="button">Weiter</button></div></div></div>

<script>
const box=document.getElementById('chatBox');
const roomsEl=document.getElementById('rooms');
const emojiList=['😀','😁','😂','🤣','😊','😍','🥳','😎','🤝','👍','👏','🔥','💡','✅','🎉','🚀','🙌','😅','🤔','😇','😴','😭','😡','❤️','💙','💚','🧡','💬','📁','🛠️','🌟','🍀'];
let username=''; let room='';
let visitedRooms=[];
let roomLastTs={};
let notificationsEnabled=false;

function fmt(ts){return new Date(ts*1000).toLocaleString('de-DE');}
function esc(s){return s.replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));}
function renderEmojiPanel(){const p=document.getElementById('emojiPanel');p.innerHTML='';emojiList.forEach(e=>{const b=document.createElement('button');b.type='button';b.className='emoji';b.textContent=e;b.onclick=()=>{const t=document.getElementById('text');t.value+=e;t.focus();};p.appendChild(b);});}
function persistState(){sessionStorage.setItem('chat_visited_rooms',JSON.stringify(visitedRooms));sessionStorage.setItem('chat_room_last_ts',JSON.stringify(roomLastTs));}
function markRoomVisited(name){if(!visitedRooms.includes(name)){visitedRooms.push(name);}persistState();}
function playNotificationTone(){try{const ctx=new (window.AudioContext||window.webkitAudioContext)();const osc=ctx.createOscillator();const gain=ctx.createGain();osc.connect(gain);gain.connect(ctx.destination);osc.type='sine';osc.frequency.value=880;gain.gain.setValueAtTime(0.0001,ctx.currentTime);gain.gain.exponentialRampToValueAtTime(0.2,ctx.currentTime+0.01);gain.gain.exponentialRampToValueAtTime(0.0001,ctx.currentTime+0.25);osc.start();osc.stop(ctx.currentTime+0.26);}catch(e){}}
function notify(message){if(notificationsEnabled && Notification.permission==='granted'){new Notification(message);}playNotificationTone();}

async function loadRooms(){
  const r=await fetch('chat_api.php?action=listRooms');
  const d=await r.json();
  const rooms=d.rooms||[];
  roomsEl.innerHTML='';
  if(!rooms.length){roomsEl.innerHTML='<div class="muted">Noch keine Chats vorhanden.</div>';return;}
  rooms.forEach(name=>{const b=document.createElement('button');b.type='button';b.className='roomBtn'+(name===room?' active':'');b.textContent=name;b.onclick=()=>openChat(name);roomsEl.appendChild(b);});
}

async function fetchLatestTs(roomName){
  const r=await fetch('chat_api.php?action=list&room='+encodeURIComponent(roomName));
  const d=await r.json();
  const messages=d.messages||[];
  if(!messages.length){return 0;}
  return messages[messages.length-1].ts||0;
}

async function load(){
  if(!room){return;}
  const r=await fetch('chat_api.php?action=list&room='+encodeURIComponent(room));
  const d=await r.json();
  const messages=d.messages||[];
  box.innerHTML=messages.map(m=>`<div class="m"><div class="meta"><strong>${esc(m.user)}</strong> • ${fmt(m.ts)}</div><div>${esc(m.text)}</div></div>`).join('') || '<div class="muted">Noch keine Nachrichten.</div>';
  box.scrollTop=box.scrollHeight;
  document.getElementById('roomLabel').textContent='Raum: '+room;
  if(messages.length){roomLastTs[room]=messages[messages.length-1].ts||0;persistState();}
  await loadRooms();
}

async function openChat(chosenRoom){
  if(!username||!chosenRoom){return;}
  room=chosenRoom;
  sessionStorage.setItem('chat_room',room);
  markRoomVisited(room);
  await load();
}

async function pollVisitedRooms(){
  if(!username || !visitedRooms.length){return;}
  for(const roomName of visitedRooms){
    if(roomName===room){continue;}
    const latest=await fetchLatestTs(roomName);
    const previous=roomLastTs[roomName]||0;
    if(latest>previous && previous>0){
      roomLastTs[roomName]=latest;
      persistState();
      notify('Neue Nachricht in Chat: '+roomName);
    } else if(previous===0 && latest>0){
      roomLastTs[roomName]=latest;
      persistState();
    }
  }
}

document.getElementById('continueBtn').onclick=async()=>{
  const n=document.getElementById('nameInput').value.trim();
  if(!n){return;}
  username=n;
  document.getElementById('userLabel').textContent=username;
  sessionStorage.setItem('chat_username',username);
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

document.getElementById('createRoomBtn').onclick=async()=>{
  const rname=document.getElementById('newRoomInput').value.trim();
  if(!rname||!username){return;}
  await fetch('chat_api.php?action=createRoom&room='+encodeURIComponent(rname));
  document.getElementById('newRoomInput').value='';
  await loadRooms();
  openChat(rname);
};

document.getElementById('chatForm').onsubmit=async(e)=>{
  e.preventDefault();
  if(!username||!room){return;}
  const t=document.getElementById('text'); const text=t.value.trim();
  if(!text){return;}
  await fetch('chat_api.php?action=send&room='+encodeURIComponent(room),{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({user:username,text})});
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

setInterval(()=>{if(username&&room){load();}if(username){pollVisitedRooms();}},3000);
(async()=>{
  renderEmojiPanel();
  visitedRooms=JSON.parse(sessionStorage.getItem('chat_visited_rooms')||'[]');
  roomLastTs=JSON.parse(sessionStorage.getItem('chat_room_last_ts')||'{}');
  const u=sessionStorage.getItem('chat_username')||'';
  const r=sessionStorage.getItem('chat_room')||'';
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
</script>
</body></html>
