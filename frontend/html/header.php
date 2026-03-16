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
  /* ══════════════════════════════════════════
     NAVBAR
  ══════════════════════════════════════════ */

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