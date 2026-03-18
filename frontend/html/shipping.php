<?php
session_start();
require_once('../../database/db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shipping & Delivery | Wine Exchange</title>
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
      margin: 0; padding: 0;
      overflow-x: hidden;
      background: var(--bg-warm);
      font-family: Georgia, 'Times New Roman', serif;
      color: var(--text);
    }

    .shipping-page {
      max-width: 860px;
      margin: 0 auto;
      padding: 60px 24px 80px;
    }

    .shipping-page-title { text-align: center; margin-bottom: 52px; }
    .shipping-page-title h1 { font-size: 3rem; font-weight: 700; color: var(--wine); margin: 0 0 10px; letter-spacing: 0.01em; }
    .shipping-page-title p { font-size: 18px; color: var(--text-soft); margin: 0; line-height: 1.7; }
    .shipping-title-divider { display: block; width: 48px; height: 3px; background: var(--wine); border-radius: 2px; margin: 16px auto 0; }

    .free-delivery-banner {
      background: var(--wine); color: #fff; border-radius: var(--radius);
      padding: 28px 32px; display: flex; align-items: center; gap: 20px; margin-bottom: 40px;
    }
    .free-delivery-banner .banner-icon { flex-shrink: 0; width: 52px; height: 52px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .free-delivery-banner .banner-icon svg { width: 26px; height: 26px; }
    .free-delivery-banner h2 { font-size: 1.3rem; font-weight: 700; margin: 0 0 4px; color: #fff; }
    .free-delivery-banner p { font-size: 16px; margin: 0; color: rgba(255,255,255,0.85); line-height: 1.5; }

    .delivery-options { margin-bottom: 44px; }
    .section-heading { font-size: 13px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--wine); margin: 0 0 16px; padding-bottom: 10px; border-bottom: 1px solid var(--border); }

    .delivery-table { width: 100%; border-collapse: collapse; font-size: 16px; }
    .delivery-table thead tr { background: var(--bg-panel); }
    .delivery-table th { text-align: left; padding: 14px 18px; font-size: 13px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase; color: var(--text-soft); border-bottom: 1px solid var(--border); }
    .delivery-table td { padding: 16px 18px; color: var(--text-mid); border-bottom: 1px solid var(--border); vertical-align: middle; font-size: 16px; line-height: 1.5; }
    .delivery-table tr:last-child td { border-bottom: none; }
    .delivery-table tr:hover td { background: #fdf6f0; }
    .price-free { font-weight: 700; color: #2a7a3b; font-size: 16px; }
    .price-paid { font-weight: 700; color: var(--text); font-size: 16px; }
    .badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 20px; letter-spacing: 0.05em; text-transform: uppercase; margin-left: 8px; vertical-align: middle; }
    .badge-popular { background: #fdf0e8; color: #7a4a00; border: 1px solid var(--gold); }

    .info-blocks { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 44px; }
    .info-block { background: #fff; border: 1px solid var(--border); border-top: 3px solid var(--wine); border-radius: var(--radius); padding: 24px 22px; }
    .info-block h3 { font-size: 17px; font-weight: 700; color: var(--wine); margin: 0 0 10px; }
    .info-block p { font-size: 16px; color: var(--text-mid); line-height: 1.8; margin: 0; }

    .international-notice { background: #fff; border: 1px solid var(--border); border-left: 4px solid var(--gold); border-radius: var(--radius); padding: 24px 26px; margin-bottom: 44px; display: flex; gap: 18px; align-items: flex-start; }
    .international-notice .notice-icon { flex-shrink: 0; width: 38px; height: 38px; background: #fdf0e8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-top: 2px; }
    .international-notice .notice-icon svg { width: 20px; height: 20px; }
    .international-notice h3 { font-size: 17px; font-weight: 700; color: var(--text); margin: 0 0 6px; }
    .international-notice p { font-size: 16px; color: var(--text-mid); line-height: 1.8; margin: 0; }

    .shipping-faqs { margin-bottom: 44px; }
    .sfaq-item { border-bottom: 1px solid var(--border); }
    .sfaq-item:last-child { border-bottom: none; }
    .sfaq-question { width: 100%; background: none; border: none; text-align: left; padding: 20px 4px; font-size: 17px; font-weight: 600; color: var(--text); font-family: Georgia, serif; cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 16px; transition: color 0.18s; }
    .sfaq-question:hover, .sfaq-question.open { color: var(--wine); }
    .sfaq-icon { flex-shrink: 0; width: 24px; height: 24px; border-radius: 50%; border: 1.5px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 15px; color: var(--wine); transition: transform 0.25s, background 0.18s; background: #fff; }
    .sfaq-question.open .sfaq-icon { background: var(--wine); color: #fff; border-color: var(--wine); transform: rotate(45deg); }
    .sfaq-answer { max-height: 0; overflow: hidden; transition: max-height 0.35s ease, padding 0.25s ease; padding: 0 4px; }
    .sfaq-answer.open { max-height: 300px; padding: 0 4px 18px; }
    .sfaq-answer p { font-size: 16px; color: var(--text-mid); line-height: 1.8; margin: 0; }

    .shipping-cta { background: #fff; border: 1px solid var(--border); border-top: 3px solid var(--gold); border-radius: var(--radius); padding: 40px 36px; text-align: center; }
    .shipping-cta p { font-size: 17px; color: var(--text-mid); margin: 0 0 24px; line-height: 1.7; }
    .shipping-cta a { display: inline-block; background: var(--wine); color: #fff; font-size: 16px; font-weight: 600; padding: 12px 30px; border-radius: var(--radius); text-decoration: none; font-family: Georgia, serif; letter-spacing: 0.04em; transition: background 0.18s; }
    .shipping-cta a:hover { background: var(--wine-light); }

    @media (max-width: 640px) {
      .shipping-page { padding: 40px 16px 60px; }
      .shipping-page-title h1 { font-size: 2.2rem; }
      .info-blocks { grid-template-columns: 1fr; }
      .free-delivery-banner { flex-direction: column; text-align: center; }
    }
  </style>
</head>

<body>

  <?php include 'header.php'; ?>

  <div class="shipping-page">

    <div class="shipping-page-title">
      <h1>Shipping & Delivery</h1>
      <p>Everything you need to know about how we get your wine to you.</p>
      <span class="shipping-title-divider"></span>
    </div>

    <div class="free-delivery-banner">
      <div class="banner-icon">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="2" y="10" width="14" height="9" rx="1.5" stroke="#fff" stroke-width="1.6"/>
          <path d="M16 13h3.5a1 1 0 01.9.6L22 17v2h-2" stroke="#fff" stroke-width="1.6" stroke-linecap="round"/>
          <circle cx="6.5" cy="19.5" r="1.8" stroke="#fff" stroke-width="1.6"/>
          <circle cx="18.5" cy="19.5" r="1.8" stroke="#fff" stroke-width="1.6"/>
          <path d="M20 19.5h2V17" stroke="#fff" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
      </div>
      <div>
        <h2>Free standard delivery on every order</h2>
        <p>No minimum spend, no hidden charges. Every Wine Exchange order comes with free standard UK delivery — always.</p>
      </div>
    </div>

    <div class="delivery-options">
      <p class="section-heading">Delivery options</p>
      <table class="delivery-table">
        <thead>
          <tr>
            <th>Delivery type</th>
            <th>Estimated time</th>
            <th>Cost</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Standard delivery <span class="badge badge-popular">Most popular</span></td>
            <td>2–3 business days</td>
            <td><span class="price-free">FREE</span></td>
          </tr>
          <tr>
            <td>Next-day delivery</td>
            <td>Next business day<br><small style="color:var(--text-soft); font-size:13px;">Order before 2pm Mon–Fri</small></td>
            <td><span class="price-paid">£4.99</span></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="info-blocks">
      <div class="info-block">
        <h3>Order tracking</h3>
        <p>As this is a fake website, you won't be getting emails updating you about your order. However you can view your order status on your accounts page.</p>
      </div>
      <div class="info-block">
        <h3>Dispatch times</h3>
        <p>As this is a fake website, you won't be getting emails updating you about your order. However you can view your order status on your accounts page.</p>
      </div>
      <div class="info-block">
        <h3>Packaging</h3>
        <p>All bottles are carefully packed in protective wine-safe packaging to ensure they arrive in perfect condition. We take no shortcuts when it comes to protecting your order.</p>
      </div>
      <div class="info-block">
        <h3>Missed deliveries</h3>
        <p>If you're not in when we attempt delivery, our courier will leave a card with instructions to rearrange or collect from a nearby depot. Most couriers will also attempt a neighbour delivery.</p>
      </div>
    </div>

    <div class="international-notice">
      <div class="notice-icon">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="9" stroke="#c9a84c" stroke-width="1.6"/>
          <path d="M12 8v4l2.5 2.5" stroke="#c9a84c" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
      </div>
      <div>
        <h3>International shipping — coming soon</h3>
        <p>We currently deliver within the UK only. We know many of you are asking about international delivery, and we're working on it — international shipping will be available very soon. Sign up to our newsletter to be the first to know when it launches.</p>
      </div>
    </div>

    <div class="shipping-faqs">
      <p class="section-heading">Common questions</p>

      <div class="sfaq-item">
        <button class="sfaq-question">
          What happens if my order arrives damaged?
          <span class="sfaq-icon">+</span>
        </button>
        <div class="sfaq-answer">
          <p>We're sorry to hear that. Please email us at contactwinexchange@gmail.com with your order number and a photo of the damage within 48 hours of delivery. We'll arrange a replacement or full refund as quickly as possible.</p>
        </div>
      </div>

      <div class="sfaq-item">
        <button class="sfaq-question">
          Do you deliver to business addresses?
          <span class="sfaq-icon">+</span>
        </button>
        <div class="sfaq-answer">
          <p>Yes — we deliver to both residential and business addresses across the UK. Simply enter your business address at checkout as normal.</p>
        </div>
      </div>

      <div class="sfaq-item">
        <button class="sfaq-question">
          Is there a signature required on delivery?
          <span class="sfaq-icon">+</span>
        </button>
        <div class="sfaq-answer">
          <p>Due to the nature of alcohol deliveries, a signature or proof of age (18+) may be required upon receipt. Our couriers are instructed not to leave wine unattended or with anyone who appears to be under 18.</p>
        </div>
      </div>
    </div>

    <div class="shipping-cta">
      <p>Still have a question about your delivery? Our team is happy to help Monday to Friday, 9am–6pm.</p>
      <a href="contact-us.php">Get in touch</a>
    </div>

  </div>

  <?php include 'footer.php'; ?>

  <script>
    document.querySelectorAll('.sfaq-question').forEach(btn => {
      btn.addEventListener('click', () => {
        const answer = btn.nextElementSibling;
        const isOpen = btn.classList.contains('open');
        document.querySelectorAll('.sfaq-question').forEach(b => { b.classList.remove('open'); b.nextElementSibling.classList.remove('open'); });
        if (!isOpen) { btn.classList.add('open'); answer.classList.add('open'); }
      });
    });
  </script>

</body>
</html>