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

  <style>

    /* PAGE BACKGROUND */

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-image: url("../../images/About us background.png");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    .about {
      padding-top: 120px;
    }

    /* MAIN TITLES */

    .center-title {
      color: white;
      text-align: center;
      text-shadow: 0 3px 8px rgba(0,0,0,0.7);
    }

    /* CONTENT FRAMES */

    .frame {
      background: rgba(0,0,0,0.3);
      backdrop-filter: blur(5px);
      border-radius: 10px;
      padding: 25px;
      color: #f5f5f5;
    }

    .frame p,
    .frame h3,
    .frame li,
    .frame span {
      color: #f5f5f5;
    }

    /* SLOGAN */

    .slogan-section {
      text-align: center;
      margin: 40px auto 20px auto;
      max-width: 100%;
    }

    /* CORE VALUES */

    .values-section {
      margin: 20px 0;
    }

    .values-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 25px;
      margin-top: 20px;
    }

    .value-card {
      background: rgba(0,0,0,0.3);
      backdrop-filter: blur(5px);
      padding: 35px;
      border-radius: 10px;
      text-align: center;
      color: #f5f5f5;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .value-card p,
    .value-card span,
    .value-card li {
      color: #f5f5f5;
    }

    .value-card h3 {
      margin-bottom: 12px;
      font-size: 22px;
      color: #ff6b6b;
    }

    .value-card p {
      font-size: 17px;
      line-height: 1.7;
    }

    .value-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    }

    /* ABOUT BLOCKS — Our Goal, Who We Are, Our Wine Collection */

    .about-block {
      display: flex;
      align-items: center;
      gap: 30px;
      margin: 20px 0;
    }

    .about-image {
      flex: 0 0 auto;
    }

    .about-image img {
      width: 400px;
      height: 300px;
      object-fit: cover;
      border-radius: 8px;
      display: block;
    }

    .about-text {
      flex: 1;
      color: #f5f5f5;
      min-width: 0;
    }

    .about-text h3,
    .about-text h2 {
      margin-bottom: 15px;
      font-size: 28px;
      color: #ff6b6b;
    }

    .about-text p {
      font-size: 18px;
      line-height: 1.8;
      margin: 0 0 12px 0;
    }

    .slogan {
      font-size: 40px;
      font-style: italic;
      margin-bottom: 15px;
      color: #ff6b6b;
    }

    .slogan-text {
      font-size: 22px;
      line-height: 1.8;
      color: #f5f5f5;
    }

    /* FOOTER */

    .footer {
      background: rgba(244,244,244,0.9);
      backdrop-filter: blur(6px);
      padding: 30px 10%;
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

    .footer-links {
      list-style: none;
      padding: 0;
    }

    .footer-links li {
      margin: 5px 0;
    }

    .footer-links a {
      text-decoration: none;
      color: inherit;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    .footer-button {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border-radius: 4px;
      text-decoration: none;
    }

    .footer-button:hover {
      opacity: 0.9;
    }

    .footer-bottom {
      text-align: center;
      margin-top: 20px;
      padding-top: 10px;
      border-top: 1px solid #ccc;
      font-size: 14px;
    }

    /* WISHLIST ITEMS */

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

    .wishlist-info {
      flex: 1;
    }

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

    .wishlist-actions {
      display: flex;
      gap: 8px;
    }

    .wishlist-view {
      padding: 4px 10px;
      font-size: 12px;
      border-radius: 6px;
      background: #eee;
      text-decoration: none;
      color: #333;
    }

    .wishlist-view:hover {
      background: #ddd;
    }

    .wishlist-basket {
      border: none;
      background: #7b1e3a;
      color: white;
      padding: 4px 8px;
      border-radius: 6px;
      cursor: pointer;
    }

    .wishlist-basket:hover {
      background: #5e152c;
    }

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

    .remove-wishlist:hover {
      color: red;
    }

    .wishlist-button {
      padding: 8px 14px;
      border: 2px solid #e63946;
      background: white;
      color: #e63946;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
    }

    .wishlist-button i {
      margin-right: 5px;
    }

    .wishlist-button:hover {
      background-color: #e63946;
      color: white;
    }

    .wishlist-button.active {
      background-color: #e63946;
      color: white;
    }

    /* WISHLIST NAV HEART */

    .wishlist-nav-button {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #e63946;
      margin-left: 10px;
      position: relative;
    }

    /* WISHLIST COUNTER BADGE */

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

    /* SIDEBAR */

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

    .wishlist-sidebar.active {
      right: 0;
    }

    .wishlist-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      display: none;
      z-index: 1500;
    }

    .wishlist-overlay.active {
      display: block;
    }

    .close-wishlist {
      font-size: 22px;
      cursor: pointer;
      text-align: right;
      margin-bottom: 15px;
    }

    /* DARK MODE — same style as light, already handled above */

    html.darkmode .footer {
      background: rgba(0,0,0,0.5);
      color: #eee;
    }

    html.darkmode .footer-links a {
      color: #ddd;
    }

    html.darkmode .footer-bottom {
      border-top: 1px solid #555;
    }

    html.darkmode .wishlist-sidebar {
      background: #121212;
      color: #ffffff;
    }

    html.darkmode .wishlist-sidebar h3 {
      color: #ffffff;
    }

    html.darkmode #wishlist-items p {
      color: #cccccc;
    }

    html.darkmode .wishlist-item {
      background: #1e1e1e;
      border: 1px solid #333;
      box-shadow: none;
    }

    html.darkmode .wishlist-name {
      color: #ffffff;
    }

    html.darkmode .wishlist-price {
      color: #ff6b6b;
    }

    html.darkmode .wishlist-view {
      background: #2c2c2c;
      color: #ffffff;
    }

    html.darkmode .wishlist-view:hover {
      background: #3a3a3a;
    }

    html.darkmode .wishlist-basket {
      background: #e63946;
    }

    html.darkmode .wishlist-basket:hover {
      background: #c92d3a;
    }

    html.darkmode .remove-wishlist {
      color: #bbbbbb;
    }

    html.darkmode .remove-wishlist:hover {
      color: #ff4d4d;
    }

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

  <!-- NAVBAR -->
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

    <h1 class="center-title">ABOUT US</h1>

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
        <h3>Our Goal</h3>
        <p>At Wine Exchange, we believe that every bottle tells a story — one shaped by the vineyards it comes from,
          the people who craft it, and the traditions passed down through generations.</p>
        <p>From bold, contemporary expressions to timeless, celebrated classics, we curate a diverse selection designed
          to inspire discovery and elevate every occasion.</p>
      </div>
    </div>

    <!-- WHO WE ARE -->

    <div class="about-block frame">
      <div class="about-text">
        <h3>Who We Are</h3>
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
        <h2>Our Wine Collection</h2>
        <p>Our wines are carefully selected from the finest vineyards across the world, each with a unique story and
          character. From bold reds to crisp whites and sparkling delights, every bottle is chosen to delight your
          senses and elevate your dining experience. Discover wines crafted with passion, tradition, and a touch of
          innovation in every sip.</p>
      </div>
    </div>

  </section>


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
