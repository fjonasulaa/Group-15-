<html> 
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
</html>

<?php
// navbar.php — reusable navigation header
// Requires: $conn (db connection), session_start() must be called before including this file
// NOTE: $conn is provided by the including page via db_connect.php — do NOT create a new connection here.

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

$basketTotal = 0;
$basketCount = 0;
if (!empty($_SESSION['basket']) && isset($conn)) {
    foreach ($_SESSION['basket'] as $id => $qty) {
        $result = $conn->query("SELECT price FROM wines WHERE wineId = " . intval($id));
        if ($result && $row = $result->fetch_assoc()) {
            $basketTotal += $row['price'] * $qty;
            $basketCount += $qty;
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
    flex: 0 0 260px;
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

.darkmode .dark-mode-button {
    background: rgba(255,255,255,0.12);
    border-color: rgba(255,255,255,0.18);
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

.navbar-links a.active {
    background: rgba(255,255,255,0.18);
    color: #ffffff;
}

.navbar-links a.active::after {
    transform: scaleX(1);
}

.navbar-gift-link.active {
    background: transparent !important;
    color: rgba(255,255,255,0.78) !important;
}

.navbar-gift-link.active::after {
    transform: scaleX(0) !important;
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
    background: rgba(255,255,255,0.12) !important;
    border: 1px solid rgba(255,255,255,0.45) !important;
    border-radius: 20px !important;  /* rounder pill shape */
    padding: 8px 13px !important;
    color: rgba(255,255,255,0.78) !important;
    font-weight: 500 !important;
    transition: background 0.2s ease !important;
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
    flex: 0 0 260px;
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
    <a href="gift-quiz.php" class="navbar-gift-link">Find a Gift</a>
    <a href="contact-us.php">Contact Us</a>
  </div>

  <!-- Right: search + icons -->
  <div class="navbar-right">
    <form method="GET" action="search.php">
      <input type="text" name="search" placeholder="Search wines…" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    </form>

    <div class="nav-divider"></div>

    <button onclick="location.href='<?= $accountLink ?>'" class="wishlist-nav-button" aria-label="Account">
      <i class="fas fa-user"></i>
    </button>
  
<button onclick="location.href='basket.php'" class="wishlist-nav-button basket-nav-button" aria-label="Basket">
    <i class="fas fa-shopping-basket"></i>
    <span id="basket-count" class="wishlist-count">0</span>
    <span class="basket-tooltip" id="basket-tooltip">£<?= number_format($basketTotal, 2) ?></span>
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

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.remove-wishlist');
    if (!btn) return;
    var id = btn.dataset.id, idx = btn.dataset.index;
    if (loggedIn) {
      fetch('remove_from_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'wineId=' + id
      }).then(function() {
        loadWishlist();
        window.dispatchEvent(new CustomEvent('wishlistUpdated'));
      });
    } else {
      var wl = getGuestWishlist();
      wl.splice(idx, 1);
      saveGuestWishlist(wl);
      renderWishlist(wl);
      window.dispatchEvent(new CustomEvent('wishlistUpdated'));
    }
  });
</script>

<!-- WISHLIST & BASKET SCRIPT -->
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

  // ── BASKET COUNT ──
  var basketCount = document.getElementById('basket-count');

function updateBasketBadge(n) {
    basketCount.textContent = n;
    basketCount.style.display = '';
}

  function loadBasketCount() {
    fetch('get-basket-count.php', { credentials: 'include' })
      .then(function(r) { return r.json(); })
      .then(function(data) { updateBasketBadge(data.count || 0); })
      .catch(function() { updateBasketBadge(0); });
  }

  loadBasketCount();

  window.addEventListener('basketUpdated', loadBasketCount);
</script>

<script>
  // basket tooltip
  function updateBasketTooltip() {
    fetch('get-basket-count.php', { credentials: 'include' })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        var tooltip = document.getElementById('basket-tooltip');
        if (tooltip && data.total !== undefined) {
          tooltip.textContent = '£' + parseFloat(data.total).toFixed(2);
        }
      });
  }

  updateBasketTooltip();
  window.addEventListener('basketUpdated', updateBasketTooltip);
</script>

<script>
  var currentPage = window.location.pathname.split('/').pop();

  // nav links
  document.querySelectorAll('.navbar-links a').forEach(function(link) {
      var linkPage = link.getAttribute('href').split('/').pop();
      if (currentPage === linkPage) {
          link.classList.add('active');
      }
  });

  // icon buttons (account + basket)
  document.querySelectorAll('.wishlist-nav-button').forEach(function(btn) {
      var href = btn.getAttribute('onclick');
      if (!href) return;
      var match = href.match(/location\.href='([^']+)'/);
      if (!match) return;
      var btnPage = match[1].split('/').pop();
      if (currentPage === btnPage) {
          btn.style.background = 'rgba(255,255,255,0.12)';
          btn.style.borderColor = 'rgba(255,255,255,0.18)';
      }
  });
</script>