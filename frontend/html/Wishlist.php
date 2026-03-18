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

    <div id="wishlist-page-items">
        <p>Your wishlist is empty.</p>
    </div>
</div>


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
    
});
</script>

<script src="../js/wishlist.js"></script>
<?php include 'footer.php'; ?>

</body>
</html>