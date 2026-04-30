(function(){
  if (window.__chatNotifierInitialized) return;
  window.__chatNotifierInitialized = true;

  const POLL_MS = 3500;
  let audioCtx = null;
  let unlocked = false;

  function unlockAudio(){
    if (unlocked) return;
    try {
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      const o = audioCtx.createOscillator();
      const g = audioCtx.createGain();
      o.connect(g); g.connect(audioCtx.destination);
      g.gain.value = 0;
      o.start(); o.stop(audioCtx.currentTime + 0.01);
      unlocked = true;
    } catch (_) {}
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
    try {
      if (!audioCtx) return;
      const now = audioCtx.currentTime;
      const o = audioCtx.createOscillator();
      const g = audioCtx.createGain();
      o.type = 'sine'; o.frequency.value = 740;
      o.connect(g); g.connect(audioCtx.destination);
      g.gain.setValueAtTime(0.0001, now);
      g.gain.exponentialRampToValueAtTime(0.15, now + 0.02);
      g.gain.exponentialRampToValueAtTime(0.0001, now + 0.30);
      o.start(now); o.stop(now + 0.31);
    } catch (_) {}
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
