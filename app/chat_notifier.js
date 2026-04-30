(function(){
  if (window.__chatNotifierInitialized) return;
  window.__chatNotifierInitialized = true;

  const POLL_MS = 3500;
  const audio = new Audio('data:audio/wav;base64,UklGRlQAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YTAAAAAAAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA');
  audio.preload = 'auto';
  let unlocked = false;

  function unlockAudio(){
    if (unlocked) return;
    audio.volume = 0;
    const p = audio.play();
    if (p && p.then) p.then(()=>{audio.pause(); audio.currentTime=0; audio.volume=1; unlocked=true;}).catch(()=>{});
  }

  async function fetchLatest(room){
    const res = await fetch('chat_api.php?action=list&room=' + encodeURIComponent(room));
    const data = await res.json();
    const messages = data.messages || [];
    return messages.length ? messages[messages.length - 1] : null;
  }

  function notify(room, msg){
    const text = `${msg.user || 'Unbekannt'}: ${msg.text || ''}`;
    if ('Notification' in window && Notification.permission === 'granted') {
      new Notification(`Neues im Chat ${room}`, { body: text });
    }
    audio.currentTime = 0;
    const p = audio.play();
    if (p && p.catch) p.catch(()=>{});
  }

  async function tick(){
    const username = localStorage.getItem('chat_username') || sessionStorage.getItem('chat_username') || '';
    if (!username) return;
    const rawRooms = localStorage.getItem('chat_visited_rooms') || sessionStorage.getItem('chat_visited_rooms') || '[]';
    const rooms = JSON.parse(rawRooms);
    const rawTs = localStorage.getItem('chat_room_last_ts') || sessionStorage.getItem('chat_room_last_ts') || '{}';
    const lastTs = JSON.parse(rawTs);
    const current = localStorage.getItem('chat_room') || sessionStorage.getItem('chat_room') || '';

    for (const room of rooms) {
      if (!room || room === current) continue;
      try {
        const latest = await fetchLatest(room);
        if (!latest || !latest.ts) continue;
        const prev = lastTs[room] || 0;
        if (latest.ts > prev && prev > 0) notify(room, latest);
        lastTs[room] = latest.ts;
      } catch (_) {}
    }
    localStorage.setItem('chat_room_last_ts', JSON.stringify(lastTs));
    sessionStorage.setItem('chat_room_last_ts', JSON.stringify(lastTs));
  }

  document.addEventListener('click', unlockAudio, { once: true });
  if ('Notification' in window && Notification.permission === 'default') {
    document.addEventListener('click', ()=>Notification.requestPermission().catch(()=>{}), { once: true });
  }
  setInterval(tick, POLL_MS);
  document.addEventListener('visibilitychange', ()=>{ if (document.visibilityState === 'visible') tick(); });
})();
