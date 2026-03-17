<?php
// navbar.php — reusable navigation header
// Requires: $conn (db connection), session_start() must be called before including this file

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
<style>
  /* NAVBAR */

  .navbar {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: #6b1a2e;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 32px;
    height: 62px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    transition: background 0.3s ease, border-color 0.3s ease;
  }

  .darkmode .navbar {
    background: #12060a;
    border-bottom: 1px solid rgba(255,255,255,0.07);
  }

  .navbar-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    flex: 0 0 220px;
  }

  body.info {
    padding-top: 0; 
  }

  .navbar-logo img {
    width: 32px;
    height: 32px;
    object-fit: contain;
    filter: brightness(0) invert(1);
    opacity: 0.9;
    flex-shrink: 0;
    transition: opacity 0.2s ease;
  }
  .navbar-logo:hover img { opacity: 1; }

  .navbar-logo-text {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0.06em;
    line-height: 1;
    color: #ffffff;
    white-space: nowrap;
    transition: color 0.3s ease;
  }
  .darkmode .navbar-logo-text {
    color: #e8c8b0;
  }

  .navbar-logo-text span {
    display: block;
    font-size: 9px;
    font-family: 'Jost', sans-serif;
    font-weight: 400;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    margin-top: 3px;
    color: rgba(255,255,255,0.5);
    transition: color 0.3s ease;
  }
  .darkmode .navbar-logo-text span {
    color: rgba(232,200,176,0.45);
  }

  .navbar-links {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
    justify-content: center;
  }

  .navbar-links a {
    position: relative;
    display: block;
    padding: 8px 13px;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    text-decoration: none;
    border-radius: 3px;
    color: rgba(255,255,255,0.78);
    transition: color 0.2s ease, background 0.2s ease;
  }
  .navbar-links a:hover {
    color: #ffffff;
    background: rgba(255,255,255,0.12);
  }
  .darkmode .navbar-links a {
    color: rgba(232,200,176,0.65);
  }
  .darkmode .navbar-links a:hover {
    color: #e8c8b0;
    background: rgba(255,255,255,0.07);
  }

  .navbar-links a::after {
    content: '';
    position: absolute;
    bottom: 4px; left: 13px; right: 13px;
    height: 1px;
    background: rgba(255,255,255,0.5);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.25s ease;
  }
  .navbar-links a:hover::after { transform: scaleX(1); }
  .darkmode .navbar-links a::after { background: rgba(232,200,176,0.5); }

  /* ── GIFT LINK PILL ── */
  .navbar-gift-link {
    background: rgba(255,255,255,0.15) !important;
    border: 1px solid rgba(255,255,255,0.3) !important;
    border-radius: 20px !important;
    padding: 5px 14px !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    transition: background 0.2s ease, transform 0.15s ease, border-color 0.2s ease !important;
  }
  .navbar-gift-link:hover {
    background: rgba(255,255,255,0.26) !important;
    border-color: rgba(255,255,255,0.55) !important;
    transform: translateY(-1px) !important;
    color: #ffffff !important;
  }
  .navbar-gift-link::after {
    display: none !important;
  }
  .darkmode .navbar-gift-link {
    background: rgba(232,200,176,0.12) !important;
    border-color: rgba(232,200,176,0.28) !important;
    color: #e8c8b0 !important;
  }
  .darkmode .navbar-gift-link:hover {
    background: rgba(232,200,176,0.22) !important;
    border-color: rgba(232,200,176,0.5) !important;
    color: #e8c8b0 !important;
  }

  .navbar-right {
    display: flex;
    align-items: center;
    gap: 3px;
    flex: 0 0 220px;
    justify-content: flex-end;
  }

  .navbar-right form {
    position: relative;
    margin-right: 4px;
  }

  .navbar-right form input[type="text"] {
    height: 34px;
    padding: 0 10px 0 28px;
    border-radius: 4px;
    border: 1px solid rgba(255,255,255,0.22);
    background: rgba(255,255,255,0.11);
    color: #ffffff;
    font-size: 12px;
    font-family: 'Jost', sans-serif;
    letter-spacing: 0.04em;
    width: 110px;
    outline: none;
    transition: border-color 0.2s ease, background 0.2s ease, width 0.3s ease;
  }
  .navbar-right form input[type="text"]::placeholder { color: rgba(255,255,255,0.42); }
  .navbar-right form input[type="text"]:focus {
    border-color: rgba(255,255,255,0.48);
    background: rgba(255,255,255,0.16);
    width: 140px;
  }

  .darkmode .navbar-right form input[type="text"] {
    border-color: rgba(232,200,176,0.18);
    background: rgba(255,255,255,0.06);
    color: #e8c8b0;
  }
  .darkmode .navbar-right form input[type="text"]::placeholder { color: rgba(232,200,176,0.35); }
  .darkmode .navbar-right form input[type="text"]:focus {
    border-color: rgba(232,200,176,0.4);
    background: rgba(255,255,255,0.09);
  }

  .navbar-right form::before {
    content: '\f002';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.42);
    font-size: 11px;
    pointer-events: none;
    transition: color 0.3s ease;
  }
  .darkmode .navbar-right form::before { color: rgba(232,200,176,0.35); }

  .nav-divider {
    width: 1px;
    height: 22px;
    background: rgba(255,255,255,0.2);
    margin: 0 5px;
    flex-shrink: 0;
    transition: background 0.3s ease;
  }
  .darkmode .nav-divider { background: rgba(255,255,255,0.1); }

  .wishlist-nav-button {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 4px;
    border: 1px solid transparent;
    background: transparent;
    color: rgba(255,255,255,0.8);
    font-size: 14px;
    cursor: pointer;
    transition: color 0.2s ease, background 0.2s ease, border-color 0.2s ease;
  }
  .wishlist-nav-button:hover {
    color: #ffffff;
    background: rgba(255,255,255,0.12);
    border-color: rgba(255,255,255,0.18);
  }
  .darkmode .wishlist-nav-button {
    color: rgba(232,200,176,0.7);
  }
  .darkmode .wishlist-nav-button:hover {
    color: #e8c8b0;
    background: rgba(255,255,255,0.08);
    border-color: rgba(232,200,176,0.18);
  }

  .wishlist-count {
    position: absolute;
    top: 4px; right: 2px;
    background: #ffffff;
    color: #6b1a2e;
    font-size: 9px;
    font-weight: 700;
    font-family: 'Jost', sans-serif;
    padding: 1px 4px;
    border-radius: 50px;
    min-width: 15px;
    text-align: center;
    line-height: 1.5;
    transition: background 0.3s ease, color 0.3s ease;
  }
  .darkmode .wishlist-count {
    background: #e8c8b0;
    color: #12060a;
  }

  .dark-mode-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    border-radius: 4px;
    border: 1px solid transparent;
    background: transparent;
    cursor: pointer;
    padding: 0;
    transition: background 0.2s ease, border-color 0.2s ease;
  }
  .dark-mode-button:hover {
    background: rgba(255,255,255,0.12);
    border-color: rgba(255,255,255,0.18);
  }
  .darkmode .dark-mode-button:hover {
    background: rgba(255,255,255,0.08);
    border-color: rgba(232,200,176,0.18);
  }

  .dark-mode-button img {
    width: 16px; height: 16px;
    object-fit: contain;
    filter: brightness(0) invert(1);
    opacity: 0.78;
    transition: opacity 0.2s ease, filter 0.3s ease;
  }
  .dark-mode-button:hover img { opacity: 1; }
  .darkmode .dark-mode-button img {
    filter: brightness(0) invert(1) sepia(0.3) saturate(1.5) hue-rotate(340deg);
    opacity: 0.75;
  }

  .wishlist-sidebar {
    position: fixed;
    top: 0; right: -420px;
    width: 380px; height: 100%;
    background: #f4f1f2;
    padding: 30px;
    box-shadow: -5px 0 20px rgba(0,0,0,.25);
    z-index: 2000;
    overflow-y: auto;
    transition: right .4s ease;
  }
  .wishlist-sidebar.active { right: 0; }

  .wishlist-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.5);
    display: none; z-index: 1500;
  }
  .wishlist-overlay.active { display: block; }

  .close-wishlist {
    font-size: 22px; cursor: pointer;
    text-align: right; margin-bottom: 15px;
  }
  .close-wishlist i {
    transition: color .2s ease, transform .2s ease;
    display: inline-block;
  }

  #wishlist-items {
    display: flex; flex-direction: column;
    gap: 15px; margin-top: 20px;
  }

  .wishlist-item {
    display: flex; gap: 12px; align-items: center;
    background: white;
    border-radius: 10px; padding: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
    position: relative;
    animation: slideInWishlist .3s ease both;
    transition: transform .25s ease, box-shadow .25s ease;
  }
  .wishlist-item:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.12); }

  .wishlist-img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; }
  .wishlist-info { flex: 1; }
  .wishlist-name { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
  .wishlist-price { color: #7b1e3a; font-weight: bold; margin-bottom: 8px; }
  .wishlist-actions { display: flex; gap: 8px; }
  .wishlist-view {
    padding: 4px 10px; font-size: 12px;
    border-radius: 6px; background: #eee;
    text-decoration: none; color: #333;
    transition: background .2s ease;
  }
  .wishlist-view:hover { background: #ddd; }

  .remove-wishlist {
    position: absolute; top: 6px; right: 6px;
    border: none; background: none;
    font-size: 14px; cursor: pointer;
    color: #999;
    transition: color .2s ease, transform .2s ease;
  }
  .remove-wishlist:hover { color: red; transform: scale(1.2); }

  html.darkmode .wishlist-sidebar { background: #121212; color: #fff; }
  html.darkmode .wishlist-item { background: #1e1e1e; border: 1px solid #333; box-shadow: none; }
  html.darkmode .wishlist-name { color: #fff; }
  html.darkmode .wishlist-price { color: #ff6b6b; }
  html.darkmode .wishlist-view { background: #2c2c2c; color: #fff; }
  html.darkmode .wishlist-view:hover { background: #3a3a3a; }
  html.darkmode .remove-wishlist { color: #bbb; }
  html.darkmode .remove-wishlist:hover { color: #ff4d4d; }
  html.darkmode #wishlist-items p { color: #ccc; }

  @media (max-width: 900px) {
    .navbar-links { display: none; }
  }
  @media (max-width: 640px) {
    .navbar { padding: 0 16px; }
    .navbar-right form input[type="text"] { width: 110px; }
    .navbar-right form input[type="text"]:focus { width: 130px; }
    .navbar-logo-text { display: none; }
  }
</style>

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
<nav class="navbar">

  <!-- Logo -->
  <a href="index.php" class="navbar-logo">
    <img src="../../images/icon.png" alt="Wine Exchange Logo">
    <div class="navbar-logo-text">
      Wine Exchange
      <span>Est. 2010</span>
    </div>
  </a>

  <!-- Centre links -->
  <div class="navbar-links">
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="search.php">Wines</a>
    <a href="Gift-quiz.php" class="navbar-gift-link">Gift a Wine</a>
    <a href="contact-us.php">Contact Us</a>
  </div>

  <!-- Right: search + icons -->
  <div class="navbar-right">
    <form method="POST" action="search.php">
      <input type="text" name="search" placeholder="Search wines…">
      <input type="hidden" name="submitted" value="true">
    </form>

    <div class="nav-divider"></div>

    <button onclick="location.href='<?= $accountLink ?>'" class="wishlist-nav-button" aria-label="Account">
      <i class="fas fa-user"></i>
    </button>
    <button onclick="location.href='basket.php'" class="wishlist-nav-button" aria-label="Basket">
      <i class="fas fa-shopping-basket"></i>
      <span id="basket-count" class="wishlist-count" style="display:none;">0</span>
    </button>
    <button id="wishlist-toggle" class="wishlist-nav-button" aria-label="Wishlist">
      <i class="fas fa-heart"></i>
      <span id="wishlist-count" class="wishlist-count">0</span>
    </button>

    <div class="nav-divider"></div>

    <button id="dark-mode" class="dark-mode-button" aria-label="Toggle dark mode">
      <img src="../../images/darkmode.png" alt="Dark Mode">
    </button>
  </div>

</nav>

<!-- DARK MODE SCRIPT -->
<script>
  var dmBtn = document.getElementById('dark-mode');
  if (localStorage.getItem('dark_mode') === 'on') {
    document.documentElement.classList.add('darkmode');
  }
  dmBtn.addEventListener('click', function() {
    document.documentElement.classList.toggle('darkmode');
    localStorage.setItem('dark_mode', document.documentElement.classList.contains('darkmode') ? 'on' : 'off');
  });
</script>

<!-- WISHLIST SCRIPT -->
<script>
  var loggedIn = <?php echo isset($_SESSION['customerID']) ? 'true' : 'false'; ?>;

  var wlSidebar = document.getElementById('wishlistSidebar');
  var wlOverlay = document.getElementById('wishlistOverlay');
  var wlItems   = document.getElementById('wishlist-items');
  var wlCount   = document.getElementById('wishlist-count');
  var wlToggle  = document.getElementById('wishlist-toggle');
  var wlClose   = document.getElementById('closeWishlist');

  wlToggle.addEventListener('click', function() { wlSidebar.classList.add('active'); wlOverlay.classList.add('active'); });

  function closeWL() { wlSidebar.classList.remove('active'); wlOverlay.classList.remove('active'); }
  wlClose.addEventListener('click', closeWL);
  wlOverlay.addEventListener('click', closeWL);

  function getGuestWishlist() {
    try { return JSON.parse(localStorage.getItem('wishlist')) || []; }
    catch(e) { return []; }
  }
  function saveGuestWishlist(list) { localStorage.setItem('wishlist', JSON.stringify(list)); }

  function loadWishlist() {
    if (loggedIn) {
      fetch('get_wishlist.php').then(function(r) { return r.json(); }).then(renderWishlist);
    } else {
      renderWishlist(getGuestWishlist());
    }
  }

  function renderWishlist(list) {
    wlItems.innerHTML = '';
    if (!list || !list.length) {
      wlItems.innerHTML = '<p>Your wishlist is empty.</p>';
      wlCount.textContent = 0;
      return;
    }
    wlCount.textContent = list.length;
    list.forEach(function(wine, i) {
      var imgSrc = loggedIn
        ? (wine.imageUrl ? '../../images/' + wine.imageUrl : '../../images/placeholder.jpg')
        : (wine.imageUrl || '../../images/placeholder.jpg');
      var wineId = wine.wineId || wine.id;
      var name   = wine.wineName || wine.name;
      var el = document.createElement('div');
      el.className = 'wishlist-item';
      el.style.animationDelay = (i * 0.06) + 's';
      el.innerHTML =
        '<img src="' + imgSrc + '" class="wishlist-img">' +
        '<div class="wishlist-info">' +
          '<div class="wishlist-name">' + name + '</div>' +
          '<div class="wishlist-price">&pound;' + wine.price + '</div>' +
          '<div class="wishlist-actions"><a href="wineinfo.php?id=' + wineId + '" class="wishlist-view">View</a></div>' +
        '</div>' +
        '<button class="remove-wishlist" data-id="' + wineId + '" data-index="' + i + '"><i class="fas fa-times"></i></button>';
      wlItems.appendChild(el);
    });
  }

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.remove-wishlist');
    if (!btn) return;
    var id = btn.dataset.id, idx = btn.dataset.index;
    if (loggedIn) {
      fetch('remove_from_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'wineId=' + id
      }).then(loadWishlist);
    } else {
      var wl = getGuestWishlist();
      wl.splice(idx, 1);
      saveGuestWishlist(wl);
      renderWishlist(wl);
    }
  });

  loadWishlist();
</script>