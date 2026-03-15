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

    /* ── FOOTER ── */
    .footer-newsletter {
      background: #6b1a2e;
      padding: 18px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
    }

    .footer-newsletter strong {
      display: block;
      font-weight: 500;
      color: #ffffff;
      font-size: 16px;
      margin-bottom: 2px;
    }

    .footer-newsletter p {
      color: #f5dde3;
      font-size: 15px;
    }

    .newsletter-form {
      display: flex;
      flex-direction: row;
      gap: 8px;
      align-items: center;
    }

    .footer-newsletter .newsletter-form input {
      padding: 7px 12px !important;
      border-radius: 8px !important;
      border: 0.5px solid rgba(255, 255, 255, 0.25) !important;
      background: rgba(255, 255, 255, 0.12) !important;
      color: #ffffff !important;
      font-size: 15px !important;
      width: 210px !important;
      max-width: 210px !important;
      min-width: 210px !important;
      outline: none !important;
      font-family: inherit !important;
      box-sizing: border-box !important;
      margin-bottom: 0 !important;
    }

    .footer-newsletter .newsletter-form input::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    .footer-newsletter .newsletter-form input:focus {
      border-color: rgba(255, 255, 255, 0.5) !important;
    }

    .footer-newsletter .btn-subscribe {
      padding: 7px 16px !important;
      border-radius: 8px !important;
      background: #ffffff !important;
      border: none !important;
      color: #6b1a2e !important;
      font-size: 15px !important;
      font-weight: 500 !important;
      cursor: pointer !important;
      font-family: inherit !important;
      transition: background 0.15s !important;
      width: 210px !important;
      max-width: 210px !important;
      min-width: 210px !important;
      box-sizing: border-box !important;
    }

    .footer-newsletter .btn-subscribe:hover {
      background: #f0e8ea !important;
    }

    .footer-main {
      background: #ffffff;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
      gap: 28px;
      padding: 36px 32px 28px;
    }

    .footer-col h4 {
      font-size: 13px;
      font-weight: 600;
      color: #6b1a2e;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      margin-bottom: 14px;
      font-family: Georgia, serif;
    }

    .footer-col p {
      color: #4a2a30;
      font-size: 15px;
      line-height: 1.8;
    }

    .footer-col a {
      display: block;
      color: #4a2a30;
      font-size: 15px;
      line-height: 2;
      text-decoration: none;
      transition: color 0.15s;
    }

    .footer-col a:hover {
      color: #6b1a2e;
    }

    .footer-tagline {
      color: #8a5a60;
      font-style: italic;
      font-size: 15px;
      margin-bottom: 12px;
    }

    .footer-contact-email {
      color: #6b1a2e !important;
    }

    .footer-contact-muted {
      color: #8a5a60 !important;
    }

    .btn-browse {
      display: inline-block;
      margin-top: 16px;
      background: #ffffff;
      color: #6b1a2e;
      font-size: 15px;
      font-weight: 500;
      padding: 9px 18px;
      border-radius: 8px;
      border: 1px solid #6b1a2e;
      text-decoration: none;
      font-family: inherit;
      transition: background 0.15s;
    }

    .btn-browse:hover {
      background: #f9f0f2;
      color: #6b1a2e;
    }

    .trust-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .trust-item {
      display: flex;
      align-items: center;
      gap: 9px;
      color: #4a2a30;
      font-size: 15px;
    }

    .trust-item svg {
      flex-shrink: 0;
      width: 16px;
      height: 16px;
    }

    .payment-icons {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 8px;
    }

    .payment-icon {
      background: #6b1a2e;
      color: #f5e6c8;
      border-radius: 3px;
      padding: 2px 7px;
      font-size: 10px;
      font-weight: 600;
      font-family: Georgia, serif;
      letter-spacing: 0.04em;
    }

    .col-divider {
      border: none;
      border-top: 0.5px solid #e8c8c8;
      margin: 10px 0;
    }

    .footer-bottom {
      border-top: 1px solid #e8c8c8;
      background: #fdf6f0;
      padding: 16px 32px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
    }

    .footer-bottom p {
      font-size: 14px;
      color: #8a5a60;
    }

    .age-badge {
      background: #fdf0e8;
      border: 0.5px solid #c9a84c;
      color: #7a4a00;
      font-size: 13px;
      padding: 3px 10px;
      border-radius: 8px;
    }

    .darkmode .footer-main { background: #1c100d; }
    .darkmode .footer-col p,
    .darkmode .footer-col a { color: #c0a898; }
    .darkmode .footer-col a:hover { color: #f0e6de; }
    .darkmode .footer-bottom { background: #140a08; border-top-color: #3a2820; }
    .darkmode .footer-bottom p { color: #8a7a72; }

    @media (max-width: 600px) {
      .footer-newsletter {
        padding: 16px 20px;
        flex-direction: column;
        align-items: flex-start;
      }

      .newsletter-form {
        width: 100%;
      }

      .newsletter-form input {
        flex: 1;
        width: auto;
      }

      .footer-main {
        padding: 24px 20px;
        grid-template-columns: 1fr 1fr;
      }

      .footer-bottom {
        padding: 14px 20px;
        flex-direction: column;
        align-items: flex-start;
      }
    }

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
            data-review="Delivery was very impressive. My order arrived right on time, carefully packaged, and in perfect condition. It's clear that attention to detail and customer satisfaction are top priorities. Consistency like this is rare, and it's refreshing to know I can rely on them every single time."
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
            data-review="Delivery was very impressive. My order arrived right on time, carefully packaged, and in perfect condition. It's clear that attention to detail and customer satisfaction are top priorities. Consistency like this is rare, and it's refreshing to know I can rely on them every single time."
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
            data-review="Delivery was very impressive. My order arrived right on time, carefully packaged, and in perfect condition. It's clear that attention to detail and customer satisfaction are top priorities. Consistency like this is rare, and it's refreshing to know I can rely on them every single time."
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
  <footer>

    <!-- Newsletter bar -->
    <div class="footer-newsletter">
      <div>
        <strong>Join our wine newsletter</strong>
        <p>Tasting notes, new arrivals &amp; exclusive offers</p>
      </div>
      <div class="newsletter-form" style="display:flex; flex-direction:row; gap:8px; align-items:center;">
        <input type="email" placeholder="Your email address" style="width:210px !important; min-width:210px !important; max-width:210px !important; padding:7px 12px; border-radius:8px; border:0.5px solid rgba(255,255,255,0.25); background:rgba(255,255,255,0.12); color:#ffffff; font-size:15px; outline:none; box-sizing:border-box; margin-bottom:0;" />
        <button class="btn-subscribe" style="width:210px !important; min-width:210px !important; max-width:210px !important; padding:7px 16px; border-radius:8px; background:#ffffff; border:none; color:#6b1a2e; font-size:15px; font-weight:500; cursor:pointer; box-sizing:border-box;">Subscribe</button>
      </div>
    </div>

    <!-- Main columns -->
    <div class="footer-main">

      <!-- Col 1: Brand info -->
      <div class="footer-col">
        <h4>Wine Exchange</h4>
        <p class="footer-tagline">Independent wine merchant since 2010</p>
        <p>123 Vineyard Lane<br>London, UK</p>
        <p class="footer-contact-muted" style="margin-top: 8px;">+44 1234 567890</p>
        <a href="mailto:contactwinexchange@gmail.com" class="footer-contact-email">contactwinexchange@gmail.com</a>
        <p class="footer-contact-muted" style="margin-top: 8px;">Mon–Fri, 9am–6pm</p>
        <a href="search.php" class="btn-browse">Browse all wines →</a>
      </div>

      <!-- Col 2: Why shop with us -->
      <div class="footer-col">
        <h4>Why shop with us</h4>
        <div class="trust-list">

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="1" y="4" width="16" height="11" rx="2" stroke="#6b1a2e" stroke-width="1.2"/>
              <path d="M1 7h16" stroke="#6b1a2e" stroke-width="1.2"/>
            </svg>
            Secure payments
          </div>

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="2" y="8" width="10" height="7" rx="1.2" stroke="#6b1a2e" stroke-width="1.2"/>
              <path d="M12 11h2.5a1 1 0 001-1V8.5a1 1 0 00-.6-.9L13 7" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/>
              <circle cx="5" cy="15.5" r="1.2" fill="#6b1a2e"/>
              <circle cx="10" cy="15.5" r="1.2" fill="#6b1a2e"/>
            </svg>
            Free standard delivery on every order
          </div>

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 2v8M9 10l-3 3m3-3l3 3" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/>
              <path d="M4 14h10" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Easy returns
          </div>

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="9" cy="9" r="7" stroke="#6b1a2e" stroke-width="1.2"/>
              <path d="M9 5v4l2.5 2.5" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Next-day dispatch
          </div>

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 2l1.8 5h5.2l-4.2 3 1.6 5L9 12l-3.4 3 1.6-5L3 7h5.2z" stroke="#6b1a2e" stroke-width="1.1" stroke-linejoin="round"/>
            </svg>
            Expert curation
          </div>

        </div>
      </div>

      <!-- Col 3: Help -->
      <div class="footer-col">
        <h4>Help</h4>
        <a href="about.php">About us</a>
        <a href="faq.php">FAQ</a>
        <a href="contact-us.php">Contact us</a>
        <a href="shipping.php">Shipping &amp; delivery</a>
        <a href="terms.php">Terms &amp; conditions</a>
      </div>

      <!-- Col 4: Legal & payments -->
      <div class="footer-col">
        <h4>Legal &amp; payments</h4>
        <a href="terms.php">Privacy policy</a>
        <a href="terms.php">Cookie policy</a>
        <a href="terms.php">Accessibility</a>
        <hr class="col-divider" />
        <div class="payment-icons">
          <span class="payment-icon">VISA</span>
          <span class="payment-icon">MC</span>
          <span class="payment-icon">AMEX</span>
          <span class="payment-icon">PayPal</span>
        </div>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="footer-bottom">
      <p>© 2026 Wine Exchange. All rights reserved.</p>
      <span class="age-badge">18+ only — please drink responsibly</span>
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

 <script src="chatbot.js"></script>
</body>
</html>