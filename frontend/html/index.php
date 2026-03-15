<?php
session_start();
require_once('../../database/db_connect.php');

$accountLink = 'log-in.php';

if (isset($_SESSION['customerID'])) {
    $accountLink = 'account.php';

    $cid = (int) $_SESSION['customerID'];
    $result = $conn->query("SELECT role FROM customer WHERE customerID = $cid");

    if ($result && $row = $result->fetch_assoc()) {
        if ($row['role'] === 'admin') {
            $accountLink = 'admin.php';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home | Wine Exchange</title>
  <link rel="icon" type="image/x-icon" href="../../images/icon.png">
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>

    :root {
      --wine:       #6B0F1A;
      --wine-dark:  #4a0912;
      --wine-light: #8b1525;
      --gold:       #9a6b4b;
      --gold-light: #d4a96a;
      --text:       #1a0a06;
      --text-mid:   #5a4a3a;
      --text-soft:  #8a7a6a;
      --bg:         #ffffff;
      --bg-warm:    #faf7f4;
      --bg-panel:   #f4ede8;
      --border:     #e8ddd5;
      --radius:     4px;
      --transition: 0.22s ease;
    }

    .darkmode {
      --text:      #f0e6de;
      --text-mid:  #c0a898;
      --text-soft: #8a7a72;
      --bg:        #140a08;
      --bg-warm:   #1c100d;
      --bg-panel:  #221410;
      --border:    #3a2820;
    }

    *, *::before, *::after { box-sizing: border-box; }

    html, body {
      margin: 0;
      padding: 0;
      max-width: 100%;
      overflow-x: hidden;
    }

    body {
      background: var(--bg);
      color: var(--text);
      transition: background var(--transition), color var(--transition);
    }

    main.main-home {
      max-width: 100% !important;
      margin: 0 !important;
      padding: 0 !important;
    }

    main.main-home > section {
      margin-top: 0 !important;
      margin-bottom: 0 !important;
    }

    main.main-home .wine-advert {
      height: auto !important;
    }

    main.main-home .faq {
      margin-top: 0 !important;
    }

    main.main-home .reviews {
      padding-top: 72px !important;
    }

    a { color: inherit; }

    .section-label {
      display: block;
      text-align: center;
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 0.22em;
      color: var(--text);
      margin-bottom: 10px;
    }

    .section-divider {
      display: block;
      width: 40px;
      height: 3px;
      background: var(--wine);
      border-radius: 2px;
      margin: 0 auto 36px;
    }

    /* ── CAROUSEL ── */
    .wine-advert {
      position: relative;
      width: 100%;
      height: auto !important;
      border-bottom: 1px solid var(--border);
    }

    .wine-advert .cards-cycle {
      height: auto !important;
      width: 100% !important;
    }

    .ca-track-wrap { overflow: hidden; width: 100%; }

    .ca-track {
      display: flex;
      transition: transform 0.55s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ca-slide {
      min-width: 100%;
      display: flex;
      height: 420px;
    }

    .ca-left {
      flex: 0 0 42%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 48px 5% 48px 8%;
      background: var(--bg);
      border-right: 1px solid var(--border);
      transition: background var(--transition), border-color var(--transition);
    }

    .ca-left--dark {
      background: #1a0a06;
      border-right-color: #3a1810;
    }

    .darkmode .ca-left--dark {
      background: var(--bg-panel);
      border-right-color: var(--border);
    }

    .ca-label {
      font-size: 10px;
      font-weight: 800;
      letter-spacing: 0.22em;
      color: var(--gold);
      margin-bottom: 14px;
      display: block;
    }

    .ca-left--dark .ca-label { color: var(--gold-light); }

    .ca-title {
      font-size: 26px;
      font-weight: 800;
      color: var(--text);
      letter-spacing: 0.02em;
      line-height: 1.2;
      margin: 0 0 16px;
    }

    .ca-left--dark .ca-title { color: #f5ece4; }
    .darkmode .ca-left--dark .ca-title { color: var(--text); }

    .ca-desc {
      font-size: 13.5px;
      color: var(--text-mid);
      line-height: 1.8;
      margin: 0 0 26px;
    }

    .ca-left--dark .ca-desc { color: #c8b09a; }
    .darkmode .ca-left--dark .ca-desc { color: var(--text-mid); }

    .ca-btn {
      display: inline-block;
      align-self: flex-start;
      padding: 10px 22px;
      background: var(--wine);
      color: #fff;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 0.12em;
      text-decoration: none;
      border-radius: var(--radius);
      transition: background var(--transition);
    }
    .ca-btn:hover { background: var(--wine-light); }

    .ca-right { flex: 1; overflow: hidden; }

    .ca-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.7s ease;
    }
    .ca-slide:hover .ca-img { transform: scale(1.04); }

    .ca-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 20;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      border: 2px solid rgba(255,255,255,0.55);
      background: rgba(26,10,6,0.5);
      color: #fff;
      font-size: 15px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background var(--transition), border-color var(--transition);
      backdrop-filter: blur(6px);
    }
    .ca-arrow:hover { background: var(--wine); border-color: #fff; }
    .ca-prev { left: 14px; }
    .ca-next { right: 14px; }

    .ca-dots {
      position: absolute;
      bottom: 13px;
      right: 20px;
      display: flex;
      gap: 7px;
      z-index: 20;
    }

    .ca-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: rgba(255,255,255,0.35);
      cursor: pointer;
      transition: background 0.3s, transform 0.3s;
    }
    .ca-dot.active { background: #fff; transform: scale(1.35); }

    @media (max-width: 700px) {
      .ca-slide { flex-direction: column; height: auto; }
      .ca-left  { flex: unset; padding: 32px 6%; border-right: none; border-bottom: 1px solid var(--border); }
      .ca-right { height: 220px; }
    }

    /* ── WELCOME ── */
    .welcome {
      display: flex;
      align-items: center;
      gap: 64px;
      padding: 72px 10%;
      background: var(--bg);
      transition: background var(--transition);
    }

    .welcome-text { flex: 1; }

    .welcome-text h1 {
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 0.22em;
      color: var(--text);
      margin: 0 0 10px;
    }

    .welcome-divider {
      display: block;
      width: 40px;
      height: 3px;
      background: var(--wine);
      border-radius: 2px;
      margin: 0 0 24px;
    }

    .welcome-text p {
      font-size: 14.5px;
      color: var(--text-mid);
      line-height: 1.85;
      margin: 0 0 16px;
    }

    .welcome img {
      flex: 0 0 360px;
      width: 360px;
      height: 270px;
      object-fit: cover;
      border-radius: var(--radius);
      box-shadow: 0 8px 32px rgba(107,15,26,0.12);
    }

    @media (max-width: 800px) {
      .welcome { flex-direction: column; gap: 32px; }
      .welcome img { width: 100%; flex: unset; height: 220px; }
    }

    /* ── REVIEWS ── */
    .reviews-header {
      display: block !important;
      width: 100% !important;
      text-align: center;
      margin-bottom: 36px;
    }

    .reviews {
      display: block !important;
      position: relative !important;
      padding: 72px 10% !important;
      background: var(--bg-warm);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      transition: background var(--transition);
      overflow: hidden;
      box-sizing: border-box !important;
      width: 100% !important;
      left: 0 !important;
      right: 0 !important;
      margin: 0 !important;
    }

    .reviews-grid {
      display: grid !important;
      grid-template-columns: repeat(3, 1fr) !important;
      gap: 22px;
      width: 100% !important;
      max-width: 100% !important;
      min-width: 0 !important;
      box-sizing: border-box !important;
      margin: 0 !important;
      padding: 0 !important;
      float: none !important;
      position: static !important;
      left: auto !important;
    }

    .review-card {
      min-width: 0 !important;
      max-width: 100% !important;
      word-wrap: break-word;
      overflow-wrap: break-word;
      box-sizing: border-box !important;
      float: none !important;
      position: static !important;
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 26px;
      transition: background var(--transition), border-color var(--transition), box-shadow var(--transition);
    }
    .review-card:hover {
      box-shadow: 0 6px 24px rgba(107,15,26,0.09);
    }

    .review-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 16px;
    }

    .profile-pic {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--border);
      flex-shrink: 0;
    }

    .review-header h3 {
      margin: 0;
      font-size: 14px;
      font-weight: 700;
      color: var(--text);
      flex: 1;
    }

    .review-rating { width: 76px; flex-shrink: 0; }

    .review-card blockquote { margin: 0; padding: 0; }

    .review-card blockquote p {
      font-size: 13.5px;
      color: var(--text-mid);
      line-height: 1.75;
      margin: 0 0 10px;
    }
    .review-card blockquote p:last-child { margin-bottom: 0; }

    @media (max-width: 900px) {
      .reviews-grid { grid-template-columns: 1fr !important; }
    }

    /* ── FAQ ── */
    .faq {
      padding: 72px 10%;
      background: var(--bg);
      transition: background var(--transition);
    }

    .faq-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 18px;
    }

    .faq-item {
      background: var(--bg-warm);
      border: 1px solid var(--border);
      border-left: 3px solid var(--wine);
      border-radius: var(--radius);
      padding: 22px 24px;
      transition: background var(--transition), border-color var(--transition);
    }

    .faq-item h3 {
      font-size: 14px;
      font-weight: 700;
      color: var(--text);
      margin: 0 0 8px;
    }

    .faq-item p {
      font-size: 13.5px;
      color: var(--text-mid);
      line-height: 1.7;
      margin: 0;
    }

    @media (max-width: 700px) {
      .faq-grid { grid-template-columns: 1fr; }
    }

    /* ── FOOTER ── */
    .footer {
      background-color: #f4f4f4;
      padding: 30px 10%;
      margin-top: 40px;
      color: #333;
    }

    .footer-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .footer-section {
      flex: 1 1 250px;
      margin: 10px;
    }

    .footer-section h3 {
      margin-bottom: 10px;
      font-size: 14px;
      color: #333;
    }

    .footer-links {
      list-style: none;
      padding: 0;
    }

    .footer-links li { margin: 5px 0; }

    .footer-links a {
      text-decoration: none;
      color: inherit;
    }

    .footer-links a:hover { text-decoration: underline; }

    .footer-button {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border-radius: 4px;
      text-decoration: none;
    }

    .footer-button:hover { opacity: 0.9; }

    .footer-bottom {
      text-align: center;
      margin-top: 20px;
      padding-top: 10px;
      border-top: 1px solid #ccc;
      font-size: 14px;
    }

    .darkmode .footer {
      background-color: #1e1e1e;
      color: #eee;
    }

    .darkmode .footer-bottom { border-top: 1px solid #555; }
    .darkmode .footer-links a { color: #ddd; }
    .darkmode .footer-section h3 { color: #eee; }

    /* ── WISHLIST SIDEBAR ── */
    .wishlist-sidebar {
      position: fixed;
      top: 0;
      right: -420px;
      width: 380px;
      height: 100%;
      background: #f4f1f2;
      padding: 30px;
      box-shadow: -5px 0 20px rgba(0,0,0,0.25);
      transition: right 0.4s ease;
      z-index: 2000;
      overflow-y: auto;
    }

    .wishlist-sidebar.active { right: 0; }

    .wishlist-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      display: none;
      z-index: 1500;
    }

    .wishlist-overlay.active { display: block; }

    .close-wishlist {
      font-size: 22px;
      cursor: pointer;
      text-align: right;
      margin-bottom: 15px;
    }

    #wishlist-items {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 20px;
    }

    .wishlist-item {
      display: flex;
      gap: 12px;
      align-items: center;
      background: white;
      border-radius: 10px;
      padding: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      position: relative;
    }

    .wishlist-img {
      width: 65px;
      height: 65px;
      object-fit: cover;
      border-radius: 8px;
    }

    .wishlist-info { flex: 1; }

    .wishlist-name {
      font-weight: 600;
      font-size: 14px;
      margin-bottom: 4px;
    }

    .wishlist-price {
      color: #7b1e3a;
      font-weight: bold;
      margin-bottom: 8px;
    }

    .wishlist-actions { display: flex; gap: 8px; }

    .wishlist-view {
      padding: 4px 10px;
      font-size: 12px;
      border-radius: 6px;
      background: #eee;
      text-decoration: none;
      color: #333;
    }

    .wishlist-view:hover { background: #ddd; }

    .remove-wishlist {
      position: absolute;
      top: 6px;
      right: 6px;
      border: none;
      background: none;
      font-size: 14px;
      cursor: pointer;
      color: #999;
    }

    .remove-wishlist:hover { color: red; }

    .wishlist-nav-button {
      position: relative;
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #e63946;
      margin-left: 10px;
    }

    .wishlist-count {
      position: absolute;
      top: -6px;
      right: -8px;
      background: #e63946;
      color: white;
      font-size: 11px;
      font-weight: bold;
      padding: 2px 6px;
      border-radius: 50px;
      min-width: 18px;
      text-align: center;
    }

    /* Dark mode wishlist */
    html.darkmode .wishlist-sidebar { background: #121212; color: #ffffff; }
    html.darkmode .wishlist-item { background: #1e1e1e; border: 1px solid #333; box-shadow: none; }
    html.darkmode .wishlist-name { color: #ffffff; }
    html.darkmode .wishlist-price { color: #ff6b6b; }
    html.darkmode .wishlist-view { background: #2c2c2c; color: #ffffff; }
    html.darkmode .wishlist-view:hover { background: #3a3a3a; }
    html.darkmode .remove-wishlist { color: #bbbbbb; }
    html.darkmode .remove-wishlist:hover { color: #ff4d4d; }
    html.darkmode #wishlist-items p { color: #cccccc; }
  </style>
</head>

<body>

  <!-- WISHLIST OVERLAY & SIDEBAR -->
  <div class="wishlist-overlay" id="wishlistOverlay"></div>
  <div class="wishlist-sidebar" id="wishlistSidebar">
    <div class="close-wishlist" id="closeWishlist"><i class="fa fa-times"></i></div>
    <h3>Your Wishlist</h3>
    <div id="wishlist-items">
      <p>Your wishlist is empty.</p>
    </div>
  </div>

  <!-- NAVBAR -->
  <div class="navbar">
    <a href="index.php"><img src="../../images/icon.png" alt="Wine Exchange Logo" style="cursor:pointer;"></a>
    <div class="navbar-links">
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a>
      <a href="search.php">Wines</a>
      <a href="basket.php">Basket</a>
      <a href="contact-us.php">Contact Us</a>
    </div>
    <div class="navbar-right">
      <form method="POST" action="search.php">
        <input type="text" name="search" placeholder="Search">
        <input type="hidden" name="submitted" value="true" />
      </form>
      <button onclick="location.href='<?= $accountLink ?>'" class="wishlist-nav-button">
        <i class="fas fa-user"></i>
      </button>
      <button id="wishlist-toggle" class="wishlist-nav-button">
        <i class="fas fa-heart"></i>
        <span id="wishlist-count" class="wishlist-count">0</span>
      </button>
      <button id="dark-mode" class="dark-mode-button">
        <img src="../../images/darkmode.png" alt="Dark Mode" />
      </button>
    </div>
  </div>

  <!-- HERO -->
  <section class="header">
    <div class="header-content">
      <p>WINE EXCHANGE - ONLINE WINE SHOP</p>
      <h1><strong>THE WORLD'S PREMIUM HOME OF WINE</strong></h1>
      <p>CURATED COLLECTIONS, DELIVERED WITH CARE</p>
    </div>
  </section>

  <main class="main-home">

    <!-- WELCOME -->
    <section class="welcome">
      <div class="welcome-text">
        <h1>WELCOME</h1>
        <span class="welcome-divider"></span>
        <p>Welcome to Wine Exchange, your place for discovering wines worth drinking and collecting. We bring together bottles from trusted producers, emerging regions, and iconic estates so you can explore with confidence. Whether you're here for everyday favourites or rare finds, we keep the experience clear and enjoyable.</p>
        <p>At Wine Exchange, we focus on transparency and quality. Every wine is selected with intention, and we make sure you know exactly what you're getting — real descriptions, honest pricing, and zero guesswork.</p>
        <p>We also believe great wine belongs to everyone, not just experts. Our platform is built to help you browse easily, learn as you go, and pick bottles that match your taste.</p>
      </div>
      <img src="../../images/welcome.png" alt="Welcome image" />
    </section>

    <!-- CAROUSEL -->
    <section class="wine-advert">
      <button class="ca-arrow ca-prev" aria-label="Previous">&#8592;</button>
      <button class="ca-arrow ca-next" aria-label="Next">&#8594;</button>

      <div class="ca-track-wrap">
        <div class="ca-track">

          <div class="ca-slide">
            <div class="ca-left ca-left--dark">
              <span class="ca-label">WINE EXCHANGE</span>
              <h2 class="ca-title" style="color:#6B0F1A;">PREMIUM WINES<br>COLLECTION</h2>
              <p class="ca-desc">Our wines are made from carefully harvested grapes grown in respected vineyards around the world. Through traditional winemaking techniques the grapes are gently pressed and fermented to create wines rich in colour, aroma, and flavour — curated for quality, character, and craftsmanship.</p>
              <a href="search.php" class="ca-btn">Shop All Wines</a>
            </div>
            <div class="ca-right">
              <img src="../../images/redWinesBG.jpg" alt="Red wines" class="ca-img">
            </div>
          </div>

          <div class="ca-slide">
            <div class="ca-left">
              <span class="ca-label">FEATURED WINE</span>
              <h2 class="ca-title">Opus One</h2>
              <p class="ca-desc">A celebrated Napa Valley blend from the Mondavi and Rothschild families. Rich with dark fruit, cedar, and refined tannins — one of the world's most iconic reds.</p>
              <a href="wineinfo.php?id=2" class="ca-btn">View Wine</a>
            </div>
            <div class="ca-right">
              <img src="../../images/indexOpus.jpg" alt="Opus One" class="ca-img">
            </div>
          </div>

          <div class="ca-slide">
            <div class="ca-left">
              <span class="ca-label">FEATURED WINE</span>
              <h2 class="ca-title">Penfolds Grange</h2>
              <p class="ca-desc">Australia's most prestigious red. A bold, full-bodied Shiraz aged in American oak, known for its extraordinary depth, complexity, and ageing potential.</p>
              <a href="wineinfo.php?id=3" class="ca-btn">View Wine</a>
            </div>
            <div class="ca-right">
              <img src="../../images/indexGrange.jpg" alt="Penfolds Grange" class="ca-img">
            </div>
          </div>

          <div class="ca-slide">
            <div class="ca-left">
              <span class="ca-label">FEATURED WINE</span>
              <h2 class="ca-title">Château Margaux</h2>
              <p class="ca-desc">A first-growth Bordeaux of legendary status. Elegant, perfumed, and graceful — Château Margaux is the benchmark of Médoc refinement and precision.</p>
              <a href="wineinfo.php?id=4" class="ca-btn">View Wine</a>
            </div>
            <div class="ca-right">
              <img src="../../images/indexMargaux.jpg" alt="Château Margaux" class="ca-img">
            </div>
          </div>

          <div class="ca-slide">
            <div class="ca-left">
              <span class="ca-label">FEATURED WINE</span>
              <h2 class="ca-title">Marchesi Antinori</h2>
              <p class="ca-desc">Tignanello — a Tuscan icon by Antinori. A bold Super Tuscan blending Sangiovese with Cabernet, aged in barriques for remarkable structure and character.</p>
              <a href="wineinfo.php?id=1" class="ca-btn">View Wine</a>
            </div>
            <div class="ca-right">
              <img src="../../images/indexTignanello.jpg" alt="Marchesi Antinori" class="ca-img">
            </div>
          </div>
        </div>
      </div>

      <div class="ca-dots">
        <span class="ca-dot active" data-index="0"></span>
        <span class="ca-dot" data-index="1"></span>
        <span class="ca-dot" data-index="2"></span>
        <span class="ca-dot" data-index="3"></span>
        <span class="ca-dot" data-index="4"></span>
      </div>
    </section>

<!-- REVIEWS -->
    <section class="reviews">
      <div class="reviews-header">
        <h1 class="section-label">REVIEWS</h1>
        <span class="section-divider"></span>
      </div>
      <div class="reviews-grid">

        <div class="review-card"
            data-name="Kathy Schwabe"
            data-title="Effortless ordering and flawless delivery."
            data-review="Delivery was very impressive. My order arrived right on time, carefully packaged, and in perfect condition. It’s clear that attention to detail and customer satisfaction are top priorities. Consistency like this is rare, and it’s refreshing to know I can rely on them every single time."
            data-stars="★★★★★">

          <div class="review-header">
            <img src="../../images/bd.jpg" alt="Kathy Schwabe" class="profile-pic" />
            <h3>Kathy Schwabe</h3>
            <img src="../../images/5star.png" alt="5 stars" class="review-rating" />
          </div>
          <blockquote>
            <h4>Effortless ordering and flawless delivery.</h4>
            <p>Wine Exchange has a truly impressive variety of red, white, sparkling, and rosé wines. Browsing the site felt effortless, with clear categories and detailed descriptions that made choosing the right bottle simple and enjoyable.</p>
          </blockquote>
        </div>

        <div class="review-card"
            data-name="Edward Sinclair"
            data-title="My go-to site for hassle-free wine shopping."
            data-review="Delivery was very impressive. My order arrived right on time, carefully packaged, and in perfect condition. It’s clear that attention to detail and customer satisfaction are top priorities. Consistency like this is rare, and it’s refreshing to know I can rely on them every single time."
            data-stars="★★★★★"> 

          <div class="review-header">
            <img src="../../images/jj.jpg" alt="Edward Sinclair" class="profile-pic" />
            <h3>Edward Sinclair</h3>
            <img src="../../images/5star.png" alt="5 stars" class="review-rating" />
          </div>
          <blockquote>
            <h4>My go-to site for hassle-free wine shopping.</h4>
            <p>I've ordered several times from Wine Exchange and they've always delivered on time. The prices are fair compared to other online shops, and the range of wines keeps me coming back to explore new options.</p>
          </blockquote>
        </div>

        <div class="review-card"
            data-name="Harry Maguire"
            data-title="Exactly as described, delivered without delay!"
            data-review="Delivery was very impressive. My order arrived right on time, carefully packaged, and in perfect condition. It’s clear that attention to detail and customer satisfaction are top priorities. Consistency like this is rare, and it’s refreshing to know I can rely on them every single time."
            data-stars="★★★★★">

          <div class="review-header">
            <img src="../../images/hm.jpg" alt="Harry Maguire" class="profile-pic" />
            <h3>Harry Maguire</h3>
            <img src="../../images/5star.png" alt="5 stars" class="review-rating" />
          </div>
          <blockquote>
            <h4>Exactly as described, delivered without delay!</h4>
            <p>I wasn't sure which wine to choose, but the clear descriptions and tasting notes made the decision much easier. The rosé I purchased was crisp, refreshing, and exactly what I hoped for.</p>
          </blockquote>
        </div>

      </div>
    </section>

    <!-- FAQ -->
    <section class="faq">
      <div class="reviews-header">
        <h1 class="section-label">FAQ</h1>
        <span class="section-divider"></span>
      </div>
      <div class="faq-grid">
        <div class="faq-item">
          <h3>Q: How does buying work on Wine Exchange?</h3>
          <p>A: Purchasing is simple and straightforward. Every bottle listed is ready to order directly from the site.</p>
        </div>
        <div class="faq-item">
          <h3>Q: Where do the wines come from?</h3>
          <p>A: All wines are sourced from reputable producers, merchants, and distributors around the world.</p>
        </div>
        <div class="faq-item">
          <h3>Q: Do you offer international shipping?</h3>
          <p>A: Many regions are supported, and delivery options are shown at checkout based on your location.</p>
        </div>
        <div class="faq-item">
          <h3>Q: How do I find the right wine?</h3>
          <p>A: Explore curated collections, browse by type, and use our detailed tasting notes and descriptions.</p>
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-section">
        <h3>Wine Exchange</h3>
        <p>123 Vineyard Lane<br>London, UK</p>
        <p>Phone: +44 1234 567890</p>
        <p>Email: <a href="mailto:contactwinexchange@gmail.com">contactwinexchange@gmail.com</a></p>
        <p>Open: Mon–Fri, 9am–6pm</p>
      </div>
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul class="footer-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="search.php">Wines</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="contact-us.php">Contact</a></li>
        </ul>
        <a href="contact-us.php" class="footer-button">Contact Us</a>
      </div>
      <div class="footer-section">
        <h3>Follow Us</h3>
        <ul class="footer-links">
          <li><a href="#">Instagram</a></li>
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Twitter</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      © 2026 Wine Exchange. All rights reserved.
    </div>
  </footer>

  <!-- CAROUSEL SCRIPT -->
  <script>
    (function () {
      const track = document.querySelector('.ca-track');
      const dots  = document.querySelectorAll('.ca-dot');
      const TOTAL = 5;
      let cur = 0, timer;

      function goTo(i) {
        cur = (i + TOTAL) % TOTAL;
        track.style.transform = `translateX(-${cur * 100}%)`;
        dots.forEach((d, idx) => d.classList.toggle('active', idx === cur));
      }

      function startTimer() {
        clearInterval(timer);
        timer = setInterval(() => goTo(cur + 1), 5000);
      }

      document.querySelector('.ca-next').addEventListener('click', () => { goTo(cur + 1); startTimer(); });
      document.querySelector('.ca-prev').addEventListener('click', () => { goTo(cur - 1); startTimer(); });
      dots.forEach(d => d.addEventListener('click', () => { goTo(+d.dataset.index); startTimer(); }));

      startTimer();
    })();
  </script>

  <!-- DARK MODE SCRIPT -->
  <script>
    (function () {
      const btn = document.getElementById('dark-mode');

      if (localStorage.getItem('dark_mode') === 'on') {
        document.documentElement.classList.add('darkmode');
      }

      btn.addEventListener('click', function () {
        document.documentElement.classList.toggle('darkmode');
        const isOn = document.documentElement.classList.contains('darkmode');
        localStorage.setItem('dark_mode', isOn ? 'on' : 'off');
      });
    })();
  </script>

  <!-- WISHLIST SCRIPT -->
  <script>
    const loggedIn = <?php echo isset($_SESSION['customerID']) ? "true" : "false"; ?>;
    const wishlistBtn     = document.getElementById("wishlist-toggle");
    const wishlistSidebar = document.getElementById("wishlistSidebar");
    const closeWishlist   = document.getElementById("closeWishlist");
    const wishlistOverlay = document.getElementById("wishlistOverlay");

    wishlistBtn.addEventListener("click", () => {
      wishlistSidebar.classList.add("active");
      wishlistOverlay.classList.add("active");
    });

    closeWishlist.addEventListener("click", () => {
      wishlistSidebar.classList.remove("active");
      wishlistOverlay.classList.remove("active");
    });

    wishlistOverlay.addEventListener("click", () => {
      wishlistSidebar.classList.remove("active");
      wishlistOverlay.classList.remove("active");
    });

    const wishlistContainer = document.getElementById("wishlist-items");
    const wishlistCount     = document.getElementById("wishlist-count");

    function getGuestWishlist() {
      return JSON.parse(localStorage.getItem("wishlist")) || [];
    }

    function saveGuestWishlist(list) {
      localStorage.setItem("wishlist", JSON.stringify(list));
    }

    function loadWishlist() {
      if (loggedIn) {
        fetch("get_wishlist.php")
          .then(res => res.json())
          .then(data => renderWishlist(data));
      } else {
        renderWishlist(getGuestWishlist());
      }
    }

    function renderWishlist(list) {
      wishlistContainer.innerHTML = "";

      if (list.length === 0) {
        wishlistContainer.innerHTML = "<p>Your wishlist is empty.</p>";
        wishlistCount.textContent = 0;
        return;
      }

      wishlistCount.textContent = list.length;

      list.forEach((wine, index) => {
        const image = loggedIn
          ? (wine.imageUrl ? "../../images/" + wine.imageUrl : "../../images/placeholder.jpg")
          : (wine.imageUrl || "../../images/placeholder.jpg");

        const item = document.createElement("div");
        item.className = "wishlist-item";
        item.innerHTML = `
          <img src="${image}" class="wishlist-img">
          <div class="wishlist-info">
            <div class="wishlist-name">${wine.wineName || wine.name}</div>
            <div class="wishlist-price">£${wine.price}</div>
            <div class="wishlist-actions">
              <a href="wineinfo.php?id=${wine.id || wine.wineId}" class="wishlist-view">View</a>
            </div>
          </div>
          <button class="remove-wishlist" data-id="${wine.wineId || wine.id}" data-index="${index}">
            <i class="fas fa-times"></i>
          </button>
        `;
        wishlistContainer.appendChild(item);
      });
    }

    document.addEventListener("click", function (e) {
      const removeBtn = e.target.closest(".remove-wishlist");
      if (!removeBtn) return;

      const wineId = removeBtn.dataset.id;
      const index  = removeBtn.dataset.index;

      if (loggedIn) {
        fetch("remove_from_wishlist.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "wineId=" + wineId
        }).then(() => loadWishlist());
      } else {
        let list = getGuestWishlist();
        list.splice(index, 1);
        saveGuestWishlist(list);
        renderWishlist(list);
      }
    });

    loadWishlist();
  </script>
  <!-- CHATBOT -->
  <script src="chatbot.js"></script>
</body>
</html>