<?php
session_start();
require_once('../../database/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FAQ | Wine Exchange</title>
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

    /* ── PAGE WRAPPER ── */
    .faq-page {
      max-width: 860px;
      margin: 0 auto;
      padding: 120px 24px 80px;
    }

    /* ── PAGE TITLE ── */
    .faq-page-title {
      text-align: center;
      margin-bottom: 40px;
    }

    .faq-page-title h1 {
      font-size: 3rem;
      font-weight: 700;
      color: var(--wine);
      margin: 0 0 10px;
      letter-spacing: 0.01em;
    }

    .faq-page-title p {
      font-size: 18px;
      color: var(--text-soft);
      margin: 0;
    }

    .faq-title-divider {
      display: block;
      width: 48px;
      height: 3px;
      background: var(--wine);
      border-radius: 2px;
      margin: 14px auto 0;
    }

    /* ── SEARCH ── */
    .faq-search-wrap {
      margin-bottom: 40px;
    }

    .faq-search-wrap input {
      width: 100% !important;
      padding: 15px 20px !important;
      font-size: 17px !important;
      border: 1px solid var(--border) !important;
      border-radius: var(--radius) !important;
      background: #fff !important;
      color: var(--text) !important;
      outline: none !important;
      font-family: Georgia, serif !important;
      box-shadow: none !important;
      margin-bottom: 0 !important;
      transition: border-color 0.2s;
    }

    .faq-search-wrap input:focus {
      border-color: var(--wine) !important;
    }

    .faq-search-wrap input::placeholder {
      color: var(--text-soft);
    }

    /* ── CATEGORY TABS ── */
    .faq-categories {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 32px;
    }

    .faq-cat-btn {
      padding: 8px 20px;
      border-radius: 20px;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--text-mid);
      font-size: 15px;
      font-family: Georgia, serif;
      cursor: pointer;
      transition: all 0.18s;
    }

    .faq-cat-btn:hover,
    .faq-cat-btn.active {
      background: var(--wine);
      color: #fff;
      border-color: var(--wine);
    }

    /* ── ACCORDION ── */
    .faq-group {
      margin-bottom: 36px;
    }

    .faq-group-title {
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--wine);
      margin: 0 0 12px;
      padding-bottom: 8px;
      border-bottom: 1px solid var(--border);
    }

    .faq-item {
      border-bottom: 1px solid var(--border);
    }

    .faq-item:last-child {
      border-bottom: none;
    }

    .faq-question {
      width: 100%;
      background: none;
      border: none;
      text-align: left;
      padding: 22px 4px;
      font-size: 17px;
      font-weight: 600;
      color: var(--text);
      font-family: Georgia, serif;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      transition: color 0.18s;
    }

    .faq-question:hover { color: var(--wine); }
    .faq-question.open  { color: var(--wine); }

    .faq-icon {
      flex-shrink: 0;
      width: 22px;
      height: 22px;
      border-radius: 50%;
      border: 1.5px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: var(--wine);
      transition: transform 0.25s, background 0.18s;
      background: #fff;
    }

    .faq-question.open .faq-icon {
      background: var(--wine);
      color: #fff;
      border-color: var(--wine);
      transform: rotate(45deg);
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.35s ease, padding 0.25s ease;
      padding: 0 4px;
    }

    .faq-answer.open {
      max-height: 400px;
      padding: 0 4px 18px;
    }

    .faq-answer p {
      font-size: 16px;
      color: var(--text-mid);
      line-height: 1.8;
      margin: 0;
    }

    /* ── NO RESULTS ── */
    .faq-no-results {
      text-align: center;
      padding: 40px 0;
      color: var(--text-soft);
      font-size: 17px;
      display: none;
    }

    /* ── CONTACT CTA ── */
    .faq-cta {
      margin-top: 56px;
      background: #fff;
      border: 1px solid var(--border);
      border-top: 3px solid var(--gold);
      border-radius: var(--radius);
      padding: 40px 36px;
      text-align: center;
    }

    .faq-cta p {
      font-size: 17px;
      color: var(--text-mid);
      margin: 0 0 24px;
      line-height: 1.7;
    }

    .faq-cta a {
      display: inline-block;
      background: var(--wine);
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      padding: 11px 28px;
      border-radius: var(--radius);
      text-decoration: none;
      font-family: Georgia, serif;
      letter-spacing: 0.04em;
      transition: background 0.18s;
    }

    .faq-cta a:hover { background: var(--wine-light); }

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
    .footer-newsletter strong { display: block; font-weight: 500; color: #fff; font-size: 16px; margin-bottom: 2px; }
    .footer-newsletter p { color: #f5dde3; font-size: 15px; margin: 0; }

    .newsletter-form { display: flex; flex-direction: row; gap: 8px; align-items: center; }
    .footer-newsletter .newsletter-form input {
      padding: 7px 12px !important; border-radius: 8px !important;
      border: 0.5px solid rgba(255,255,255,0.25) !important;
      background: rgba(255,255,255,0.12) !important; color: #ffffff !important;
      font-size: 15px !important; width: 210px !important; max-width: 210px !important;
      min-width: 210px !important; outline: none !important; font-family: inherit !important;
      box-sizing: border-box !important; margin-bottom: 0 !important;
    }
    .footer-newsletter .newsletter-form input::placeholder { color: rgba(255,255,255,0.5); }
    .footer-newsletter .newsletter-form input:focus { border-color: rgba(255,255,255,0.5) !important; }
    .footer-newsletter .btn-subscribe {
      padding: 7px 16px !important; border-radius: 8px !important; background: #ffffff !important;
      border: none !important; color: #6b1a2e !important; font-size: 15px !important;
      font-weight: 500 !important; cursor: pointer !important; font-family: inherit !important;
      transition: background 0.15s !important; width: 210px !important;
      max-width: 210px !important; min-width: 210px !important; box-sizing: border-box !important;
    }
    .footer-newsletter .btn-subscribe:hover { background: #f0e8ea !important; }

    .footer-main {
      background: #ffffff;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
      gap: 28px;
      padding: 36px 32px 28px;
    }
    .footer-col h4 { font-size: 13px; font-weight: 600; color: #6b1a2e; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 14px; font-family: Georgia, serif; }
    .footer-col p { color: #4a2a30; font-size: 15px; line-height: 1.8; margin: 0; }
    .footer-col a { display: block; color: #4a2a30; font-size: 15px; line-height: 2; text-decoration: none; transition: color 0.15s; }
    .footer-col a:hover { color: #6b1a2e; }
    .footer-tagline { color: #8a5a60; font-style: italic; font-size: 15px; margin-bottom: 12px; }
    .footer-contact-email { color: #6b1a2e !important; }
    .footer-contact-muted { color: #8a5a60 !important; }
    .btn-browse {
      display: inline-block; margin-top: 16px; background: #fff; color: #6b1a2e;
      font-size: 15px; font-weight: 500; padding: 9px 18px; border-radius: 8px;
      border: 1px solid #6b1a2e; text-decoration: none; font-family: inherit; transition: background 0.15s;
    }
    .btn-browse:hover { background: #f9f0f2; color: #6b1a2e; }
    .trust-list { display: flex; flex-direction: column; gap: 10px; }
    .trust-item { display: flex; align-items: center; gap: 9px; color: #4a2a30; font-size: 15px; }
    .trust-item svg { flex-shrink: 0; width: 16px; height: 16px; }
    .payment-icons { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .payment-icon { background: #6b1a2e; color: #f5e6c8; border-radius: 3px; padding: 2px 7px; font-size: 10px; font-weight: 600; font-family: Georgia, serif; letter-spacing: 0.04em; }
    .col-divider { border: none; border-top: 0.5px solid #e8c8c8; margin: 10px 0; }
    .footer-bottom {
      border-top: 1px solid #e8c8c8; background: #fdf6f0; padding: 16px 32px;
      display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px;
    }
    .footer-bottom p { font-size: 14px; color: #8a5a60; margin: 0; }
    .age-badge { background: #fdf0e8; border: 0.5px solid #c9a84c; color: #7a4a00; font-size: 13px; padding: 3px 10px; border-radius: 8px; }

    /* ── DARK MODE ── */
    .darkmode .faq-search-wrap input { background: #221410 !important; border-color: #3a2820 !important; color: #c0a898 !important; }
    .darkmode .faq-cat-btn { background: #221410; border-color: #3a2820; color: #c0a898; }
    .darkmode .faq-cat-btn.active { background: var(--wine); color: #fff; border-color: var(--wine); }
    .darkmode .faq-question { color: #f0e6de; }
    .darkmode .faq-answer p { color: #c0a898; }
    .darkmode .faq-group-title { color: #e8c8c8; border-bottom-color: #3a2820; }
    .darkmode .faq-item { border-bottom-color: #3a2820; }
    .darkmode .faq-icon { background: #1c100d; border-color: #3a2820; }
    .darkmode .faq-cta { background: #1c100d; border-color: #3a2820; }
    .darkmode .faq-cta p { color: #c0a898; }
    .darkmode .footer-main { background: #1c100d; }
    .darkmode .footer-col p, .darkmode .footer-col a { color: #c0a898; }
    .darkmode .footer-col a:hover { color: #f0e6de; }
    .darkmode .footer-bottom { background: #140a08; border-top-color: #3a2820; }
    .darkmode .footer-bottom p { color: #8a7a72; }

    @media (max-width: 600px) {
      .faq-page { padding: 100px 16px 60px; }
      .faq-page-title h1 { font-size: 1.8rem; }
      .footer-newsletter { padding: 16px 20px; flex-direction: column; align-items: flex-start; }
      .footer-main { padding: 24px 20px; grid-template-columns: 1fr 1fr; }
      .footer-bottom { padding: 14px 20px; flex-direction: column; align-items: flex-start; }
    }

  </style>
</head>

<body>

  <?php require_once('navbar.php'); ?>

  <!-- FAQ PAGE -->
  <div class="faq-page">

    <!-- Title -->
    <div class="faq-page-title">
      <h1>Frequently Asked Questions</h1>
      <p>Find answers to our most common questions below.</p>
      <span class="faq-title-divider"></span>
    </div>

    <!-- Search -->
    <div class="faq-search-wrap">
      <input type="text" id="faq-search" placeholder="Search this page..." />
    </div>

    <!-- Category tabs -->
    <div class="faq-categories">
      <button class="faq-cat-btn active" data-cat="all">All</button>
      <button class="faq-cat-btn" data-cat="ordering">Ordering</button>
      <button class="faq-cat-btn" data-cat="shipping">Shipping</button>
      <button class="faq-cat-btn" data-cat="returns">Returns</button>
      <button class="faq-cat-btn" data-cat="account">Account</button>
    </div>

    <!-- No results message -->
    <div class="faq-no-results" id="faq-no-results">No questions match your search.</div>

    <!-- Ordering -->
    <div class="faq-group" data-cat="ordering">
      <p class="faq-group-title">Ordering</p>

      <div class="faq-item">
        <button class="faq-question">
          How does buying work on Wine Exchange?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>Purchasing is simple and straightforward. Browse our range, add bottles to your basket, and complete checkout securely. Every bottle listed is available to order directly from the site with no middleman.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Where do the wines come from?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>All wines are sourced from reputable producers, merchants, and distributors around the world. We work with trusted suppliers across Europe, South America, Australia, and beyond to bring you an exciting and reliable selection.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          How do I find the right wine?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>Use our search and filter tools to browse by type, region, or price. Every wine includes detailed tasting notes and descriptions to help you pick with confidence — whether you're a seasoned collector or just getting started.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Can I change or cancel my order after placing it?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>Please contact us as soon as possible at contactwinexchange@gmail.com if you need to make changes.</p>
        </div>
      </div>

    </div>

    <!-- Shipping -->
    <div class="faq-group" data-cat="shipping">
      <p class="faq-group-title">Shipping &amp; Delivery</p>

      <div class="faq-item">
        <button class="faq-question">
          How much does shipping cost?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>Standard delivery is free on every order — no minimum spend required. We believe great wine should be accessible, so we've removed the barrier of delivery fees entirely.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Where do you ship to?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>We currently only ship across the UK.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          How long will it take for my order to arrive?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>UK orders are typically dispatched the next working day and arrive within 2–3 business days.</p>
        </div>
      </div>

    </div>

    <!-- Returns -->
    <div class="faq-group" data-cat="returns">
      <p class="faq-group-title">Returns &amp; Refunds</p>

      <div class="faq-item">
        <button class="faq-question">
          What is your returns policy?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>If your order arrives damaged or there is a fault, please contact us within 48 hours of delivery and we will arrange a replacement or full refund. We are unable to accept returns on wines that have been opened.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          My bottle arrived damaged — what do I do?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>We're sorry to hear that. Please email us at contactwinexchange@gmail.com with your order number and a photo of the damage within 48 hours. We'll arrange a replacement or refund as quickly as possible.</p>
        </div>
      </div>

    </div>

    <!-- Account -->
    <div class="faq-group" data-cat="account">
      <p class="faq-group-title">Account &amp; Payments</p>

      <div class="faq-item">
        <button class="faq-question">
          Do I need an account to order?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>You do not need an account to purchase.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          What payment methods do you accept?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>We accept Visa, Mastercard, American Express, and ApplePay. All transactions are processed securely and your payment details are never stored on our servers.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Is my personal information kept safe?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>Absolutely. We take data privacy seriously. Your details are never sold or shared with third parties. Please see our Privacy Policy for full details.</p>
        </div>
      </div>

    </div>

    <!-- Contact CTA -->
    <div class="faq-cta">
      <p>Still have questions? We'd love to hear from you — our team is on hand Monday to Friday, 9am–6pm.</p>
      <a href="contact-us.php">Contact Us</a>
    </div>

  </div>

  <!-- FOOTER -->
  <footer>

    <div class="footer-newsletter">
      <div>
        <strong>Join our wine newsletter</strong>
        <p>Tasting notes, new arrivals &amp; exclusive offers</p>
      </div>
      <div class="newsletter-form">
        <input type="email" placeholder="Your email address" />
        <button class="btn-subscribe">Subscribe</button>
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
          <span class="payment-icon">ApplePay</span>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <p>© 2026 Wine Exchange. All rights reserved.</p>
      <span class="age-badge">18+ only — please drink responsibly</span>
    </div>

  </footer>

  <!-- FAQ ACCORDION + SEARCH + CATEGORIES -->
  <script>
    // Accordion
    document.querySelectorAll('.faq-question').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var answer = btn.nextElementSibling;
        var isOpen = btn.classList.contains('open');

        // Close all
        document.querySelectorAll('.faq-question').forEach(function (b) {
          b.classList.remove('open');
          b.nextElementSibling.classList.remove('open');
        });

        // Toggle clicked
        if (!isOpen) {
          btn.classList.add('open');
          answer.classList.add('open');
        }
      });
    });

    // Category filter
    document.querySelectorAll('.faq-cat-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.faq-cat-btn').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        var cat = btn.dataset.cat;
        document.querySelectorAll('.faq-group').forEach(function (group) {
          group.style.display = (cat === 'all' || group.dataset.cat === cat) ? '' : 'none';
        });
        document.getElementById('faq-search').value = '';
        document.getElementById('faq-no-results').style.display = 'none';
      });
    });

    // Search
    document.getElementById('faq-search').addEventListener('input', function () {
      var query = this.value.toLowerCase().trim();
      var anyVisible = false;

      // Reset category
      document.querySelectorAll('.faq-cat-btn').forEach(function (b) { b.classList.remove('active'); });
      document.querySelector('[data-cat="all"]').classList.add('active');
      document.querySelectorAll('.faq-group').forEach(function (g) { g.style.display = ''; });

      document.querySelectorAll('.faq-item').forEach(function (item) {
        var text  = item.textContent.toLowerCase();
        var match = !query || text.includes(query);
        item.style.display = match ? '' : 'none';
        if (match) anyVisible = true;
      });

      document.getElementById('faq-no-results').style.display = anyVisible || !query ? 'none' : 'block';
    });
  </script>

</body>
</html>