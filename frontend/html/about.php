<?php
session_start();
require_once('../../database/db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us | Wine Exchange</title>
  <link rel="icon" type="image/x-icon" href="../../images/icon.png">
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

  <style>

    /* ─── COLOUR TOKENS ─────────────────────────────────────── */
    :root {
      --white:       #ffffff;
      --off-white:   #faf8f5;
      --gold-light:  #f0d98c;
      --gold:        #c9a84c;
      --gold-dark:   #9e7a2a;
      --burgundy:    #6b1a2e;
      --burgundy-mid:#8c2640;
      --burgundy-lt: #f5eaed;
      --ink:         #2a1a1e;
      --ink-soft:    #5a3d44;
      --border:      rgba(201,168,76,0.25);
    }

    /* ─── BASE ──────────────────────────────────────────────── */
    body {
      margin: 0;
      background: var(--white);
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 18px;
      color: var(--ink);
    }

    .about {
      padding-top: 120px;
      padding-bottom: 80px;
      background: var(--white);
    }

    /* ─── PAGE HEADING ──────────────────────────────────────── */
    .center-title {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-weight: 300;
      font-size: 13px;
      letter-spacing: 5px;
      text-transform: uppercase;
      color: var(--gold-dark);
      text-align: center;
      text-shadow: none;
      margin-bottom: 8px;
    }

    /* page-level "About Us" h1 gets a decorative rule */
    .about > h1.center-title {
      font-size: 13px;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 18px;
      margin: 0 10% 48px;
    }
    .about > h1.center-title::before,
    .about > h1.center-title::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(to right, transparent, var(--gold));
    }
    .about > h1.center-title::after {
      background: linear-gradient(to left, transparent, var(--gold));
    }

    /* ─── FRAMES (shared card style) ───────────────────────── */
    .frame {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 2px;
      padding: 48px 56px;
      color: var(--ink);
      margin: 0 10% 32px;
    }

    .frame p,
    .frame h3,
    .frame li,
    .frame span {
      color: var(--ink);
    }

    /* ─── SLOGAN ────────────────────────────────────────────── */
    .slogan-section {
      text-align: center;
      max-width: 100%;
      position: relative;
    }

    .slogan {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 38px;
      font-style: italic;
      font-weight: 400;
      line-height: 1.35;
      color: var(--burgundy);
      margin: 0 0 24px;
      letter-spacing: 0.3px;
    }

    .slogan-section::before {
      content: '❧';
      display: block;
      font-size: 28px;
      color: var(--gold);
      margin-bottom: 20px;
      opacity: 0.7;
    }

    .slogan-text {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 18px;
      line-height: 1.9;
      color: var(--ink-soft);
      max-width: 680px;
      margin: 0 auto;
    }

    /* ─── CORE VALUES ───────────────────────────────────────── */
    .values-section {
      margin: 0 10% 32px;
    }

    .values-section .center-title {
      margin-bottom: 32px;
    }

    .values-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1px;
      background: var(--border);
      border: 1px solid var(--border);
      margin-top: 0;
    }

    .value-card {
      background: var(--white);
      padding: 40px 32px;
      text-align: center;
      color: var(--ink);
      transition: background 0.25s ease;
      position: relative;
    }

    .value-card::before {
      content: '';
      display: block;
      width: 32px;
      height: 1px;
      background: var(--gold);
      margin: 0 auto 20px;
    }

    .value-card:hover {
      background: var(--off-white);
      transform: none;
      box-shadow: none;
    }

    .value-card p,
    .value-card span,
    .value-card li {
      color: var(--ink-soft);
    }

    .value-card h3 {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-weight: 500;
      font-size: 20px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--burgundy);
      margin-bottom: 14px;
    }

    .value-card p {
      font-size: 16px;
      line-height: 1.75;
    }

    /* ─── ABOUT BLOCKS ──────────────────────────────────────── */
    .about-block {
      display: flex;
      align-items: stretch;
      gap: 0;
      margin: 0 10% 32px;
      border: 1px solid var(--border);
      overflow: hidden;
      border-radius: 2px;
    }

    .about-block.frame {
      padding: 0;
      border: 1px solid var(--border);
    }

    .about-image {
      flex: 0 0 auto;
    }

    .about-image img {
      width: 380px;
      height: 100%;
      min-height: 280px;
      object-fit: cover;
      display: block;
      filter: sepia(15%) saturate(0.9);
    }

    .about-text {
      flex: 1;
      padding: 48px 52px;
      color: var(--ink);
      min-width: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: var(--white);
    }

    .about-text .section-eyebrow {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 11px;
      letter-spacing: 4px;
      text-transform: uppercase;
      color: var(--gold-dark);
      margin-bottom: 14px;
      display: block;
    }

    .about-text h3,
    .about-text h2 {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-weight: 400;
      font-size: 32px;
      color: var(--burgundy);
      margin: 0 0 20px;
      line-height: 1.2;
    }

    .about-text p {
      font-size: 17px;
      line-height: 1.85;
      color: var(--ink-soft);
      margin: 0 0 12px;
    }

    /* decorative rule after heading */
    .about-text h3::after,
    .about-text h2::after {
      content: '';
      display: block;
      width: 40px;
      height: 1px;
      background: var(--gold);
      margin-top: 14px;
    }

    /* ─── DARK MODE — page content ─────────────────────────── */
    html.darkmode body {
      background: #1a0e12;
    }

    html.darkmode .about {
      background: #1a0e12;
    }

    html.darkmode .center-title {
      color: var(--gold-light);
    }

    html.darkmode .about > h1.center-title::before {
      background: linear-gradient(to right, transparent, var(--gold-light));
    }
    html.darkmode .about > h1.center-title::after {
      background: linear-gradient(to left, transparent, var(--gold-light));
    }

    html.darkmode .frame {
      background: #261118;
      border-color: rgba(201,168,76,0.2);
      color: #e8ddd8;
    }

    html.darkmode .frame p,
    html.darkmode .frame h3,
    html.darkmode .frame li,
    html.darkmode .frame span {
      color: #c9b8b8;
    }

    html.darkmode .slogan {
      color: var(--gold-light);
    }

    html.darkmode .slogan-section::before {
      color: var(--gold);
    }

    html.darkmode .slogan-text {
      color: #c9b8b8;
    }

    html.darkmode .values-section .center-title {
      color: var(--gold-light);
    }

    html.darkmode .values-grid {
      background: rgba(201,168,76,0.15);
      border-color: rgba(201,168,76,0.15);
    }

    html.darkmode .value-card {
      background: #261118;
      color: #e8ddd8;
    }

    html.darkmode .value-card:hover {
      background: #321520;
    }

    html.darkmode .value-card h3 {
      color: var(--gold-light);
    }

    html.darkmode .value-card p,
    html.darkmode .value-card span,
    html.darkmode .value-card li {
      color: #c9b8b8;
    }

    html.darkmode .about-block {
      border-color: rgba(201,168,76,0.2);
    }

    html.darkmode .about-block.frame {
      border-color: rgba(201,168,76,0.2);
    }

    html.darkmode .about-text {
      background: #261118;
    }

    html.darkmode .about-text .section-eyebrow {
      color: var(--gold);
    }

    html.darkmode .about-text h3,
    html.darkmode .about-text h2 {
      color: var(--gold-light);
    }

    html.darkmode .about-text h3::after,
    html.darkmode .about-text h2::after {
      background: var(--gold);
    }

    html.darkmode .about-text p {
      color: #c9b8b8;
    }

    html.darkmode .about-image img {
      filter: sepia(20%) saturate(0.7) brightness(0.85);
    }

  </style>
</head>

<body>

  <?php include('header.php'); ?>

  <section class="about">

    <h1 class="center-title">About Us</h1>

    <!-- SLOGAN -->
    <div class="slogan-section frame">
      <h2 class="slogan">"Where Authenticity Meets the Art of Fine Wine."</h2>
      <p class="slogan-text">
        At Wine Exchange, we believe that exceptional wine begins with authenticity.
        Our mission is to connect people with carefully selected wines that celebrate
        craftsmanship, heritage, and discovery. Every bottle in our collection reflects
        our dedication to quality and the experience of sharing great wine.
      </p>
    </div>

    <!-- CORE VALUES -->
    <div class="values-section">
      <h2 class="center-title">Our Core Values</h2>
      <div class="values-grid">
        <div class="value-card">
          <h3>Trust</h3>
          <p>We build lasting relationships with our customers by offering reliable service, honest recommendations, and wines you can truly rely on.</p>
        </div>
        <div class="value-card">
          <h3>Authenticity</h3>
          <p>Every wine we offer represents genuine craftsmanship and heritage, carefully chosen from vineyards that honour tradition and passion.</p>
        </div>
        <div class="value-card">
          <h3>Community</h3>
          <p>Wine is meant to be shared. We aim to create a community of wine lovers who appreciate discovery, connection, and memorable experiences.</p>
        </div>
        <div class="value-card">
          <h3>Quality</h3>
          <p>We focus on selecting wines that meet high standards of taste, production, and character, ensuring every bottle delivers excellence.</p>
        </div>
      </div>
    </div>

    <!-- GOAL -->
    <div class="about-block frame">
      <div class="about-image">
        <img src="../../images/vinery.jpg" alt="Our Goal image" />
      </div>
      <div class="about-text">
        <span class="section-eyebrow">Our goal</span>
        <h3>Every Bottle Tells a Story</h3>
        <p>At Wine Exchange, we believe that every bottle tells a story — one shaped by the vineyards it comes from,
          the people who craft it, and the traditions passed down through generations.</p>
        <p>From bold, contemporary expressions to timeless, celebrated classics, we curate a diverse selection designed
          to inspire discovery and elevate every occasion.</p>
      </div>
    </div>

    <!-- WHO WE ARE -->
    <div class="about-block frame">
      <div class="about-text">
        <span class="section-eyebrow">Who we are</span>
        <h3>Guided by Heritage</h3>
        <p>At Wine Exchange, we believe every bottle carries its own narrative — shaped by the soil it grows in, the
          hands that nurture it, and the heritage that guides each vintage.</p>
        <p>From vibrant modern wines to enduring and iconic favorites, we curate a collection meant to spark curiosity
          and elevate any moment. At Wine Exchange, exceptional wine is just the beginning.</p>
      </div>
      <div class="about-image">
        <img src="../../images/cheers.jpg" alt="Who We Are image" />
      </div>
    </div>

    <!-- WINE COLLECTION -->
    <div class="about-block frame">
      <div class="about-image">
        <img src="../../images/wine_collection.jpg" alt="Wine Collection image" />
      </div>
      <div class="about-text">
        <span class="section-eyebrow">Our collection</span>
        <h2>Our Wine Collection</h2>
        <p>Our wines are carefully selected from the finest vineyards across the world, each with a unique story and
          character. From bold reds to crisp whites and sparkling delights, every bottle is chosen to delight your
          senses and elevate your dining experience. Discover wines crafted with passion, tradition, and a touch of
          innovation in every sip.</p>
      </div>
    </div>

  </section>

  <!-- FOOTER -->
  <?php include 'footer.php'; ?>

  <script>
    // DARK MODE
    const darkButton = document.getElementById("dark-mode");
    if (localStorage.getItem("dark_mode") === "on") {
      document.documentElement.classList.add("darkmode");
    }
    darkButton.addEventListener("click", () => {
      document.documentElement.classList.toggle("darkmode");
      localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
    });
  </script>

</body>
</html>