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
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

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

    .faq-page {
      max-width: 860px;
      margin: 0 auto;
      padding: 60px 24px 80px;
    }

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

    .faq-search-wrap { margin-bottom: 40px; }

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

    .faq-search-wrap input:focus { border-color: var(--wine) !important; }
    .faq-search-wrap input::placeholder { color: var(--text-soft); }

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

    .faq-group { margin-bottom: 36px; }

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

    .faq-item { border-bottom: 1px solid var(--border); }
    .faq-item:last-child { border-bottom: none; }

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

    .faq-no-results {
      text-align: center;
      padding: 40px 0;
      color: var(--text-soft);
      font-size: 17px;
      display: none;
    }

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

    @media (max-width: 600px) {
      .faq-page { padding: 40px 16px 60px; }
      .faq-page-title h1 { font-size: 1.8rem; }
    }
  </style>
</head>

<body>

  <?php include 'header.php'; ?>

  <div class="faq-page">

    <div class="faq-page-title">
      <h1>Frequently Asked Questions</h1>
      <p>Find answers to our most common questions below.</p>
      <span class="faq-title-divider"></span>
    </div>

    <div class="faq-search-wrap">
      <input type="text" id="faq-search" placeholder="Search this page..." />
    </div>

    <div class="faq-categories">
      <button class="faq-cat-btn active" data-cat="all">All</button>
      <button class="faq-cat-btn" data-cat="ordering">Ordering</button>
      <button class="faq-cat-btn" data-cat="shipping">Shipping</button>
      <button class="faq-cat-btn" data-cat="returns">Returns</button>
      <button class="faq-cat-btn" data-cat="account">Account</button>
    </div>

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
      <p class="faq-group-title">Shipping & Delivery</p>

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
      <p class="faq-group-title">Returns & Refunds</p>

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
      <p class="faq-group-title">Account & Payments</p>

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

    <div class="faq-cta">
      <p>Still have questions? We'd love to hear from you — our team is on hand Monday to Friday, 9am–6pm.</p>
      <a href="contact-us.php">Contact Us</a>
    </div>

  </div>

  <?php include 'footer.php'; ?>

  <script>
    document.querySelectorAll('.faq-question').forEach(btn => {
      btn.addEventListener('click', () => {
        const answer = btn.nextElementSibling;
        const isOpen = btn.classList.contains('open');

        document.querySelectorAll('.faq-question').forEach(b => {
          b.classList.remove('open');
          b.nextElementSibling.classList.remove('open');
        });

        if (!isOpen) {
          btn.classList.add('open');
          answer.classList.add('open');
        }
      });
    });

    document.querySelectorAll('.faq-cat-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.faq-cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const cat = btn.dataset.cat;
        document.querySelectorAll('.faq-group').forEach(group => {
          group.style.display = (cat === 'all' || group.dataset.cat === cat) ? '' : 'none';
        });
        document.getElementById('faq-search').value = '';
        document.getElementById('faq-no-results').style.display = 'none';
      });
    });

    document.getElementById('faq-search').addEventListener('input', function () {
      const query = this.value.toLowerCase().trim();
      let anyVisible = false;

      document.querySelectorAll('.faq-cat-btn').forEach(b => b.classList.remove('active'));
      document.querySelector('[data-cat="all"]').classList.add('active');
      document.querySelectorAll('.faq-group').forEach(g => g.style.display = '');

      document.querySelectorAll('.faq-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        const match = !query || text.includes(query);
        item.style.display = match ? '' : 'none';
        if (match) anyVisible = true;
      });

      document.getElementById('faq-no-results').style.display = anyVisible || !query ? 'none' : 'block';
    });
  </script>

</body>
</html>