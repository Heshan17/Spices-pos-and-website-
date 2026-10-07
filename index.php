<?php
// Root Portal Launcher for Spices Project
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ceylon Spices Project Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #f7f4ef;
    --card: #ffffff;
    --tx: #201712;
    --mu: #7a6d62;
    --ac: #c85217;
    --ac2: #e57b32;
    --bd: #ebdccd;
  }
  @media(prefers-color-scheme:dark){
    :root{--bg:#140e0a;--card:#1f1712;--tx:#f5ebe2;--mu:#a89789;--bd:#33251a}
  }
  *{box-sizing:border-box}
  body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    background: var(--bg);
    color: var(--tx);
    padding: 24px;
  }
  .portal-wrap {
    max-width: 820px;
    width: 100%;
    text-align: center;
  }
  .logo-icon {
    font-size: 54px;
    margin-bottom: 12px;
  }
  h1 {
    font-size: 32px;
    font-weight: 800;
    margin: 0 0 8px;
    letter-spacing: -0.02em;
  }
  p.sub {
    color: var(--mu);
    font-size: 15px;
    margin: 0 0 36px;
  }
  .grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }
  @media(max-width: 680px) {
    .grid { grid-template-columns: 1fr; }
  }
  .card {
    background: var(--card);
    border: 1px solid var(--bd);
    border-radius: 20px;
    padding: 32px 24px;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
  }
  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 36px rgba(200,82,23,0.15);
    border-color: var(--ac);
  }
  .card-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: rgba(200,82,23,0.1);
    color: var(--ac);
    display: grid;
    place-items: center;
    font-size: 30px;
    margin-bottom: 18px;
  }
  .card h2 {
    font-size: 20px;
    margin: 0 0 8px;
    font-weight: 700;
  }
  .card p {
    font-size: 13.5px;
    color: var(--mu);
    margin: 0 0 20px;
    line-height: 1.5;
  }
  .badge-btn {
    margin-top: auto;
    background: linear-gradient(135deg, var(--ac), var(--ac2));
    color: #fff;
    padding: 10px 22px;
    border-radius: 99px;
    font-size: 14px;
    font-weight: 700;
    display: inline-block;
  }
</style>
</head>
<body>
<div class="portal-wrap">
  <div class="logo-icon">🌶️</div>
  <h1>Ceylon Spices Project Portal</h1>
  <p class="sub">Select an application to launch</p>
  <div class="grid">
    <a href="spices-pos/" class="card">
      <div class="card-icon">🖥️</div>
      <h2>Spices POS & Inventory</h2>
      <p>Terminal system for shop owner & staff. Counter billing, barcode scan, inventory stock, mixer cost calculator & analytics.</p>
      <span class="badge-btn">Launch POS System →</span>
    </a>
    <a href="spices-selling-website/" class="card">
      <div class="card-icon">🛍️</div>
      <h2>Spices Selling Website</h2>
      <p>Customer-facing online storefront. 3D spices view, catalog browsing, cart, and consumer online ordering.</p>
      <span class="badge-btn">Launch Website →</span>
    </a>
  </div>
</div>
</body>
</html>
