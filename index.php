<?php
// Root Portal Launcher for Ceylon Spice Haven Project
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ceylon Spice Haven Artisans — Project Portal</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --black: #000000;
    --surface: #0d0d0d;
    --surface2: #161616;
    --border: #2a2a2a;
    --cream: #d9d9d9;
    --muted: #888888;
    --gold: #77721f;
    --gold-lt: #b5a84a;
    --white: #f2ede6;
    --font-serif: 'Cormorant Garamond', Georgia, serif;
    --font-sans: 'Jost', system-ui, sans-serif;
  }
  body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-sans);
    background: var(--black);
    color: var(--cream);
    padding: 30px 20px;
    background-image: radial-gradient(circle at 50% 20%, rgba(119, 114, 31, 0.12), transparent 60%);
  }
  .portal-wrap {
    max-width: 860px;
    width: 100%;
    text-align: center;
  }
  .brand-logo {
    height: 48px;
    margin-bottom: 20px;
    filter: brightness(0.95);
  }
  .eyebrow {
    font-size: 0.72rem;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--gold-lt);
    margin-bottom: 10px;
    font-weight: 500;
  }
  h1 {
    font-family: var(--font-serif);
    font-size: clamp(2.4rem, 5vw, 3.8rem);
    color: var(--white);
    font-weight: 300;
    margin-bottom: 10px;
    line-height: 1.1;
  }
  h1 em {
    font-style: italic;
    color: var(--gold-lt);
  }
  p.sub {
    color: var(--muted);
    font-size: 0.95rem;
    margin: 0 auto 46px;
    max-width: 540px;
    line-height: 1.7;
    font-weight: 300;
  }
  .grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
  }
  @media(max-width: 720px) {
    .grid { grid-template-columns: 1fr; }
  }
  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    padding: 40px 30px;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
  }
  .card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: var(--gold);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.35s ease;
  }
  .card:hover {
    transform: translateY(-5px);
    border-color: var(--gold-lt);
    box-shadow: 0 16px 40px rgba(0,0,0,0.6);
  }
  .card:hover::before {
    transform: scaleX(1);
  }
  .card-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--surface2);
    border: 1px solid var(--border);
    display: grid;
    place-items: center;
    font-size: 28px;
    margin-bottom: 22px;
    transition: border-color .2s;
  }
  .card:hover .card-icon {
    border-color: var(--gold-lt);
  }
  .card h2 {
    font-family: var(--font-serif);
    font-size: 1.6rem;
    margin: 0 0 10px;
    font-weight: 400;
    color: var(--white);
  }
  .card p {
    font-size: 0.88rem;
    color: var(--muted);
    margin: 0 0 28px;
    line-height: 1.7;
    text-align: center;
  }
  .badge-btn {
    margin-top: auto;
    background: var(--gold);
    color: #000;
    padding: 12px 28px;
    font-size: 0.76rem;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    display: inline-block;
    transition: background .2s;
  }
  .card:hover .badge-btn {
    background: var(--gold-lt);
  }
  .footer-est {
    margin-top: 50px;
    font-size: 0.74rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--gold-lt);
    font-family: var(--font-serif);
    font-style: italic;
  }
</style>
</head>
<body>
<div class="portal-wrap">
  <img src="spices-selling-website/images/img-1.png" alt="Ceylon Spice Haven" class="brand-logo" onerror="this.style.display='none'">
  <p class="eyebrow">Artisans · Est. 2021</p>
  <h1>Ceylon Spice Haven<br><em>Project Portal</em></h1>
  <p class="sub">Direct access to the customer-facing luxury spice storefront and the store management terminal.</p>
  <div class="grid">
    <a href="spices-selling-website/" class="card">
      <div class="card-icon">✨</div>
      <h2>Artisanal Storefront</h2>
      <p>Luxury customer website inspired by Ceylon Spice Haven. Catalog browsing, pure spices, gifting, quick checkout, and cart ordering.</p>
      <span class="badge-btn">Launch Storefront →</span>
    </a>
    <a href="spices-pos/" class="card">
      <div class="card-icon">🖥️</div>
      <h2>POS & Inventory Terminal</h2>
      <p>Staff and merchant terminal. Counter sales billing, stock management, custom spice mixer calculations, and revenue reporting.</p>
      <span class="badge-btn">Launch POS Terminal →</span>
    </a>
  </div>
  <div class="footer-est">Ceylon Spice Haven Artisans — Pure Sri Lankan Spices</div>
</div>
</body>
</html>
