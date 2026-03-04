<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wishlist | Wine Exchange</title>
<link rel="stylesheet" href="../css/styles.css" />

<style>

/*GENERAL */

.wishlist-wrapper {
    width: 90%;
    margin: 120px auto 0 auto;
    font-family: Arial, sans-serif;
}

.place-order {
    margin-top: 25px;
    background: #8b0000;
    color: white;
    padding: 14px 22px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 1.1rem;
    display: inline-block;
}

/*GRID*/

.wishlist-header {
    display: grid;
    grid-template-columns: 1fr 350px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ccc;
    font-weight: bold;
}

.wishlist-row {
    display: grid;
    grid-template-columns: 160px 1fr 350px;
    padding: 25px 0;
    align-items: center;
    border-bottom: 1px solid #eee;
}

.wishlist-row img {
    width: 140px;
    border-radius: 8px;
}

.wishlist-info-title {
    font-size: 1.3rem;
    font-weight: 600;
}

.remove-link {
    color: #444;
    text-decoration: underline;
    font-size: 0.9rem;
}

/*  DARK MODE BUTTON */

.dark-mode-button {
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px;
}

.dark-mode-button img {
    width: 24px;
    height: 24px;
}

/* WISHLIST SIDEBAR  */

.wishlist-button {
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    margin-left: 10px;
}

.wishlist-sidebar {
    position: fixed;
    top: 0;
    right: -400px;
    width: 350px;
    height: 100%;
    background: white;
    box-shadow: -4px 0 10px rgba(0,0,0,0.1);
    padding: 20px;
    transition: right 0.3s ease;
    z-index: 999;
    overflow-y: auto;
}

.wishlist-sidebar.active {
    right: 0;
}

.wishlist-item {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

/*  FOOTER  */

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

.footer-section h3 {
  margin-bottom: 10px;
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-links li {
  margin: 5px 0;
}

.footer-links a {
  text-decoration: none;
  color: inherit;
}

.footer-links a:hover {
  text-decoration: underline;
}

.footer-button {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 15px;
  background-color: #4CAF50;
  color: white;
  border-radius: 4px;
  text-decoration: none;
}

.footer-button:hover {
  opacity: 0.9;
}

.footer-bottom {
  text-align: center;
  margin-top: 20px;
  padding-top: 10px;
  border-top: 1px solid #ccc;
  font-size: 14px;
}

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
</head>

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
        <input type="text" placeholder="Search">
        <a href="log-in.html">Login</a>
        <a href="signup.html">Sign up</a>
        <a href="account.html">Account</a>

        <button id="dark-mode" class="dark-mode-button">
            <img src="../../images/darkmode.png" alt="Dark Mode">
        </button>

        <button id="wishlist-toggle" class="wishlist-button">❤️</button>
    </div>
</div>

<!-- WISHLIST SIDEBAR -->
<div id="wishlist-sidebar" class="wishlist-sidebar">
    <h3>Your Wishlist</h3>
    <button id="close-wishlist">Close</button>
    <div id="wishlist-items">
        <p id="empty-msg">Your wishlist is empty.</p>
    </div>
</div>

<!-- WISHLIST CONTENT -->
<div class="wishlist-wrapper">
    <h2>Your Wishlist</h2>
    
</div>

<div style="margin: 40px;">
    <a href="basket.php" class="place-order">Place Order</a>
</div>

<!-- FULL FOOTER -->
<footer class="footer">
  <div class="footer-container">

    <div class="footer-section">
      <h3>Wine Exchange</h3>
      <p>123 Vineyard Lane<br>London, UK</p>
      <p>Phone: +44 1234 567890</p>
      <p>Email: <a href="mailto:contactwinexchange@gmail.com">contactwinexchange@gmail.com</a></p>
      <p>Open: Mon–Fri, 9am–6pm</p>
    </div>

    <div class="footer-section">
      <h3>Quick Links</h3>
      <ul class="footer-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="wines.html">Wines</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="contact-us.php">Contact</a></li>
      </ul>
      <a href="contact-us.php" class="footer-button">Contact Us</a>
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
    © 2026 Wine Exchange. All rights reserved.
  </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const darkBtn = document.getElementById("dark-mode");

    if (localStorage.getItem("dark_mode") === "on") {
        document.documentElement.classList.add("darkmode");
    }

    if (darkBtn) {
        darkBtn.addEventListener("click", function() {
            document.documentElement.classList.toggle("darkmode");
            localStorage.setItem("dark_mode",
                document.documentElement.classList.contains("darkmode") ? "on" : "off"
            );
        });
    }

    const wishlistToggle = document.getElementById("wishlist-toggle");
    const wishlistSidebar = document.getElementById("wishlist-sidebar");
    const closeWishlist = document.getElementById("close-wishlist");
    const wishlistItems = document.getElementById("wishlist-items");
    const emptyMsg = document.getElementById("empty-msg");

    if (wishlistToggle) {
        wishlistToggle.addEventListener("click", () => {
            wishlistSidebar.classList.add("active");
        });
    }

    if (closeWishlist) {
        closeWishlist.addEventListener("click", () => {
            wishlistSidebar.classList.remove("active");
        });
    }

    document.querySelectorAll(".add-btn").forEach(button => {
        button.addEventListener("click", function() {
            const productName = this.closest(".wishlist-row")
                .querySelector(".wishlist-info-title").innerText;

            const item = document.createElement("div");
            item.classList.add("wishlist-item");
            item.innerText = productName;

            if (emptyMsg) emptyMsg.style.display = "none";

            wishlistItems.appendChild(item);
            wishlistSidebar.classList.add("active");
        });
    });

});
</script>

</body>
</html>