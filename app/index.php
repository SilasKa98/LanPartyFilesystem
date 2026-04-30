<!doctype html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LocalLoot Dashboard</title>
  <style>
    :root{--blue:#1f6fff;--blue2:#1560ea;--ink:#051736;--muted:#51698d;--bg:#f4f8ff;--card:#fff;--border:#dbe7ff}
    *{box-sizing:border-box}
    body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:linear-gradient(180deg,#fff,#f7faff 42%,#f3f8ff 100%);color:var(--ink)}
    .wrap{max-width:1400px;margin:0 auto;padding:0 24px}
    .nav{height:98px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #e7eefc}
    .brand{display:flex;gap:14px;align-items:center;text-decoration:none;color:inherit}
    .logo{width:58px;height:58px;border-radius:12px;background:#e8f0ff;display:grid;place-items:center;object-fit:contain;padding:6px}
    .brand h1{margin:0;font-size:3rem;line-height:1}
    .brand p{margin:4px 0 0;color:#5b7093;font-size:1.12rem}
    .menu{display:flex;gap:28px;font-size:1.2rem;color:#2c3f63}
    .menu a{text-decoration:none;color:inherit}

    .hero{margin-top:10px;border-radius:22px;display:grid;grid-template-columns:1.08fr .92fr;min-height:420px;overflow:hidden;background:linear-gradient(102deg,#fff 0%,#f5f9ff 57%,#e6f0ff 100%);border:1px solid #e4ecfb}
    .hero-copy{padding:44px 0 34px 6px}
    .hero h2{font-size:4.2rem;line-height:1.02;letter-spacing:-.02em;margin:0 0 12px}
    .hero h2 .accent{color:var(--blue)}
    .hero p{font-size:1.35rem;line-height:1.5;color:#3c5478;max-width:640px;margin:0 0 22px}
    .actions{display:flex;gap:16px;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;gap:10px;padding:13px 22px;border-radius:14px;text-decoration:none;font-size:1.2rem;font-weight:700;border:2px solid #2f6ff4}
    .btn.primary{background:linear-gradient(180deg,#1f74ff,#155fe7);color:#fff;box-shadow:0 10px 28px rgba(31,111,255,.25)}
    .btn.ghost{background:#fff;color:#2568ef}
    .trust{display:flex;gap:18px;flex-wrap:wrap;margin-top:22px;color:#476188;font-weight:600;font-size:1rem}
    .hero-art{display:grid;place-items:center;padding:20px}
    .hero-art img{max-width:67%;height:auto;object-fit:contain;filter:drop-shadow(0 20px 32px rgba(31,111,255,.18));}

    .modules{padding:28px 0 48px;display:grid;grid-template-columns:repeat(4,minmax(220px,1fr));gap:14px}
    .module{background:var(--card);border:1px solid #e1eafc;border-radius:24px;padding:24px;display:flex;align-items:center;gap:18px;text-decoration:none;color:inherit;box-shadow:0 12px 28px rgba(13,53,116,.07)}
    .icon{width:88px;height:88px;min-width:88px;min-height:88px;flex:0 0 88px;border-radius:999px;background:#e9f1ff;display:grid;place-items:center;font-size:2.2rem;color:#196cf4;line-height:1}
    .module h3{margin:0 0 6px;font-size:1.5rem}.module p{margin:0;color:var(--muted);font-size:1.05rem;line-height:1.4}
    @media(max-width:1200px){.hero{grid-template-columns:1fr}.hero-art{min-height:240px}.modules{grid-template-columns:repeat(2,minmax(220px,1fr))}}
  </style>
</head>
<body>
  <div class="wrap">
    <header class="nav">
      <a class="brand" href="index.php"><img class="logo" src="../media/LocalLoot_logo.png" alt="LocalLoot Logo"><div><h1>LocalLoot</h1><p>Local file & game sharing for LAN parties</p></div></a>
      <nav class="menu"><a href="#">Features</a><a href="#">Modules</a><a href="#">How It Works</a><a href="#">Screenshots</a><a href="#">FAQ</a><a href="#">Docs</a></nav>
    </header>
  </div>

  <section class="hero">
    <div class="wrap hero-copy">
      <h2>Share Games.<br><span class="accent">Share Files.</span><br>Stay Local.</h2>
      <p>LocalLoot hilft LAN-Partys mit schnellem File Sharing, Game Management, integriertem Chat und nützlichen Tools in einer schlanken Web-App.</p>
      <div class="actions">
        <a class="btn primary" href="file-browser.php">🌐 Open File Browser</a>
        <a class="btn ghost" href="chat.php">💬 Start Chatting</a>
      </div>
      <div class="trust"><span>🛡️ 100% Local</span><span>⚡ Blazing Fast</span><span>🔒 Private & Secure</span><span>👥 Made for LAN Parties</span></div>
    </div>
    <div class="hero-art"><img src="../media/LocalLoot_logo.png" alt="LocalLoot Hero"></div>
  </section>

  <div class="wrap">
    <section class="modules">
      <a class="module" href="file-browser.php"><div class="icon">📁</div><div><h3>File Sharing</h3><p>Dateien teilen, Uploads und Downloads verwalten.</p></div></a>
      <a class="module" href="game-library.php"><div class="icon">🎮</div><div><h3>Game Library</h3><p>Spiele erkennen, katalogisieren und öffnen.</p></div></a>
      <a class="module" href="chat.php"><div class="icon">💬</div><div><h3>Chat</h3><p>Integrierter Gruppenchat für eure LAN-Runde.</p></div></a>
      <a class="module" href="tools.php"><div class="icon">🧰</div><div><h3>Tools</h3><p>Hilfreiche Utilities für Setup und Session.</p></div></a>
    </section>
  </div>
<script src="chat_notifier.js"></script>
</body>
</html>
