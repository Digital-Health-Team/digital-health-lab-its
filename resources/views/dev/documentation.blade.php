<!DOCTYPE html>
<html lang="en" data-theme="dark" data-lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>(function(){var t=localStorage.getItem('idig-docs-theme')||'dark';var l=localStorage.getItem('idig-docs-lang')||'en';document.documentElement.dataset.theme=t;document.documentElement.dataset.lang=l;document.documentElement.lang=l;})()</script>
<title>Developer Documentation — IDIG Health Tech</title>
<meta name="description" content="Internal developer documentation for the IDIG Health Tech platform — architecture, patterns, conventions, and workflows.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{
  --p950:#031026; --p900:#062E5C; --p800:#0A3D7A; --p700:#00426D; --p600:#0D5A9E;
  --cyan:#22D3EE; --cyan2:#00A8B5; --cyan-soft:#67E8F9; --gold:#FFC72C;
  --light:#F5F8FC; --card:#FFFFFF; --slate800:#1E293B; --slate600:#475569; --slate400:#94A3B8;
  --on-dark:#E8EEF6; --muted-dark:#8DA3C0;
  --line-dark:rgba(103,232,249,.14); --line-light:#E2E9F2;
  --disp:'Plus Jakarta Sans',sans-serif; --body:'Inter',sans-serif; --mono:'JetBrains Mono',monospace;
  --maxw:1180px;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--body);background:var(--p950);color:var(--on-dark);line-height:1.65;-webkit-font-smoothing:antialiased;overflow-x:hidden}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}
h1,h2,h3,h4{font-family:var(--disp);line-height:1.15;font-weight:700}
code{font-family:var(--mono);font-size:.87em;background:rgba(34,211,238,.08);border:1px solid rgba(34,211,238,.15);padding:2px 6px;border-radius:5px;color:var(--cyan-soft)}
section.light code{background:rgba(0,168,181,.08);border-color:rgba(0,168,181,.2);color:var(--p600)}
pre{background:rgba(255,255,255,.04);border:1px solid var(--line-dark);border-radius:12px;padding:20px 22px;overflow-x:auto;margin-top:14px}
pre code{background:none;border:none;padding:0;color:var(--cyan-soft);font-size:13px;line-height:1.7}
::selection{background:var(--cyan);color:var(--p950)}

/* ---------- honeycomb ---------- */
.honey{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='100' viewBox='0 0 56 100'%3E%3Cpath d='M28 66L0 50V16l28-16 28 16v34zM28 0L0 16l28 16 28-16z' fill='%2322D3EE' fill-opacity='0.05'/%3E%3C/svg%3E");background-size:56px 100px}

/* ---------- layout shell ---------- */
.shell{display:grid;grid-template-columns:270px 1fr;max-width:1440px;margin:0 auto}
.sidebar{position:sticky;top:0;height:100vh;background:linear-gradient(180deg,#02101f,#031026);border-right:1px solid var(--line-dark);padding:28px 20px;overflow-y:auto;z-index:40}
.brand{display:flex;align-items:center;gap:11px;padding-bottom:20px;margin-bottom:14px;border-bottom:1px solid var(--line-dark)}
.brand .mark{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--p700),var(--cyan2));display:grid;place-items:center;flex:0 0 auto;box-shadow:0 0 22px rgba(34,211,238,.35)}
.brand .mark svg{width:22px;height:22px}
.brand b{font-family:var(--disp);font-weight:800;font-size:16px;letter-spacing:-.02em;display:block;color:#fff}
.brand span{font-size:11px;color:var(--muted-dark);letter-spacing:.06em;text-transform:uppercase}
.toc a{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:9px;font-size:13.5px;color:var(--muted-dark);font-weight:500;transition:.18s;margin-bottom:2px}
.toc a .n{font-family:var(--mono);font-size:11px;opacity:.5}
.toc a:hover{background:rgba(34,211,238,.07);color:#fff}
.toc a.active{background:linear-gradient(90deg,rgba(0,66,109,.55),rgba(10,61,122,.25));color:#fff}
.toc a.active .n{color:var(--cyan);opacity:1}
.sb-foot{margin-top:20px;padding-top:16px;border-top:1px solid var(--line-dark);font-size:11.5px;color:var(--slate400);line-height:1.7}

.main{min-width:0}
.wrap{max-width:var(--maxw);margin:0 auto;padding:0 40px}
section{padding:72px 0;border-bottom:1px solid var(--line-dark)}
section.light{background:var(--light);color:var(--slate800)}
section.light h2,section.light h3{color:var(--p700)}
section.light .lead{color:var(--slate600)}

/* ---------- hero ---------- */
.hero{position:relative;min-height:72vh;display:flex;align-items:center;overflow:hidden;background:radial-gradient(1200px 600px at 78% 8%,rgba(13,90,158,.5),transparent 60%),radial-gradient(900px 500px at 10% 100%,rgba(0,168,181,.22),transparent 60%),linear-gradient(160deg,#041428,#031026 55%,#02101f)}
.hero .honey{position:absolute;inset:0;opacity:.6;pointer-events:none}
.ecg{position:absolute;left:0;right:0;top:52%;width:100%;height:180px;opacity:.5;pointer-events:none}
.ecg path{stroke:url(#eg);stroke-width:2.2;fill:none;stroke-linecap:round;stroke-dasharray:2400;stroke-dashoffset:2400;animation:draw 5s ease-in-out infinite}
@keyframes draw{0%{stroke-dashoffset:2400}45%,100%{stroke-dashoffset:0}}
.hero-inner{position:relative;z-index:2;max-width:var(--maxw);margin:0 auto;padding:0 40px;width:100%}
.eyebrow{display:inline-flex;align-items:center;gap:9px;font-family:var(--mono);font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--cyan-soft);background:rgba(34,211,238,.08);border:1px solid rgba(34,211,238,.25);padding:7px 14px;border-radius:100px;margin-bottom:26px}
.eyebrow .dot{width:7px;height:7px;border-radius:50%;background:var(--cyan);box-shadow:0 0 10px var(--cyan);animation:pulse 2s infinite}
@keyframes pulse{50%{opacity:.35}}
.hero h1{font-size:clamp(40px,7vw,86px);font-weight:800;letter-spacing:-.03em;line-height:1.02}
.hero h1 .it{font-style:italic;color:transparent;background:linear-gradient(120deg,#fff 20%,var(--cyan-soft) 60%,var(--cyan));-webkit-background-clip:text;background-clip:text;text-shadow:0 0 60px rgba(34,211,238,.25)}
.hero .sub{font-size:clamp(17px,2vw,21px);color:var(--muted-dark);max-width:640px;margin:26px 0 34px;line-height:1.6}
.hero .sub b{color:var(--on-dark);font-weight:600}
.chips{display:flex;flex-wrap:wrap;gap:10px}
.chip{font-size:12.5px;font-family:var(--mono);color:var(--cyan-soft);border:1px solid rgba(103,232,249,.22);background:rgba(3,16,38,.5);padding:7px 13px;border-radius:8px;backdrop-filter:blur(6px)}
.chip b{color:#fff;font-weight:500}

/* ---------- section headers ---------- */
.sec-head{margin-bottom:44px;max-width:760px}
.kicker{font-family:var(--mono);font-size:12.5px;letter-spacing:.14em;text-transform:uppercase;color:var(--cyan2);font-weight:500;display:flex;align-items:center;gap:12px;margin-bottom:16px}
.kicker::before{content:"";width:30px;height:1px;background:var(--cyan2)}
section.light .kicker{color:var(--p600)}
h2{font-size:clamp(28px,4vw,44px);letter-spacing:-.02em;font-weight:700}
.lead{font-size:17px;color:var(--muted-dark);margin-top:18px;line-height:1.7}

/* ---------- prose / cards ---------- */
.grid{display:grid;gap:20px}
.g2{grid-template-columns:repeat(2,1fr)}
.g3{grid-template-columns:repeat(3,1fr)}
.g4{grid-template-columns:repeat(4,1fr)}
.card{background:rgba(255,255,255,.03);border:1px solid var(--line-dark);border-radius:18px;padding:26px;transition:.22s}
.card:hover{border-color:rgba(34,211,238,.4);transform:translateY(-3px);box-shadow:0 18px 50px -20px rgba(34,211,238,.25)}
section.light .card{background:#fff;border:1px solid var(--line-light);box-shadow:0 8px 30px -18px rgba(3,16,38,.25)}
section.light .card:hover{border-color:var(--cyan2);box-shadow:0 18px 46px -20px rgba(0,66,109,.3)}
.card .ic{width:44px;height:44px;border-radius:12px;display:grid;place-items:center;background:linear-gradient(135deg,rgba(0,66,109,.6),rgba(0,168,181,.35));margin-bottom:16px;color:var(--cyan-soft)}
section.light .card .ic{background:linear-gradient(135deg,#EAF6FA,#D7F0F3);color:var(--p700)}
.card .ic svg{width:22px;height:22px}
.card h3{font-size:18px;margin-bottom:8px;font-weight:700}
.card h4{font-size:15.5px}
.card p{font-size:14.5px;color:var(--muted-dark);line-height:1.65}
section.light .card p{color:var(--slate600)}
.card .tag{font-family:var(--mono);font-size:11px;color:var(--cyan2);text-transform:uppercase;letter-spacing:.08em}
section.light .card .tag{color:var(--p600)}

/* role badge */
.role-badge{display:inline-block;font-family:var(--mono);font-size:11px;font-weight:500;padding:4px 10px;border-radius:6px;margin-bottom:14px;text-transform:uppercase;letter-spacing:.06em}
.rb-a{background:rgba(148,163,184,.14);color:#cbd5e1;border:1px solid rgba(148,163,184,.3)}
.rb-b{background:rgba(34,211,238,.12);color:var(--cyan-soft);border:1px solid rgba(34,211,238,.3)}
.rb-c{background:rgba(255,199,44,.12);color:var(--gold);border:1px solid rgba(255,199,44,.3)}
.rb-d{background:rgba(13,90,158,.25);color:#93c5fd;border:1px solid rgba(59,130,246,.35)}
.rb-e{background:rgba(34,197,94,.12);color:#86efac;border:1px solid rgba(34,197,94,.3)}

/* workflow */
.flow{position:relative;padding-left:34px}
.flow::before{content:"";position:absolute;left:11px;top:8px;bottom:8px;width:2px;background:linear-gradient(180deg,var(--cyan2),transparent)}
.step{position:relative;margin-bottom:14px}
.step::before{content:"";position:absolute;left:-28px;top:6px;width:12px;height:12px;border-radius:50%;background:var(--p950);border:2px solid var(--cyan2);box-shadow:0 0 0 4px rgba(0,168,181,.12)}
section.light .step::before{background:#fff}
.step h4{font-size:15px;margin-bottom:3px}
.step p{font-size:14px;color:var(--muted-dark)}
section.light .step p{color:var(--slate600)}
.flow-card{background:rgba(255,255,255,.03);border:1px solid var(--line-dark);border-radius:18px;padding:28px 30px}
section.light .flow-card{background:#fff;border:1px solid var(--line-light)}
.flow-card>h3{display:flex;align-items:center;gap:12px;font-size:19px;margin-bottom:22px}
section.light .flow-card>h3{color:var(--p700)}
.flow-num{font-family:var(--mono);font-size:13px;color:var(--p950);background:var(--cyan);width:30px;height:30px;border-radius:9px;display:grid;place-items:center;font-weight:700;flex:0 0 auto}

/* tech stack */
.stack-row{display:flex;flex-wrap:wrap;gap:10px;margin-top:10px;margin-bottom:10px}
.pill{font-size:13px;padding:8px 15px;border-radius:100px;background:rgba(255,255,255,.04);border:1px solid var(--line-dark);color:var(--on-dark);font-weight:500}
section.light .pill{background:#fff;border-color:var(--line-light);color:var(--slate800)}
.pill b{color:var(--cyan-soft);font-family:var(--mono);font-size:11px;font-weight:500;display:block;text-transform:uppercase;letter-spacing:.05em;margin-bottom:1px}
section.light .pill b{color:var(--p600)}
.stack-block h4{font-size:14px;color:var(--cyan2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;margin:22px 0 4px;font-weight:500}
section.light .stack-block h4{color:var(--p600)}

/* db schema */
.db-group{margin-bottom:8px}
.db-group>h3{font-size:16px;color:var(--cyan2);font-family:var(--mono);letter-spacing:.04em;margin:26px 0 14px;display:flex;align-items:center;gap:10px}
.db-group>h3 .idx{background:var(--p700);color:#fff;font-size:12px;width:26px;height:26px;border-radius:7px;display:grid;place-items:center}
.tbl{display:grid;grid-template-columns:repeat(auto-fill,minmax(215px,1fr));gap:12px}
.tcard{background:rgba(255,255,255,.03);border:1px solid var(--line-dark);border-radius:12px;padding:15px 17px}
section.light .tcard{background:#fff;border-color:var(--line-light)}
.tcard .tn{font-family:var(--mono);font-size:13.5px;color:#fff;font-weight:500;margin-bottom:5px}
section.light .tcard .tn{color:var(--p700)}
.tcard .td{font-size:12.5px;color:var(--muted-dark);line-height:1.5}
section.light .tcard .td{color:var(--slate600)}
.tcard.key{border-color:rgba(255,199,44,.3)}
.tcard.key .tn{color:var(--gold)}

/* callout */
.callout{border-left:3px solid var(--cyan2);background:rgba(0,168,181,.06);padding:16px 20px;border-radius:0 12px 12px 0;font-size:14.5px;color:var(--on-dark);margin-top:22px}
section.light .callout{background:rgba(0,168,181,.08);color:var(--slate800)}
.callout b{color:var(--cyan2)}
.callout.warn{border-color:var(--gold);background:rgba(255,199,44,.06)}
.callout.warn b{color:var(--gold)}

/* rule cards */
.rule-num{font-family:var(--mono);font-size:11px;color:var(--p950);background:var(--cyan);padding:2px 8px;border-radius:5px;font-weight:700;display:inline-block;margin-bottom:10px}

/* footer */
.foot{background:#02101f;padding:56px 0 40px;text-align:center;border-bottom:none}
.foot .wrap{max-width:680px}
.foot h3{font-size:22px;margin-bottom:12px}
.foot p{color:var(--muted-dark);font-size:14.5px}
.foot .meta{margin-top:26px;padding-top:22px;border-top:1px solid var(--line-dark);font-size:12.5px;color:var(--slate400);font-family:var(--mono)}

.mobile-nav{display:none}
@media(max-width:1000px){
  .shell{grid-template-columns:1fr}
  .sidebar{display:none}
  .g3,.g4{grid-template-columns:1fr 1fr}
  .g2{grid-template-columns:1fr}
  .wrap{padding:0 22px}
  .hero-inner{padding:0 22px}
  .mobile-nav{display:flex;position:sticky;top:0;z-index:50;background:rgba(3,16,38,.92);backdrop-filter:blur(10px);border-bottom:1px solid var(--line-dark);padding:12px 22px;align-items:center;gap:10px}
  .mobile-nav b{font-family:var(--disp);font-weight:800;color:#fff}
}
@media(max-width:600px){.g3,.g4,.tbl{grid-template-columns:1fr}}

/* ---- language ---- */
.lang-id{display:none}
[data-lang="id"] .lang-en{display:none}
[data-lang="id"] .lang-id{display:inline}

/* ---- controls ---- */
.sb-controls{display:flex;flex-direction:column;gap:8px;margin-top:16px}
.ctrl-btn{display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:9px;font-size:13px;border:1px solid var(--line-dark);background:rgba(255,255,255,.04);color:var(--muted-dark);cursor:pointer;font-family:var(--body);font-weight:500;transition:.18s;width:100%;text-align:left}
.ctrl-btn:hover{background:rgba(34,211,238,.07);color:#fff;border-color:rgba(34,211,238,.3)}
.mob-ctrl{display:none;gap:6px;margin-left:auto}
.mob-ctrl .ctrl-btn{width:auto;padding:6px 10px;font-size:12px}
@media(max-width:1000px){.mob-ctrl{display:flex}}

/* ---- light mode ---- */
[data-theme="light"] body{background:#F1F5F9;color:#1E293B}
[data-theme="light"] .sidebar{background:#fff;border-right-color:#E2E9F2}
[data-theme="light"] .brand b{color:#00426D}
[data-theme="light"] .brand span{color:#64748B}
[data-theme="light"] .sb-foot{color:#94A3B8;border-top-color:#E2E9F2}
[data-theme="light"] .toc a{color:#64748B}
[data-theme="light"] .toc a:hover{background:rgba(0,168,181,.08);color:#00426D}
[data-theme="light"] .toc a.active{background:rgba(0,66,109,.08);color:#00426D}
[data-theme="light"] .toc a.active .n{color:#00A8B5;opacity:1}
[data-theme="light"] .ctrl-btn{border-color:#E2E9F2;background:#F8FAFC;color:#475569}
[data-theme="light"] .ctrl-btn:hover{background:rgba(0,168,181,.08);color:#00426D}
[data-theme="light"] section{border-bottom-color:#E2E9F2}
[data-theme="light"] section:not(.light){background:#fff;color:#1E293B}
[data-theme="light"] section:not(.light) h2,[data-theme="light"] section:not(.light) h3{color:#00426D}
[data-theme="light"] section:not(.light) .kicker{color:#0D5A9E}
[data-theme="light"] section:not(.light) .lead{color:#475569}
[data-theme="light"] section:not(.light) .card{background:#fff;border-color:#E2E9F2;box-shadow:0 8px 30px -18px rgba(3,16,38,.15)}
[data-theme="light"] section:not(.light) .card p{color:#475569}
[data-theme="light"] section:not(.light) .card .ic{background:linear-gradient(135deg,#EAF6FA,#D7F0F3);color:#00426D}
[data-theme="light"] section:not(.light) .card .tag{color:#0D5A9E}
[data-theme="light"] section:not(.light) .tcard{background:#F8FAFC;border-color:#E2E9F2}
[data-theme="light"] section:not(.light) .tcard .tn{color:#00426D}
[data-theme="light"] section:not(.light) .tcard .td{color:#475569}
[data-theme="light"] section:not(.light) .tcard.key{border-color:rgba(0,66,109,.3)}
[data-theme="light"] section:not(.light) .tcard.key .tn{color:#00426D}
[data-theme="light"] section:not(.light) .flow-card{background:#F8FAFC;border-color:#E2E9F2}
[data-theme="light"] section:not(.light) .flow-card>h3{color:#00426D}
[data-theme="light"] section:not(.light) .step p{color:#475569}
[data-theme="light"] section:not(.light) .callout{background:rgba(0,168,181,.07);color:#1E293B}
[data-theme="light"] section:not(.light) .stack-block h4{color:#0D5A9E}
[data-theme="light"] section:not(.light) .db-group>h3{color:#0D5A9E}
[data-theme="light"] section:not(.light) .db-group>h3 .idx{background:#0D5A9E}
[data-theme="light"] section:not(.light) .pill{background:#F8FAFC;border-color:#E2E9F2;color:#1E293B}
[data-theme="light"] section:not(.light) .pill b{color:#0D5A9E}
[data-theme="light"] code{background:rgba(0,168,181,.08);border-color:rgba(0,168,181,.2);color:#0D5A9E}
[data-theme="light"] pre{background:#F8FAFC;border-color:#E2E9F2}
[data-theme="light"] pre code{color:#0D5A9E;background:none;border:none}
[data-theme="light"] .mobile-nav{background:rgba(255,255,255,.95);border-bottom-color:#E2E9F2}
[data-theme="light"] .mobile-nav b{color:#00426D}
[data-theme="light"] .foot{background:#E2E9F2}
</style>
</head>
<body>

<div class="mobile-nav">
  <div class="brand" style="border:none;padding:0;margin:0">
    <div class="mark"><svg viewBox="0 0 24 24" fill="none"><path d="M12 2 3 7v10l9 5 9-5V7z" stroke="#fff" stroke-width="1.6"/><path d="M7 12h2l1.5-3 2 6 1.5-3H17" stroke="#22D3EE" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
    <b>iDIG Dev Docs</b>
  </div>
  <div class="mob-ctrl">
    <button onclick="toggleTheme()" class="ctrl-btn" id="mob-theme-btn" title="Toggle theme">☀️</button>
    <button onclick="toggleLang()" class="ctrl-btn" id="mob-lang-btn">ID</button>
  </div>
</div>

<div class="shell">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="brand">
      <div class="mark"><svg viewBox="0 0 24 24" fill="none"><path d="M12 2 3 7v10l9 5 9-5V7z" stroke="#fff" stroke-width="1.6"/><path d="M7 12h2l1.5-3 2 6 1.5-3H17" stroke="#22D3EE" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
      <div><b>iDIG Health Tech</b><span>Dev Docs · Internal</span></div>
    </div>
    <nav class="toc">
      <a href="#overview"><span class="n">01</span> <span class="lang-en">Project Overview</span><span class="lang-id">Gambaran Proyek</span></a>
      <a href="#setup"><span class="n">02</span> <span class="lang-en">Local Setup &amp; Commands</span><span class="lang-id">Setup &amp; Perintah</span></a>
      <a href="#architecture"><span class="n">03</span> <span class="lang-en">Architecture</span><span class="lang-id">Arsitektur</span></a>
      <a href="#rules"><span class="n">04</span> <span class="lang-en">Critical Rules</span><span class="lang-id">Aturan Kritis</span></a>
      <a href="#rbac"><span class="n">05</span> RBAC &amp; Routing</a>
      <a href="#database"><span class="n">06</span> <span class="lang-en">Database Schema</span><span class="lang-id">Skema Database</span></a>
      <a href="#stack"><span class="n">07</span> Tech Stack</a>
      <a href="#testing"><span class="n">08</span> <span class="lang-en">Testing Guide</span><span class="lang-id">Panduan Pengujian</span></a>
      <a href="#frontend"><span class="n">09</span> <span class="lang-en">Frontend Patterns</span><span class="lang-id">Pola Frontend</span></a>
      <a href="#workflows"><span class="n">10</span> <span class="lang-en">Core Workflows</span><span class="lang-id">Alur Kerja Inti</span></a>
      <a href="#map"><span class="n">11</span> <span class="lang-en">Directory Map</span><span class="lang-id">Peta Direktori</span></a>
    </nav>
    <div class="sb-controls">
      <button onclick="toggleTheme()" class="ctrl-btn" id="theme-btn">
        <span id="theme-icon">☀️</span>
        <span class="lang-en" id="theme-label-en">Light Mode</span><span class="lang-id" id="theme-label-id">Mode Terang</span>
      </button>
      <button onclick="toggleLang()" class="ctrl-btn">
        🌐 <span class="lang-en">Indonesia</span><span class="lang-id">English</span>
      </button>
    </div>
    <div class="sb-foot">
      <span class="lang-en">Internal Developer Docs</span><span class="lang-id">Dokumentasi Internal Dev</span><br>
      Digital Research Lab<br>
      FKK — ITS
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main">

    <!-- HERO -->
    <header class="hero" id="top">
      <div class="honey"></div>
      <svg class="ecg" viewBox="0 0 1600 180" preserveAspectRatio="none">
        <defs><linearGradient id="eg" x1="0" x2="1"><stop offset="0" stop-color="#22D3EE" stop-opacity="0"/><stop offset=".5" stop-color="#22D3EE"/><stop offset="1" stop-color="#22D3EE" stop-opacity="0"/></linearGradient></defs>
        <path d="M0,90 L360,90 L390,90 L410,40 L435,140 L460,55 L485,90 L820,90 L850,90 L870,30 L895,150 L920,50 L945,90 L1600,90"/>
      </svg>
      <div class="hero-inner">
        <div class="eyebrow"><span class="dot"></span> DEV DOCS · Internal · IDIG Health Tech</div>
        <h1><span class="lang-en">Developer<br><span class="it">Documentation</span></span><span class="lang-id">Dokumentasi<br><span class="it">Developer</span></span></h1>
        <p class="sub"><span class="lang-en">Everything a new engineer needs to <b>understand</b>, <b>run</b>, and <b>contribute</b> to the IDIG platform — architecture, conventions, patterns, and workflows.</span><span class="lang-id">Semua yang dibutuhkan engineer baru untuk <b>memahami</b>, <b>menjalankan</b>, dan <b>berkontribusi</b> ke platform IDIG — arsitektur, konvensi, pola, dan alur kerja.</span></p>
        <div class="chips">
          <div class="chip"><b>Backend</b> Laravel 12</div>
          <div class="chip"><b>React</b> 19.2.5 + TypeScript 6</div>
          <div class="chip"><b>Admin</b> Livewire 4.1</div>
          <div class="chip"><b>Build</b> Vite 7 · Pest 4</div>
          <div class="chip"><b>DB</b> MySQL · 37 models · 6 domains</div>
        </div>
      </div>
    </header>

    <!-- 01 PROJECT OVERVIEW -->
    <section id="overview" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">01 — Project Overview</span><span class="lang-id">01 — Gambaran Proyek</span></div>
          <h2><span class="lang-en">A dual-purpose medical-technology platform in a single Laravel monolith</span><span class="lang-id">Platform teknologi medis serbaguna dalam satu Laravel monolith</span></h2>
          <p class="lead"><span class="lang-en">IDIG serves three simultaneous roles. Understanding all three is essential for making correct scope and architecture decisions.</span><span class="lang-id">IDIG melayani tiga peran sekaligus. Memahami ketiganya penting untuk membuat keputusan arsitektur yang tepat.</span></p>
        </div>
        <div class="grid g3">
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><path d="M4 4h16v14H4z" stroke="currentColor" stroke-width="1.6"/><path d="M4 9h16M9 4v14" stroke="currentColor" stroke-width="1.6"/></svg></div>
            <h3><span class="lang-en">Digital Repository</span><span class="lang-id">Repositori Digital</span></h3>
            <p><span class="lang-en">Public archive of student innovations — 3D models, journals, open-source projects, and Innovatech event entries. Guests can browse the catalogue, render 3D previews, and download attachments. All files are stored via the polymorphic <code>attachments</code> table.</span><span class="lang-id">Arsip publik inovasi mahasiswa — model 3D, jurnal, proyek open-source, dan entri event Innovatech. Tamu dapat menjelajahi katalog, merender pratinjau 3D, dan mengunduh lampiran. Semua file disimpan via tabel polymorphic <code>attachments</code>.</span></p>
          </div>
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="20" r="1.6" stroke="currentColor" stroke-width="1.6"/><circle cx="18" cy="20" r="1.6" stroke="currentColor" stroke-width="1.6"/><path d="M2 3h3l2.5 12h11l2-8H6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <h3><span class="lang-en">E-Commerce Hub</span><span class="lang-id">Hub E-Commerce</span></h3>
            <p><span class="lang-en">Made-by-order 3D print / scan / design services. Pricing is calculated transparently from slicer output: <code>grams × base_price</code> (e.g. Rp 2,000/gram). Custom products go through WhatsApp price negotiation. Clients track order progress in real-time.</span><span class="lang-id">Layanan 3D print / scan / desain berbasis pesanan. Harga dihitung transparan dari output slicer: <code>grams × base_price</code> (mis. Rp 2.000/gram). Produk custom melalui negosiasi harga via WhatsApp. Klien memantau progress pesanan secara real-time.</span></p>
          </div>
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><path d="M4 21V5a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v16M4 21h15" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <h3><span class="lang-en">Company Profile &amp; CMS</span><span class="lang-id">Profil Perusahaan &amp; CMS</span></h3>
            <p><span class="lang-en">The public face of the IDIG organisation. The landing page (Hero → About → Competency → Services → Collaboration → Organisation → CTA → Article → Contact → Footer) is entirely driven by the <code>page_sections</code> key-value table. No redeploy needed for content changes.</span><span class="lang-id">Wajah publik organisasi IDIG. Landing page (Hero → About → Competency → Services → Collaboration → Organisation → CTA → Article → Contact → Footer) sepenuhnya dikendalikan tabel key-value <code>page_sections</code>. Tidak perlu redeploy untuk perubahan konten.</span></p>
          </div>
        </div>
        <div class="callout"><span class="lang-en">The codebase is a <b>Modern Monolith</b> — one Laravel application with two rendering strategies: <b>Inertia + React</b> for public/user pages and <b>Livewire + Blade</b> for the admin dashboard. There is no separate API service.</span><span class="lang-id">Codebase ini adalah <b>Modern Monolith</b> — satu aplikasi Laravel dengan dua strategi rendering: <b>Inertia + React</b> untuk halaman publik/pengguna dan <b>Livewire + Blade</b> untuk dashboard admin. Tidak ada layanan API terpisah.</span></div>
      </div>
    </section>

    <!-- 02 LOCAL SETUP -->
    <section id="setup">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">02 — Local Setup &amp; Dev Commands</span><span class="lang-id">02 — Setup Lokal &amp; Perintah Dev</span></div>
          <h2><span class="lang-en">From zero to running in five steps</span><span class="lang-id">Dari nol hingga berjalan dalam lima langkah</span></h2>
        </div>
        <div class="grid g2" style="align-items:start">
          <div class="flow-card">
            <h3><span class="flow-num">↑</span> <span class="lang-en">Initial Setup</span><span class="lang-id">Setup Awal</span></h3>
            <div class="flow">
              <div class="step"><h4>1 · Clone &amp; install</h4><p><code>git clone &lt;repo&gt;</code> → <code>composer install</code> → <code>npm install</code></p></div>
              <div class="step"><h4>2 · Environment</h4><p><span class="lang-en">Copy <code>.env.example</code> to <code>.env</code>. Configure <code>DB_*</code> credentials and <code>AWS_*</code> / MinIO keys for S3 storage.</span><span class="lang-id">Salin <code>.env.example</code> ke <code>.env</code>. Konfigurasi kredensial <code>DB_*</code> dan kunci <code>AWS_*</code> / MinIO untuk penyimpanan S3.</span></p></div>
              <div class="step"><h4>3 · Generate &amp; seed</h4><p><code>php artisan key:generate</code> → <code>php artisan migrate --seed</code></p></div>
              <div class="step"><h4>4 · <span class="lang-en">Start dev server</span><span class="lang-id">Mulai dev server</span></h4><p><code>composer run dev</code> — <span class="lang-en">starts server + queue worker + log tail + Vite concurrently.</span><span class="lang-id">memulai server + queue worker + log tail + Vite secara bersamaan.</span></p></div>
              <div class="step"><h4>5 · <span class="lang-en">Open browser</span><span class="lang-id">Buka browser</span></h4><p><span class="lang-en">Visit <code>http://localhost:8000</code>. The React frontend is hot-reloaded via Vite.</span><span class="lang-id">Kunjungi <code>http://localhost:8000</code>. Frontend React di-hot-reload via Vite.</span></p></div>
            </div>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">⌘</span> <span class="lang-en">Daily Commands</span><span class="lang-id">Perintah Harian</span></h3>
            <div style="display:flex;flex-direction:column;gap:14px">
              <div>
                <div class="card .tag" style="font-family:var(--mono);font-size:11px;color:var(--cyan2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px">Full dev environment</div>
                <pre style="margin:0"><code>composer run dev</code></pre>
              </div>
              <div>
                <div class="card .tag" style="font-family:var(--mono);font-size:11px;color:var(--cyan2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px">Run tests</div>
                <pre style="margin:0"><code>php artisan test --compact
php artisan test --compact --filter=testName
php artisan test --compact tests/Feature/SomeTest.php</code></pre>
              </div>
              <div>
                <div class="card .tag" style="font-family:var(--mono);font-size:11px;color:var(--gold);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px">⚠ <span class="lang-en">Run after every PHP change</span><span class="lang-id">Wajib setelah setiap perubahan PHP</span></div>
                <pre style="margin:0"><code>vendor/bin/pint --dirty --format agent</code></pre>
              </div>
              <div>
                <div class="card .tag" style="font-family:var(--mono);font-size:11px;color:var(--cyan2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px">Frontend &amp; database</div>
                <pre style="margin:0"><code>npm run build
npm run dev
php artisan migrate --seed
php artisan tinker --execute 'Your::code();'</code></pre>
              </div>
            </div>
          </div>
        </div>
        <div class="callout warn"><b><span class="lang-en">Mandatory:</span><span class="lang-id">Wajib:</span></b> <span class="lang-en">Run <code>vendor/bin/pint --dirty --format agent</code> after every PHP change. This is enforced in code review — unformatted code will be rejected.</span><span class="lang-id">Jalankan <code>vendor/bin/pint --dirty --format agent</code> setelah setiap perubahan PHP. Ini diterapkan saat code review — kode yang tidak diformat akan ditolak.</span></div>
      </div>
    </section>

    <!-- 03 ARCHITECTURE -->
    <section id="architecture" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">03 — Architecture</span><span class="lang-id">03 — Arsitektur</span></div>
          <h2><span class="lang-en">React for public, Livewire for admin — one codebase, two rendering strategies</span><span class="lang-id">React untuk publik, Livewire untuk admin — satu codebase, dua strategi rendering</span></h2>
          <p class="lead"><span class="lang-en">The hybrid approach gives each surface the right tool: a rich, interactive SPA for users and a fast server-rendered CRUD panel for admins — without the overhead of a separate API.</span><span class="lang-id">Pendekatan hybrid memberikan alat yang tepat: SPA interaktif untuk pengguna dan panel CRUD server-rendered cepat untuk admin — tanpa overhead API terpisah.</span></p>
        </div>
        <div class="grid g2">
          <div class="card">
            <span class="tag">Public &amp; User Pages</span>
            <h3 style="margin-top:8px">Inertia.js + React 19</h3>
            <p>Controllers return <code>Inertia::render('Features/Foo/Pages/FooPage', [...])</code>. Data flows as Inertia props — no REST API needed. Entry point: <code>resources/js/app.tsx</code>. Pages live in <code>resources/js/pages/</code>.</p>
            <p style="margin-top:10px">Use <code>Inertia::optional()</code> for deferred/conditional props. <code>Inertia::lazy()</code> was removed in v3.</p>
          </div>
          <div class="card">
            <span class="tag">Admin Dashboard</span>
            <h3 style="margin-top:8px">Livewire 4 + Alpine.js + Blade</h3>
            <p>All admin CRUD lives in <code>app/Livewire/Admin/</code> with Blade views in <code>resources/views/livewire/</code>. Uses <b>MaryUI</b> (<code>robsontenorio/mary</code>) component library. Routes point directly to the Livewire class: <code>Route::get('/admin/foo', FooIndex::class)</code>.</p>
          </div>
        </div>

        <div class="stack-block"><h4><span class="lang-en">Backend — Action-Based Architecture</span><span class="lang-id">Backend — Arsitektur Berbasis Action</span></h4></div>
        <p style="color:var(--slate600);margin-bottom:16px;font-size:14.5px"><span class="lang-en">All business logic lives in <code>app/Actions/</code>. Controllers and Livewire components are thin dispatchers that collect input and call Actions. The flow is always: <strong>Controller / Livewire → DTO → Action</strong>.</span><span class="lang-id">Seluruh logika bisnis berada di <code>app/Actions/</code>. Controller dan komponen Livewire hanya dispatcher tipis yang mengumpulkan input dan memanggil Action. Alurnya selalu: <strong>Controller / Livewire → DTO → Action</strong>.</span></p>
        <div class="grid g4">
          <div class="tcard"><div class="tn">app/Actions/</div><div class="td">95 single-responsibility classes. One public <code>execute()</code> method per class. E.g. <code>ApproveOpenSourceProjectAction</code>, <code>CreateServiceBookingAction</code>.</div></div>
          <div class="tcard"><div class="tn">app/DTOs/</div><div class="td">28 strongly-typed data transfer objects. Constructed from validated <code>Request</code> data before passing to an Action. E.g. <code>CreateBookingData</code>, <code>SlicerCalculationData</code>.</div></div>
          <div class="tcard"><div class="tn">app/Services/</div><div class="td">Third-party integrations only. <code>S3StorageService</code> for pre-signed URL generation. <code>PaymentGatewayService</code> for transaction processing.</div></div>
          <div class="tcard"><div class="tn">app/Livewire/</div><div class="td">Thin visual components. Collect form data, validate, construct a DTO, and call the appropriate Action. No business logic here.</div></div>
        </div>

        <div class="stack-block" style="margin-top:28px"><h4><span class="lang-en">Frontend — Feature-Based Architecture</span><span class="lang-id">Frontend — Arsitektur Berbasis Fitur</span></h4></div>
        <p style="color:var(--slate600);margin-bottom:16px;font-size:14.5px"><span class="lang-en">The React frontend is split into two strict zones. Domain isolation is enforced by convention — not by a build tool — so everyone must follow the rule.</span><span class="lang-id">Frontend React dibagi dua zona ketat. Isolasi domain diterapkan berdasarkan konvensi — bukan oleh build tool — sehingga semua orang wajib mengikuti aturan ini.</span></p>
        <div class="grid g2">
          <div class="card">
            <h4 style="color:var(--p600);font-family:var(--mono);font-size:13px;margin-bottom:10px">Core/ — Foundation (logic-agnostic)</h4>
            <p><code>Components/Common/</code> — primitive wrappers (Box, Text, Heading, Image, Container)<br>
            <code>Components/Shared/</code> — reusable UI (Button, Card, Modal, Badge, Input, etc.)<br>
            <code>Hooks/</code> — global hooks (useTranslation, useMobile, useScrollLock…)<br>
            <code>Types/</code> · <code>Utils/</code> · <code>Store/</code> (Zustand) · <code>Config/</code> · <code>Locales/</code></p>
          </div>
          <div class="card">
            <h4 style="color:var(--p600);font-family:var(--mono);font-size:13px;margin-bottom:10px">Features/ — Domain Modules (isolated)</h4>
            <p>12 feature domains: <strong>Landing, Dashboard, Products, Publications, Projects, Services, Orders, Training, Pameran, Scan, Portfolio, Search</strong>. Each is a mini-app with its own <code>components/</code>, <code>hooks/</code>, <code>pages/</code>, <code>schemas/</code>, <code>types/</code>, and <code>index.ts</code> barrel export.</p>
          </div>
        </div>
        <div class="callout"><b><span class="lang-en">Golden Rule:</span><span class="lang-id">Aturan Emas:</span></b> <span class="lang-en"><code>Core/</code> must never import from <code>Features/</code>. Feature modules must never cross-import each other. If two features share something, it belongs in <code>Core/</code>.</span><span class="lang-id"><code>Core/</code> tidak boleh mengimpor dari <code>Features/</code>. Modul fitur tidak boleh saling mengimpor satu sama lain. Jika dua fitur berbagi sesuatu, letakkan di <code>Core/</code>.</span></div>
      </div>
    </section>

    <!-- 04 CRITICAL RULES -->
    <section id="rules">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">04 — Critical Rules &amp; Conventions</span><span class="lang-id">04 — Aturan &amp; Konvensi Kritis</span></div>
          <h2><span class="lang-en">Non-negotiable patterns every contributor must follow</span><span class="lang-id">Pola tak dapat dinegosiasikan yang wajib diikuti setiap kontributor</span></h2>
          <p class="lead"><span class="lang-en">These constraints exist for specific reasons — violating them causes real problems (Lighthouse regressions, RAM spikes, broken builds). Learn why, not just what.</span><span class="lang-id">Batasan ini ada karena alasan tertentu — melanggarnya menyebabkan masalah nyata (regresi Lighthouse, RAM melonjak, build rusak). Pahami alasannya, bukan sekadar aturannya.</span></p>
        </div>
        <div class="grid g3">
          <div class="card">
            <span class="rule-num">RULE 1</span>
            <h3><span class="lang-en">React: Core Primitives Only</span><span class="lang-id">React: Hanya Core Primitives</span></h3>
            <p style="margin-bottom:14px"><span class="lang-en">Never use raw HTML tags in React pages or feature components. Always use wrappers from <code>Core/Components/Common/</code>:</span><span class="lang-id">Jangan gunakan tag HTML mentah di halaman React atau komponen fitur. Selalu gunakan wrapper dari <code>Core/Components/Common/</code>:</span></p>
            <table style="width:100%;border-collapse:collapse;font-size:13px">
              <tr style="border-bottom:1px solid var(--line-dark)"><td style="padding:5px 0;color:var(--muted-dark)"><code>&lt;div&gt;</code>, <code>&lt;section&gt;</code></td><td style="padding:5px 0;color:var(--cyan-soft)">→ <code>&lt;Box&gt;</code></td></tr>
              <tr style="border-bottom:1px solid var(--line-dark)"><td style="padding:5px 0;color:var(--muted-dark)"><code>&lt;h1&gt;</code>–<code>&lt;h6&gt;</code></td><td style="padding:5px 0;color:var(--cyan-soft)">→ <code>&lt;Heading level={n}&gt;</code></td></tr>
              <tr style="border-bottom:1px solid var(--line-dark)"><td style="padding:5px 0;color:var(--muted-dark)"><code>&lt;p&gt;</code>, <code>&lt;span&gt;</code></td><td style="padding:5px 0;color:var(--cyan-soft)">→ <code>&lt;Text&gt;</code></td></tr>
              <tr style="border-bottom:1px solid var(--line-dark)"><td style="padding:5px 0;color:var(--muted-dark)"><code>&lt;img&gt;</code></td><td style="padding:5px 0;color:var(--cyan-soft)">→ <code>&lt;Image&gt;</code></td></tr>
              <tr><td style="padding:5px 0;color:var(--muted-dark)"><code>.container</code> div</td><td style="padding:5px 0;color:var(--cyan-soft)">→ <code>&lt;Container&gt;</code></td></tr>
            </table>
          </div>
          <div class="card">
            <span class="rule-num">RULE 2</span>
            <h3><span class="lang-en">3D Viewer: Lazy-Load Only</span><span class="lang-id">3D Viewer: Hanya Lazy-Load</span></h3>
            <p><span class="lang-en">React Three Fiber canvas components <strong>must</strong> use <code>React.lazy()</code> + <code>&lt;Suspense&gt;</code>. Loading Three.js eagerly on initial page load adds ~500 KB to the main bundle and destroys Lighthouse performance scores.</span><span class="lang-id">Komponen canvas React Three Fiber <strong>wajib</strong> menggunakan <code>React.lazy()</code> + <code>&lt;Suspense&gt;</code>. Memuat Three.js secara eager menambah ~500 KB ke bundle utama dan merusak skor Lighthouse.</span></p>
            <pre><code>const Viewer = React.lazy(
  () =&gt; import('./ThreeViewer')
);

&lt;Suspense fallback={&lt;Skeleton /&gt;}&gt;
  &lt;Viewer file={url} /&gt;
&lt;/Suspense&gt;</code></pre>
          </div>
          <div class="card">
            <span class="rule-num">RULE 3</span>
            <h3><span class="lang-en">File Uploads: Pre-Signed S3</span><span class="lang-id">Unggahan File: Pre-Signed S3</span></h3>
            <p style="margin-bottom:12px"><span class="lang-en">Large files (<code>.stl</code>, <code>.obj</code>, <code>.zip</code>) must <strong>never</strong> pass through Laravel's RAM. The required flow:</span><span class="lang-id">File besar (<code>.stl</code>, <code>.obj</code>, <code>.zip</code>) <strong>tidak boleh</strong> melewati RAM Laravel. Alur yang diperlukan:</span></p>
            <div class="flow" style="margin-top:0">
              <div class="step"><h4><span class="lang-en">Frontend requests pre-signed URL</span><span class="lang-id">Frontend meminta pre-signed URL</span></h4><p>GET <code>/upload/presign</code> → Laravel returns S3 pre-signed PUT URL</p></div>
              <div class="step"><h4><span class="lang-en">Frontend uploads directly to S3</span><span class="lang-id">Frontend unggah langsung ke S3</span></h4><p><span class="lang-en">PUT file to the pre-signed URL with a progress event listener</span><span class="lang-id">PUT file ke pre-signed URL dengan event listener progres</span></p></div>
              <div class="step"><h4><span class="lang-en">Frontend saves the path</span><span class="lang-id">Frontend menyimpan path</span></h4><p><span class="lang-en">POST the resulting S3 key → Laravel saves as <code>attachments</code> row</span><span class="lang-id">POST S3 key yang dihasilkan → Laravel menyimpan sebagai baris <code>attachments</code></span></p></div>
            </div>
          </div>
          <div class="card">
            <span class="rule-num">RULE 4</span>
            <h3><span class="lang-en">Middleware: bootstrap/app.php</span><span class="lang-id">Middleware: bootstrap/app.php</span></h3>
            <p><span class="lang-en">Laravel 12 registers middleware in <code>bootstrap/app.php</code> via <code>Application::configure()->withMiddleware()</code>. There is <strong>no</strong> <code>app/Http/Kernel.php</code>.</span><span class="lang-id">Laravel 12 mendaftarkan middleware di <code>bootstrap/app.php</code> via <code>Application::configure()->withMiddleware()</code>. <strong>Tidak ada</strong> <code>app/Http/Kernel.php</code>.</span></p>
            <pre><code>->withMiddleware(function (Middleware $m) {
    $m->alias([
        'role' =&gt; RoleMiddleware::class,
    ]);
    $m->web(append: [
        HandleInertiaRequests::class,
    ]);
})</code></pre>
          </div>
          <div class="card">
            <span class="rule-num">RULE 5</span>
            <h3><span class="lang-en">Routes: Wayfinder Type-Safety</span><span class="lang-id">Routing: Type-Safety Wayfinder</span></h3>
            <p style="margin-bottom:12px"><span class="lang-en">Import typed route helpers from auto-generated files — never hardcode URL strings in React.</span><span class="lang-id">Impor helper route bertipe dari file yang di-generate otomatis — jangan pernah hardcode string URL di React.</span></p>
            <pre><code>// Named routes
import { route } from '@/routes/...'

// Controller action URLs
import { action } from '@/actions/...'</code></pre>
            <p style="margin-top:12px;font-size:13.5px;color:var(--muted-dark)"><span class="lang-en">Both are regenerated automatically by <code>@laravel/vite-plugin-wayfinder</code> on every <code>npm run dev/build</code>.</span><span class="lang-id">Keduanya di-generate ulang otomatis oleh <code>@laravel/vite-plugin-wayfinder</code> setiap <code>npm run dev/build</code>.</span></p>
          </div>
          <div class="card">
            <span class="rule-num">RULE 6</span>
            <h3>Inertia: optional(), not lazy()</h3>
            <p><span class="lang-en"><code>Inertia::lazy()</code> was <strong>removed</strong> in Inertia v3. Always use <code>Inertia::optional()</code> for deferred or conditional props.</span><span class="lang-id"><code>Inertia::lazy()</code> telah <strong>dihapus</strong> di Inertia v3. Selalu gunakan <code>Inertia::optional()</code> untuk props yang ditangguhkan atau bersyarat.</span></p>
            <pre><code>// ✗ Removed in v3
Inertia::lazy(fn() =&gt; [...]);

// ✓ Correct
Inertia::optional(fn() =&gt; [...]);</code></pre>
          </div>
        </div>
      </div>
    </section>

    <!-- 05 RBAC & ROUTING -->
    <section id="rbac" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker">05 — RBAC &amp; Routing</div>
          <h2><span class="lang-en">Five roles, one pipe-separated middleware</span><span class="lang-id">Lima peran, satu middleware dengan pemisah pipa</span></h2>
          <p class="lead"><span class="lang-en">Role-based access is enforced at the route level via the <code>role</code> middleware alias. A single pipe-separated string controls which roles can access a group.</span><span class="lang-id">Akses berbasis peran diterapkan di level route via alias middleware <code>role</code>. Satu string berpemisah pipa mengontrol peran mana yang dapat mengakses suatu grup.</span></p>
        </div>

        <div class="grid g3" style="margin-bottom:28px">
          <div class="card">
            <span class="role-badge rb-d">super_admin</span>
            <h3>Super Administrator</h3>
            <p><span class="lang-en">Full system access. Manages user roles, CMS content, landing page sections, structural members, and financial reports. Sees all admin routes.</span><span class="lang-id">Akses sistem penuh. Mengelola peran pengguna, konten CMS, seksi landing page, anggota struktural, dan laporan keuangan. Melihat semua route admin.</span></p>
          </div>
          <div class="card">
            <span class="role-badge rb-c">admin_lab</span>
            <h3>Lab Admin</h3>
            <p><span class="lang-en">Validates publication submissions (approve / reject), manages services, products, events, trainings, and updates service order progress.</span><span class="lang-id">Memvalidasi pengajuan publikasi (setuju / tolak), mengelola layanan, produk, event, pelatihan, dan memperbarui progress pesanan layanan.</span></p>
          </div>
          <div class="card">
            <span class="role-badge rb-b">admin_gudang</span>
            <h3><span class="lang-en">Warehouse Admin</span><span class="lang-id">Admin Gudang</span></h3>
            <p><span class="lang-en">Manages raw material inventory: restocking, issue reports, and material verification gate before an order enters slicing/printing.</span><span class="lang-id">Mengelola inventaris bahan baku: restocking, laporan masalah, dan gerbang verifikasi material sebelum pesanan masuk ke tahap slicing/printing.</span></p>
          </div>
          <div class="card">
            <span class="role-badge rb-a">mahasiswa</span>
            <h3><span class="lang-en">Student / Creator</span><span class="lang-id">Mahasiswa / Kreator</span></h3>
            <p><span class="lang-en">Authenticated student users. Can submit open-source projects for validation, view portfolio, and book services.</span><span class="lang-id">Pengguna mahasiswa terautentikasi. Dapat mengajukan proyek open-source untuk validasi, melihat portofolio, dan memesan layanan.</span></p>
          </div>
          <div class="card">
            <span class="role-badge rb-e">user_publik</span>
            <h3><span class="lang-en">Public User</span><span class="lang-id">Pengguna Publik</span></h3>
            <p><span class="lang-en">Registered non-student users. Can browse the catalogue, order custom services, negotiate prices via WhatsApp, and track order progress.</span><span class="lang-id">Pengguna non-mahasiswa terdaftar. Dapat menjelajahi katalog, memesan layanan custom, negosiasi harga via WhatsApp, dan melacak progress pesanan.</span></p>
          </div>
        </div>

        <div class="grid g2">
          <div class="flow-card">
            <h3><span class="flow-num">&lt;/&gt;</span> <span class="lang-en">Route Group Pattern</span><span class="lang-id">Pola Grup Route</span></h3>
            <p style="font-size:14px;color:var(--slate600);margin-bottom:16px"><span class="lang-en">Copy-pasteable boilerplate for a new role-restricted route group:</span><span class="lang-id">Boilerplate siap pakai untuk grup route dengan pembatasan peran:</span></p>
            <pre><code>Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        Route::get('/dashboard', SuperAdminDashboard::class)
            ->name('dashboard');
    });</code></pre>
            <p style="font-size:13px;color:var(--slate600);margin-top:12px">The <code>role</code> alias maps to <code>App\Http\Middleware\RoleMiddleware</code> registered in <code>bootstrap/app.php</code>.</p>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">→</span> <span class="lang-en">Login Redirect Logic</span><span class="lang-id">Logika Redirect Login</span></h3>
            <div class="flow">
              <div class="step"><h4>super_admin</h4><p><span class="lang-en">Redirected to</span><span class="lang-id">Diarahkan ke</span> <code>/super-admin/dashboard</code></p></div>
              <div class="step"><h4>admin_lab</h4><p><span class="lang-en">Redirected to</span><span class="lang-id">Diarahkan ke</span> <code>/admin/dashboard</code></p></div>
              <div class="step"><h4>admin_gudang</h4><p><span class="lang-en">Redirected to</span><span class="lang-id">Diarahkan ke</span> <code>/gudang/dashboard</code></p></div>
              <div class="step"><h4>mahasiswa / user_publik</h4><p><span class="lang-en">Redirected to</span><span class="lang-id">Diarahkan ke</span> <code>/dashboard</code> (React page)</p></div>
            </div>
            <p style="font-size:13px;color:var(--slate600);margin-top:14px"><span class="lang-en"><code>SwitchRoleController</code> allows switching active role without re-authenticating (for users with multiple roles).</span><span class="lang-id"><code>SwitchRoleController</code> memungkinkan pergantian peran aktif tanpa autentikasi ulang (untuk pengguna dengan beberapa peran).</span></p>
          </div>
        </div>
        <div class="callout"><b><span class="lang-en">Pipe-separated roles:</span><span class="lang-id">Peran berpemisah pipa:</span></b> <code>role:super_admin|admin_lab</code> — <span class="lang-en"><code>RoleMiddleware</code> explodes on <code>|</code> and checks if the user's role matches any in the list.</span><span class="lang-id"><code>RoleMiddleware</code> memecah pada <code>|</code> dan memeriksa apakah peran pengguna cocok dengan salah satu dalam daftar.</span></div>
      </div>
    </section>

    <!-- 06 DATABASE SCHEMA -->
    <section id="database">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">06 — Database Schema</span><span class="lang-id">06 — Skema Database</span></div>
          <h2><span class="lang-en">37 models across 6 domains, 61 migrations</span><span class="lang-id">37 model di 6 domain, 61 migrasi</span></h2>
          <p class="lead"><span class="lang-en">All tables are in a single MySQL database. The schema is domain-organised. Three key tables (highlighted in gold) are central to most features.</span><span class="lang-id">Semua tabel ada dalam satu database MySQL. Skema diorganisir berdasarkan domain. Tiga tabel kunci (disorot emas) adalah inti dari sebagian besar fitur.</span></p>
        </div>

        <div class="db-group"><h3><span class="idx">1</span> <span class="lang-en">Users &amp; Profiles</span><span class="lang-id">Pengguna &amp; Profil</span></h3>
          <div class="tbl">
            <div class="tcard"><div class="tn">roles</div><div class="td">Master access levels: super_admin, admin_lab, admin_gudang, mahasiswa, user_publik.</div></div>
            <div class="tcard"><div class="tn">users</div><div class="td">Core auth table: email, password, role_id, email_verified_at, two_factor_secret.</div></div>
            <div class="tcard"><div class="tn">user_profiles</div><div class="td">1-to-1 detail: full name, phone, NIM/NIK, department, faculty.</div></div>
            <div class="tcard"><div class="tn">activity_logs</div><div class="td">Auditable trail of user actions via the <code>RecordsActivity</code> trait.</div></div>
          </div>
        </div>

        <div class="db-group"><h3><span class="idx">2</span> <span class="lang-en">Centralised Media</span><span class="lang-id">Media Terpusat</span></h3>
          <div class="tbl">
            <div class="tcard key"><div class="tn">attachments</div><div class="td">Polymorphic table for all files (images, 3D models, docs). Columns: <code>attachable_type</code>, <code>attachable_id</code>, <code>disk</code>, <code>path</code>, <code>mime_type</code>, <code>is_primary</code>, <code>sort_order</code>.</div></div>
          </div>
        </div>

        <div class="db-group"><h3><span class="idx">3</span> <span class="lang-en">Events &amp; Publications</span><span class="lang-id">Event &amp; Publikasi</span></h3>
          <div class="tbl">
            <div class="tcard"><div class="tn">events</div><div class="td">Master exhibitions / competitions (e.g. Innovatech). has_teams toggle.</div></div>
            <div class="tcard"><div class="tn">teams</div><div class="td">Student groups within an event.</div></div>
            <div class="tcard"><div class="tn">team_members</div><div class="td">Pivot M-to-M: students ↔ teams, with <code>role</code> column (leader/member).</div></div>
            <div class="tcard"><div class="tn">projects</div><div class="td">Event-scoped innovations; status: pending / approved / rejected.</div></div>
            <div class="tcard"><div class="tn">open_source_projects</div><div class="td">Individual public projects outside of events; same status enum.</div></div>
            <div class="tcard"><div class="tn">publications</div><div class="td">Journal / paper entries with translatable abstract.</div></div>
          </div>
        </div>

        <div class="db-group"><h3><span class="idx">4</span> <span class="lang-en">Catalogue &amp; Materials</span><span class="lang-id">Katalog &amp; Material</span></h3>
          <div class="tbl">
            <div class="tcard"><div class="tn">products</div><div class="td">Portfolio items shown on the catalogue: price_min, price_max, category, brand.</div></div>
            <div class="tcard"><div class="tn">services</div><div class="td">Lab service types (e.g. 3D Print): base_price per gram, description, is_active.</div></div>
            <div class="tcard"><div class="tn">raw_materials</div><div class="td">Filament / silicone / resin inventory: current_stock (grams), filament_type, color, brand.</div></div>
            <div class="tcard"><div class="tn">raw_material_movements</div><div class="td">Log of stock in/out mutations, each tied to a service_booking_id.</div></div>
            <div class="tcard"><div class="tn">brands · colors · filament_types · material_categories</div><div class="td">Master lookup tables for normalised product &amp; material metadata.</div></div>
            <div class="tcard"><div class="tn">tools</div><div class="td">Lab equipment with unique_code for QR scanning and tracking.</div></div>
          </div>
        </div>

        <div class="db-group"><h3><span class="idx">5</span> <span class="lang-en">Transactions &amp; Orders</span><span class="lang-id">Transaksi &amp; Pesanan</span></h3>
          <div class="tbl">
            <div class="tcard"><div class="tn">transactions</div><div class="td">Payment header: total_amount, payment_proof (S3 path), status, expired_at.</div></div>
            <div class="tcard key"><div class="tn">service_bookings</div><div class="td">Core order record: slicer_weight_grams, agreed_price, current_status (BookingStatus enum), linked user &amp; service.</div></div>
            <div class="tcard"><div class="tn">service_progress_updates</div><div class="td">Timeline: status_label (e.g. "Printing"), percentage 0–100, notes, created_at.</div></div>
            <div class="tcard"><div class="tn">booking_messages</div><div class="td">Chat history between client and admin for an order (Pusher/Reverb powered).</div></div>
            <div class="tcard"><div class="tn">booking_payments</div><div class="td">Down-payment and final payment records per booking; proof image uploads.</div></div>
            <div class="tcard"><div class="tn">trainings · training_registrations</div><div class="td">Lab workshop management with participant registration and payment verification.</div></div>
            <div class="tcard"><div class="tn">reimbursements</div><div class="td">Admin expense reimbursement claims linked to bookings.</div></div>
            <div class="tcard"><div class="tn">issue_reports</div><div class="td">Material / tool issues raised by warehouse admin; status: open / resolved.</div></div>
          </div>
        </div>

        <div class="db-group"><h3><span class="idx">6</span> <span class="lang-en">CMS &amp; Lab Structure</span><span class="lang-id">CMS &amp; Struktur Lab</span></h3>
          <div class="tbl">
            <div class="tcard key"><div class="tn">page_sections</div><div class="td">Key-value CMS store: unique on (page_name, section_key). E.g. page_name=<code>landing</code>, section_key=<code>hero_title</code>.</div></div>
            <div class="tcard"><div class="tn">structural_members</div><div class="td">Organisation chart personnel (Kepala Lab, IDIG HTECH, IDIG RCMED) with display_order.</div></div>
            <div class="tcard"><div class="tn">lab_team_sections · lab_team_persons</div><div class="td">Team section groupings and individual member profiles for the landing page organisation block.</div></div>
            <div class="tcard"><div class="tn">inventories · inventory_usages · item_stocks</div><div class="td">General lab inventory (beyond raw materials): item tracking, stock levels, and usage logs.</div></div>
          </div>
        </div>
      </div>
    </section>

    <!-- 07 TECH STACK -->
    <section id="stack" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker">07 — Tech Stack</div>
          <h2><span class="lang-en">Every library, its version, and why it exists</span><span class="lang-id">Setiap library, versinya, dan alasan keberadaannya</span></h2>
        </div>

        <div class="stack-block"><h4>Backend</h4></div>
        <div class="stack-row">
          <div class="pill"><b>Framework</b>Laravel 12.0</div>
          <div class="pill"><b>Admin UI</b>Livewire 4.1</div>
          <div class="pill"><b>SPA Bridge</b>Inertia.js 3.0</div>
          <div class="pill"><b>Auth</b>Laravel Fortify 1.30</div>
          <div class="pill"><b>Admin Components</b>MaryUI 2.6</div>
          <div class="pill"><b>Type-safe Routes</b>laravel/wayfinder</div>
          <div class="pill"><b>Multilingual Models</b>spatie/laravel-translatable 6.12</div>
          <div class="pill"><b>Real-time</b>Pusher / Laravel Reverb</div>
          <div class="pill"><b>Storage</b>AWS S3 / MinIO</div>
          <div class="pill"><b>QR Codes</b>chillerlan/php-qrcode</div>
        </div>

        <div class="stack-block"><h4><span class="lang-en">Dev Tools</span><span class="lang-id">Alat Dev</span></h4></div>
        <div class="stack-row">
          <div class="pill"><b>Tests</b>Pest 4.3</div>
          <div class="pill"><b>Linter</b>Laravel Pint 1.24</div>
          <div class="pill"><b>Debug</b>Laravel Debugbar</div>
          <div class="pill"><b>Dev Server</b>Laravel Sail / Pail</div>
        </div>

        <div class="stack-block"><h4>Frontend — Public (React)</h4></div>
        <div class="stack-row">
          <div class="pill"><b>UI Library</b>React 19.2.5</div>
          <div class="pill"><b>Language</b>TypeScript 6.0</div>
          <div class="pill"><b>Bundler</b>Vite 7.0</div>
          <div class="pill"><b>Styling</b>Tailwind CSS 4.1</div>
          <div class="pill"><b>Component Lib</b>@heroui/react</div>
          <div class="pill"><b>Animation</b>Framer Motion 12.38</div>
          <div class="pill"><b>Timeline Anim</b>GSAP 3.15</div>
          <div class="pill"><b>State</b>Zustand 5.0</div>
          <div class="pill"><b>Forms</b>React Hook Form 7.76</div>
          <div class="pill"><b>Validation</b>Zod 4.4</div>
          <div class="pill"><b>3D Rendering</b>React Three Fiber</div>
          <div class="pill"><b>Icons</b>lucide-react 1.16</div>
          <div class="pill"><b>Guided Tours</b>Driver.js 1.6</div>
        </div>
        <div class="callout"><b>Compiler:</b> <span class="lang-en">The Babel React Compiler (<code>babel-plugin-react-compiler</code>) is enabled in Vite config — it auto-memoises components. Do not add manual <code>useMemo</code> / <code>useCallback</code> calls unless the compiler explicitly can't handle the case.</span><span class="lang-id">Babel React Compiler (<code>babel-plugin-react-compiler</code>) diaktifkan di konfigurasi Vite — ia memoize komponen secara otomatis. Jangan tambahkan <code>useMemo</code> / <code>useCallback</code> manual kecuali compiler benar-benar tidak dapat menanganinya.</span></div>
      </div>
    </section>

    <!-- 08 TESTING -->
    <section id="testing">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">08 — Testing Guide</span><span class="lang-id">08 — Panduan Pengujian</span></div>
          <h2><span class="lang-en">Pest v4 on SQLite in-memory — fast, isolated, mandatory</span><span class="lang-id">Pest v4 pada SQLite in-memory — cepat, terisolasi, wajib</span></h2>
          <p class="lead"><span class="lang-en">Every PHP code change requires a new or updated test. Tests run against an in-memory SQLite database so the suite is fast and stateless.</span><span class="lang-id">Setiap perubahan kode PHP membutuhkan tes baru atau yang diperbarui. Tes berjalan terhadap database SQLite in-memory sehingga suite cepat dan stateless.</span></p>
        </div>
        <div class="grid g2" style="align-items:start">
          <div class="flow-card">
            <h3><span class="flow-num">✓</span> <span class="lang-en">Test Conventions</span><span class="lang-id">Konvensi Pengujian</span></h3>
            <div class="flow">
              <div class="step"><h4>Pest v4</h4><p><span class="lang-en">Create tests with <code>php artisan make:test --pest {Name}</code>. Use descriptive <code>test('...')</code> strings — not class methods.</span><span class="lang-id">Buat tes dengan <code>php artisan make:test --pest {Name}</code>. Gunakan string <code>test('...')</code> yang deskriptif — bukan metode kelas.</span></p></div>
              <div class="step"><h4>SQLite in-memory</h4><p><span class="lang-en"><code>DB_CONNECTION=sqlite</code>, <code>DB_DATABASE=:memory:</code> set in <code>phpunit.xml</code>. <code>RefreshDatabase</code> applied globally in <code>tests/Pest.php</code>.</span><span class="lang-id"><code>DB_CONNECTION=sqlite</code>, <code>DB_DATABASE=:memory:</code> diatur di <code>phpunit.xml</code>. <code>RefreshDatabase</code> diterapkan secara global di <code>tests/Pest.php</code>.</span></p></div>
              <div class="step"><h4>withoutVite()</h4><p><span class="lang-en">Call <code>$this->withoutVite()</code> in any test that renders a Livewire or Blade view that references <code>@@vite</code> — otherwise the test fails trying to read the manifest.</span><span class="lang-id">Panggil <code>$this->withoutVite()</code> pada tes yang merender view Livewire atau Blade yang mereferensikan <code>@@vite</code> — jika tidak, tes gagal saat mencoba membaca manifest.</span></p></div>
              <div class="step"><h4>Factories</h4><p><span class="lang-en">Use model factories for all test data. Avoid hardcoding IDs — factories handle relationships automatically.</span><span class="lang-id">Gunakan model factory untuk semua data tes. Hindari hardcode ID — factory menangani relasi secara otomatis.</span></p></div>
              <div class="step"><h4><span class="lang-en">Pint before commit</span><span class="lang-id">Pint sebelum commit</span></h4><p><span class="lang-en">Run <code>vendor/bin/pint --dirty --format agent</code> before every commit. CI runs Pint as part of <code>composer test</code>.</span><span class="lang-id">Jalankan <code>vendor/bin/pint --dirty --format agent</code> sebelum setiap commit. CI menjalankan Pint sebagai bagian dari <code>composer test</code>.</span></p></div>
            </div>
          </div>
          <div>
            <div class="flow-card" style="margin-bottom:16px">
              <h3><span class="flow-num">#</span> <span class="lang-en">Test Structure</span><span class="lang-id">Struktur Pengujian</span></h3>
              <p style="font-size:14px;color:var(--muted-dark);margin-bottom:14px"><span class="lang-en">42 tests across 8 domains in <code>tests/Feature/</code>:</span><span class="lang-id">42 tes di 8 domain dalam <code>tests/Feature/</code>:</span></p>
              <div class="grid" style="grid-template-columns:1fr 1fr;gap:8px">
                <div class="tcard" style="padding:10px 14px"><div class="tn" style="font-size:12px">Auth/</div><div class="td">Login, Register, 2FA, Password Reset</div></div>
                <div class="tcard" style="padding:10px 14px"><div class="tn" style="font-size:12px">Admin/</div><div class="td">Global Search, CRUD (Service, Tool, Brand, Material)</div></div>
                <div class="tcard" style="padding:10px 14px"><div class="tn" style="font-size:12px">Orders/</div><div class="td">Booking Lifecycle, Material Gate, Order Flow</div></div>
                <div class="tcard" style="padding:10px 14px"><div class="tn" style="font-size:12px">Pages/</div><div class="td">Landing, Dashboard, Products, Publications, Training, Services</div></div>
                <div class="tcard" style="padding:10px 14px"><div class="tn" style="font-size:12px">Settings/</div><div class="td">Profile, Password, 2FA</div></div>
                <div class="tcard" style="padding:10px 14px"><div class="tn" style="font-size:12px">Gudang/</div><div class="td">Warehouse pages &amp; material verification</div></div>
                <div class="tcard" style="padding:10px 14px"><div class="tn" style="font-size:12px">Portfolio/</div><div class="td">User portfolio page</div></div>
                <div class="tcard" style="padding:10px 14px"><div class="tn" style="font-size:12px">Support/</div><div class="td">UniqueCodeTest, ExampleTest</div></div>
              </div>
            </div>
            <pre><code>// Minimal Pest test example
test('products page loads for guests', function () {
    $this->withoutVite()
        ->get('/products')
        ->assertOk()
        ->assertInertia(fn ($page) =&gt; $page
            ->component('Features/Products/Pages/...')
        );
});</code></pre>
          </div>
        </div>
        <div class="callout warn"><b><span class="lang-en">Rule:</span><span class="lang-id">Aturan:</span></b> <span class="lang-en">Every PHP code change requires a new or updated test. PRs without tests for new behaviour will be rejected during review.</span><span class="lang-id">Setiap perubahan kode PHP membutuhkan tes baru atau yang diperbarui. PR tanpa tes untuk perilaku baru akan ditolak saat review.</span></div>
      </div>
    </section>

    <!-- 09 FRONTEND PATTERNS -->
    <section id="frontend" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">09 — Frontend Patterns</span><span class="lang-id">09 — Pola Frontend</span></div>
          <h2><span class="lang-en">Feature modules, Zustand scope, and the Core contract</span><span class="lang-id">Modul fitur, scope Zustand, dan kontrak Core</span></h2>
          <p class="lead"><span class="lang-en">These patterns keep the React codebase maintainable as the feature count grows. Each answers a specific question new contributors run into on their first week.</span><span class="lang-id">Pola-pola ini menjaga codebase React tetap terpelihara seiring bertambahnya fitur. Masing-masing menjawab pertanyaan spesifik yang dihadapi kontributor baru di minggu pertama mereka.</span></p>
        </div>
        <div class="grid g2">
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <h3><span class="lang-en">Multi-Step File Upload</span><span class="lang-id">Unggahan File Multi-Langkah</span></h3>
            <p><span class="lang-en">Multi-step forms for large files follow a strict three-step pattern: Step 1 — metadata (title, category, description); Step 2 — thumbnail image; Step 3 — main file (3D/zip) uploaded directly to S3 via pre-signed URL with a real-time progress bar. Each step saves an <code>attachments</code> row.</span><span class="lang-id">Form multi-langkah untuk file besar mengikuti pola tiga langkah ketat: Langkah 1 — metadata (judul, kategori, deskripsi); Langkah 2 — gambar thumbnail; Langkah 3 — file utama (3D/zip) diunggah langsung ke S3 via pre-signed URL dengan progress bar real-time. Setiap langkah menyimpan baris <code>attachments</code>.</span></p>
          </div>
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/><path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></div>
            <h3><span class="lang-en">Zustand — What Goes In State</span><span class="lang-id">Zustand — Apa yang Masuk State</span></h3>
            <p><span class="lang-en">Zustand (<code>Core/Store/</code>) is <strong>only</strong> for persistent client-side state that must survive navigation: 3D camera rotation/zoom, cart state. Server-derived data (user, products, orders) flows as Inertia props — never store it in Zustand.</span><span class="lang-id">Zustand (<code>Core/Store/</code>) <strong>hanya</strong> untuk state client-side persisten yang harus bertahan saat navigasi: rotasi/zoom kamera 3D, state keranjang. Data dari server (user, produk, pesanan) mengalir sebagai Inertia props — jangan pernah menyimpannya di Zustand.</span></p>
          </div>
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><path d="M9 12h6M9 16h6M9 8h6M5 4h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <h3><span class="lang-en">Forms: React Hook Form + Zod</span><span class="lang-id">Form: React Hook Form + Zod</span></h3>
            <p><span class="lang-en">All forms use React Hook Form with a Zod schema. Never use raw <code>useState</code> for form fields. Schemas live in <code>Features/{Domain}/schemas/</code>. Use <code>zodResolver</code> from <code>@hookform/resolvers/zod</code>.</span><span class="lang-id">Semua form menggunakan React Hook Form dengan skema Zod. Jangan gunakan <code>useState</code> mentah untuk field form. Skema berada di <code>Features/{Domain}/schemas/</code>. Gunakan <code>zodResolver</code> dari <code>@hookform/resolvers/zod</code>.</span></p>
          </div>
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.6"/><path d="M12 8v4l3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></div>
            <h3><span class="lang-en">i18n — Indonesian + English</span><span class="lang-id">i18n — Indonesia + Inggris</span></h3>
            <p><span class="lang-en">Frontend translations live in <code>Core/Locales/translations.ts</code> and <code>lang/en.json</code> / <code>lang/id.json</code>. Use the <code>useTranslation</code> hook from <code>Core/Hooks/</code>. Backend model fields (name, description) use <code>spatie/laravel-translatable</code> — always store both <code>en</code> and <code>id</code> keys.</span><span class="lang-id">Terjemahan frontend berada di <code>Core/Locales/translations.ts</code> dan <code>lang/en.json</code> / <code>lang/id.json</code>. Gunakan hook <code>useTranslation</code> dari <code>Core/Hooks/</code>. Field model backend (name, description) menggunakan <code>spatie/laravel-translatable</code> — selalu simpan kunci <code>en</code> dan <code>id</code>.</span></p>
          </div>
        </div>

        <div class="grid g2" style="margin-top:20px">
          <div class="flow-card">
            <h3><span class="flow-num">W</span> <span class="lang-en">Wayfinder Integration</span><span class="lang-id">Integrasi Wayfinder</span></h3>
            <p style="font-size:14px;color:var(--slate600);margin-bottom:14px"><span class="lang-en">Wayfinder generates TypeScript bindings from PHP routes and controllers so you never hardcode URL strings.</span><span class="lang-id">Wayfinder menghasilkan binding TypeScript dari route dan controller PHP sehingga Anda tidak perlu hardcode string URL.</span></p>
            <pre><code>// Named route (resources/js/routes/)
import { route } from '@/routes/products'
const url = route('products.show', { product: id })

// Controller action URL (resources/js/actions/)
import { store } from '@/actions/User/OrderController'
&lt;form action={store()} method="POST"&gt;</code></pre>
            <p style="font-size:13px;color:var(--slate600);margin-top:12px"><span class="lang-en">Run <code>npm run dev</code> or <code>npm run build</code> to regenerate. Never edit files inside <code>resources/js/routes/</code> or <code>resources/js/actions/</code> manually — they are overwritten on every build.</span><span class="lang-id">Jalankan <code>npm run dev</code> atau <code>npm run build</code> untuk regenerasi. Jangan pernah edit file di dalam <code>resources/js/routes/</code> atau <code>resources/js/actions/</code> secara manual — file tersebut akan ditimpa setiap build.</span></p>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">🗺</span> <span class="lang-en">Guided Tours</span><span class="lang-id">Tur Terpandu</span></h3>
            <p style="font-size:14px;color:var(--slate600);margin-bottom:14px"><span class="lang-en">Role-specific onboarding tours powered by Driver.js 1.6. Tour seen state persists in localStorage under <code>idig-tour-seen</code>.</span><span class="lang-id">Tur onboarding spesifik per peran yang didukung Driver.js 1.6. Status tur tersimpan di localStorage dengan kunci <code>idig-tour-seen</code>.</span></p>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Add data-tour selectors</span><span class="lang-id">Tambahkan selektor data-tour</span></h4><p><span class="lang-en">Add <code>data-tour="step-name"</code> to target elements.</span><span class="lang-id">Tambahkan <code>data-tour="step-name"</code> ke elemen target.</span></p></div>
              <div class="step"><h4><span class="lang-en">Define steps in resources/js/tours/</span><span class="lang-id">Definisikan langkah di resources/js/tours/</span></h4><p><span class="lang-en">Each role has its own tour configuration file.</span><span class="lang-id">Setiap peran memiliki file konfigurasi tur tersendiri.</span></p></div>
              <div class="step"><h4><span class="lang-en">Check localStorage</span><span class="lang-id">Periksa localStorage</span></h4><p><span class="lang-en">Tours only show once; reset via <code>localStorage.removeItem('idig-tour-seen')</code>.</span><span class="lang-id">Tur hanya ditampilkan sekali; reset via <code>localStorage.removeItem('idig-tour-seen')</code>.</span></p></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 10 CORE WORKFLOWS -->
    <section id="workflows">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">10 — Core Workflows</span><span class="lang-id">10 — Alur Kerja Inti</span></div>
          <h2><span class="lang-en">Three end-to-end flows, traced from request to database</span><span class="lang-id">Tiga alur end-to-end, ditelusuri dari request hingga database</span></h2>
          <p class="lead"><span class="lang-en">Understanding these three flows gives you the mental model to navigate 80% of the codebase confidently.</span><span class="lang-id">Memahami tiga alur ini memberi Anda model mental untuk menavigasi 80% codebase dengan percaya diri.</span></p>
        </div>
        <div class="grid" style="gap:22px">
          <div class="flow-card">
            <h3><span class="flow-num">1</span> <span class="lang-en">Service Order Lifecycle</span><span class="lang-id">Siklus Hidup Pesanan Layanan</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Client submits order</span><span class="lang-id">Klien mengajukan pesanan</span></h4><p><code>OrderController@store</code> validates input → constructs <code>CreateBookingData</code> DTO → calls <code>CreateServiceBookingAction</code> → <code>ServiceBooking</code> created with <code>status=pending</code>.</p></div>
              <div class="step"><h4><span class="lang-en">Warehouse gate</span><span class="lang-id">Gerbang gudang</span></h4><p><span class="lang-en"><code>admin_gudang</code> reviews material requirements at <code>/gudang/orders</code>. Calls <code>VerifyBookingMaterialAction</code> or <code>FlagBookingMaterialAction</code>. Booking cannot progress to slicing until verified.</span><span class="lang-id"><code>admin_gudang</code> meninjau kebutuhan material di <code>/gudang/orders</code>. Memanggil <code>VerifyBookingMaterialAction</code> atau <code>FlagBookingMaterialAction</code>. Pesanan tidak dapat berlanjut ke slicing sampai diverifikasi.</span></p></div>
              <div class="step"><h4><span class="lang-en">Slicer calculation</span><span class="lang-id">Kalkulasi slicer</span></h4><p><span class="lang-en">Admin (Livewire) inputs grams and estimated minutes from the slicer app → <code>SlicerCalculationData</code> DTO → price auto-calculated (<code>grams × service.base_price</code>) → invoice issued.</span><span class="lang-id">Admin (Livewire) input gram dan estimasi menit dari app slicer → DTO <code>SlicerCalculationData</code> → harga dihitung otomatis (<code>grams × service.base_price</code>) → invoice diterbitkan.</span></p></div>
              <div class="step"><h4><span class="lang-en">Production &amp; progress</span><span class="lang-id">Produksi &amp; progress</span></h4><p><code>DeductRawMaterialAction</code> <span class="lang-en">creates a <code>RawMaterialMovement</code> row and decrements <code>raw_materials.current_stock</code>. Admin adds <code>ServiceProgressUpdate</code> rows as production advances: Slicing → Printing 50% → Finishing.</span><span class="lang-id">membuat baris <code>RawMaterialMovement</code> dan mengurangi <code>raw_materials.current_stock</code>. Admin menambah baris <code>ServiceProgressUpdate</code> seiring produksi: Slicing → Printing 50% → Finishing.</span></p></div>
              <div class="step"><h4><span class="lang-en">Payment &amp; completion</span><span class="lang-id">Pembayaran &amp; penyelesaian</span></h4><p><span class="lang-en">Client uploads payment proof → <code>BookingPayment</code> row created → admin verifies → <code>Transaction</code> header record created → booking marked <code>completed</code>.</span><span class="lang-id">Klien mengunggah bukti pembayaran → baris <code>BookingPayment</code> dibuat → admin memverifikasi → record header <code>Transaction</code> dibuat → booking ditandai <code>completed</code>.</span></p></div>
            </div>
          </div>

          <div class="flow-card">
            <h3><span class="flow-num">2</span> <span class="lang-en">Open-Source Project Submission</span><span class="lang-id">Pengajuan Proyek Open-Source</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Step 1 — Metadata</span><span class="lang-id">Langkah 1 — Metadata</span></h4><p><span class="lang-en">Creator fills title, category, tags, description via React form (<code>Features/Projects/</code>). Saved via <code>CreateOpenSourceProjectAction</code>.</span><span class="lang-id">Kreator mengisi judul, kategori, tag, deskripsi via form React (<code>Features/Projects/</code>). Disimpan via <code>CreateOpenSourceProjectAction</code>.</span></p></div>
              <div class="step"><h4><span class="lang-en">Step 2 — Thumbnail</span><span class="lang-id">Langkah 2 — Thumbnail</span></h4><p><span class="lang-en">Image uploaded; saved as <code>attachments</code> row with <code>is_primary=true</code> and <code>sort_order=0</code>.</span><span class="lang-id">Gambar diunggah; disimpan sebagai baris <code>attachments</code> dengan <code>is_primary=true</code> dan <code>sort_order=0</code>.</span></p></div>
              <div class="step"><h4><span class="lang-en">Step 3 — 3D / source files</span><span class="lang-id">Langkah 3 — File 3D / sumber</span></h4><p><span class="lang-en">Frontend requests pre-signed S3 PUT URL → uploads directly to S3 → sends S3 key back to Laravel → saved as additional <code>attachments</code> rows (polymorphic to <code>OpenSourceProject</code>).</span><span class="lang-id">Frontend meminta pre-signed S3 PUT URL → unggah langsung ke S3 → kirim kunci S3 kembali ke Laravel → disimpan sebagai baris <code>attachments</code> tambahan (polymorphic ke <code>OpenSourceProject</code>).</span></p></div>
              <div class="step"><h4><span class="lang-en">Admin validation</span><span class="lang-id">Validasi admin</span></h4><p><span class="lang-en">Project appears in admin panel with <code>status=pending</code>. Admin calls <code>UpdateOpenSourceProjectStatusAction</code> to approve or reject with an optional note.</span><span class="lang-id">Proyek muncul di panel admin dengan <code>status=pending</code>. Admin memanggil <code>UpdateOpenSourceProjectStatusAction</code> untuk menyetujui atau menolak dengan catatan opsional.</span></p></div>
              <div class="step"><h4><span class="lang-en">Public catalogue</span><span class="lang-id">Katalog publik</span></h4><p><span class="lang-en">Approved projects appear in the public catalogue with interactive 3D viewer (lazy-loaded Three.js) and download links.</span><span class="lang-id">Proyek yang disetujui muncul di katalog publik dengan viewer 3D interaktif (Three.js lazy-loaded) dan tautan unduhan.</span></p></div>
            </div>
          </div>

          <div class="flow-card">
            <h3><span class="flow-num">3</span> <span class="lang-en">CMS Content Update (No Redeploy)</span><span class="lang-id">Pembaruan Konten CMS (Tanpa Redeploy)</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Super Admin edits content</span><span class="lang-id">Super Admin mengedit konten</span></h4><p><span class="lang-en">Opens <code>/admin/cms/page-sections</code> (Livewire). Edits a <code>page_sections</code> row — e.g. <code>page_name=landing</code>, <code>section_key=hero_title</code>, <code>content=New Headline</code>.</span><span class="lang-id">Membuka <code>/admin/cms/page-sections</code> (Livewire). Mengedit baris <code>page_sections</code> — mis. <code>page_name=landing</code>, <code>section_key=hero_title</code>, <code>content=Judul Baru</code>.</span></p></div>
              <div class="step"><h4><span class="lang-en">Action saves the change</span><span class="lang-id">Action menyimpan perubahan</span></h4><p><code>UpsertPageSectionAction</code> <span class="lang-en">does a <code>firstOrCreate</code> on the unique (page_name, section_key) pair, then updates <code>content</code>.</span><span class="lang-id">melakukan <code>firstOrCreate</code> pada pasangan unik (page_name, section_key), lalu memperbarui <code>content</code>.</span></p></div>
              <div class="step"><h4><span class="lang-en">Controller reads CMS</span><span class="lang-id">Controller membaca CMS</span></h4><p><code>LandingPageController</code> <span class="lang-en">queries <code>PageSection::where('page_name','landing')->get()->keyBy('section_key')</code> and passes the map as an Inertia prop.</span><span class="lang-id">mengquery <code>PageSection::where('page_name','landing')->get()->keyBy('section_key')</code> dan meneruskan map sebagai Inertia prop.</span></p></div>
              <div class="step"><h4><span class="lang-en">React renders dynamic content</span><span class="lang-id">React merender konten dinamis</span></h4><p><span class="lang-en">The <code>LandingPage</code> React component destructures <code>landingContent</code> from Inertia props and renders each section from the CMS data. No code change, no redeploy.</span><span class="lang-id">Komponen React <code>LandingPage</code> mendestrukturisasi <code>landingContent</code> dari Inertia props dan merender setiap seksi dari data CMS. Tidak ada perubahan kode, tidak perlu redeploy.</span></p></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 11 DIRECTORY MAP -->
    <section id="map" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">11 — Directory Map</span><span class="lang-id">11 — Peta Direktori</span></div>
          <h2><span class="lang-en">Where everything lives — a file tree for new contributors</span><span class="lang-id">Di mana segalanya berada — pohon file untuk kontributor baru</span></h2>
          <p class="lead"><span class="lang-en">A complete map of the directories a developer touches regularly. Bookmark this section for your first week.</span><span class="lang-id">Peta lengkap direktori yang sering disentuh developer. Tandai bagian ini untuk minggu pertama Anda.</span></p>
        </div>

        <div class="grid g2">
          <div>
            <h3 style="font-family:var(--mono);font-size:15px;color:var(--p700);margin-bottom:16px;letter-spacing:.04em">Backend (PHP)</h3>
            <div class="tbl" style="grid-template-columns:1fr">
              <div class="tcard"><div class="tn">app/Actions/</div><div class="td">95 single-responsibility action classes, organised by domain: Auth/, CMS/, Event/, Product/, Project/, Publication/, RawMaterial/, Report/, Services/, Training/, Transaction/, User/, Warehouse/.</div></div>
              <div class="tcard"><div class="tn">app/DTOs/</div><div class="td">28 strongly-typed data transfer objects. Match naming to their Action (e.g. <code>CreateBookingData</code> feeds <code>CreateServiceBookingAction</code>).</div></div>
              <div class="tcard"><div class="tn">app/Models/</div><div class="td">37 Eloquent models. Use <code>HasTranslations</code> trait from spatie for multilingual text fields.</div></div>
              <div class="tcard"><div class="tn">app/Http/Controllers/</div><div class="td">Thin HTTP dispatchers only — validate, build DTO, call Action, return Inertia response. Admin/ subdirectory for admin-only controllers.</div></div>
              <div class="tcard"><div class="tn">app/Http/Requests/</div><div class="td">31 FormRequest classes for input validation before reaching controllers.</div></div>
              <div class="tcard"><div class="tn">app/Livewire/</div><div class="td">Admin/ — 45 Livewire components for the admin dashboard. Shared/ — navbar, search bar, notifications. Auth/ — login/register forms.</div></div>
              <div class="tcard"><div class="tn">app/Services/</div><div class="td">S3StorageService (pre-signed URL generation), AuthService (utility helpers).</div></div>
              <div class="tcard"><div class="tn">app/Enums/</div><div class="td">BookingStatus (8 stages), CustomerBookingStatus (5 public-facing stages), IssueReportStatus.</div></div>
              <div class="tcard"><div class="tn">app/Http/Middleware/</div><div class="td">RoleMiddleware (role:… alias), HandleInertiaRequests (shares auth + locale as Inertia props), SetLocale, SetTimezone, RedirectIfAuthenticated.</div></div>
              <div class="tcard"><div class="tn">bootstrap/app.php</div><div class="td">Middleware registration point (not Kernel.php). All middleware aliases and web-stack appends go here.</div></div>
              <div class="tcard"><div class="tn">routes/web.php</div><div class="td">All routes in one file. Organised in middleware groups: guest, auth+verified, super-admin, admin, gudang, user.</div></div>
              <div class="tcard"><div class="tn">database/migrations/</div><div class="td">61 migration files. Always add new columns via a new migration — never modify existing ones directly.</div></div>
              <div class="tcard"><div class="tn">database/seeders/</div><div class="td">DatabaseSeeder orchestrates all seeders. Feature seeders live alongside (e.g. LandingContentSeeder, RolesSeeder).</div></div>
              <div class="tcard"><div class="tn">tests/Feature/</div><div class="td">41 feature tests in Auth/, Admin/, Orders/, Pages/, Settings/, Gudang/, Portfolio/ sub-directories. One test file per feature area.</div></div>
            </div>
          </div>

          <div>
            <h3 style="font-family:var(--mono);font-size:15px;color:var(--p700);margin-bottom:16px;letter-spacing:.04em">Frontend (TypeScript / Blade)</h3>
            <div class="tbl" style="grid-template-columns:1fr">
              <div class="tcard"><div class="tn">resources/js/Core/Components/Common/</div><div class="td">Primitive wrappers: Box, Text, Heading, Image, Container. These are the ONLY wrappers permitted in React pages. No raw HTML tags.</div></div>
              <div class="tcard"><div class="tn">resources/js/Core/Components/Shared/</div><div class="td">20 shared UI components: Avatar, Badge, Button, Card, DropdownMenu, Input, Modal, PillToggle, Sheet, Skeleton, Tooltip, TopProgressBar, etc.</div></div>
              <div class="tcard"><div class="tn">resources/js/Core/Hooks/</div><div class="td">9 global hooks: useTranslation, useMobile, useMediaQuery, useScrollLock, useEscapeKey, useOutsideClick, usePreloader, useNavigationProgress.</div></div>
              <div class="tcard"><div class="tn">resources/js/Core/Store/</div><div class="td">Zustand stores (ui.store.ts). Only persistent client-side state — never server data.</div></div>
              <div class="tcard"><div class="tn">resources/js/Features/</div><div class="td">12 domain modules: Landing, Dashboard, Products, Publications, Projects, Services, Orders, Training, Pameran, Scan, Portfolio, Search. Each is self-contained.</div></div>
              <div class="tcard"><div class="tn">resources/js/Features/{Domain}/pages/</div><div class="td">Inertia page entry points. One file per route. Returned by controllers via <code>Inertia::render('Features/Domain/Pages/PageName')</code>.</div></div>
              <div class="tcard"><div class="tn">resources/js/Features/{Domain}/schemas/</div><div class="td">Zod validation schemas for all forms in that domain.</div></div>
              <div class="tcard"><div class="tn">resources/js/routes/</div><div class="td">Auto-generated by Wayfinder — typed named route helpers. Do NOT edit manually.</div></div>
              <div class="tcard"><div class="tn">resources/js/actions/</div><div class="td">Auto-generated by Wayfinder — typed controller action URLs. Do NOT edit manually.</div></div>
              <div class="tcard"><div class="tn">resources/js/tours/</div><div class="td">Driver.js tour configurations per role. Tour seen state: localStorage key <code>idig-tour-seen</code>.</div></div>
              <div class="tcard"><div class="tn">resources/views/livewire/</div><div class="td">Blade views for all Livewire components. Mirror the directory structure of app/Livewire/.</div></div>
              <div class="tcard"><div class="tn">resources/views/print/</div><div class="td">Standalone Blade views (not Inertia) for printable output: item-label.blade.php (thermal labels).</div></div>
              <div class="tcard"><div class="tn">resources/views/dev/</div><div class="td">This documentation page. Standalone Blade view, no Inertia wrapper, no Livewire.</div></div>
              <div class="tcard"><div class="tn">resources/views/emails/</div><div class="td">Email Blade templates for notifications (booking updates, password reset, etc.).</div></div>
            </div>
          </div>
        </div>

        <div class="callout" style="margin-top:28px"><span class="lang-en">Missing a pattern? Check <code>CLAUDE.md</code> in the project root — it is the canonical source of truth for architecture decisions and is kept up-to-date with the codebase. This page summarises it with additional context.</span><span class="lang-id">Ada pola yang kurang? Periksa <code>CLAUDE.md</code> di root proyek — ini adalah sumber kebenaran kanonik untuk keputusan arsitektur dan selalu diperbarui mengikuti codebase. Halaman ini merangkumnya dengan konteks tambahan.</span></div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="foot">
      <div class="wrap">
        <h3><span class="lang-en">IDIG Health Tech — Developer Documentation</span><span class="lang-id">IDIG Health Tech — Dokumentasi Developer</span></h3>
        <p>Repositori Digital &amp; Innovation Hub · Departemen Teknologi Kedokteran<br>Fakultas Kedokteran &amp; Kesehatan — Institut Teknologi Sepuluh Nopember (ITS)</p>
        <div class="meta"><span class="lang-en">Internal Dev Docs</span><span class="lang-id">Dokumentasi Internal Dev</span> · Digital Research Lab · FKK — ITS · <a href="/dev/documentations" style="color:var(--cyan2)">/dev/documentations</a></div>
      </div>
    </footer>
  </main>
</div>

<script>
function updateLabels() {
  var isDark = document.documentElement.dataset.theme === 'dark';
  var isEN   = document.documentElement.dataset.lang  === 'en';
  var icon = document.getElementById('theme-icon');
  var labelEN = document.getElementById('theme-label-en');
  var labelID = document.getElementById('theme-label-id');
  if (icon)    icon.textContent    = isDark ? '☀️' : '🌙';
  if (labelEN) labelEN.textContent = isDark ? 'Light Mode' : 'Dark Mode';
  if (labelID) labelID.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
  var mobT = document.getElementById('mob-theme-btn');
  var mobL = document.getElementById('mob-lang-btn');
  if (mobT) mobT.textContent = isDark ? '☀️' : '🌙';
  if (mobL) mobL.textContent = isEN  ? 'ID'  : 'EN';
}
function toggleTheme() {
  var next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
  document.documentElement.dataset.theme = next;
  localStorage.setItem('idig-docs-theme', next);
  updateLabels();
}
function toggleLang() {
  var next = document.documentElement.dataset.lang === 'en' ? 'id' : 'en';
  document.documentElement.dataset.lang = next;
  document.documentElement.lang = next;
  localStorage.setItem('idig-docs-lang', next);
  updateLabels();
}
updateLabels();

// Sidebar scrollspy
var links = [...document.querySelectorAll('.toc a')];
var secs  = links.map(l => document.querySelector(l.getAttribute('href')));
var obs = new IntersectionObserver(es => {
  es.forEach(e => {
    if (e.isIntersecting) {
      var id = '#' + e.target.id;
      links.forEach(l => l.classList.toggle('active', l.getAttribute('href') === id));
    }
  });
}, { rootMargin: '-45% 0px -50% 0px' });
secs.forEach(s => s && obs.observe(s));
</script>
</body>
</html>
