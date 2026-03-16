<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Footer | Wine Exchange</title>

  <style>

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: inherit;
    }

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

    .footer-newsletter strong {
      display: block;
      font-weight: 500;
      color: #ffffff;
      font-size: 16px;
      margin-bottom: 2px;
    }

    .footer-newsletter p {
      color: #f5dde3;
      font-size: 15px;
    }

    .newsletter-form {
      display: flex;
      flex-direction: row;
      gap: 8px;
      align-items: center;
    }

    .footer-newsletter .newsletter-form input {
      padding: 7px 12px !important;
      border-radius: 8px !important;
      border: 0.5px solid rgba(255, 255, 255, 0.25) !important;
      background: rgba(255, 255, 255, 0.12) !important;
      color: #ffffff !important;
      font-size: 15px !important;
      width: 210px !important;
      max-width: 210px !important;
      min-width: 210px !important;
      outline: none !important;
      font-family: inherit !important;
      box-sizing: border-box !important;
      margin-bottom: 0 !important;
    }

    .footer-newsletter .newsletter-form input::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    .footer-newsletter .newsletter-form input:focus {
      border-color: rgba(255, 255, 255, 0.5) !important;
    }

    .footer-newsletter .btn-subscribe {
      padding: 7px 16px !important;
      border-radius: 8px !important;
      background: #ffffff !important;
      border: none !important;
      color: #6b1a2e !important;
      font-size: 15px !important;
      font-weight: 500 !important;
      cursor: pointer !important;
      font-family: inherit !important;
      transition: background 0.15s !important;
      width: 210px !important;
      max-width: 210px !important;
      min-width: 210px !important;
      box-sizing: border-box !important;
    }

    .footer-newsletter .btn-subscribe:hover {
      background: #f0e8ea !important;
    }

    .footer-newsletter .btn-subscribe:disabled {
      opacity: 0.7 !important;
      cursor: not-allowed !important;
    }

    #newsletter-msg {
      font-size: 15px;
      margin-top: 4px;
    }

    .footer-main {
      background: #ffffff;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
      gap: 28px;
      padding: 36px 32px 28px;
    }

    .footer-col h4 {
      font-size: 13px;
      font-weight: 600;
      color: #6b1a2e;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      margin-bottom: 14px;
      font-family: Georgia, serif;
    }

    .footer-col p {
      color: #4a2a30;
      font-size: 15px;
      line-height: 1.8;
    }

    .footer-col a {
      display: block;
      color: #4a2a30;
      font-size: 15px;
      line-height: 2;
      text-decoration: none;
      transition: color 0.15s;
    }

    .footer-col a:hover {
      color: #6b1a2e;
    }

    .footer-tagline {
      color: #8a5a60;
      font-style: italic;
      font-size: 15px;
      margin-bottom: 12px;
    }

    .footer-contact-email {
      color: #6b1a2e !important;
    }

    .footer-contact-muted {
      color: #8a5a60 !important;
    }

    .btn-browse {
      display: inline-block;
      margin-top: 16px;
      background: #ffffff;
      color: #6b1a2e;
      font-size: 15px;
      font-weight: 500;
      padding: 9px 18px;
      border-radius: 8px;
      border: 1px solid #6b1a2e;
      text-decoration: none;
      font-family: inherit;
      transition: background 0.15s;
    }

    .btn-browse:hover {
      background: #f9f0f2;
      color: #6b1a2e;
    }

    .trust-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .trust-item {
      display: flex;
      align-items: center;
      gap: 9px;
      color: #4a2a30;
      font-size: 15px;
    }

    .trust-item svg {
      flex-shrink: 0;
      width: 16px;
      height: 16px;
    }

    .payment-icons {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 8px;
    }

    .payment-icon {
      background: #6b1a2e;
      color: #f5e6c8;
      border-radius: 3px;
      padding: 2px 7px;
      font-size: 10px;
      font-weight: 600;
      font-family: Georgia, serif;
      letter-spacing: 0.04em;
    }

    .col-divider {
      border: none;
      border-top: 0.5px solid #e8c8c8;
      margin: 10px 0;
    }

    .footer-bottom {
      border-top: 1px solid #e8c8c8;
      background: #fdf6f0;
      padding: 16px 32px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
    }

    .footer-bottom p {
      font-size: 14px;
      color: #8a5a60;
    }

    .age-badge {
      background: #fdf0e8;
      border: 0.5px solid #c9a84c;
      color: #7a4a00;
      font-size: 13px;
      padding: 3px 10px;
      border-radius: 8px;
    }

    /* ── DARK MODE ── */
    .darkmode .footer-main { background: #1c100d; }
    .darkmode .footer-col p,
    .darkmode .footer-col a { color: #c0a898; }
    .darkmode .footer-col a:hover { color: #f0e6de; }
    .darkmode .footer-bottom { background: #140a08; border-top-color: #3a2820; }
    .darkmode .footer-bottom p { color: #8a7a72; }

    @media (max-width: 600px) {
      .footer-newsletter {
        padding: 16px 20px;
        flex-direction: column;
        align-items: flex-start;
      }

      .newsletter-form {
        width: 100%;
      }

      .newsletter-form input {
        flex: 1;
        width: auto;
      }

      .footer-main {
        padding: 24px 20px;
        grid-template-columns: 1fr 1fr;
      }

      .footer-bottom {
        padding: 14px 20px;
        flex-direction: column;
        align-items: flex-start;
      }
    }

  </style>
</head>

<body>

  <footer>

    <!-- Newsletter bar -->
    <div class="footer-newsletter">
      <div>
        <strong>Join our wine newsletter</strong>
        <p>Tasting notes, new arrivals &amp; exclusive offers</p>
      </div>

      <!-- Form (hidden after successful submission) -->
      <div class="newsletter-form" id="newsletter-form-wrap">
        <input type="email" id="newsletter-email" placeholder="Your email address" />
        <button class="btn-subscribe" id="newsletter-btn" onclick="subscribeNewsletter()">Subscribe</button>
      </div>

      <!-- Message shown after submission (success or error) -->
      <p id="newsletter-msg" style="display:none;"></p>
    </div>

    <!-- Main columns -->
    <div class="footer-main">

      <!-- Col 1: Brand info -->
      <div class="footer-col">
        <h4>Wine Exchange</h4>
        <p class="footer-tagline">Independent wine merchant since 2010</p>
        <p>123 Vineyard Lane<br>London, UK</p>
        <p class="footer-contact-muted" style="margin-top: 8px;">+44 1234 567890</p>
        <a href="mailto:contactwinexchange@gmail.com" class="footer-contact-email">contactwinexchange@gmail.com</a>
        <p class="footer-contact-muted" style="margin-top: 8px;">Mon–Fri, 9am–6pm</p>
        <a href="search.php" class="btn-browse">Browse all wines →</a>
      </div>

      <!-- Col 2: Why shop with us -->
      <div class="footer-col">
        <h4>Why shop with us</h4>
        <div class="trust-list">

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="1" y="4" width="16" height="11" rx="2" stroke="#6b1a2e" stroke-width="1.2"/>
              <path d="M1 7h16" stroke="#6b1a2e" stroke-width="1.2"/>
            </svg>
            Secure payments
          </div>

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="2" y="8" width="10" height="7" rx="1.2" stroke="#6b1a2e" stroke-width="1.2"/>
              <path d="M12 11h2.5a1 1 0 001-1V8.5a1 1 0 00-.6-.9L13 7" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/>
              <circle cx="5" cy="15.5" r="1.2" fill="#6b1a2e"/>
              <circle cx="10" cy="15.5" r="1.2" fill="#6b1a2e"/>
            </svg>
            Free standard delivery on every order
          </div>

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 2v8M9 10l-3 3m3-3l3 3" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/>
              <path d="M4 14h10" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Easy returns
          </div>

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="9" cy="9" r="7" stroke="#6b1a2e" stroke-width="1.2"/>
              <path d="M9 5v4l2.5 2.5" stroke="#6b1a2e" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Next-day dispatch
          </div>

          <div class="trust-item">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 2l1.8 5h5.2l-4.2 3 1.6 5L9 12l-3.4 3 1.6-5L3 7h5.2z" stroke="#6b1a2e" stroke-width="1.1" stroke-linejoin="round"/>
            </svg>
            Expert curation
          </div>

        </div>
      </div>

      <!-- Col 3: Help -->
      <div class="footer-col">
        <h4>Help</h4>
        <a href="about.php">About us</a>
        <a href="faq.php">FAQ</a>
        <a href="contact-us.php">Contact us</a>
        <a href="shipping.php">Shipping &amp; delivery</a>
        <a href="terms.php">Terms &amp; conditions</a>
      </div>

      <!-- Col 4: Legal & payments -->
      <div class="footer-col">
        <h4>Legal &amp; payments</h4>
        <a href="terms.php">Privacy policy</a>
        <a href="terms.php">Cookie policy</a>
        <a href="terms.php">Accessibility</a>
        <hr class="col-divider" />
        <div class="payment-icons">
          <span class="payment-icon">VISA</span>
          <span class="payment-icon">MC</span>
          <span class="payment-icon">AMEX</span>
          <span class="payment-icon">ApplePay</span>
        </div>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="footer-bottom">
      <p>© 2026 Wine Exchange. All rights reserved.</p>
      <span class="age-badge">18+ only — please drink responsibly</span>
    </div>

  </footer>

  <script>
    function subscribeNewsletter() {
      const email = document.getElementById('newsletter-email').value.trim();
      const btn   = document.getElementById('newsletter-btn');
      const msg   = document.getElementById('newsletter-msg');
      const wrap  = document.getElementById('newsletter-form-wrap');

      // Basic client-side check
      if (!email) {
        showMsg('Please enter your email address.', false);
        return;
      }

      // Disable button while request is in flight
      btn.disabled = true;
      btn.textContent = 'Subscribing…';

      const data = new FormData();
      data.append('email', email);

      fetch('subscribe.php', { method: 'POST', body: data })
        .then(r => r.json())
        .then(res => {
          // Hide the form, show the message
          wrap.style.display = 'none';
          msg.style.display  = 'block';
          msg.style.color    = res.success ? '#ffffff' : '#ffc0cb';
          msg.textContent    = res.message;
        })
        .catch(() => {
          btn.disabled = false;
          btn.textContent = 'Subscribe';
          showMsg('Something went wrong. Please try again.', false);
        });
    }

    function showMsg(text, ok) {
      const msg = document.getElementById('newsletter-msg');
      msg.style.display = 'block';
      msg.style.color   = ok ? '#ffffff' : '#ffc0cb';
      msg.textContent   = text;
    }

    // Allow pressing Enter in the email input to subscribe
    document.getElementById('newsletter-email').addEventListener('keydown', function(e) {
      if (e.key === 'Enter') subscribeNewsletter();
    });
  </script>

</body>
</html>