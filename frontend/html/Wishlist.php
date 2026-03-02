<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist | Wine Exchange</title>
    <link rel="stylesheet" href="../css/styles.css" />
</head>

<style>
.place-order {
    margin-top: 25px;
    background: var(--primary-colour);
    color: white;
    padding: 14px 22px;
    border-radius: var(--radius);
    text-decoration: none;
    font-size: 1.1rem;
    display: inline-block;
    text-align: center;
}

.wishlist-wrapper {
    width: 90%;
    margin: 120px auto 0 auto;
    font-family: Arial, sans-serif;
}

.wishlist-header {
    display: grid;
    grid-template-columns: 1fr 160px 100px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ccc;
    font-weight: bold;
    letter-spacing: 1px;
    color: #333;
}

.wishlist-row {
    display: grid;
    grid-template-columns: 160px 1fr 160px 100px;
    padding: 25px 0;
    align-items: center;
    border-bottom: 1px solid #eee;
}

.wishlist-row img {
    width: 140px;
    border-radius: 8px;
}

.wishlist-info-title { font-size: 1.3rem; font-weight: 600; margin-bottom: 5px; }
.wishlist-info-sub { color: #666; font-size: 0.9rem; margin-bottom: 4px; }

.qty-control { display: flex; align-items: center; gap: 15px; }

.qty-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #aaa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    cursor: pointer;
}

.remove-link { color: #444; text-decoration: underline; font-size: 0.9rem; margin-top: 8px; display: inline-block; }

.wishlist-total-price { font-size: 1.2rem; font-weight: bold; }

/* FOOTER */
.footer {
  background-color: #f4f4f4;
  padding: 30px 10%;
  margin-top: 40px;
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

.footer-section h3 { margin-bottom: 10px; }

.footer-links { list-style: none; padding: 0; }

.footer-links li { margin: 5px 0; }

.footer-links a {
  text-decoration: none;
  color: inherit;
}

.footer-links a:hover { text-decoration: underline; }

.footer-button {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 15px;
  background-color: #4CAF50;
  color: white;
  border-radius: 4px;
  text-decoration: none;
}

.footer-button:hover { opacity: 0.9; }

.footer-bottom {
  text-align: center;
  margin-top: 20px;
  padding-top: 10px;
  border-top: 1px solid #ccc;
  font-size: 14px;
}

/* DARK MODE */
.darkmode .footer {
  background-color: #1e1e1e;
  color: #eee;
}

.darkmode .footer-bottom {
  border-top: 1px solid #555;
}

.darkmode .footer-links a {
  color: #ddd;
}
</style>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <img src="../../images/icon.png" alt="Wine Exchange Logo">
    <div class="navbar-links">
        <a href="index.html">Home</a>
        <a href="about.html">About Us</a>
        <a href="wines.html">Wines</a>
        <a href="basket.html">Basket</a>
        <a href="contact-us.html">Contact Us</a>
    </div>
    <div class="navbar-right">
        <form>
            <input type="text" name="search" placeholder="Search">
        </form>
        <a href="log-in.html">Login</a>
        <a href="signup.html">Sign up</a>
        <a href="account.html">Account</a>
        <button id="dark-mode" class="dark-mode-button">
            <img src="../../images/darkmode.png" alt="Dark Mode" />
        </button>
    </div>
</div>

<!-- WISHLIST CONTENT -->
<div class="wishlist-wrapper">
    <h2>Your Wishlist</h2>

    <div class="wishlist-header">
        <span>PRODUCT</span>
        <span style="text-align:center;">QUANTITY</span>
        <span style="text-align:right;">TOTAL</span>
    </div>

    <!-- Example static item -->
    <div class="wishlist-row" data-product-id="1" data-price="10.00">
        <img src="../../images/sample.jpg" alt="Product Image">

        <div>
            <div class="wishlist-info-title">Sample Wine</div>
            <a href="#" class="remove-link">Remove Item</a>
        </div>

        <div class="qty-control" style="justify-content:center;">
            <div class="qty-btn">-</div>
            <span>1</span>
            <div class="qty-btn">+</div>
        </div>

        <div class="wishlist-total-price" style="text-align:right;">£10.00</div>
    </div>

</div>

<!-- PLACE ORDER BUTTON -->
<div style="margin-top: 50px; text-align: left; padding-left: 20px;">
    <a href="checkout.html" class="place-order">Place Order</a>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-container">

    <div class="footer-section">
      <h3>Wine Exchange</h3>
      <p>123 Vineyard Lane<br>London, UK</p>
      <p>Phone: +44 1234 567890</p>
      <p>Email: contactwinexchange@gmail.com</p>
      <p>Open: Mon–Fri, 9am–6pm</p>
    </div>

    <div class="footer-section">
      <h3>Quick Links</h3>
      <ul class="footer-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="wines.html">Wines</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="contact-us.html">Contact</a></li>
      </ul>
      <a href="contact-us.html" class="footer-button">Contact Us</a>
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
    © 2024 Wine Exchange. All rights reserved.
  </div>
</footer>

<script>
// Dark mode
const button = document.getElementById("dark-mode");

if (localStorage.getItem("dark_mode") === "on") {
  document.documentElement.classList.add("darkmode");
}

button.addEventListener("click", () => {
  document.documentElement.classList.toggle("darkmode");

  if (document.documentElement.classList.contains("darkmode")) {
    localStorage.setItem("dark_mode", "on");
  } else {
    localStorage.setItem("dark_mode", "off");
  }
});

// Quantity buttons (frontend only)
const rows = document.querySelectorAll('.wishlist-row');

rows.forEach(row => {
    const qtyBtns = row.querySelectorAll('.qty-btn');
    const qtyDisplay = row.querySelector('.qty-control span');
    const priceElement = row.querySelector('.wishlist-total-price');

    let qty = parseInt(qtyDisplay.textContent);
    const basePrice = parseFloat(row.getAttribute('data-price'));

    qtyBtns[0].addEventListener('click', () => {
        if (qty > 1) {
            qty--;
            qtyDisplay.textContent = qty;
            priceElement.textContent = '£' + (basePrice * qty).toFixed(2);
        }
    });

    qtyBtns[1].addEventListener('click', () => {
        qty++;
        qtyDisplay.textContent = qty;
        priceElement.textContent = '£' + (basePrice * qty).toFixed(2);
    });

    row.querySelector('.remove-link').addEventListener('click', e => {
        e.preventDefault();
        row.remove();
    });
});
</script>

</body>
</html>