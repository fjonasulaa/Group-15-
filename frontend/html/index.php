<?php
session_start();
require_once('../../database/db_connect.php');

$reviewSQL = "
SELECT 
    w.wStars,
    w.wReviewHeading,
    w.wReviewText,
    w.reviewDate,
    c.firstName,
    c.surname,
    c.userProfileImage

FROM websiteReviews w
JOIN customer c 
ON w.customerId = c.customerID
ORDER BY w.reviewDate DESC
";

$reviews = $conn->query($reviewSQL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home | Wine Exchange</title>
  <link rel="icon" href="../../images/icon.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --wine:       #6B0F1A;
      --wine-dark:  #4a0912;
      --wine-light: #8b1525;
      --wine-nav:   #6b1a2e;
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
      --speed:      0.22s ease;
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
      margin: 0; padding: 0;
      overflow-x: hidden;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Jost', sans-serif;
      transition: background var(--speed), color var(--speed);
    }

    a { color: inherit; }

    /* home layout overrides */
    main.main-home { max-width: 100%; margin: 0; padding: 0; }
    main.main-home > section { margin: 0; }
    main.main-home .wine-advert { height: auto; }
    main.main-home .faq { margin-top: 0; }
    main.main-home .reviews { padding-top: 72px; }

    .section-label {
      display: block;
      text-align: center;
      font-size: 12px;
      font-weight: 800;
      letter-spacing: .22em;
      color: var(--text);
      margin-bottom: 10px;
    }

    .section-divider {
      display: block;
      width: 40px; height: 3px;
      background: var(--wine);
      border-radius: 2px;
      margin: 0 auto 36px;
    }

    /* ══════════════════════════════════════════
       HERO — BIGGER
    ══════════════════════════════════════════ */
    .header {
      min-height: calc(100vh + 62px) !important;
      height: calc(100vh + 62px) !important;
      margin-top: -62px !important;
      padding-top: 0 !important;
    }

    .header .header-content h1 {
      font-size: 6rem !important;
      line-height: 1.05 !important;
    }

    /* ── CAROUSEL ── */
    .wine-advert {
      position: relative;
      width: 100%;
      border-bottom: 1px solid var(--border);
    }

    .ca-track-wrap { overflow: hidden; width: 100%; }

    .ca-track {
      display: flex;
      transition: transform .55s cubic-bezier(.4,0,.2,1);
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
      transition: background var(--speed), border-color var(--speed);
    }

    .ca-left--dark { background: #1a0a06; border-right-color: #3a1810; }
    .darkmode .ca-left--dark { background: var(--bg-panel); border-right-color: var(--border); }

    .ca-label {
      font-size: 10px; font-weight: 800;
      letter-spacing: .22em;
      color: var(--gold);
      margin-bottom: 14px;
      display: block;
    }
    .ca-left--dark .ca-label { color: var(--gold-light); }

    .ca-title {
      font-size: 26px; font-weight: 800;
      line-height: 1.2; letter-spacing: .02em;
      color: var(--text);
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
      align-self: flex-start;
      display: inline-block;
      padding: 10px 22px;
      background: var(--wine);
      color: #fff;
      font-size: 11px; font-weight: 800;
      letter-spacing: .12em;
      text-decoration: none;
      border-radius: var(--radius);
      transition: background var(--speed), transform .18s ease, box-shadow .18s ease;
    }
    .ca-btn:hover {
      background: var(--wine-light);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(107,15,26,.25);
    }
    .ca-btn:active { transform: none; box-shadow: none; }

    .ca-right { flex: 1; overflow: hidden; }

    .ca-img {
      width: 100%; height: 100%;
      object-fit: cover; display: block;
      transition: transform .7s ease;
    }
    .ca-slide:hover .ca-img { transform: scale(1.04); }

    .ca-arrow {
      position: absolute;
      top: 50%; transform: translateY(-50%);
      z-index: 20;
      width: 38px; height: 38px;
      border-radius: 50%;
      border: 2px solid rgba(255,255,255,.55);
      background: rgba(26,10,6,.5);
      color: #fff; font-size: 15px;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      backdrop-filter: blur(6px);
      transition: background var(--speed), border-color var(--speed), transform .18s ease;
    }
    .ca-arrow:hover {
      background: var(--wine);
      border-color: #fff;
      transform: translateY(-50%) scale(1.1);
    }
    .ca-prev { left: 14px; }
    .ca-next { right: 14px; }

    .ca-dots {
      position: absolute;
      bottom: 13px; right: 20px;
      display: flex; gap: 7px;
      z-index: 20;
    }
    .ca-dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      background: rgba(255,255,255,.35);
      cursor: pointer;
      transition: background .3s, transform .3s;
    }
    .ca-dot.active { background: #fff; transform: scale(1.35); }

    @media (max-width: 700px) {
      .ca-slide { flex-direction: column; height: auto; }
      .ca-left { flex: unset; padding: 32px 6%; border-right: none; border-bottom: 1px solid var(--border); }
      .ca-right { height: 220px; }
    }

    /* ── WELCOME ── */
    .welcome {
      display: flex;
      align-items: center;
      gap: 64px;
      padding: 72px 10%;
      background: var(--bg);
      transition: background var(--speed);
    }
    .welcome-text { flex: 1; }
    .welcome-text h1 {
      font-size: 12px; font-weight: 800;
      letter-spacing: .22em;
      color: var(--text);
      margin: 0 0 10px;
    }
    .welcome-divider {
      display: block;
      width: 40px; height: 3px;
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
    .welcome-img-wrap {
      flex: 0 0 auto; width: 360px;
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(107,15,26,.12);
      transition: box-shadow .4s ease;
    }
    .welcome-img-wrap:hover { box-shadow: 0 16px 48px rgba(107,15,26,.2); }
    .welcome img {
      display: block; width: 100%; height: auto;
      object-fit: contain;
      border-radius: var(--radius);
      transition: transform .4s ease;
    }
    .welcome-img-wrap:hover img { transform: scale(1.02); }

    @media (max-width: 800px) {
      .welcome { flex-direction: column; gap: 32px; }
      .welcome-img-wrap { width: 100%; }
    }

    /* ── REVIEWS ── */
    .reviews-header {
      display: block; width: 100%;
      text-align: center;
      margin-bottom: 36px;
    }

    .reviews {
      display: block;
      position: relative;
      padding: 72px 10%;
      background: var(--bg-warm);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      transition: background var(--speed);
      overflow: hidden;
      width: 100%; margin: 0;
    }

    .reviews-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 22px; width: 100%;
    }

    .review-card {
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 26px;
      word-wrap: break-word;
      cursor: pointer;
      transition: background var(--speed), border-color var(--speed), box-shadow .3s ease, transform .3s ease;
    }
    .review-card:hover {
      box-shadow: 0 12px 36px rgba(107,15,26,.13);
      transform: translateY(-4px);
    }

    .review-header {
      display: flex; align-items: center;
      gap: 12px; margin-bottom: 16px;
    }

    .add-btn {
      background-color: rgba(107,15,26);
      border: 0;
      border-radius: 5px;
      color: white;
      font-size: 14px;
      font-weight: bold;
      padding: 10px 25px;
      margin-top: 10px;
      cursor: pointer;
    }

    .reviews-grid a{
      grid-column: 1 / -1;
      text-align: center;
    }

    .no-reviews{
      text-align:center;
      font-size:18px;
      color:#777;
      grid-column:1 / -1;
      margin:20px 0;
    }

    .profile-pic {
      width: 44px; height: 44px;
      border-radius: 50%; object-fit: cover;
      border: 2px solid var(--border);
      flex-shrink: 0;
      transition: border-color .25s ease, transform .25s ease;
    }
    .review-card:hover .profile-pic { border-color: var(--wine); transform: scale(1.08); }
    .review-header h3 {
      margin: 0; font-size: 14px; font-weight: 700;
      color: var(--text); flex: 1;
    }
    .review-header .stars {
      color: rgb(255, 215, 0);
      font-size: 1.1rem;
    }
    .review-card blockquote { margin: 0; padding: 0; }
    .review-card blockquote p {
      font-size: 13.5px;
      color: var(--text-mid);
      line-height: 1.75;
      margin: 0 0 10px;
    }
    .review-card blockquote p:last-child { margin-bottom: 0; }

    @media (max-width: 900px) { .reviews-grid { grid-template-columns: 1fr; } }

    /* ── REVIEW POPUP ── */
    .popup-container {
      position: fixed;
      top: 0; left: 0;
      height: 100vh; width: 100vw;
      background-color: rgba(0,0,0,0.45);
      display: flex;
      justify-content: center;
      align-items: center;
      pointer-events: none;
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: 3000;
    }
    .popup-container.show {
      pointer-events: auto;
      opacity: 1;
    }
    .pop-up {
      background-color: var(--bg);
      color: var(--text);
      padding: 36px 48px;
      width: 580px;
      max-width: 92vw;
      border-radius: 6px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.25);
      transform: translateY(16px);
      transition: transform 0.3s ease;
    }
    .popup-container.show .pop-up {
      transform: translateY(0);
    }
    .pop-up h1 { font-size: 1.6rem; margin: 0 0 6px; }
    .pop-up h2 { font-size: 1rem; font-weight: 600; color: var(--text-mid); margin: 0 0 14px; }
    .pop-up p  { font-size: 14px; color: var(--text-mid); line-height: 1.75; margin: 0 0 20px; }
    .popup-stars { color: rgb(255, 215, 0); }
    .pop-up .btn-close-popup {
      display: inline-block;
      padding: 10px 22px;
      background: var(--wine);
      color: #fff;
      font-size: 13px; font-weight: 700;
      border: none; border-radius: var(--radius);
      cursor: pointer;
      transition: background var(--speed), transform .18s ease;
    }

    .popup-footer {
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-top:20px;
      color:#777;
      font-size: 1rem;
      font-weight: 600;
      margin: 0 0 14px;
    }

    .pop-up .btn-close-popup:hover {
      background: var(--wine-light);
      transform: translateY(-1px);
    }

    /* ── FAQ ── */
    .faq {
      padding: 72px 10%;
      background: var(--bg);
      transition: background var(--speed);
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
      cursor: default;
      transition: background var(--speed), border-color var(--speed), transform .28s ease, box-shadow .28s ease;
    }
    .faq-item:hover {
      transform: translateX(4px);
      box-shadow: 4px 0 20px rgba(107,15,26,.1);
      border-left-color: var(--wine-light);
    }
    .faq-item h3 { font-size: 14px; font-weight: 700; color: var(--text); margin: 0 0 8px; }
    .faq-item p  { font-size: 13.5px; color: var(--text-mid); line-height: 1.7; margin: 0; }

    @media (max-width: 700px) { .faq-grid { grid-template-columns: 1fr; } }

    /* ── SCROLL FADE-INS ── */
    .fade-in {
      opacity: 0;
      transform: translateY(28px);
      transition: opacity .65s ease, transform .65s ease;
    }
    .fade-in.visible {
      opacity: 1;
      transform: none;
    }
    .stagger-children > * {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity .55s ease, transform .55s ease;
    }
    .stagger-children.visible > * {
      opacity: 1;
      transform: none;
    }

    /* ── HERO TEXT ENTRANCE ── */
    .header-content p:first-child,
    .header-content h1,
    .header-content p:last-child { opacity: 0; }
    .header-content.hero-animate p:first-child { animation: heroFadeUp .7s ease .1s  both; }
    .header-content.hero-animate h1            { animation: heroFadeUp .7s ease .25s both; }
    .header-content.hero-animate p:last-child  { animation: heroFadeUp .7s ease .4s  both; }

    @keyframes heroFadeUp {
      from { opacity:0; transform:translateY(20px); }
      to   { opacity:1; transform:none; }
    }

    /* ── DIVIDER GROW ── */
    .section-divider,
    .welcome-divider {
      transform-origin: left center;
      animation: growBar .6s ease .2s both;
    }
    .reviews-header .section-divider { transform-origin: center; }

    @keyframes growBar {
      from { transform:scaleX(0); opacity:0; }
      to   { transform:scaleX(1); opacity:1; }
    }

    @keyframes slideInWishlist {
      from { opacity:0; transform:translateX(16px); }
      to   { opacity:1; transform:none; }
    }
  </style>
</head>

<body>

  <?php include('header.php'); ?>

  <!-- HERO — full viewport height -->
  <section class="header" style="min-height: calc(100vh + 62px); height: calc(100vh + 62px); margin-top: -62px;">
    <div class="header-content">
      <p>WINE EXCHANGE - ONLINE WINE SHOP</p>
      <h1><strong>THE WORLD'S PREMIUM HOME OF WINE</strong></h1>
      <p>CURATED COLLECTIONS, DELIVERED WITH CARE</p>
    </div>
  </section>

  <main class="main-home">

    <!-- WELCOME -->
    <section class="welcome fade-in">
      <div class="welcome-text">
        <h1>WELCOME</h1>
        <span class="welcome-divider"></span>
        <p>Welcome to Wine Exchange, your place for discovering wines worth drinking and collecting. We bring together bottles from trusted producers, emerging regions, and iconic estates so you can explore with confidence. Whether you're here for everyday favourites or rare finds, we keep the experience clear and enjoyable.</p>
        <p>At Wine Exchange, we focus on transparency and quality. Every wine is selected with intention, and we make sure you know exactly what you're getting — real descriptions, honest pricing, and zero guesswork.</p>
        <p>We also believe great wine belongs to everyone, not just experts. Our platform is built to help you browse easily, learn as you go, and pick bottles that match your taste.</p>
      </div>
      <div class="welcome-img-wrap">
        <img src="../../images/welcome.png" alt="Welcome image">
      </div>
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
      <div class="reviews-header fade-in">
        <h1 class="section-label">WEBSITE REVIEWS</h1>
        <span class="section-divider"></span>
      </div>
      <div class="reviews-grid stagger-children">

      <?php
        if ($reviews && $reviews->num_rows > 0) {
          while ($row = $reviews->fetch_assoc()) {

          $name = htmlspecialchars($row['firstName'] . " " . $row['surname']);
          $heading = htmlspecialchars($row['wReviewHeading']);
          $text = htmlspecialchars($row['wReviewText']);
          $date = $row['reviewDate'];
          $stars = str_repeat("★", $row['wStars']) . str_repeat("☆", 5 - $row['wStars']);

          $image = !empty($row['userProfileImage'])
          ? "../../" . $row['userProfileImage']
          : "../../images/guestPfp.jpg";
      ?>      
        <div class="review-card"
             data-name="<?= $name ?>"
             data-title="<?= $heading ?>"
             data-review="<?= $text ?>"
             data-stars="<?= $stars ?>"
             data-date="<?= $date ?>">

            <div class="review-header">
                <img src="<?= $image ?>" alt="<?= $name ?>" class="profile-pic">
                <h3><?= $name ?></h3>
                <span class="stars"><?= $stars ?></span>
            </div>
            <blockquote>
                <h4><?= $heading ?></h4>
            </blockquote>
        </div>
      <?php
    }
  } else {
?>
  <p class="no-reviews">No reviews yet. Be the first to write one!</p>
<?php
}
?> 
        <a href="reviewForm-W.php">
          <button class="add-btn">Write Your Review</button>
        </a>
        </div>

      <!-- Review popup -->
      <div class="popup-container" id="popup">
        <div class="pop-up">
          <h1 id="popup-name"></h1>
          <h2 id="popup-title"></h2>
          <p id="popup-text"></p>
          <div class="popup-footer">
            <button class="btn-close-popup" id="close">Close Review</button>
            <span id="popup-date"></span>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="faq">
      <div class="reviews-header fade-in">
        <h1 class="section-label"><a href="faq.php">FAQ</a></h1>
        <span class="section-divider"></span>
      </div>
      <div class="faq-grid stagger-children">
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
          <p>A: Explore curated collections, browse by type, or take our quiz to find the perfect gift for yourself or others.</p>
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <?php include 'footer.php'; ?>

  <!-- CAROUSEL SCRIPT -->
  <script>
    var caTrack = document.querySelector('.ca-track');
    var caDots  = document.querySelectorAll('.ca-dot');
    var caTotal = 5;
    var caIdx   = 0;
    var caTimer;

    function caGoTo(n) {
      caIdx = ((n % caTotal) + caTotal) % caTotal;
      caTrack.style.transform = 'translateX(-' + (caIdx * 100) + '%)';
      caDots.forEach(function(d, i) { d.classList.toggle('active', i === caIdx); });
    }

    function caResetTimer() {
      clearInterval(caTimer);
      caTimer = setInterval(function() { caGoTo(caIdx + 1); }, 5000);
    }

    document.querySelector('.ca-next').addEventListener('click', function() { caGoTo(caIdx + 1); caResetTimer(); });
    document.querySelector('.ca-prev').addEventListener('click', function() { caGoTo(caIdx - 1); caResetTimer(); });
    caDots.forEach(function(dot) {
      dot.addEventListener('click', function() { caGoTo(+dot.dataset.index); caResetTimer(); });
    });
    caResetTimer();
  </script>

  <!-- REVIEW POPUP SCRIPT -->
  <script>
    var reviewCards = document.querySelectorAll('.review-card');
    var popup = document.getElementById('popup');
    var popupName = document.getElementById('popup-name');
    var popupTitle = document.getElementById('popup-title');
    var popupText = document.getElementById('popup-text');
    var popupDate = document.getElementById('popup-date');
    var closeBtn = document.getElementById('close');

    function timeAgo(dateString) {
      const now = new Date();
      const reviewDate = new Date(dateString);
      const seconds = Math.floor((now - reviewDate) / 1000);

      const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60
      };

      for (let key in intervals) {
        const interval = Math.floor(seconds / intervals[key]);
        if (interval >= 1) {
          return interval + " " + key + (interval > 1 ? "s" : "") + " ago";
        }
      }

      return "just now";
    }

    reviewCards.forEach(function(card) {
      card.addEventListener('click', function() {
        popupName.innerHTML  = card.dataset.name + ' <span class="popup-stars">' + card.dataset.stars + '</span>';
        popupTitle.textContent = card.dataset.title;
        popupText.textContent  = card.dataset.review;
        popupDate.textContent = "Review added: " + timeAgo(card.dataset.date);
        popup.classList.add('show');
      });
    });

    closeBtn.addEventListener('click', function() { popup.classList.remove('show'); });

    popup.addEventListener('click', function(e) {
      if (e.target === popup) popup.classList.remove('show');
    });
  </script>

  <!-- SCROLL ANIMATIONS + HERO ENTRANCE -->
  <script>
    // hero entrance
    var heroContent = document.querySelector('.header-content');
    var heroObs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          heroContent.classList.remove('hero-animate');
          void heroContent.offsetWidth;
          heroContent.classList.add('hero-animate');
        }
      });
    }, { threshold: 0.3 });
    heroObs.observe(document.querySelector('.header'));

    // scroll fade-ins
    var scrollObs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        entry.target.classList.toggle('visible', entry.isIntersecting);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.fade-in, .stagger-children').forEach(function(el) {
      scrollObs.observe(el);
    });
  </script>

  <script src="chatbot.js"></script>
</body>
</html>