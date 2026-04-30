<!doctype html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LocalLoot Dashboard</title>
  <style>
    :root{--blue:#1f6fff;--ink:#0b1b3a;--muted:#5f7193;--bg:#f4f8ff;--card:#fff;--border:#dbe7ff}
    *{box-sizing:border-box} body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:linear-gradient(180deg,#fff,var(--bg));color:var(--ink)}
    .wrap{max-width:1280px;margin:0 auto;padding:18px}
    .nav{display:flex;justify-content:space-between;align-items:center;padding:10px 0 18px;border-bottom:1px solid var(--border)}
    .brand{display:flex;gap:12px;align-items:center}.logo{width:48px;height:48px;border-radius:10px;background:#e8f0ff;display:grid;place-items:center;object-fit:contain;padding:4px}
    .brand h1{margin:0;font-size:2.1rem}.brand p{margin:0;color:var(--muted)}
    .menu{display:flex;gap:20px;color:#1e2e4d;font-weight:600}
    .hero{margin-top:22px;background:#fff;border:1px solid var(--border);border-radius:20px;display:grid;grid-template-columns:1.1fr .9fr;gap:22px;padding:34px;box-shadow:0 12px 40px rgba(31,111,255,.08)}
    .hero h2{font-size:4rem;line-height:1.02;margin:0 0 12px}.hero .accent{color:var(--blue)}.hero p{font-size:1.2rem;color:var(--muted);max-width:56ch}
    .actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:18px}.btn{padding:14px 24px;border-radius:12px;text-decoration:none;font-weight:700;border:1px solid #b8cdff}
    .btn.primary{background:var(--blue);color:#fff}.btn.ghost{background:#fff;color:var(--blue)}
    .hero-art{border-radius:18px;background:radial-gradient(circle at 40% 20%,#d6e6ff,#f7fbff 60%);position:relative;min-height:360px;border:1px solid var(--border)}
    .tile{position:absolute;border-radius:22px;box-shadow:0 20px 35px rgba(26,78,168,.2)}
    .server{right:70px;top:44px;width:230px;height:220px;background:linear-gradient(180deg,#163667,#091d3f)}
    .folder{left:70px;bottom:55px;width:260px;height:180px;background:linear-gradient(180deg,#2080ff,#1661dd)}
    .modules{display:grid;grid-template-columns:repeat(4,minmax(180px,1fr));gap:14px;margin:20px 0}
    .module{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:18px;text-decoration:none;color:inherit}
    .module h3{margin:0 0 8px}.module p{margin:0;color:var(--muted)}
    @media(max-width:980px){.hero{grid-template-columns:1fr}.hero h2{font-size:3rem}.modules{grid-template-columns:repeat(2,minmax(180px,1fr))}}
  </style>
</head>
<body>
  <div class="wrap">
    <header class="nav">
      <div class="brand"><img class="logo" src="../media/LocalLoot_logo.png" alt="LocalLoot Logo"><div><h1>LocalLoot</h1><p>Local file & game sharing for LAN parties</p></div></div>
      <nav class="menu"><span>Features</span><span>Modules</span><span>How It Works</span><span>FAQ</span><span>Docs</span></nav>
    </header>

    <section class="hero">
      <div>
        <h2>Share Games.<br><span class="accent">Share Files.</span><br>Stay Local.</h2>
        <p>Das zentrale Dashboard für deine App-Module. Starte direkt den File Browser oder springe in das File Sharing für Uploads und Downloads.</p>
        <div class="actions">
          <a class="btn primary" href="file-browser.php">Open File Browser</a>
          <a class="btn ghost" href="file-browser.php#uploadWrapper">Open File Sharing</a>
        </div>
      </div>
      <div class="hero-art"><div class="tile server"></div><div class="tile folder"></div></div>
    </section>

    <section class="modules">
      <a class="module" href="file-browser.php"><h3>📁 Files</h3><p>Dateien durchsuchen, öffnen und verwalten.</p></a>
      <a class="module" href="#"><h3>🎮 Game Library</h3><p>Spielekatalog für LAN-Sessions (in Planung).</p></a>
      <a class="module" href="chat.php"><h3>💬 Chat</h3><p>Team-Kommunikation im gemeinsamen Chat-Raum.</p></a>
      <a class="module" href="#"><h3>🖥️ Server Status <small style="color:#1f6fff;">TBA</small></h3><p>Server-Metriken und Health-Checks folgen bald.</p></a>
    </section>
  </div>
<script src="chat_notifier.js"></script>
</body>
</html>
