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
    .menu{display:flex;gap:44px;font-size:2rem;color:#2c3f63}
    .menu a{text-decoration:none;color:inherit}

    .hero{margin-top:6px;border-radius:0;display:grid;grid-template-columns:1.04fr .96fr;min-height:620px;overflow:hidden;background:linear-gradient(102deg,#fff 0%,#f5f9ff 57%,#dfebff 100%);border-bottom:1px solid #e4ecfb}
    .hero-copy{padding:58px 0 30px 2px}
    .hero h2{font-size:6.6rem;line-height:.95;letter-spacing:-.03em;margin:0 0 18px}
    .hero h2 .accent{color:var(--blue)}
    .hero p{font-size:2rem;line-height:1.45;color:#3c5478;max-width:640px;margin:0 0 28px}
    .actions{display:flex;gap:16px;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;gap:10px;padding:16px 30px;border-radius:16px;text-decoration:none;font-size:1.8rem;font-weight:700;border:2px solid #2f6ff4}
    .btn.primary{background:linear-gradient(180deg,#1f74ff,#155fe7);color:#fff;box-shadow:0 10px 28px rgba(31,111,255,.25)}
    .btn.ghost{background:#fff;color:#2568ef}
    .trust{display:flex;gap:34px;flex-wrap:wrap;margin-top:26px;color:#476188;font-weight:600;font-size:1.4rem}

    .hero-art{position:relative;display:grid;place-items:center}
    .halo{position:absolute;inset:7% 6%;border-radius:50%;background:radial-gradient(circle at 50% 48%,rgba(255,255,255,.82) 0%,rgba(234,244,255,.8) 38%,rgba(208,227,255,.55) 62%,rgba(196,218,251,.28) 78%,rgba(190,214,250,.08) 100%)}
    .server{position:absolute;right:25%;top:14%;width:38%;height:52%;border-radius:30px;background:linear-gradient(180deg,#163d71,#08203f);box-shadow:0 20px 32px rgba(14,42,84,.25)}
    .server::before,.server::after{content:"";position:absolute;left:14%;right:16%;height:12px;background:#e8f1ff;border-radius:10px}
    .server::before{top:18%}.server::after{top:36%}
    .folder{position:absolute;left:16%;top:35%;width:50%;height:36%;border-radius:28px;background:linear-gradient(138deg,#2081ff,#0e5be8);box-shadow:0 26px 34px rgba(19,95,220,.26)}
    .folder::before{content:"";position:absolute;left:6%;top:9%;width:78%;height:18%;border-radius:10px;background:#dcebff}
    .rail{position:absolute;left:14%;right:11%;bottom:18%;height:20px;background:#1a436f;border-radius:999px}
    .dot{position:absolute;bottom:16%;width:62px;height:62px;background:#0b315f;border-radius:18px}
    .dot.d1{left:11%}.dot.d2{left:46%;background:#2380ff}.dot.d3{right:10%}

    .modules{padding:34px 0 56px;display:grid;grid-template-columns:repeat(4,minmax(230px,1fr));gap:18px}
    .module{background:var(--card);border:1px solid #e1eafc;border-radius:24px;padding:24px;display:flex;align-items:center;gap:18px;text-decoration:none;color:inherit;box-shadow:0 12px 28px rgba(13,53,116,.07)}
    .icon{width:88px;height:88px;border-radius:50%;background:#e9f1ff;display:grid;place-items:center;font-size:2.2rem;color:#196cf4}
    .module h3{margin:0 0 6px;font-size:2rem}.module p{margin:0;color:var(--muted);font-size:1.35rem;line-height:1.4}
    @media(max-width:1200px){html{font-size:13px}.hero{grid-template-columns:1fr}.hero-art{min-height:420px}.modules{grid-template-columns:repeat(2,minmax(230px,1fr))}}
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
    <div class="hero-art"><div class="halo"></div><div class="server"></div><div class="folder"></div><div class="rail"></div><div class="dot d1"></div><div class="dot d2"></div><div class="dot d3"></div></div>
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
