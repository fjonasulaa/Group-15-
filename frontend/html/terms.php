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
  <title>Terms & Conditions | Wine Exchange</title>
  <link rel="icon" type="image/x-icon" href="../../images/icon.png">
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>

    :root {
      --wine:       #6B0F1A;
      --wine-dark:  #4a0912;
      --wine-light: #8b1525;
      --gold:       #c9a84c;
      --text:       #1a0a06;
      --text-mid:   #4a2a30;
      --text-soft:  #8a5a60;
      --bg:         #ffffff;
      --bg-warm:    #fdf6f0;
      --bg-panel:   #fdf0e8;
      --border:     #e8c8c8;
      --radius:     8px;
      --transition: 0.22s ease;
    }

    *, *::before, *::after { box-sizing: border-box; }

    html, body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      background: var(--bg-warm);
      font-family: Georgia, 'Times New Roman', serif;
      color: var(--text);
    }

    /* ── WISHLIST SIDEBAR ── */
    .wishlist-sidebar { position: fixed; top: 0; right: -420px; width: 380px; height: 100%; background: #f4f1f2; padding: 30px; box-shadow: -5px 0 20px rgba(0,0,0,0.25); transition: right 0.4s ease; z-index: 2000; overflow-y: auto; }
    .wishlist-sidebar.active { right: 0; }
    .wishlist-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; z-index: 1500; }
    .wishlist-overlay.active { display: block; }
    .close-wishlist { font-size: 22px; cursor: pointer; text-align: right; margin-bottom: 15px; }
    #wishlist-items { display: flex; flex-direction: column; gap: 15px; margin-top: 20px; }
    .wishlist-item { display: flex; gap: 12px; align-items: center; background: white; border-radius: 10px; padding: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); position: relative; }
    .wishlist-img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; }
    .wishlist-info { flex: 1; }
    .wishlist-name { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
    .wishlist-price { color: #7b1e3a; font-weight: bold; margin-bottom: 8px; }
    .wishlist-actions { display: flex; gap: 8px; }
    .wishlist-view { padding: 4px 10px; font-size: 12px; border-radius: 6px; background: #eee; text-decoration: none; color: #333; }
    .wishlist-view:hover { background: #ddd; }
    .remove-wishlist { position: absolute; top: 6px; right: 6px; border: none; background: none; font-size: 14px; cursor: pointer; color: #999; }
    .remove-wishlist:hover { color: red; }
    .wishlist-nav-button { position: relative; background: none; border: none; font-size: 20px; cursor: pointer; color: #e63946; margin-left: 10px; }
    .wishlist-count { position: absolute; top: -6px; right: -8px; background: #e63946; color: white; font-size: 11px; font-weight: bold; padding: 2px 6px; border-radius: 50px; min-width: 18px; text-align: center; }

    /* ── LAYOUT ── */
    .tc-wrapper {
      display: flex;
      gap: 48px;
      max-width: 1100px;
      margin: 0 auto;
      padding: 120px 24px 80px;
      align-items: flex-start;
    }

    /* ── SIDEBAR NAV ── */
    .tc-nav {
      flex: 0 0 220px;
      position: sticky;
      top: 110px;
    }

    .tc-nav p {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--text-soft);
      margin: 0 0 12px;
    }

    .tc-nav ul {
      list-style: none;
      margin: 0;
      padding: 0;
      border-left: 2px solid var(--border);
    }

    .tc-nav ul li a {
      display: block;
      padding: 7px 14px;
      font-size: 14px;
      color: var(--text-mid);
      text-decoration: none;
      line-height: 1.5;
      transition: color 0.18s, border-color 0.18s;
      border-left: 2px solid transparent;
      margin-left: -2px;
    }

    .tc-nav ul li a:hover {
      color: var(--wine);
      border-left-color: var(--wine);
    }

    /* ── MAIN CONTENT ── */
    .tc-content {
      flex: 1;
      min-width: 0;
    }

    /* ── PAGE TITLE ── */
    .tc-page-title {
      margin-bottom: 48px;
    }

    .tc-page-title h1 {
      font-size: 3rem;
      font-weight: 700;
      color: var(--wine);
      margin: 0 0 10px;
      letter-spacing: 0.01em;
    }

    .tc-page-title p {
      font-size: 16px;
      color: var(--text-soft);
      margin: 0 0 6px;
      line-height: 1.7;
    }

    .tc-title-divider {
      display: block;
      width: 48px;
      height: 3px;
      background: var(--wine);
      border-radius: 2px;
      margin: 16px 0 0;
    }

    /* ── LAST UPDATED BADGE ── */
    .tc-updated {
      display: inline-block;
      background: var(--bg-panel);
      border: 1px solid var(--border);
      border-radius: 20px;
      font-size: 13px;
      color: var(--text-soft);
      padding: 4px 14px;
      margin-bottom: 40px;
    }

    /* ── SECTIONS ── */
    .tc-section {
      margin-bottom: 48px;
      scroll-margin-top: 110px;
    }

    .tc-section h2 {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--wine);
      margin: 0 0 16px;
      padding-bottom: 10px;
      border-bottom: 1px solid var(--border);
    }

    .tc-section p {
      font-size: 16px;
      color: var(--text-mid);
      line-height: 1.9;
      margin: 0 0 14px;
    }

    .tc-section p:last-child { margin-bottom: 0; }

    .tc-section ul {
      margin: 10px 0 14px 0;
      padding-left: 22px;
    }

    .tc-section ul li {
      font-size: 16px;
      color: var(--text-mid);
      line-height: 1.9;
      margin-bottom: 6px;
    }

    /* ── HIGHLIGHT BOX ── */
    .tc-highlight {
      background: #fff;
      border: 1px solid var(--border);
      border-left: 4px solid var(--wine);
      border-radius: var(--radius);
      padding: 18px 22px;
      margin: 16px 0;
    }

    .tc-highlight p {
      margin: 0;
      font-size: 15px;
      color: var(--text-mid);
      line-height: 1.8;
    }

    /* ── CONTACT CTA ── */
    .tc-cta {
      background: #fff;
      border: 1px solid var(--border);
      border-top: 3px solid var(--gold);
      border-radius: var(--radius);
      padding: 40px 36px;
      text-align: center;
      margin-top: 56px;
    }

    .tc-cta p {
      font-size: 17px;
      color: var(--text-mid);
      margin: 0 0 24px;
      line-height: 1.7;
    }

    .tc-cta a {
      display: inline-block;
      background: var(--wine);
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      padding: 12px 30px;
      border-radius: var(--radius);
      text-decoration: none;
      font-family: Georgia, serif;
      letter-spacing: 0.04em;
      transition: background 0.18s;
    }

    .tc-cta a:hover { background: var(--wine-light); }

    /* ── FOOTER ── */
    .footer-newsletter { background: #6b1a2e; padding: 18px 32px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
    .footer-newsletter strong { display: block; font-weight: 500; color: #fff; font-size: 16px; margin-bottom: 2px; }
    .footer-newsletter p { color: #f5dde3; font-size: 15px; margin: 0; }
    .footer-main { background: #ffffff; display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 28px; padding: 36px 32px 28px; }
    .footer-col h4 { font-size: 13px; font-weight: 600; color: #6b1a2e; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 14px; font-family: Georgia, serif; }
    .footer-col p { color: #4a2a30; font-size: 15px; line-height: 1.8; margin: 0; }
    .footer-col a { display: block; color: #4a2a30; font-size: 15px; line-height: 2; text-decoration: none; transition: color 0.15s; }
    .footer-col a:hover { color: #6b1a2e; }
    .footer-tagline { color: #8a5a60; font-style: italic; font-size: 15px; margin-bottom: 12px; }
    .footer-contact-email { color: #6b1a2e !important; }
    .footer-contact-muted { color: #8a5a60 !important; }
    .btn-browse { display: inline-block; margin-top: 16px; background: #fff; color: #6b1a2e; font-size: 15px; font-weight: 500; padding: 9px 18px; border-radius: 8px; border: 1px solid #6b1a2e; text-decoration: none; font-family: inherit; transition: background 0.15s; }
    .btn-browse:hover { background: #f9f0f2; color: #6b1a2e; }
    .trust-list { display: flex; flex-direction: column; gap: 10px; }
    .trust-item { display: flex; align-items: center; gap: 9px; color: #4a2a30; font-size: 15px; }
    .trust-item svg { flex-shrink: 0; width: 16px; height: 16px; }
    .payment-icons { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .payment-icon { background: #6b1a2e; color: #f5e6c8; border-radius: 3px; padding: 2px 7px; font-size: 10px; font-weight: 600; font-family: Georgia, serif; letter-spacing: 0.04em; }
    .col-divider { border: none; border-top: 0.5px solid #e8c8c8; margin: 10px 0; }
    .footer-bottom { border-top: 1px solid #e8c8c8; background: #fdf6f0; padding: 16px 32px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; }
    .footer-bottom p { font-size: 14px; color: #8a5a60; margin: 0; }
    .age-badge { background: #fdf0e8; border: 0.5px solid #c9a84c; color: #7a4a00; font-size: 13px; padding: 3px 10px; border-radius: 8px; }

    @media (max-width: 768px) {
      .tc-wrapper { flex-direction: column; padding: 100px 16px 60px; gap: 32px; }
      .tc-nav { position: static; flex: unset; width: 100%; }
      .tc-page-title h1 { font-size: 2.2rem; }
      .footer-newsletter { padding: 16px 20px; flex-direction: column; align-items: flex-start; }
      .footer-main { padding: 24px 20px; grid-template-columns: 1fr 1fr; }
      .footer-bottom { padding: 14px 20px; flex-direction: column; align-items: flex-start; }
    }

  </style>
</head>

<body>

  <!-- WISHLIST OVERLAY & SIDEBAR -->
  <div class="wishlist-overlay" id="wishlistOverlay"></div>
  <div class="wishlist-sidebar" id="wishlistSidebar">
    <div class="close-wishlist" id="closeWishlist"><i class="fa fa-times"></i></div>
    <h3>Your Wishlist</h3>
    <div id="wishlist-items"><p>Your wishlist is empty.</p></div>
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

  <!-- PAGE -->
  <div class="tc-wrapper">

    <!-- Sticky sidebar nav -->
    <nav class="tc-nav">
      <p>On this page</p>
      <ul>
        <li><a href="#introduction">Introduction</a></li>
        <li><a href="#eligibility">Age & eligibility</a></li>
        <li><a href="#ordering">Ordering</a></li>
        <li><a href="#pricing">Pricing & payment</a></li>
        <li><a href="#delivery">Delivery</a></li>
        <li><a href="#returns">Returns & refunds</a></li>
        <li><a href="#intellectual-property">Intellectual property</a></li>
        <li><a href="#liability">Limitation of liability</a></li>
        <li><a href="#privacy">Privacy</a></li>
        <li><a href="#changes">Changes to these terms</a></li>
        <li><a href="#contact">Contact us</a></li>
      </ul>
    </nav>

    <!-- Main content -->
    <div class="tc-content">

      <div class="tc-page-title">
        <h1>Terms & Conditions</h1>
        <p>Please read these terms carefully before using Wine Exchange or placing an order with us.</p>
        <span class="tc-title-divider"></span>
      </div>

      <span class="tc-updated">Last updated: 1 January 2026</span>

      <!-- 1. Introduction -->
      <div class="tc-section" id="introduction">
        <h2>1. Introduction</h2>
        <p>Welcome to Wine Exchange. These terms and conditions govern your use of our website and the purchase of products from us. By accessing our website or placing an order, you agree to be bound by these terms.</p>
        <p>Wine Exchange is a UK-based online wine retailer operating at wineexchange.co.uk. References to "we", "us", or "our" refer to Wine Exchange. References to "you" or "your" refer to the customer using our website or placing an order.</p>
        <div class="tc-highlight">
          <p>If you do not agree with any part of these terms, you must not use our website or place an order with us.</p>
        </div>
      </div>

      <!-- 2. Age & Eligibility -->
      <div class="tc-section" id="eligibility">
        <h2>2. Age & eligibility</h2>
        <p>You must be aged 18 or over to purchase alcohol from Wine Exchange. This is a legal requirement under UK law. By placing an order, you confirm that you are at least 18 years of age.</p>
        <p>We operate a Challenge 25 policy. Our couriers may request proof of age upon delivery. If satisfactory proof cannot be provided, or if the recipient appears to be under 18, delivery will be refused and the order returned to us.</p>
        <p>We reserve the right to cancel any order where we have reasonable grounds to believe the customer is under 18, or where alcohol may be purchased on behalf of a person under 18.</p>
      </div>

      <!-- 3. Ordering -->
      <div class="tc-section" id="ordering">
        <h2>3. Ordering</h2>
        <p>When you place an order through our website, you are making an offer to purchase the selected products at the stated price. Your order constitutes an offer to us to buy a product and does not constitute acceptance by us.</p>
        <p>We will send you an order confirmation email once your order has been received. This email is an acknowledgement of receipt, not an acceptance of your order. Acceptance of your order takes place when we dispatch the goods and send you a dispatch confirmation.</p>
        <p>We reserve the right to decline or cancel any order at our discretion. Reasons may include, but are not limited to:</p>
        <ul>
          <li>The product being out of stock or no longer available</li>
          <li>A pricing or product description error on our website</li>
          <li>Suspected fraudulent activity</li>
          <li>Failure of payment authorisation</li>
          <li>Suspicion that the order is being placed on behalf of a person under 18</li>
        </ul>
        <p>You may cancel or amend your order within 1 hour of placing it, provided it has not yet entered our fulfilment process. Please contact us immediately at contactwinexchange@gmail.com to request a change.</p>
      </div>

      <!-- 4. Pricing & Payment -->
      <div class="tc-section" id="pricing">
        <h2>4. Pricing & payment</h2>
        <p>All prices on our website are displayed in pounds sterling (£) and include VAT where applicable. Prices are correct at the time of display but are subject to change without notice.</p>
        <p>In the unlikely event that a product is listed at an incorrect price, we reserve the right to cancel the order and issue a full refund, or contact you to confirm whether you wish to proceed at the correct price.</p>
        <p>We accept the following payment methods: Visa, Mastercard, American Express, and PayPal. Payment is taken in full at the time of ordering. All transactions are processed securely. We do not store your card details on our servers.</p>
      </div>

      <!-- 5. Delivery -->
      <div class="tc-section" id="delivery">
        <h2>5. Delivery</h2>
        <p>We currently deliver within the United Kingdom only. International shipping is not available at this time but will be introduced in the near future.</p>
        <p>We offer the following delivery options:</p>
        <ul>
          <li><strong>Standard delivery</strong> — Free on every order. Estimated delivery within 2–3 business days.</li>
          <li><strong>Next-day delivery</strong> — £4.99. Orders must be placed before 2pm Monday to Friday. Delivery the following business day.</li>
        </ul>
        <p>Delivery times are estimates and not guaranteed. We are not liable for delays caused by third-party couriers, adverse weather, or other circumstances beyond our control.</p>
        <p>Risk in the goods passes to you upon delivery. If you are not present at the time of delivery, the courier will leave a card with instructions for redelivery or collection.</p>
        <div class="tc-highlight">
          <p>Due to the nature of alcohol deliveries, a signature and proof of age (18+) may be required. Delivery will not be made to anyone who appears to be under 18.</p>
        </div>
      </div>

      <!-- 6. Returns & Refunds -->
      <div class="tc-section" id="returns">
        <h2>6. Returns & refunds</h2>
        <p>We want you to be completely satisfied with your purchase. If something isn't right, please contact us as soon as possible.</p>
        <p>We will offer a replacement or full refund in the following circumstances:</p>
        <ul>
          <li>The product arrived damaged or broken</li>
          <li>The incorrect product was delivered</li>
          <li>The product is faulty or not as described</li>
        </ul>
        <p>To request a return or refund, please email contactwinexchange@gmail.com within 48 hours of delivery, including your order number and, where relevant, photographs of the issue. We will respond within 2 business days.</p>
        <p>We are unable to accept returns on products that have been opened, partially consumed, or are no longer in their original condition, unless they are faulty.</p>
        <p>Your statutory rights under the Consumer Rights Act 2015 are not affected by these terms.</p>
      </div>

      <!-- 7. Intellectual Property -->
      <div class="tc-section" id="intellectual-property">
        <h2>7. Intellectual property</h2>
        <p>All content on the Wine Exchange website — including but not limited to text, images, logos, graphics, and design — is the property of Wine Exchange and is protected by applicable copyright and intellectual property laws.</p>
        <p>You may not reproduce, distribute, modify, or use any content from our website for commercial purposes without our prior written consent. Personal, non-commercial use is permitted provided you do not remove any copyright notices.</p>
      </div>

      <!-- 8. Limitation of Liability -->
      <div class="tc-section" id="liability">
        <h2>8. Limitation of liability</h2>
        <p>To the fullest extent permitted by law, Wine Exchange shall not be liable for any indirect, incidental, special, or consequential loss or damage arising from your use of our website or the products you purchase from us.</p>
        <p>Our total liability to you for any claim arising in connection with an order shall not exceed the total value of that order.</p>
        <p>Nothing in these terms limits or excludes our liability for death or personal injury caused by negligence, fraud or fraudulent misrepresentation, or any other liability that cannot be excluded by law.</p>
      </div>

      <!-- 9. Privacy -->
      <div class="tc-section" id="privacy">
        <h2>9. Privacy</h2>
        <p>Your use of our website is also governed by our Privacy Policy, which is incorporated into these terms by reference. By using our website, you consent to the processing of your personal data as described in our Privacy Policy.</p>
        <p>We comply fully with the UK General Data Protection Regulation (UK GDPR) and the Data Protection Act 2018. Your personal data is never sold or shared with third parties for marketing purposes.</p>
      </div>

      <!-- 10. Changes to Terms -->
      <div class="tc-section" id="changes">
        <h2>10. Changes to these terms</h2>
        <p>We reserve the right to update or amend these terms and conditions at any time. Changes will be effective immediately upon posting to our website. The date at the top of this page will be updated to reflect any changes.</p>
        <p>Your continued use of our website or placement of an order following any changes constitutes your acceptance of the revised terms. We recommend reviewing this page periodically.</p>
      </div>

      <!-- 11. Contact -->
      <div class="tc-section" id="contact">
        <h2>11. Governing law & contact</h2>
        <p>These terms and conditions are governed by and construed in accordance with the laws of England and Wales. Any disputes arising in connection with these terms shall be subject to the exclusive jurisdiction of the courts of England and Wales.</p>
        <p>If you have any questions about these terms, please contact us:</p>
        <ul>
          <li><strong>Email:</strong> contactwinexchange@gmail.com</li>
          <li><strong>Address:</strong> 123 Vineyard Lane, London, UK</li>
          <li><strong>Phone:</strong> +44 1234 567890</li>
          <li><strong>Hours:</strong> Monday to Friday, 9am–6pm</li>
        </ul>
      </div>

      <!-- CTA -->
      <div class="tc-cta">
        <p>Have a question about our terms? We're happy to help — get in touch with our team.</p>
        <a href="contact-us.php">Contact us</a>
      </div>

    </div>
  </div>

  <!-- FOOTER -->
  <footer>

    <div class="footer-newsletter">
      <div>
        <strong>Join our wine newsletter</strong>
        <p>Tasting notes, new arrivals &amp; exclusive offers</p>
      </div>
      <div style="display:flex; flex-direction:row; gap:8px; align-items:center;">
        <input type="email" placeholder="Your email address" style="width:210px !important; min-width:210px !important; max-width:210px !important; padding:7px 12px; border-radius:8px; border:0.5px solid rgba(255,255,255,0.25); background:rgba(255,255,255,0.12); color:#ffffff; font-size:15px; outline:none; box-sizing:border-box; margin-bottom:0;" />
        <button style="width:210px !important; min-width:210px !important; max-width:210px !important; padding:7px 16px; border-radius:8px; background:#ffffff; border:none; color:#6b1a2e; font-size:15px; font-weight:500; cursor:pointer; box-sizing:border-box;">Subscribe</button>
      </div>
    </div>

    <div class="footer-main">
      <div class="footer-col">
        <h4>Wine Exchange</h4>
        <p class="footer-tagline">Independent wine merchant since 2010</p>
        <p>123 Vineyard Lane<br>London, UK</p>
        <p class="footer-contact-muted" style="margin-top:8px;">+44 1234 567890</p>
        <a href="mailto:contactwinexchange@gmail.com" class="footer-contact-email">contactwinexchange@gmail.com</a>
        <p class="footer-contact-muted" style="margin-top:8px;">Mon–Fri, 9am–6pm</p>
        <a href="search.php" class="btn-browse">Browse all wines →</a>
      </div>

      <div class="footer-col">
        <h4>Why shop with us</h4>
        <div class="trust-list">
          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none"><rect x="1" y="4" width="16" height="11" rx="2" stroke="#6b1a2e" stroke-width="1.2"/><path d="M1 7h16" stroke="#6b1a2e" stroke-width="1.2"/></svg>
            Secure payments
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none"><rect x="2" y="8" width="10" height="7" rx="1.2" stroke="#6b1a2e" stroke-width="1.2"/><path d="M12 11h2.5a1 1 0 001-1V8.5a1 1 0 00-.6-.9L13 7" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/><circle cx="5" cy="15.5" r="1.2" fill="#6b1a2e"/><circle cx="10" cy="15.5" r="1.2" fill="#6b1a2e"/></svg>
            Free standard delivery on every order
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none"><path d="M9 2v8M9 10l-3 3m3-3l3 3" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/><path d="M4 14h10" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/></svg>
            Easy returns
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none"><circle cx="9" cy="9" r="7" stroke="#6b1a2e" stroke-width="1.2"/><path d="M9 5v4l2.5 2.5" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/></svg>
            Next-day dispatch
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none"><path d="M9 2l1.8 5h5.2l-4.2 3 1.6 5L9 12l-3.4 3 1.6-5L3 7h5.2z" stroke="#6b1a2e" stroke-width="1.1" stroke-linejoin="round"/></svg>
            Expert curation
          </div>
        </div>
      </div>

      <div class="footer-col">
        <h4>Help</h4>
        <a href="about.php">About us</a>
        <a href="faq.php">FAQ</a>
        <a href="contact-us.php">Contact us</a>
        <a href="shipping.php">Shipping &amp; delivery</a>
        <a href="terms.php">Terms &amp; conditions</a>
      </div>

      <div class="footer-col">
        <h4>Legal &amp; payments</h4>
        <a href="privacy.php">Privacy policy</a>
        <a href="cookies.php">Cookie policy</a>
        <a href="accessibility.php">Accessibility</a>
        <hr class="col-divider" />
        <div class="payment-icons">
          <span class="payment-icon">VISA</span>
          <span class="payment-icon">MC</span>
          <span class="payment-icon">AMEX</span>
          <span class="payment-icon">PayPal</span>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2026 Wine Exchange. All rights reserved.</p>
      <span class="age-badge">18+ only — please drink responsibly</span>
    </div>

  </footer>

  <!-- DARK MODE -->
  <script>
    (function () {
      const btn = document.getElementById('dark-mode');
      if (localStorage.getItem('dark_mode') === 'on') {
        document.documentElement.classList.add('darkmode');
      }
      btn.addEventListener('click', function () {
        document.documentElement.classList.toggle('darkmode');
        localStorage.setItem('dark_mode', document.documentElement.classList.contains('darkmode') ? 'on' : 'off');
      });
    })();
  </script>

  <!-- WISHLIST -->
  <script>
    const loggedIn = <?php echo isset($_SESSION['customerID']) ? "true" : "false"; ?>;
    const wishlistBtn     = document.getElementById("wishlist-toggle");
    const wishlistSidebar = document.getElementById("wishlistSidebar");
    const closeWishlist   = document.getElementById("closeWishlist");
    const wishlistOverlay = document.getElementById("wishlistOverlay");

    wishlistBtn.addEventListener("click", () => { wishlistSidebar.classList.add("active"); wishlistOverlay.classList.add("active"); });
    closeWishlist.addEventListener("click", () => { wishlistSidebar.classList.remove("active"); wishlistOverlay.classList.remove("active"); });
    wishlistOverlay.addEventListener("click", () => { wishlistSidebar.classList.remove("active"); wishlistOverlay.classList.remove("active"); });

    const wishlistContainer = document.getElementById("wishlist-items");
    const wishlistCount     = document.getElementById("wishlist-count");

    function getGuestWishlist() { return JSON.parse(localStorage.getItem("wishlist")) || []; }
    function saveGuestWishlist(list) { localStorage.setItem("wishlist", JSON.stringify(list)); }

    function loadWishlist() {
      if (loggedIn) {
        fetch("get_wishlist.php").then(res => res.json()).then(data => renderWishlist(data));
      } else { renderWishlist(getGuestWishlist()); }
    }

    function renderWishlist(list) {
      wishlistContainer.innerHTML = "";
      if (list.length === 0) { wishlistContainer.innerHTML = "<p>Your wishlist is empty.</p>"; wishlistCount.textContent = 0; return; }
      wishlistCount.textContent = list.length;
      list.forEach((wine, index) => {
        const image = loggedIn ? (wine.imageUrl ? "../../images/" + wine.imageUrl : "../../images/placeholder.jpg") : (wine.imageUrl || "../../images/placeholder.jpg");
        const item = document.createElement("div");
        item.className = "wishlist-item";
        item.innerHTML = `<img src="${image}" class="wishlist-img"><div class="wishlist-info"><div class="wishlist-name">${wine.wineName || wine.name}</div><div class="wishlist-price">£${wine.price}</div><div class="wishlist-actions"><a href="wineinfo.php?id=${wine.id || wine.wineId}" class="wishlist-view">View</a></div></div><button class="remove-wishlist" data-id="${wine.wineId || wine.id}" data-index="${index}"><i class="fas fa-times"></i></button>`;
        wishlistContainer.appendChild(item);
      });
    }

    document.addEventListener("click", function (e) {
      const removeBtn = e.target.closest(".remove-wishlist");
      if (!removeBtn) return;
      const wineId = removeBtn.dataset.id;
      const index  = removeBtn.dataset.index;
      if (loggedIn) {
        fetch("remove_from_wishlist.php", { method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "wineId=" + wineId }).then(() => loadWishlist());
      } else {
        let list = getGuestWishlist(); list.splice(index, 1); saveGuestWishlist(list); renderWishlist(list);
      }
    });

    loadWishlist();
  </script>

</body>
</html>