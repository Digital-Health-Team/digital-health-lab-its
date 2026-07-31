<!DOCTYPE html>
<html lang="en" data-theme="dark" data-lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Guide — IDIG Health Tech</title>
<meta name="description" content="User guide for IDIG admin roles — order management, inventory, content, CMS, and data flows.">
<script>(function(){var t=localStorage.getItem('idig-admin-docs-theme')||'dark';var l=localStorage.getItem('idig-admin-docs-lang')||'en';document.documentElement.dataset.theme=t;document.documentElement.dataset.lang=l;document.documentElement.lang=l;})()</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{
  --p950:#031026;--p900:#062E5C;--p800:#0A3D7A;--p700:#00426D;--p600:#0D5A9E;
  --gold:#FFC72C;--gold2:#F59E0B;--gold-soft:#FDE68A;
  --cyan:#22D3EE;--cyan2:#00A8B5;
  --light:#F5F8FC;--slate800:#1E293B;--slate600:#475569;--slate400:#94A3B8;
  --on-dark:#E8EEF6;--muted-dark:#8DA3C0;
  --line-dark:rgba(255,199,44,.12);--line-light:#E2E9F2;
  --disp:'Plus Jakarta Sans',sans-serif;--body:'Inter',sans-serif;--mono:'JetBrains Mono',monospace;
  --maxw:1180px;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--body);background:var(--p950);color:var(--on-dark);line-height:1.65;-webkit-font-smoothing:antialiased;overflow-x:hidden}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}
h1,h2,h3,h4{font-family:var(--disp);line-height:1.15;font-weight:700}
code{font-family:var(--mono);font-size:.87em;background:rgba(255,199,44,.1);border:1px solid rgba(255,199,44,.2);padding:2px 6px;border-radius:5px;color:var(--gold-soft)}
section.light code{background:rgba(245,158,11,.08);border-color:rgba(245,158,11,.2);color:#92400E}
pre{background:rgba(255,255,255,.04);border:1px solid var(--line-dark);border-radius:12px;padding:20px 22px;overflow-x:auto;margin-top:14px}
pre code{background:none;border:none;padding:0;color:var(--gold-soft);font-size:13px;line-height:1.7}
::selection{background:var(--gold);color:var(--p950)}

.honey{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='100' viewBox='0 0 56 100'%3E%3Cpath d='M28 66L0 50V16l28-16 28 16v34zM28 0L0 16l28 16 28-16z' fill='%23FFC72C' fill-opacity='0.04'/%3E%3C/svg%3E");background-size:56px 100px}

/* layout */
.shell{display:grid;grid-template-columns:270px 1fr;max-width:1440px;margin:0 auto}
.sidebar{position:sticky;top:0;height:100vh;background:linear-gradient(180deg,#02101f,#031026);border-right:1px solid var(--line-dark);padding:28px 20px;overflow-y:auto;z-index:40}
.brand{display:flex;align-items:center;gap:11px;padding-bottom:20px;margin-bottom:14px;border-bottom:1px solid var(--line-dark)}
.brand .mark{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,#7C3AED,var(--gold2));display:grid;place-items:center;flex:0 0 auto;box-shadow:0 0 22px rgba(255,199,44,.3)}
.brand .mark svg{width:22px;height:22px}
.brand b{font-family:var(--disp);font-weight:800;font-size:16px;letter-spacing:-.02em;display:block;color:#fff}
.brand span{font-size:11px;color:var(--muted-dark);letter-spacing:.06em;text-transform:uppercase}
.toc a{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:9px;font-size:13.5px;color:var(--muted-dark);font-weight:500;transition:.18s;margin-bottom:2px}
.toc a .n{font-family:var(--mono);font-size:11px;opacity:.5}
.toc a:hover{background:rgba(255,199,44,.07);color:#fff}
.toc a.active{background:linear-gradient(90deg,rgba(124,58,237,.35),rgba(245,158,11,.15));color:#fff}
.toc a.active .n{color:var(--gold);opacity:1}
.sb-foot{margin-top:20px;padding-top:16px;border-top:1px solid var(--line-dark);font-size:11.5px;color:var(--slate400);line-height:1.7}
.sb-controls{display:flex;flex-direction:column;gap:8px;margin-top:16px}
.ctrl-btn{display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:9px;font-size:13px;border:1px solid var(--line-dark);background:rgba(255,255,255,.04);color:var(--muted-dark);cursor:pointer;font-family:var(--body);font-weight:500;transition:.18s;width:100%;text-align:left}
.ctrl-btn:hover{background:rgba(255,199,44,.08);color:#fff;border-color:rgba(255,199,44,.3)}
.mob-ctrl{display:none;gap:6px;margin-left:auto}
.mob-ctrl .ctrl-btn{width:auto;padding:6px 10px;font-size:12px}
@media(max-width:1000px){.mob-ctrl{display:flex}}

.main{min-width:0}
.wrap{max-width:var(--maxw);margin:0 auto;padding:0 40px}
section{padding:72px 0;border-bottom:1px solid var(--line-dark)}
section.light{background:var(--light);color:var(--slate800)}
section.light h2,section.light h3{color:var(--p700)}
section.light .lead{color:var(--slate600)}

/* hero */
.hero{position:relative;min-height:68vh;display:flex;align-items:center;overflow:hidden;background:radial-gradient(1200px 600px at 78% 8%,rgba(124,58,237,.35),transparent 60%),radial-gradient(900px 500px at 10% 100%,rgba(245,158,11,.18),transparent 60%),linear-gradient(160deg,#041428,#031026 55%,#02101f)}
.hero .honey{position:absolute;inset:0;opacity:.6;pointer-events:none}
.ecg{position:absolute;left:0;right:0;top:52%;width:100%;height:180px;opacity:.45;pointer-events:none}
.ecg path{stroke:url(#eg);stroke-width:2.2;fill:none;stroke-linecap:round;stroke-dasharray:2400;stroke-dashoffset:2400;animation:draw 5s ease-in-out infinite}
@keyframes draw{0%{stroke-dashoffset:2400}45%,100%{stroke-dashoffset:0}}
.hero-inner{position:relative;z-index:2;max-width:var(--maxw);margin:0 auto;padding:0 40px;width:100%}
.eyebrow{display:inline-flex;align-items:center;gap:9px;font-family:var(--mono);font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-soft);background:rgba(255,199,44,.08);border:1px solid rgba(255,199,44,.25);padding:7px 14px;border-radius:100px;margin-bottom:26px}
.eyebrow .dot{width:7px;height:7px;border-radius:50%;background:var(--gold);box-shadow:0 0 10px var(--gold);animation:pulse 2s infinite}
@keyframes pulse{50%{opacity:.35}}
.hero h1{font-size:clamp(38px,6vw,80px);font-weight:800;letter-spacing:-.03em;line-height:1.02}
.hero h1 .it{font-style:italic;color:transparent;background:linear-gradient(120deg,#fff 20%,var(--gold-soft) 60%,var(--gold));-webkit-background-clip:text;background-clip:text;text-shadow:0 0 60px rgba(255,199,44,.2)}
.hero .sub{font-size:clamp(16px,2vw,20px);color:var(--muted-dark);max-width:620px;margin:24px 0 32px;line-height:1.6}
.hero .sub b{color:var(--on-dark);font-weight:600}
.chips{display:flex;flex-wrap:wrap;gap:10px}
.chip{font-size:12.5px;font-family:var(--mono);color:var(--gold-soft);border:1px solid rgba(255,199,44,.2);background:rgba(3,16,38,.5);padding:7px 13px;border-radius:8px;backdrop-filter:blur(6px)}
.chip b{color:#fff;font-weight:500}

/* section headers */
.sec-head{margin-bottom:44px;max-width:760px}
.kicker{font-family:var(--mono);font-size:12.5px;letter-spacing:.14em;text-transform:uppercase;color:var(--gold2);font-weight:500;display:flex;align-items:center;gap:12px;margin-bottom:16px}
.kicker::before{content:"";width:30px;height:1px;background:var(--gold2)}
section.light .kicker{color:#92400E}
h2{font-size:clamp(26px,4vw,42px);letter-spacing:-.02em;font-weight:700}
.lead{font-size:17px;color:var(--muted-dark);margin-top:18px;line-height:1.7}

/* grids / cards */
.grid{display:grid;gap:20px}
.g2{grid-template-columns:repeat(2,1fr)}
.g3{grid-template-columns:repeat(3,1fr)}
.g4{grid-template-columns:repeat(4,1fr)}
.card{background:rgba(255,255,255,.03);border:1px solid var(--line-dark);border-radius:18px;padding:26px;transition:.22s}
.card:hover{border-color:rgba(255,199,44,.35);transform:translateY(-3px);box-shadow:0 18px 50px -20px rgba(255,199,44,.2)}
section.light .card{background:#fff;border:1px solid var(--line-light);box-shadow:0 8px 30px -18px rgba(3,16,38,.25)}
section.light .card:hover{border-color:var(--gold2);box-shadow:0 18px 46px -20px rgba(0,66,109,.3)}
.card .ic{width:44px;height:44px;border-radius:12px;display:grid;place-items:center;background:linear-gradient(135deg,rgba(124,58,237,.4),rgba(245,158,11,.3));margin-bottom:16px;color:var(--gold-soft)}
section.light .card .ic{background:linear-gradient(135deg,#FEF3C7,#FDE68A);color:#92400E}
.card .ic svg{width:22px;height:22px}
.card h3{font-size:18px;margin-bottom:8px;font-weight:700}
.card p{font-size:14.5px;color:var(--muted-dark);line-height:1.65}
section.light .card p{color:var(--slate600)}
.card .tag{font-family:var(--mono);font-size:11px;color:var(--gold2);text-transform:uppercase;letter-spacing:.08em}
section.light .card .tag{color:#92400E}

/* role badges */
.role-badge{display:inline-block;font-family:var(--mono);font-size:11px;font-weight:600;padding:4px 10px;border-radius:6px;margin-bottom:14px;text-transform:uppercase;letter-spacing:.06em}
.rb-sa{background:rgba(99,102,241,.15);color:#a5b4fc;border:1px solid rgba(99,102,241,.3)}
.rb-al{background:rgba(245,158,11,.12);color:var(--gold-soft);border:1px solid rgba(245,158,11,.3)}
.rb-ag{background:rgba(34,211,238,.12);color:#67E8F9;border:1px solid rgba(34,211,238,.3)}

/* pipeline stepper */
.pipeline{display:flex;flex-wrap:wrap;gap:0;margin:0 0 32px}
.pipe-step{flex:1;min-width:140px;position:relative;padding:18px 16px 18px 32px;background:rgba(255,255,255,.03);border:1px solid var(--line-dark);border-left:none}
.pipe-step:first-child{border-left:1px solid var(--line-dark);border-radius:12px 0 0 12px;padding-left:16px}
.pipe-step:last-child{border-radius:0 12px 12px 0}
.pipe-step::before{content:"›";position:absolute;left:-12px;top:50%;transform:translateY(-50%);font-size:20px;color:var(--gold2);font-weight:700;z-index:2}
.pipe-step:first-child::before{display:none}
.pipe-step.done{background:rgba(245,158,11,.06);border-color:rgba(245,158,11,.25)}
.pipe-step.block{background:rgba(99,102,241,.06);border-color:rgba(99,102,241,.25)}
.pipe-step .ps-num{font-family:var(--mono);font-size:10px;color:var(--gold2);font-weight:600;margin-bottom:4px;letter-spacing:.08em}
.pipe-step .ps-label{font-size:12.5px;font-weight:700;color:#fff;margin-bottom:4px}
.pipe-step .ps-who{font-size:11px;color:var(--muted-dark)}
section.light .pipe-step{background:#fff;border-color:#E2E9F2;border-left:none}
section.light .pipe-step:first-child{border-left:1px solid #E2E9F2}
section.light .pipe-step .ps-label{color:var(--p700)}
section.light .pipe-step .ps-who{color:var(--slate600)}
section.light .pipe-step.done{background:#FFFBEB;border-color:rgba(245,158,11,.3)}
section.light .pipe-step.block{background:#EFF6FF;border-color:rgba(99,102,241,.3)}

/* workflow */
.flow{position:relative;padding-left:34px}
.flow::before{content:"";position:absolute;left:11px;top:8px;bottom:8px;width:2px;background:linear-gradient(180deg,var(--gold2),transparent)}
.step{position:relative;margin-bottom:14px}
.step::before{content:"";position:absolute;left:-28px;top:6px;width:12px;height:12px;border-radius:50%;background:var(--p950);border:2px solid var(--gold2);box-shadow:0 0 0 4px rgba(245,158,11,.1)}
section.light .step::before{background:#fff}
.step h4{font-size:15px;margin-bottom:3px}
.step p{font-size:14px;color:var(--muted-dark)}
section.light .step p{color:var(--slate600)}
.flow-card{background:rgba(255,255,255,.03);border:1px solid var(--line-dark);border-radius:18px;padding:28px 30px}
section.light .flow-card{background:#fff;border:1px solid var(--line-light)}
.flow-card>h3{display:flex;align-items:center;gap:12px;font-size:19px;margin-bottom:22px}
section.light .flow-card>h3{color:var(--p700)}
.flow-num{font-family:var(--mono);font-size:13px;color:var(--p950);background:var(--gold);width:30px;height:30px;border-radius:9px;display:grid;place-items:center;font-weight:700;flex:0 0 auto}

/* access matrix */
.matrix{width:100%;border-collapse:collapse;font-size:13.5px;margin-top:8px}
.matrix th{font-family:var(--mono);font-size:11px;letter-spacing:.06em;text-transform:uppercase;padding:10px 14px;border-bottom:2px solid var(--line-dark);text-align:left;color:var(--muted-dark)}
.matrix td{padding:10px 14px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.matrix tr:hover td{background:rgba(255,255,255,.02)}
section.light .matrix th{border-bottom-color:#E2E9F2;color:var(--slate600)}
section.light .matrix td{border-bottom-color:#F1F5F9}
section.light .matrix tr:hover td{background:rgba(0,0,0,.02)}
.tick{color:#4ade80;font-size:16px}
.cross{color:#f87171;font-size:16px}

/* tcard */
.tbl{display:grid;grid-template-columns:repeat(auto-fill,minmax(215px,1fr));gap:12px}
.tcard{background:rgba(255,255,255,.03);border:1px solid var(--line-dark);border-radius:12px;padding:15px 17px}
section.light .tcard{background:#fff;border-color:var(--line-light)}
.tcard .tn{font-family:var(--mono);font-size:13.5px;color:var(--gold-soft);font-weight:500;margin-bottom:5px}
section.light .tcard .tn{color:#92400E}
.tcard .td{font-size:12.5px;color:var(--muted-dark);line-height:1.5}
section.light .tcard .td{color:var(--slate600)}

/* callout */
.callout{border-left:3px solid var(--gold2);background:rgba(245,158,11,.06);padding:16px 20px;border-radius:0 12px 12px 0;font-size:14.5px;color:var(--on-dark);margin-top:22px}
section.light .callout{background:rgba(245,158,11,.07);color:var(--slate800)}
.callout b{color:var(--gold2)}
.callout.info{border-color:#818cf8;background:rgba(99,102,241,.07)}
.callout.info b{color:#a5b4fc}
section.light .callout.info{background:rgba(99,102,241,.06)}
.callout.warn{border-color:#f87171;background:rgba(248,113,113,.06)}
.callout.warn b{color:#fca5a5}

/* status pills */
.status-grid{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}
.st-pill{font-family:var(--mono);font-size:11.5px;padding:5px 12px;border-radius:20px;font-weight:600;white-space:nowrap}
.st-a{background:rgba(148,163,184,.12);color:#cbd5e1;border:1px solid rgba(148,163,184,.25)}
.st-b{background:rgba(245,158,11,.12);color:var(--gold-soft);border:1px solid rgba(245,158,11,.3)}
.st-c{background:rgba(99,102,241,.12);color:#a5b4fc;border:1px solid rgba(99,102,241,.3)}
.st-d{background:rgba(74,222,128,.12);color:#86efac;border:1px solid rgba(74,222,128,.3)}
.st-e{background:rgba(248,113,113,.12);color:#fca5a5;border:1px solid rgba(248,113,113,.3)}

/* data flow arrows */
.flow-row{display:flex;align-items:center;flex-wrap:wrap;gap:0;margin:16px 0}
.flow-box{background:rgba(255,255,255,.04);border:1px solid var(--line-dark);border-radius:10px;padding:12px 16px;text-align:center;font-size:13px;font-weight:600;flex:1;min-width:110px}
section.light .flow-box{background:#fff;border-color:#E2E9F2;color:var(--p700)}
.flow-arrow{font-size:18px;color:var(--gold2);padding:0 6px;flex:0 0 auto}
.flow-box.gold{background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.3);color:var(--gold-soft)}
section.light .flow-box.gold{background:#FFFBEB;border-color:rgba(245,158,11,.4);color:#92400E}
.flow-box.purple{background:rgba(99,102,241,.1);border-color:rgba(99,102,241,.3);color:#a5b4fc}
.flow-box.cyan{background:rgba(34,211,238,.1);border-color:rgba(34,211,238,.3);color:#67E8F9}

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
  .pipeline{flex-direction:column}
  .pipe-step{border-left:1px solid var(--line-dark)!important;border-radius:12px!important;padding-left:16px}
  .pipe-step::before{display:none!important}
  .mobile-nav{display:flex;position:sticky;top:0;z-index:50;background:rgba(3,16,38,.92);backdrop-filter:blur(10px);border-bottom:1px solid var(--line-dark);padding:12px 22px;align-items:center;gap:10px}
  .mobile-nav b{font-family:var(--disp);font-weight:800;color:#fff}
}
@media(max-width:600px){.g3,.g4,.tbl{grid-template-columns:1fr}}

/* ---- language ---- */
.lang-id{display:none}
[data-lang="id"] .lang-en{display:none}
[data-lang="id"] .lang-id{display:inline}

/* ---- light mode ---- */
[data-theme="light"] body{background:#F1F5F9;color:#1E293B}
[data-theme="light"] .sidebar{background:#fff;border-right-color:#E2E9F2}
[data-theme="light"] .brand b{color:#1E293B}
[data-theme="light"] .brand span{color:#64748B}
[data-theme="light"] .sb-foot{color:#94A3B8;border-top-color:#E2E9F2}
[data-theme="light"] .toc a{color:#64748B}
[data-theme="light"] .toc a:hover{background:rgba(245,158,11,.08);color:#92400E}
[data-theme="light"] .toc a.active{background:rgba(124,58,237,.07);color:#6D28D9}
[data-theme="light"] .toc a.active .n{color:#D97706;opacity:1}
[data-theme="light"] .ctrl-btn{border-color:#E2E9F2;background:#F8FAFC;color:#475569}
[data-theme="light"] .ctrl-btn:hover{background:rgba(245,158,11,.08);color:#92400E}
[data-theme="light"] section{border-bottom-color:#E2E9F2}
[data-theme="light"] section:not(.light){background:#fff;color:#1E293B}
[data-theme="light"] section:not(.light) h2,[data-theme="light"] section:not(.light) h3{color:#1E293B}
[data-theme="light"] section:not(.light) .kicker{color:#D97706}
[data-theme="light"] section:not(.light) .lead{color:#475569}
[data-theme="light"] section:not(.light) .card{background:#fff;border-color:#E2E9F2;box-shadow:0 8px 30px -18px rgba(3,16,38,.15)}
[data-theme="light"] section:not(.light) .card p{color:#475569}
[data-theme="light"] section:not(.light) .card .ic{background:linear-gradient(135deg,#FEF3C7,#FDE68A);color:#92400E}
[data-theme="light"] section:not(.light) .card .tag{color:#D97706}
[data-theme="light"] section:not(.light) .tcard{background:#F8FAFC;border-color:#E2E9F2}
[data-theme="light"] section:not(.light) .tcard .tn{color:#D97706}
[data-theme="light"] section:not(.light) .tcard .td{color:#475569}
[data-theme="light"] section:not(.light) .flow-card{background:#F8FAFC;border-color:#E2E9F2}
[data-theme="light"] section:not(.light) .flow-card>h3{color:#1E293B}
[data-theme="light"] section:not(.light) .step p{color:#475569}
[data-theme="light"] section:not(.light) .callout{background:rgba(245,158,11,.07);color:#1E293B}
[data-theme="light"] section:not(.light) .callout.info{background:rgba(99,102,241,.06)}
[data-theme="light"] section:not(.light) .callout.warn{background:rgba(248,113,113,.05)}
[data-theme="light"] section:not(.light) .pipe-step{background:#fff;border-color:#E2E9F2;border-left:none}
[data-theme="light"] section:not(.light) .pipe-step:first-child{border-left:1px solid #E2E9F2}
[data-theme="light"] section:not(.light) .pipe-step .ps-label{color:#1E293B}
[data-theme="light"] section:not(.light) .pipe-step.done{background:#FFFBEB;border-color:rgba(245,158,11,.3)}
[data-theme="light"] section:not(.light) .pipe-step.block{background:#EFF6FF;border-color:rgba(99,102,241,.25)}
[data-theme="light"] code{background:rgba(245,158,11,.08);border-color:rgba(245,158,11,.2);color:#92400E}
[data-theme="light"] pre{background:#F8FAFC;border-color:#E2E9F2}
[data-theme="light"] pre code{color:#92400E;background:none;border:none}
[data-theme="light"] .mobile-nav{background:rgba(255,255,255,.95);border-bottom-color:#E2E9F2}
[data-theme="light"] .mobile-nav b{color:#1E293B}
[data-theme="light"] .foot{background:#E2E9F2}
[data-theme="light"] .flow-box{background:#fff;border-color:#E2E9F2;color:#1E293B}
[data-theme="light"] .flow-box.gold{background:#FFFBEB;border-color:rgba(245,158,11,.4);color:#92400E}
[data-theme="light"] .flow-box.purple{background:#EFF6FF;border-color:rgba(99,102,241,.3);color:#6D28D9}
[data-theme="light"] .flow-box.cyan{background:#ECFEFF;border-color:rgba(6,182,212,.3);color:#0369A1}
</style>
</head>
<body>

<div class="mobile-nav">
  <div class="brand" style="border:none;padding:0;margin:0">
    <div class="mark"><svg viewBox="0 0 24 24" fill="none"><path d="M12 2 3 7v10l9 5 9-5V7z" stroke="#fff" stroke-width="1.6"/><path d="M8 12h1l1-2.5 2 5 1-2.5H16" stroke="#FFC72C" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
    <b>IDIG Admin Guide</b>
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
      <div class="mark"><svg viewBox="0 0 24 24" fill="none"><path d="M12 2 3 7v10l9 5 9-5V7z" stroke="#fff" stroke-width="1.6"/><path d="M8 12h1l1-2.5 2 5 1-2.5H16" stroke="#FFC72C" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
      <div><b>IDIG Health Tech</b><span><span class="lang-en">Admin Guide · Internal</span><span class="lang-id">Panduan Admin · Internal</span></span></div>
    </div>
    <nav class="toc">
      <a href="#roles"><span class="n">01</span> <span class="lang-en">Role Overview</span><span class="lang-id">Gambaran Peran</span></a>
      <a href="#orders"><span class="n">02</span> <span class="lang-en">Order Center</span><span class="lang-id">Pusat Pesanan</span></a>
      <a href="#warehouse"><span class="n">03</span> <span class="lang-en">Inventory &amp; Warehouse</span><span class="lang-id">Inventaris &amp; Gudang</span></a>
      <a href="#content"><span class="n">04</span> <span class="lang-en">Content Management</span><span class="lang-id">Manajemen Konten</span></a>
      <a href="#issues"><span class="n">05</span> <span class="lang-en">Issue Reports</span><span class="lang-id">Laporan Masalah</span></a>
      <a href="#cms"><span class="n">06</span> <span class="lang-en">CMS &amp; System</span><span class="lang-id">CMS &amp; Sistem</span></a>
      <a href="#flows"><span class="n">07</span> <span class="lang-en">Data Flow Reference</span><span class="lang-id">Referensi Alur Data</span></a>
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
      <span class="lang-en">Admin User Guide</span><span class="lang-id">Panduan Pengguna Admin</span><br>
      IDIG Health Tech<br>
      FKK — ITS
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main">

    <!-- HERO -->
    <header class="hero" id="top">
      <div class="honey"></div>
      <svg class="ecg" viewBox="0 0 1600 180" preserveAspectRatio="none">
        <defs><linearGradient id="eg" x1="0" x2="1"><stop offset="0" stop-color="#FFC72C" stop-opacity="0"/><stop offset=".5" stop-color="#FFC72C"/><stop offset="1" stop-color="#FFC72C" stop-opacity="0"/></linearGradient></defs>
        <path d="M0,90 L360,90 L390,90 L410,40 L435,140 L460,55 L485,90 L820,90 L850,90 L870,30 L895,150 L920,50 L945,90 L1600,90"/>
      </svg>
      <div class="hero-inner">
        <div class="eyebrow"><span class="dot"></span> <span class="lang-en">ADMIN GUIDE · Internal · IDIG Health Tech</span><span class="lang-id">PANDUAN ADMIN · Internal · IDIG Health Tech</span></div>
        <h1><span class="lang-en">Admin<br><span class="it">User Guide</span></span><span class="lang-id">Panduan<br><span class="it">Pengguna Admin</span></span></h1>
        <p class="sub"><span class="lang-en">Everything an admin needs to <b>manage orders</b>, <b>maintain inventory</b>, <b>curate content</b>, and <b>configure the platform</b> — role by role, step by step.</span><span class="lang-id">Semua yang dibutuhkan admin untuk <b>mengelola pesanan</b>, <b>menjaga inventaris</b>, <b>mengkurasi konten</b>, dan <b>mengkonfigurasi platform</b> — per peran, langkah demi langkah.</span></p>
        <div class="chips">
          <div class="chip"><b>Roles</b> super_admin · admin_lab · admin_gudang</div>
          <div class="chip"><b>Route</b> /admin/*</div>
          <div class="chip"><b>Auth</b> Livewire + MaryUI</div>
        </div>
      </div>
    </header>

    <!-- 01 ROLE OVERVIEW -->
    <section id="roles" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">01 — Role Overview</span><span class="lang-id">01 — Gambaran Peran</span></div>
          <h2><span class="lang-en">Three admin roles — each with a defined scope</span><span class="lang-id">Tiga peran admin — masing-masing dengan lingkup yang jelas</span></h2>
          <p class="lead"><span class="lang-en">Every admin account is assigned one (or more) roles. The sidebar menu, accessible routes, and available actions all change based on your active role.</span><span class="lang-id">Setiap akun admin ditetapkan satu (atau lebih) peran. Menu sidebar, route yang dapat diakses, dan tindakan yang tersedia semuanya berubah berdasarkan peran aktif Anda.</span></p>
        </div>
        <div class="grid g3">
          <div class="card">
            <span class="role-badge rb-sa">super_admin</span>
            <h3><span class="lang-en">Super Administrator</span><span class="lang-id">Super Administrator</span></h3>
            <p><span class="lang-en">Full access to every admin route. Manages users, CMS content, financial reports, and oversees all lab + warehouse operations. Can switch into any role view.</span><span class="lang-id">Akses penuh ke setiap route admin. Mengelola pengguna, konten CMS, laporan keuangan, dan mengawasi semua operasi lab + gudang. Dapat beralih ke tampilan peran mana pun.</span></p>
          </div>
          <div class="card">
            <span class="role-badge rb-al">admin_lab</span>
            <h3><span class="lang-en">Lab Admin</span><span class="lang-id">Admin Lab</span></h3>
            <p><span class="lang-en">Manages the order pipeline from brief review through completion. Curates catalogue items (services, products), approves student projects, manages events, publications, and training workshops.</span><span class="lang-id">Mengelola pipeline pesanan dari review brief hingga selesai. Mengkurasi item katalog (layanan, produk), menyetujui proyek mahasiswa, mengelola event, publikasi, dan workshop pelatihan.</span></p>
          </div>
          <div class="card">
            <span class="role-badge rb-ag">admin_gudang</span>
            <h3><span class="lang-en">Warehouse Admin</span><span class="lang-id">Admin Gudang</span></h3>
            <p><span class="lang-en">Controls raw material inventory. Verifies material availability before orders proceed to production. Manages tool records, restocking, and resolves issue reports.</span><span class="lang-id">Mengontrol inventaris bahan baku. Memverifikasi ketersediaan material sebelum pesanan masuk ke produksi. Mengelola catatan alat, restocking, dan menyelesaikan laporan masalah.</span></p>
          </div>
        </div>

        <div style="margin-top:32px;overflow-x:auto">
          <table class="matrix">
            <thead>
              <tr>
                <th><span class="lang-en">Feature / Route</span><span class="lang-id">Fitur / Route</span></th>
                <th>super_admin</th>
                <th>admin_lab</th>
                <th>admin_gudang</th>
              </tr>
            </thead>
            <tbody>
              <tr><td><span class="lang-en">Dashboard</span><span class="lang-id">Dashboard</span></td><td class="tick">✓</td><td class="tick">✓</td><td><span style="color:var(--muted-dark);font-size:12px"><span class="lang-en">Gudang view</span><span class="lang-id">Tampilan gudang</span></span></td></tr>
              <tr><td>Order Center <code>/admin/order-center</code></td><td class="tick">✓</td><td class="tick">✓</td><td class="cross">—</td></tr>
              <tr><td><span class="lang-en">Incoming Orders</span><span class="lang-id">Pesanan Masuk</span> <code>/gudang/orders</code></td><td class="tick">✓</td><td class="cross">—</td><td class="tick">✓</td></tr>
              <tr><td>Lab Services, Products</td><td class="tick">✓</td><td class="tick">✓</td><td class="cross">—</td></tr>
              <tr><td>Events, Publications, Trainings</td><td class="tick">✓</td><td class="tick">✓</td><td class="cross">—</td></tr>
              <tr><td>Open Source Projects</td><td class="tick">✓</td><td class="tick">✓</td><td class="cross">—</td></tr>
              <tr><td><span class="lang-en">Inventory</span><span class="lang-id">Inventaris</span> <code>/admin/inventory</code></td><td class="tick">✓</td><td class="cross">—</td><td class="tick">✓</td></tr>
              <tr><td>Tools <code>/admin/tools</code></td><td class="tick">✓</td><td class="cross">—</td><td class="tick">✓</td></tr>
              <tr><td>Issue Reports</td><td class="tick">✓</td><td class="tick">✓</td><td class="tick">✓</td></tr>
              <tr><td>User Management</td><td class="tick">✓</td><td class="cross">—</td><td class="cross">—</td></tr>
              <tr><td>CMS / Landing Content</td><td class="tick">✓</td><td class="cross">—</td><td class="cross">—</td></tr>
            </tbody>
          </table>
        </div>

        <div class="callout info" style="margin-top:24px"><b><span class="lang-en">Role Switching:</span><span class="lang-id">Pergantian Peran:</span></b> <span class="lang-en">If your account has multiple roles, use the <b>Switch Mode</b> panel at the bottom of the sidebar to change your active role without logging out.</span><span class="lang-id">Jika akun Anda memiliki beberapa peran, gunakan panel <b>Switch Mode</b> di bagian bawah sidebar untuk mengubah peran aktif tanpa keluar.</span></div>
      </div>
    </section>

    <!-- 02 ORDER CENTER -->
    <section id="orders">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">02 — Order Center</span><span class="lang-id">02 — Pusat Pesanan</span></div>
          <h2><span class="lang-en">Nine-stage order pipeline — from brief to completion</span><span class="lang-id">Pipeline pesanan sembilan tahap — dari brief hingga selesai</span></h2>
          <p class="lead"><span class="lang-en">Every service order follows a strict linear pipeline. Each stage has a clear action for the responsible party. The order cannot skip stages.</span><span class="lang-id">Setiap pesanan layanan mengikuti pipeline linier yang ketat. Setiap tahap memiliki tindakan yang jelas untuk pihak yang bertanggung jawab. Pesanan tidak dapat melewati tahap.</span></p>
        </div>

        <div class="pipeline">
          <div class="pipe-step">
            <div class="ps-num">01</div>
            <div class="ps-label">review_brief</div>
            <div class="ps-who">admin_lab</div>
          </div>
          <div class="pipe-step done">
            <div class="ps-num">02</div>
            <div class="ps-label">check_material</div>
            <div class="ps-who">admin_gudang</div>
          </div>
          <div class="pipe-step">
            <div class="ps-num">03</div>
            <div class="ps-label">slicing</div>
            <div class="ps-who">admin_lab</div>
          </div>
          <div class="pipe-step">
            <div class="ps-num">04</div>
            <div class="ps-label">set_price</div>
            <div class="ps-who">admin_lab</div>
          </div>
          <div class="pipe-step done">
            <div class="ps-num">05</div>
            <div class="ps-label">awaiting_dp</div>
            <div class="ps-who"><span class="lang-en">client pays</span><span class="lang-id">klien bayar</span></div>
          </div>
          <div class="pipe-step block">
            <div class="ps-num">06</div>
            <div class="ps-label">printing</div>
            <div class="ps-who">admin_lab</div>
          </div>
          <div class="pipe-step block">
            <div class="ps-num">07</div>
            <div class="ps-label">finishing</div>
            <div class="ps-who">admin_lab</div>
          </div>
          <div class="pipe-step done">
            <div class="ps-num">08</div>
            <div class="ps-label">final_payment</div>
            <div class="ps-who"><span class="lang-en">client pays</span><span class="lang-id">klien bayar</span></div>
          </div>
          <div class="pipe-step">
            <div class="ps-num">09</div>
            <div class="ps-label">completed</div>
            <div class="ps-who">admin_lab</div>
          </div>
        </div>
        <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:28px;font-size:12px">
          <span><span class="pipe-step done" style="display:inline-block;padding:3px 10px;border-radius:6px;font-size:11px">gold</span> = <span class="lang-en">client action required</span><span class="lang-id">tindakan klien diperlukan</span></span>
          <span><span class="pipe-step block" style="display:inline-block;padding:3px 10px;border-radius:6px;font-size:11px">indigo</span> = <span class="lang-en">production — cancellation locked</span><span class="lang-id">produksi — pembatalan terkunci</span></span>
        </div>

        <div class="grid g2" style="align-items:start">
          <div class="flow-card">
            <h3><span class="flow-num">①</span> <span class="lang-en">Opening an Order</span><span class="lang-id">Membuka Pesanan</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Go to Order Center</span><span class="lang-id">Buka Order Center</span></h4><p><span class="lang-en">Click <b>Order Center</b> in the sidebar. Use the search bar and status filter to find the order.</span><span class="lang-id">Klik <b>Order Center</b> di sidebar. Gunakan search bar dan filter status untuk menemukan pesanan.</span></p></div>
              <div class="step"><h4><span class="lang-en">Open the detail page</span><span class="lang-id">Buka halaman detail</span></h4><p><span class="lang-en">Click the row or the detail icon. The order detail page has three panels: Order Info, Chat, and Progress Timeline.</span><span class="lang-id">Klik baris atau ikon detail. Halaman detail pesanan memiliki tiga panel: Info Pesanan, Chat, dan Timeline Progress.</span></p></div>
              <div class="step"><h4><span class="lang-en">Review the brief</span><span class="lang-id">Tinjau brief</span></h4><p><span class="lang-en">Read the client's file requirements and notes. Use the Chat panel to ask clarifying questions before advancing.</span><span class="lang-id">Baca persyaratan file dan catatan klien. Gunakan panel Chat untuk mengajukan pertanyaan klarifikasi sebelum melanjutkan.</span></p></div>
            </div>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">②</span> <span class="lang-en">Slicer Calculation</span><span class="lang-id">Kalkulasi Slicer</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Run the slicer app</span><span class="lang-id">Jalankan app slicer</span></h4><p><span class="lang-en">Slice the client's 3D file. Note the output weight (grams) and estimated print time (minutes).</span><span class="lang-id">Slice file 3D klien. Catat berat output (gram) dan estimasi waktu cetak (menit).</span></p></div>
              <div class="step"><h4><span class="lang-en">Enter values in the form</span><span class="lang-id">Masukkan nilai di form</span></h4><p><span class="lang-en">On the order detail page, fill in <b>Slicer Weight (g)</b> and <b>Print Time (min)</b>. The system auto-calculates: <code>price = grams × service.base_price</code>.</span><span class="lang-id">Di halaman detail pesanan, isi <b>Berat Slicer (g)</b> dan <b>Waktu Cetak (min)</b>. Sistem menghitung otomatis: <code>harga = gram × service.base_price</code>.</span></p></div>
              <div class="step"><h4><span class="lang-en">Issue the invoice</span><span class="lang-id">Terbitkan invoice</span></h4><p><span class="lang-en">Confirm the final price. The system sends a notification to the client to pay the 30% down-payment.</span><span class="lang-id">Konfirmasi harga akhir. Sistem mengirim notifikasi ke klien untuk membayar uang muka 30%.</span></p></div>
            </div>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">③</span> <span class="lang-en">Payment Verification</span><span class="lang-id">Verifikasi Pembayaran</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Client uploads proof</span><span class="lang-id">Klien mengunggah bukti</span></h4><p><span class="lang-en">The client uploads a payment screenshot. A notification appears in the order detail panel.</span><span class="lang-id">Klien mengunggah screenshot pembayaran. Notifikasi muncul di panel detail pesanan.</span></p></div>
              <div class="step"><h4><span class="lang-en">Verify or reject</span><span class="lang-id">Verifikasi atau tolak</span></h4><p><span class="lang-en">Click <b>Verify Payment</b> to confirm receipt and advance the order, or <b>Reject</b> with a reason note if the proof is invalid.</span><span class="lang-id">Klik <b>Verifikasi Pembayaran</b> untuk mengkonfirmasi penerimaan dan melanjutkan pesanan, atau <b>Tolak</b> dengan catatan alasan jika bukti tidak valid.</span></p></div>
            </div>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">④</span> <span class="lang-en">Progress Updates</span><span class="lang-id">Pembaruan Progress</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Add a progress entry</span><span class="lang-id">Tambah entri progress</span></h4><p><span class="lang-en">In the Progress Timeline panel, click <b>Add Update</b>. Enter a status label (e.g. "Printing 50%"), optional note, and attach a photo of the print in progress.</span><span class="lang-id">Di panel Timeline Progress, klik <b>Tambah Pembaruan</b>. Masukkan label status (mis. "Printing 50%"), catatan opsional, dan lampirkan foto proses cetak.</span></p></div>
              <div class="step"><h4><span class="lang-en">Mark as finished</span><span class="lang-id">Tandai sebagai selesai</span></h4><p><span class="lang-en">When production is done, advance to <b>final_payment</b>. The client is notified to pay the remaining balance. After their payment is verified, mark <b>completed</b>.</span><span class="lang-id">Saat produksi selesai, lanjutkan ke <b>final_payment</b>. Klien diberitahu untuk membayar sisa tagihan. Setelah pembayaran diverifikasi, tandai <b>completed</b>.</span></p></div>
            </div>
          </div>
        </div>

        <div class="callout" style="margin-top:8px"><b><span class="lang-en">Material Gate:</span><span class="lang-id">Gerbang Material:</span></b> <span class="lang-en">An order cannot advance past <code>check_material</code> until <b>admin_gudang</b> verifies that sufficient raw material stock is available. If stock is insufficient, the order is flagged and admin_lab is notified.</span><span class="lang-id">Pesanan tidak dapat melewati <code>check_material</code> sampai <b>admin_gudang</b> memverifikasi bahwa stok bahan baku yang cukup tersedia. Jika stok tidak mencukupi, pesanan diberi tanda dan admin_lab diberitahu.</span></div>
        <div class="callout warn" style="margin-top:12px"><b><span class="lang-en">Cancellation Lock:</span><span class="lang-id">Kunci Pembatalan:</span></b> <span class="lang-en">Orders at <code>printing</code> stage or beyond <b>cannot be cancelled</b>. Ensure the brief and material are confirmed before advancing past <code>awaiting_dp</code>.</span><span class="lang-id">Pesanan di tahap <code>printing</code> atau setelahnya <b>tidak dapat dibatalkan</b>. Pastikan brief dan material dikonfirmasi sebelum melewati <code>awaiting_dp</code>.</span></div>
      </div>
    </section>

    <!-- 03 INVENTORY & WAREHOUSE -->
    <section id="warehouse" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">03 — Inventory &amp; Warehouse</span><span class="lang-id">03 — Inventaris &amp; Gudang</span></div>
          <h2><span class="lang-en">Raw materials, tools, and the material gate</span><span class="lang-id">Bahan baku, alat, dan gerbang material</span></h2>
          <p class="lead"><span class="lang-en">admin_gudang is the gatekeeper for production. No order can enter printing until warehouse confirms material availability.</span><span class="lang-id">admin_gudang adalah penjaga gerbang produksi. Tidak ada pesanan yang dapat masuk ke printing sampai gudang mengkonfirmasi ketersediaan material.</span></p>
        </div>

        <div class="grid g2" style="margin-bottom:32px">
          <div class="flow-card">
            <h3><span class="flow-num">↑</span> <span class="lang-en">Restocking Materials</span><span class="lang-id">Restock Material</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Go to Inventory → Raw Materials</span><span class="lang-id">Buka Inventaris → Bahan Baku</span></h4><p><span class="lang-en">Click <b>Inventory</b> in the sidebar. Switch to the <b>Raw Materials</b> tab.</span><span class="lang-id">Klik <b>Inventaris</b> di sidebar. Beralih ke tab <b>Bahan Baku</b>.</span></p></div>
              <div class="step"><h4><span class="lang-en">Select material → Add Restock</span><span class="lang-id">Pilih material → Tambah Restock</span></h4><p><span class="lang-en">Click the material row, then click <b>Add Restock</b>. Enter the quantity (grams), unit cost, and upload the purchase receipt as proof.</span><span class="lang-id">Klik baris material, lalu klik <b>Tambah Restock</b>. Masukkan kuantitas (gram), biaya satuan, dan unggah struk pembelian sebagai bukti.</span></p></div>
              <div class="step"><h4><span class="lang-en">Stock is updated instantly</span><span class="lang-id">Stok diperbarui seketika</span></h4><p><span class="lang-en">The <code>current_stock</code> field increments and a <code>RawMaterialMovement</code> (in) record is created for audit purposes.</span><span class="lang-id">Field <code>current_stock</code> bertambah dan catatan <code>RawMaterialMovement</code> (masuk) dibuat untuk keperluan audit.</span></p></div>
            </div>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">✓</span> <span class="lang-en">Material Verification (Gate)</span><span class="lang-id">Verifikasi Material (Gerbang)</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Open Incoming Orders</span><span class="lang-id">Buka Pesanan Masuk</span></h4><p><span class="lang-en">At <code>/gudang/orders</code>, orders at <code>check_material</code> stage appear in the queue. Review the required material type and quantity.</span><span class="lang-id">Di <code>/gudang/orders</code>, pesanan di tahap <code>check_material</code> muncul dalam antrian. Tinjau jenis dan kuantitas material yang diperlukan.</span></p></div>
              <div class="step"><h4><span class="lang-en">Verify or Flag</span><span class="lang-id">Verifikasi atau Tandai</span></h4><p><span class="lang-en">If stock is sufficient → click <b>Verify Material</b>. The order advances to <code>slicing</code>. If stock is low → click <b>Flag Issue</b> to notify admin_lab and pause the order.</span><span class="lang-id">Jika stok mencukupi → klik <b>Verifikasi Material</b>. Pesanan berlanjut ke <code>slicing</code>. Jika stok rendah → klik <b>Tandai Masalah</b> untuk memberitahu admin_lab dan menghentikan sementara pesanan.</span></p></div>
            </div>
          </div>
        </div>

        <div class="grid g3">
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg></div>
            <span class="tag"><span class="lang-en">Master Data</span><span class="lang-id">Data Master</span></span>
            <h3 style="margin-top:8px"><span class="lang-en">Lookup Tables</span><span class="lang-id">Tabel Lookup</span></h3>
            <p><span class="lang-en">Before adding materials, create the master records under the <b>Labs</b>, <b>Categories</b>, <b>Brands</b>, and <b>Colors</b> tabs in Inventory. Materials reference these records.</span><span class="lang-id">Sebelum menambahkan material, buat catatan master di tab <b>Lab</b>, <b>Kategori</b>, <b>Merek</b>, dan <b>Warna</b> di Inventaris. Material mereferensikan catatan ini.</span></p>
          </div>
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <span class="tag">Tools</span>
            <h3 style="margin-top:8px"><span class="lang-en">Tool Management</span><span class="lang-id">Manajemen Alat</span></h3>
            <p><span class="lang-en">Each tool has a unique QR code. Go to <b>Tools</b> in the sidebar to add/edit. Click <b>Print Label</b> to print a thermal label for physical tagging. Scan <code>/scan/alat/{code}</code> to view tool details.</span><span class="lang-id">Setiap alat memiliki kode QR unik. Buka <b>Alat</b> di sidebar untuk menambah/mengedit. Klik <b>Cetak Label</b> untuk mencetak label termal untuk penandaan fisik. Scan <code>/scan/alat/{code}</code> untuk melihat detail alat.</span></p>
          </div>
          <div class="card">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <span class="tag"><span class="lang-en">Deductions</span><span class="lang-id">Pengurangan</span></span>
            <h3 style="margin-top:8px"><span class="lang-en">Material Deduction</span><span class="lang-id">Pengurangan Material</span></h3>
            <p><span class="lang-en">When admin_lab clicks <b>Deduct Material</b> on an active order, the system automatically decrements <code>raw_materials.current_stock</code> and creates a movement record (out) tied to that booking ID.</span><span class="lang-id">Saat admin_lab mengklik <b>Kurangi Material</b> pada pesanan aktif, sistem secara otomatis mengurangi <code>raw_materials.current_stock</code> dan membuat catatan pergerakan (keluar) yang terkait dengan booking ID tersebut.</span></p>
          </div>
        </div>

        <div class="callout" style="margin-top:24px"><b><span class="lang-en">Low Stock Alert:</span><span class="lang-id">Peringatan Stok Rendah:</span></b> <span class="lang-en">The admin dashboard shows a <b>Low Stock Alert</b> counter for materials with stock ≤ 100 grams. Restock before the next order is verified to avoid blocking the pipeline.</span><span class="lang-id">Dashboard admin menampilkan penghitung <b>Peringatan Stok Rendah</b> untuk material dengan stok ≤ 100 gram. Lakukan restock sebelum pesanan berikutnya diverifikasi untuk menghindari pemblokiran pipeline.</span></div>
      </div>
    </section>

    <!-- 04 CONTENT MANAGEMENT -->
    <section id="content">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">04 — Content Management</span><span class="lang-id">04 — Manajemen Konten</span></div>
          <h2><span class="lang-en">Publications, projects, events, and training</span><span class="lang-id">Publikasi, proyek, event, dan pelatihan</span></h2>
          <p class="lead"><span class="lang-en">admin_lab manages all public-facing content. Open-source projects submitted by students require explicit admin approval before appearing in the catalogue.</span><span class="lang-id">admin_lab mengelola semua konten yang ditampilkan ke publik. Proyek open-source yang diajukan mahasiswa memerlukan persetujuan admin eksplisit sebelum muncul di katalog.</span></p>
        </div>
        <div class="grid g2">
          <div class="flow-card">
            <h3><span class="flow-num">📄</span> <span class="lang-en">Open Source Projects</span><span class="lang-id">Proyek Open Source</span></h3>
            <p style="font-size:14px;color:var(--muted-dark);margin-bottom:16px"><span class="lang-en">Students submit projects via the public portal. These appear in the admin panel with <code>status=pending</code> until reviewed.</span><span class="lang-id">Mahasiswa mengajukan proyek melalui portal publik. Proyek ini muncul di panel admin dengan <code>status=pending</code> sampai ditinjau.</span></p>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Go to Open Source Projects</span><span class="lang-id">Buka Proyek Open Source</span></h4><p><span class="lang-en">The list shows pending projects with a yellow badge. Click a project to view its files, description, and 3D preview.</span><span class="lang-id">Daftar menampilkan proyek tertunda dengan lencana kuning. Klik proyek untuk melihat file, deskripsi, dan pratinjau 3D.</span></p></div>
              <div class="step"><h4><span class="lang-en">Approve or Reject</span><span class="lang-id">Setujui atau Tolak</span></h4><p><span class="lang-en">Click <b>Approve</b> to make the project public, or <b>Reject</b> and provide a reason note (sent to the student). Approved projects appear immediately in the public catalogue.</span><span class="lang-id">Klik <b>Setujui</b> untuk mempublikasikan proyek, atau <b>Tolak</b> dan berikan catatan alasan (dikirim ke mahasiswa). Proyek yang disetujui langsung muncul di katalog publik.</span></p></div>
            </div>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">📅</span> <span class="lang-en">Events &amp; Teams</span><span class="lang-id">Event &amp; Tim</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Create the event</span><span class="lang-id">Buat event</span></h4><p><span class="lang-en">Go to <b>Events &amp; Exhibitions</b>. Click <b>Create</b>. Set the event name, year, and theme. Toggle <b>Has Teams</b> if participants compete in groups.</span><span class="lang-id">Buka <b>Event &amp; Pameran</b>. Klik <b>Buat</b>. Atur nama event, tahun, dan tema. Toggle <b>Has Teams</b> jika peserta berkompetisi dalam kelompok.</span></p></div>
              <div class="step"><h4><span class="lang-en">Manage teams</span><span class="lang-id">Kelola tim</span></h4><p><span class="lang-en">Open an event → click a team to see its members and submitted projects. You can approve or reject projects from the team detail page.</span><span class="lang-id">Buka event → klik tim untuk melihat anggota dan proyek yang diajukan. Anda dapat menyetujui atau menolak proyek dari halaman detail tim.</span></p></div>
            </div>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">📚</span> Publications</h3>
            <p style="font-size:14px;color:var(--muted-dark);margin-bottom:16px"><span class="lang-en">Publications are created and edited directly by admin_lab — no approval workflow. They appear on the public Publications page immediately on save.</span><span class="lang-id">Publikasi dibuat dan diedit langsung oleh admin_lab — tidak ada alur persetujuan. Publikasi muncul di halaman Publikasi publik segera setelah disimpan.</span></p>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Fill publication details</span><span class="lang-id">Isi detail publikasi</span></h4><p><span class="lang-en">Go to <b>Publications</b> → <b>Create</b>. Fill in title, author, journal, DOI, abstract. Upload thumbnail (max 4 MB) and PDF (max 50 MB).</span><span class="lang-id">Buka <b>Publikasi</b> → <b>Buat</b>. Isi judul, penulis, jurnal, DOI, abstrak. Unggah thumbnail (maks 4 MB) dan PDF (maks 50 MB).</span></p></div>
              <div class="step"><h4><span class="lang-en">Toggle free access</span><span class="lang-id">Toggle akses gratis</span></h4><p><span class="lang-en">Check <b>Free Access</b> to allow guests to download the PDF without logging in. Uncheck to require authentication.</span><span class="lang-id">Centang <b>Akses Gratis</b> untuk memungkinkan tamu mengunduh PDF tanpa login. Hapus centang untuk memerlukan autentikasi.</span></p></div>
            </div>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">🎓</span> <span class="lang-en">Training Workshops</span><span class="lang-id">Workshop Pelatihan</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Create a training session</span><span class="lang-id">Buat sesi pelatihan</span></h4><p><span class="lang-en">Go to <b>Training Workshops</b> → <b>Create</b>. Set the title, date, price, and maximum participant count.</span><span class="lang-id">Buka <b>Workshop Pelatihan</b> → <b>Buat</b>. Atur judul, tanggal, harga, dan jumlah peserta maksimum.</span></p></div>
              <div class="step"><h4><span class="lang-en">Manage registrations</span><span class="lang-id">Kelola pendaftaran</span></h4><p><span class="lang-en">Open the training detail page to see all registrants. Verify payment proofs submitted by participants to confirm their seats.</span><span class="lang-id">Buka halaman detail pelatihan untuk melihat semua pendaftar. Verifikasi bukti pembayaran yang dikirimkan peserta untuk mengkonfirmasi kursi mereka.</span></p></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 05 ISSUE REPORTS -->
    <section id="issues" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">05 — Issue Reports</span><span class="lang-id">05 — Laporan Masalah</span></div>
          <h2><span class="lang-en">Report and resolve equipment &amp; material problems</span><span class="lang-id">Laporkan dan selesaikan masalah peralatan &amp; material</span></h2>
          <p class="lead"><span class="lang-en">Any admin role can create an issue report. Only admin_gudang and super_admin can change the status to Resolved or Closed.</span><span class="lang-id">Peran admin mana pun dapat membuat laporan masalah. Hanya admin_gudang dan super_admin yang dapat mengubah status menjadi Resolved atau Closed.</span></p>
        </div>

        <div class="grid g2" style="margin-bottom:28px">
          <div>
            <h3 style="margin-bottom:16px;font-size:18px"><span class="lang-en">Status Flow</span><span class="lang-id">Alur Status</span></h3>
            <div class="flow-row">
              <div class="flow-box gold">Open</div>
              <div class="flow-arrow">→</div>
              <div class="flow-box purple">In Progress</div>
              <div class="flow-arrow">→</div>
              <div class="flow-box gold">Resolved</div>
              <div class="flow-arrow">→</div>
              <div class="flow-box">Closed</div>
            </div>
            <div class="status-grid" style="margin-top:20px">
              <span class="st-pill st-b">Open</span>
              <span class="st-pill st-c">In Progress</span>
              <span class="st-pill st-d">Resolved</span>
              <span class="st-pill st-a">Closed</span>
            </div>
          </div>
          <div class="flow-card">
            <h3><span class="flow-num">!</span> <span class="lang-en">Creating a Report</span><span class="lang-id">Membuat Laporan</span></h3>
            <div class="flow">
              <div class="step"><h4><span class="lang-en">Go to Issue Reports</span><span class="lang-id">Buka Laporan Masalah</span></h4><p><span class="lang-en">Click <b>Issue Reports</b> in the sidebar. All roles can access this page.</span><span class="lang-id">Klik <b>Laporan Masalah</b> di sidebar. Semua peran dapat mengakses halaman ini.</span></p></div>
              <div class="step"><h4><span class="lang-en">Click Create → Fill details</span><span class="lang-id">Klik Buat → Isi detail</span></h4><p><span class="lang-en">Select the report type (Tool / Raw Material / Inventory), describe the issue, and optionally attach up to 10 photos.</span><span class="lang-id">Pilih jenis laporan (Alat / Bahan Baku / Inventaris), jelaskan masalahnya, dan opsional lampirkan hingga 10 foto.</span></p></div>
              <div class="step"><h4><span class="lang-en">Warehouse resolves it</span><span class="lang-id">Gudang menyelesaikannya</span></h4><p><span class="lang-en">admin_gudang or super_admin opens the report, takes action, then updates the status to <b>Resolved</b> with a resolution note.</span><span class="lang-id">admin_gudang atau super_admin membuka laporan, mengambil tindakan, lalu memperbarui status menjadi <b>Resolved</b> dengan catatan penyelesaian.</span></p></div>
            </div>
          </div>
        </div>

        <div class="callout info"><b>Visibility:</b> <span class="lang-en">admin_lab can only see reports they created. admin_gudang and super_admin see all reports across all reporters.</span><span class="lang-id">admin_lab hanya dapat melihat laporan yang mereka buat. admin_gudang dan super_admin melihat semua laporan dari semua pelapor.</span></div>
      </div>
    </section>

    <!-- 06 CMS & SYSTEM -->
    <section id="cms">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">06 — CMS &amp; System</span><span class="lang-id">06 — CMS &amp; Sistem</span> <span style="font-size:11px;background:rgba(99,102,241,.15);color:#a5b4fc;padding:3px 8px;border-radius:5px;font-family:var(--mono)">super_admin only</span></div>
          <h2><span class="lang-en">Landing page content, structure, and user accounts</span><span class="lang-id">Konten landing page, struktur, dan akun pengguna</span></h2>
          <p class="lead"><span class="lang-en">The entire public landing page is driven by a key-value CMS. Changes take effect immediately — no code change or redeploy needed.</span><span class="lang-id">Seluruh landing page publik dikendalikan oleh CMS key-value. Perubahan langsung berlaku — tidak perlu perubahan kode atau redeploy.</span></p>
        </div>

        <div class="grid g3" style="margin-bottom:28px">
          <div class="card">
            <span class="tag">CMS</span>
            <h3 style="margin-top:8px"><span class="lang-en">Landing Content</span><span class="lang-id">Konten Landing</span></h3>
            <p><span class="lang-en">At <code>/admin/cms/landing-content</code> edit each landing page section visually: Hero, About, Services, Collaboration, Wisdom (quote), Contact, Footer. Changes save instantly.</span><span class="lang-id">Di <code>/admin/cms/landing-content</code> edit setiap seksi landing page secara visual: Hero, About, Services, Collaboration, Wisdom (kutipan), Contact, Footer. Perubahan disimpan seketika.</span></p>
          </div>
          <div class="card">
            <span class="tag">CMS</span>
            <h3 style="margin-top:8px"><span class="lang-en">Page Sections</span><span class="lang-id">Seksi Halaman</span></h3>
            <p><span class="lang-en">At <code>/admin/cms/page-sections</code> directly edit the raw key-value records. Each row is a unique <code>(page_name, section_key)</code> pair. Advanced use for structured JSON content (capability cards, service cards, etc.).</span><span class="lang-id">Di <code>/admin/cms/page-sections</code> edit langsung catatan key-value mentah. Setiap baris adalah pasangan unik <code>(page_name, section_key)</code>. Penggunaan lanjutan untuk konten JSON terstruktur (kartu kemampuan, kartu layanan, dll.).</span></p>
          </div>
          <div class="card">
            <span class="tag">CMS</span>
            <h3 style="margin-top:8px"><span class="lang-en">Lab Structure &amp; Team</span><span class="lang-id">Struktur Lab &amp; Tim</span></h3>
            <p><span class="lang-en">Manage the organisation chart (<code>structural_members</code>) and the team section shown on the landing page (<code>lab_team_sections</code> + <code>lab_team_persons</code>). Drag to reorder by display_order.</span><span class="lang-id">Kelola bagan organisasi (<code>structural_members</code>) dan seksi tim yang ditampilkan di landing page (<code>lab_team_sections</code> + <code>lab_team_persons</code>). Seret untuk mengubah urutan berdasarkan display_order.</span></p>
          </div>
        </div>

        <div class="flow-card">
          <h3><span class="flow-num">👤</span> <span class="lang-en">User Management</span><span class="lang-id">Manajemen Pengguna</span></h3>
          <p style="font-size:14px;color:var(--muted-dark);margin-bottom:16px"><span class="lang-en">At <code>/admin/users</code> you can view all registered users, assign or remove roles, and toggle account status. Role changes take effect on the user's next page load.</span><span class="lang-id">Di <code>/admin/users</code> Anda dapat melihat semua pengguna terdaftar, menetapkan atau menghapus peran, dan mengubah status akun. Perubahan peran berlaku pada pemuatan halaman berikutnya oleh pengguna.</span></p>
          <div class="callout warn" style="margin-top:0"><b><span class="lang-en">Be careful:</span><span class="lang-id">Hati-hati:</span></b> <span class="lang-en">Removing a user's only role does not delete the account — it becomes role-less. Assign at least one role before removing another.</span><span class="lang-id">Menghapus satu-satunya peran pengguna tidak menghapus akun — akun menjadi tanpa peran. Tetapkan setidaknya satu peran sebelum menghapus yang lain.</span></div>
        </div>
      </div>
    </section>

    <!-- 07 DATA FLOW REFERENCE -->
    <section id="flows" class="light">
      <div class="wrap">
        <div class="sec-head">
          <div class="kicker"><span class="lang-en">07 — Data Flow Reference</span><span class="lang-id">07 — Referensi Alur Data</span></div>
          <h2><span class="lang-en">How data moves through the system</span><span class="lang-id">Bagaimana data bergerak melalui sistem</span></h2>
          <p class="lead"><span class="lang-en">Two end-to-end flows showing every role's touchpoint and where data is written or read.</span><span class="lang-id">Dua alur end-to-end yang menunjukkan setiap titik sentuh peran dan di mana data ditulis atau dibaca.</span></p>
        </div>

        <div style="margin-bottom:40px">
          <h3 style="margin-bottom:20px;font-size:20px"><span class="lang-en">Flow 1 — Service Order to Completion</span><span class="lang-id">Alur 1 — Pesanan Layanan hingga Selesai</span></h3>
          <div class="flow-row">
            <div class="flow-box"><span class="lang-en">Client submits order</span><span class="lang-id">Klien ajukan pesanan</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box gold"><span class="lang-en">admin_lab reviews brief</span><span class="lang-id">admin_lab tinjau brief</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box cyan"><span class="lang-en">admin_gudang verifies material</span><span class="lang-id">admin_gudang verifikasi material</span></div>
          </div>
          <div class="flow-row">
            <div class="flow-box gold"><span class="lang-en">admin_lab slices &amp; sets price</span><span class="lang-id">admin_lab slice &amp; tetapkan harga</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box"><span class="lang-en">Client pays 30% DP</span><span class="lang-id">Klien bayar DP 30%</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box gold"><span class="lang-en">admin_lab verifies DP</span><span class="lang-id">admin_lab verifikasi DP</span></div>
          </div>
          <div class="flow-row">
            <div class="flow-box purple"><span class="lang-en">Printing begins (locked)</span><span class="lang-id">Printing dimulai (terkunci)</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box gold"><span class="lang-en">admin_lab deducts material</span><span class="lang-id">admin_lab kurangi material</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box"><span class="lang-en">Progress updates added</span><span class="lang-id">Pembaruan progress ditambahkan</span></div>
          </div>
          <div class="flow-row">
            <div class="flow-box"><span class="lang-en">Finishing complete</span><span class="lang-id">Finishing selesai</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box"><span class="lang-en">Client pays final balance</span><span class="lang-id">Klien bayar sisa tagihan</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box gold"><span class="lang-en">Order marked completed ✓</span><span class="lang-id">Pesanan ditandai selesai ✓</span></div>
          </div>
        </div>

        <div>
          <h3 style="margin-bottom:20px;font-size:20px"><span class="lang-en">Flow 2 — Student Project Submission</span><span class="lang-id">Alur 2 — Pengajuan Proyek Mahasiswa</span></h3>
          <div class="flow-row">
            <div class="flow-box"><span class="lang-en">Student fills metadata</span><span class="lang-id">Mahasiswa isi metadata</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box"><span class="lang-en">Uploads thumbnail</span><span class="lang-id">Unggah thumbnail</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box"><span class="lang-en">Uploads 3D/source files to S3</span><span class="lang-id">Unggah file 3D/sumber ke S3</span></div>
          </div>
          <div class="flow-row">
            <div class="flow-box"><span class="lang-en">Project saved as pending</span><span class="lang-id">Proyek disimpan sebagai pending</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box gold"><span class="lang-en">admin_lab reviews &amp; approves</span><span class="lang-id">admin_lab tinjau &amp; setujui</span></div>
            <div class="flow-arrow">→</div>
            <div class="flow-box"><span class="lang-en">Appears in public catalogue</span><span class="lang-id">Muncul di katalog publik</span></div>
          </div>
        </div>

        <div class="grid g2" style="margin-top:36px">
          <div class="tcard">
            <div class="tn">service_bookings</div>
            <div class="td"><span class="lang-en">Core order record. Holds status, pricing, material requirements, and links to user &amp; service.</span><span class="lang-id">Catatan pesanan inti. Menyimpan status, harga, kebutuhan material, dan tautan ke pengguna &amp; layanan.</span></div>
          </div>
          <div class="tcard">
            <div class="tn">raw_material_movements</div>
            <div class="td"><span class="lang-en">Audit log of every stock in/out event. Each row links to a booking_id (for out) or restock proof (for in).</span><span class="lang-id">Log audit setiap kejadian stok masuk/keluar. Setiap baris terhubung ke booking_id (untuk keluar) atau bukti restock (untuk masuk).</span></div>
          </div>
          <div class="tcard">
            <div class="tn">booking_payments</div>
            <div class="td"><span class="lang-en">Down-payment and final-payment records per booking. Stores the proof image path and verification status.</span><span class="lang-id">Catatan uang muka dan pembayaran akhir per booking. Menyimpan path gambar bukti dan status verifikasi.</span></div>
          </div>
          <div class="tcard">
            <div class="tn">service_progress_updates</div>
            <div class="td"><span class="lang-en">Timeline entries showing the order's production journey: status label, percentage, notes, and optional photo.</span><span class="lang-id">Entri timeline yang menampilkan perjalanan produksi pesanan: label status, persentase, catatan, dan foto opsional.</span></div>
          </div>
          <div class="tcard">
            <div class="tn">attachments</div>
            <div class="td"><span class="lang-en">Polymorphic table for all uploads across the platform (photos, 3D files, PDFs). Links to any model via attachable_type / attachable_id.</span><span class="lang-id">Tabel polymorphic untuk semua unggahan di seluruh platform (foto, file 3D, PDF). Terhubung ke model mana pun via attachable_type / attachable_id.</span></div>
          </div>
          <div class="tcard">
            <div class="tn">page_sections</div>
            <div class="td"><span class="lang-en">Key-value CMS store. Unique on (page_name, section_key). Drives all dynamic public landing page content — no redeploy required for changes.</span><span class="lang-id">Penyimpanan CMS key-value. Unik pada (page_name, section_key). Menggerakkan semua konten landing page publik dinamis — tidak perlu redeploy untuk perubahan.</span></div>
          </div>
        </div>

        <div class="callout" style="margin-top:28px"><b><span class="lang-en">Need developer context?</span><span class="lang-id">Butuh konteks developer?</span></b> <span class="lang-en">Visit <a href="/dev/documentations" style="color:var(--gold2);text-decoration:underline">/dev/documentations</a> for the full architecture, code patterns, and database schema reference aimed at engineers.</span><span class="lang-id">Kunjungi <a href="/dev/documentations" style="color:var(--gold2);text-decoration:underline">/dev/documentations</a> untuk referensi arsitektur lengkap, pola kode, dan skema database yang ditujukan untuk engineer.</span></div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="foot">
      <div class="wrap">
        <h3><span class="lang-en">IDIG Health Tech — Admin Guide</span><span class="lang-id">IDIG Health Tech — Panduan Admin</span></h3>
        <p>Repositori Digital &amp; Innovation Hub · Departemen Teknologi Kedokteran<br>Fakultas Kedokteran &amp; Kesehatan — Institut Teknologi Sepuluh Nopember (ITS)</p>
        <div class="meta"><span class="lang-en">Admin User Guide · Internal</span><span class="lang-id">Panduan Pengguna Admin · Internal</span> · <a href="/admin/documentations" style="color:var(--gold2)">/admin/documentations</a></div>
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
  localStorage.setItem('idig-admin-docs-theme', next);
  updateLabels();
}
function toggleLang() {
  var next = document.documentElement.dataset.lang === 'en' ? 'id' : 'en';
  document.documentElement.dataset.lang = next;
  document.documentElement.lang = next;
  localStorage.setItem('idig-admin-docs-lang', next);
  updateLabels();
}
updateLabels();

// Scrollspy
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
