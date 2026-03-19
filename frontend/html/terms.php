<?php
session_start();
require_once('../../database/db_connect.php');
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

    .tc-wrapper {
      display: flex;
      gap: 48px;
      max-width: 1100px;
      margin: 0 auto;
      padding: 60px 24px 80px;
      align-items: flex-start;
    }

    .tc-nav {
      flex: 0 0 220px;
      position: sticky;
      top: 80px;
    }

    .tc-nav p { font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text-soft); margin: 0 0 12px; }
    .tc-nav ul { list-style: none; margin: 0; padding: 0; border-left: 2px solid var(--border); }
    .tc-nav ul li a { display: block; padding: 7px 14px; font-size: 14px; color: var(--text-mid); text-decoration: none; line-height: 1.5; transition: color 0.18s, border-color 0.18s; border-left: 2px solid transparent; margin-left: -2px; }
    .tc-nav ul li a:hover { color: var(--wine); border-left-color: var(--wine); }

    .tc-content { flex: 1; min-width: 0; }

    .tc-page-title { margin-bottom: 48px; }
    .tc-page-title h1 { font-size: 3rem; font-weight: 700; color: var(--wine); margin: 0 0 10px; letter-spacing: 0.01em; }
    .tc-page-title p { font-size: 16px; color: var(--text-soft); margin: 0 0 6px; line-height: 1.7; }
    .tc-title-divider { display: block; width: 48px; height: 3px; background: var(--wine); border-radius: 2px; margin: 16px 0 0; }

    .tc-updated { display: inline-block; background: var(--bg-panel); border: 1px solid var(--border); border-radius: 20px; font-size: 13px; color: var(--text-soft); padding: 4px 14px; margin-bottom: 40px; }

    .tc-section { margin-bottom: 48px; scroll-margin-top: 80px; }
    .tc-section h2 { font-size: 1.4rem; font-weight: 700; color: var(--wine); margin: 0 0 16px; padding-bottom: 10px; border-bottom: 1px solid var(--border); }
    .tc-section p { font-size: 16px; color: var(--text-mid); line-height: 1.9; margin: 0 0 14px; }
    .tc-section p:last-child { margin-bottom: 0; }
    .tc-section ul { margin: 10px 0 14px 0; padding-left: 22px; }
    .tc-section ul li { font-size: 16px; color: var(--text-mid); line-height: 1.9; margin-bottom: 6px; }

    .tc-highlight { background: #fff; border: 1px solid var(--border); border-left: 4px solid var(--wine); border-radius: var(--radius); padding: 18px 22px; margin: 16px 0; }
    .tc-highlight p { margin: 0; font-size: 15px; color: var(--text-mid); line-height: 1.8; }

    .tc-cta { background: #fff; border: 1px solid var(--border); border-top: 3px solid var(--gold); border-radius: var(--radius); padding: 40px 36px; text-align: center; margin-top: 56px; }
    .tc-cta p { font-size: 17px; color: var(--text-mid); margin: 0 0 24px; line-height: 1.7; }
    .tc-cta a { display: inline-block; background: var(--wine); color: #fff; font-size: 16px; font-weight: 600; padding: 12px 30px; border-radius: var(--radius); text-decoration: none; font-family: Georgia, serif; letter-spacing: 0.04em; transition: background 0.18s; }
    .tc-cta a:hover { background: var(--wine-light); }

    @media (max-width: 768px) {
      .tc-wrapper { flex-direction: column; padding: 40px 16px 60px; gap: 32px; }
      .tc-nav { position: static; flex: unset; width: 100%; }
      .tc-page-title h1 { font-size: 2.2rem; }
    }
  </style>
</head>

<body>

  <?php include 'header.php'; ?>

  <div class="tc-wrapper">

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

    <div class="tc-content">

      <div class="tc-page-title">
        <h1>Terms & Conditions</h1>
        <p>Please read these terms carefully before using Wine Exchange or placing an order with us.</p>
        <span class="tc-title-divider"></span>
      </div>

      <span class="tc-updated">Last updated: 1 January 2026</span>

      <div class="tc-section" id="introduction">
        <h2>1. Introduction</h2>
        <p>Welcome to Wine Exchange. These terms and conditions govern your use of our website and the purchase of products from us. By accessing our website or placing an order, you agree to be bound by these terms.</p>
        <p>Wine Exchange is a UK-based online wine retailer operating at wineexchange.co.uk. References to "we", "us", or "our" refer to Wine Exchange. References to "you" or "your" refer to the customer using our website or placing an order.</p>
        <div class="tc-highlight">
          <p>If you do not agree with any part of these terms, you must not use our website or place an order with us.</p>
        </div>
      </div>

      <div class="tc-section" id="eligibility">
        <h2>2. Age & eligibility</h2>
        <p>You must be aged 18 or over to purchase alcohol from Wine Exchange. This is a legal requirement under UK law. By placing an order, you confirm that you are at least 18 years of age.</p>
        <p>We operate a Challenge 25 policy. Our couriers may request proof of age upon delivery. If satisfactory proof cannot be provided, or if the recipient appears to be under 18, delivery will be refused and the order returned to us.</p>
        <p>We reserve the right to cancel any order where we have reasonable grounds to believe the customer is under 18, or where alcohol may be purchased on behalf of a person under 18.</p>
      </div>

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

      <div class="tc-section" id="pricing">
        <h2>4. Pricing & payment</h2>
        <p>All prices on our website are displayed in pounds sterling (£) and include VAT where applicable. Prices are correct at the time of display but are subject to change without notice.</p>
        <p>In the unlikely event that a product is listed at an incorrect price, we reserve the right to cancel the order and issue a full refund, or contact you to confirm whether you wish to proceed at the correct price.</p>
        <p>We accept the following payment methods: Visa, Mastercard, American Express, and PayPal. Payment is taken in full at the time of ordering. All transactions are processed securely. We do not store your card details on our servers.</p>
      </div>

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

      <div class="tc-section" id="intellectual-property">
        <h2>7. Intellectual property</h2>
        <p>All content on the Wine Exchange website — including but not limited to text, images, logos, graphics, and design — is the property of Wine Exchange and is protected by applicable copyright and intellectual property laws.</p>
        <p>You may not reproduce, distribute, modify, or use any content from our website for commercial purposes without our prior written consent. Personal, non-commercial use is permitted provided you do not remove any copyright notices.</p>
      </div>

      <div class="tc-section" id="liability">
        <h2>8. Limitation of liability</h2>
        <p>To the fullest extent permitted by law, Wine Exchange shall not be liable for any indirect, incidental, special, or consequential loss or damage arising from your use of our website or the products you purchase from us.</p>
        <p>Our total liability to you for any claim arising in connection with an order shall not exceed the total value of that order.</p>
        <p>Nothing in these terms limits or excludes our liability for death or personal injury caused by negligence, fraud or fraudulent misrepresentation, or any other liability that cannot be excluded by law.</p>
      </div>

      <div class="tc-section" id="privacy">
        <h2>9. Privacy</h2>
        <p>Your use of our website is also governed by our Privacy Policy, which is incorporated into these terms by reference. By using our website, you consent to the processing of your personal data as described in our Privacy Policy.</p>
        <p>We comply fully with the UK General Data Protection Regulation (UK GDPR) and the Data Protection Act 2018. Your personal data is never sold or shared with third parties for marketing purposes.</p>
      </div>

      <div class="tc-section" id="changes">
        <h2>10. Changes to these terms</h2>
        <p>We reserve the right to update or amend these terms and conditions at any time. Changes will be effective immediately upon posting to our website. The date at the top of this page will be updated to reflect any changes.</p>
        <p>Your continued use of our website or placement of an order following any changes constitutes your acceptance of the revised terms. We recommend reviewing this page periodically.</p>
      </div>

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

      <div class="tc-cta">
        <p>Have a question about our terms? We're happy to help — get in touch with our team.</p>
        <a href="contact-us.php">Contact us</a>
      </div>

    </div>
  </div>

  <?php include 'footer.php'; ?>

</body>
</html>