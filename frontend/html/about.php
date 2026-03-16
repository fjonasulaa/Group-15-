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

    /* ─── WISHLIST — untouched ──────────────────────────────── */
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

    .wishlist-basket {
      border: none;
      background: #7b1e3a;
      color: white;
      padding: 4px 8px;
      border-radius: 6px;
      cursor: pointer;
    }

    .wishlist-basket:hover { background: #5e152c; }

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

    .wishlist-button {
      padding: 8px 14px;
      border: 2px solid #e63946;
      background: white;
      color: #e63946;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
    }

    .wishlist-button i { margin-right: 5px; }

    .wishlist-button:hover {
      background-color: #e63946;
      color: white;
    }

    .wishlist-button.active {
      background-color: #e63946;
      color: white;
    }

    .wishlist-nav-button {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #e63946;
      margin-left: 10px;
      position: relative;
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

    /* ─── DARK MODE — wishlist only ─────────────────────────── */
    html.darkmode .wishlist-sidebar {
      background: #121212;
      color: #ffffff;
    }

    html.darkmode .wishlist-sidebar h3 { color: #ffffff; }

    html.darkmode #wishlist-items p { color: #cccccc; }

    html.darkmode .wishlist-item {
      background: #1e1e1e;
      border: 1px solid #333;
      box-shadow: none;
    }

    html.darkmode .wishlist-name { color: #ffffff; }

    html.darkmode .wishlist-price { color: #ff6b6b; }

    html.darkmode .wishlist-view {
      background: #2c2c2c;
      color: #ffffff;
    }

    html.darkmode .wishlist-view:hover { background: #3a3a3a; }

    html.darkmode .wishlist-basket { background: #e63946; }

    html.darkmode .wishlist-basket:hover { background: #c92d3a; }

    html.darkmode .remove-wishlist { color: #bbbbbb; }

    html.darkmode .remove-wishlist:hover { color: #ff4d4d; }

  </style>
</head>

<body>

  <div class="wishlist-overlay" id="wishlistOverlay"></div>

  <div class="wishlist-sidebar" id="wishlistSidebar">
    <div class="close-wishlist" id="closeWishlist">
      <i class="fa fa-times"></i>
    </div>
    <h3>Your Wishlist</h3>
    <div id="wishlist-items">
      <p>Your wishlist is empty.</p>
    </div>
  </div>

  <!-- NAVBAR — untouched -->
  <div class="navbar">
    <img src="../../images/icon.png" alt="Wine Exchange Logo">
    <div class="navbar-links">
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a>
      <a href="search.php">Wines</a>
      <a href="basket.php">Basket</a>
      <a href="contact-us.php">Contact Us</a>
      <a href="reviews.html">Feedback</a>
    </div>
    <div class="navbar-right">
      <form method="POST" action="search.php">
        <input type="text" name="search" placeholder="Search">
        <input type="hidden" name="submitted" value="true" />
      </form>
      <a href="log-in.php">Login</a>
      <a href="signup.php">Sign up</a>
      <a href="account.php">Account</a>
      <button id="dark-mode" class="dark-mode-button">
        <img src="../../images/darkmode.png" alt="Dark Mode" />
      </button>
      <button id="wishlist-toggle" class="wishlist-nav-button">
        <i class="fas fa-heart"></i>
        <span id="wishlist-count" class="wishlist-count">0</span>
      </button>
    </div>
  </div>


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

    // WISHLIST
    const loggedIn = <?php echo isset($_SESSION['customerID']) ? "true" : "false"; ?>;
    const wishlistBtn = document.getElementById("wishlist-toggle");
    const wishlistSidebar = document.getElementById("wishlistSidebar");
    const closeWishlist = document.getElementById("closeWishlist");
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
    const wishlistCount = document.getElementById("wishlist-count");

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
          .then(data => { renderWishlist(data); });
      } else {
        const list = getGuestWishlist();
        renderWishlist(list);
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
        let image;
        if (loggedIn) {
          image = wine.imageUrl ? "../../images/" + wine.imageUrl : "../../images/placeholder.jpg";
        } else {
          image = wine.imageUrl || "../../images/placeholder.jpg";
        }

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

    document.addEventListener("click", function(e) {
      const removeBtn = e.target.closest(".remove-wishlist");
      if (!removeBtn) return;

      const wineId = removeBtn.dataset.id;
      const index = removeBtn.dataset.index;

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

</body>
</html>