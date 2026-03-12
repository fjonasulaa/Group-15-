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
<style>
      /* WISHLIST ITEMS */

#wishlist-items{
    display:flex;
    flex-direction:column;
    gap:15px;
    margin-top:20px;
}

.wishlist-item{
    display:flex;
    gap:12px;
    align-items:center;
    background:white;
    border-radius:10px;
    padding:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    position:relative;
}

.wishlist-img{
    width:65px;
    height:65px;
    object-fit:cover;
    border-radius:8px;
}

.wishlist-info{
    flex:1;
}

.wishlist-name{
    font-weight:600;
    font-size:14px;
    margin-bottom:4px;
}

.wishlist-price{
    color:#7b1e3a;
    font-weight:bold;
    margin-bottom:8px;
}

.wishlist-actions{
    display:flex;
    gap:8px;
}

.wishlist-view{
    padding:4px 10px;
    font-size:12px;
    border-radius:6px;
    background:#eee;
    text-decoration:none;
    color:#333;
}

.wishlist-view:hover{
    background:#ddd;
}

.wishlist-basket{
    border:none;
    background:#7b1e3a;
    color:white;
    padding:4px 8px;
    border-radius:6px;
    cursor:pointer;
}

.wishlist-basket:hover{
    background:#5e152c;
}

.remove-wishlist{
    position:absolute;
    top:6px;
    right:6px;
    border:none;
    background:none;
    font-size:14px;
    cursor:pointer;
    color:#999;
}

.remove-wishlist:hover{
    color:red;
}

/* Footer bottom bar */
.footer-bottom {
  text-align: center;
  margin-top: 20px;
  padding-top: 10px;
  border-top: 1px solid #ccc;
  font-size: 14px;
}

/* DARK MODE SUPPORT */
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

.wishlist-button {
    padding: 8px 14px;
    border: 2px solid #e63946;
    background: white;
    color: #e63946;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.wishlist-button i {
    margin-right: 5px;
}

.wishlist-button:hover {
    background-color: #e63946;
    color: white;
}
.wishlist-button.active {
    background-color: #e63946;
    color: white;
}

/* WISHLIST NAV HEART */

.wishlist-nav-button{
    background:none;
    border:none;
    font-size:20px;
    cursor:pointer;
    color:#e63946;
    margin-left:10px;
}

/* SIDEBAR */

.wishlist-sidebar{
    position:fixed;
    top:0;
    right:-420px;
    width:380px;
    height:100%;
    background:#f4f1f2;
    padding:30px;
    box-shadow:-5px 0 20px rgba(0,0,0,0.25);
    transition:right 0.4s ease;
    z-index:2000;
    overflow-y:auto;
}

.wishlist-sidebar.active{
    right:0;
}

.wishlist-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.5);
    display:none;
    z-index:1500;
}

.wishlist-overlay.active{
    display:block;
}

.close-wishlist{
    font-size:22px;
    cursor:pointer;
    text-align:right;
    margin-bottom:15px;
}
/* DARK MODE WISHLIST */

html.darkmode .wishlist-sidebar{
    background:#141414;
    color:#ffffff;
}

html.darkmode .wishlist-sidebar h3{
    color:#ffffff;
}

html.darkmode #wishlist-items p{
    color:#dddddd;
}

html.darkmode .wishlist-item{
    background:#1f1f1f;
    border:1px solid #333;
}

html.darkmode .wishlist-name{
    color:#ffffff;
}

html.darkmode .wishlist-price{
    color:#ff6b6b;
}

html.darkmode .wishlist-view{
    background:#333;
    color:#ffffff;
}

html.darkmode .wishlist-view:hover{
    background:#444;
}

html.darkmode .remove-wishlist{
    color:#bbbbbb;
}

html.darkmode .remove-wishlist:hover{
    color:#ff4d4d;
}

/* DARK MODE WISHLIST STYLES */

html.darkmode .wishlist-sidebar{
    background:#121212;
    color:#ffffff;
}

/* cards */
html.darkmode .wishlist-item{
    background:#1e1e1e;
    border:1px solid #333;
    box-shadow:none;
}

/* wine name */
html.darkmode .wishlist-name{
    color:#ffffff;
}

/* price */
html.darkmode .wishlist-price{
    color:#ff6b6b;
}

/* buttons */
html.darkmode .wishlist-view{
    background:#2c2c2c;
    color:#ffffff;
}

html.darkmode .wishlist-view:hover{
    background:#3a3a3a;
}

html.darkmode .wishlist-basket{
    background:#e63946;
}

html.darkmode .wishlist-basket:hover{
    background:#c92d3a;
}

/* remove X */
html.darkmode .remove-wishlist{
    color:#bbbbbb;
}

html.darkmode .remove-wishlist:hover{
    color:#ff4d4d;
}

/* empty text */
html.darkmode #wishlist-items p{
    color:#cccccc;
}

/* WISHLIST COUNTER BADGE */

.wishlist-nav-button{
    position:relative;
}

.wishlist-count{
    position:absolute;
    top:-6px;
    right:-8px;
    background:#e63946;
    color:white;
    font-size:11px;
    font-weight:bold;
    padding:2px 6px;
    border-radius:50px;
    min-width:18px;
    text-align:center;
}

</style>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home | Wine Exchange</title>
  <link rel="icon" type="image/x-icon" href="../../images/icon.png">
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* Footer styling */
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

    /* Contact button */
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

    /* Footer bottom bar */
    .footer-bottom {
      text-align: center;
      margin-top: 20px;
      padding-top: 10px;
      border-top: 1px solid #ccc;
      font-size: 14px;
    }

    /* DARK MODE SUPPORT */
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

<body class="info">
    <div class="wishlist-overlay" id="wishlistOverlay"></div>

<div class="wishlist-sidebar" id="wishlistSidebar">

    <div class="close-wishlist" id="closeWishlist">
        <i class="fa fa-times"></i>
    </div>

    <h3>Your Wishlist</h3>

    <div id="wishlist-items">
        <p>Your wishlist is empty.</p>
    </div>

</div>
  <!-- NAVBAR -->
  <div class="navbar">
    <a href="index.php"><img src="../../images/icon.png" alt="Wine Exchange Logo" style="cursor: pointer;"></a>
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

  <!-- header -->

  <section class="header">
    <div class="header-content">
      <p>WINE EXCHANGE - ONLINE WINE SHOP</p>
      <h1><strong>THE WORLD'S PREMIUM HOME OF WINE</strong></h1>
      <p>CURATED COLLECTIONS, DELIVERED WITH CARE</p>
    </div>
  </section>

  <main>
    <!-- welcome -->

    <section class="welcome">
      <div class="welcome-text">
        <h1>WELCOME</h1>
        <p>
          Welcome to Wine Exchange, your place for discovering wines worth
          drinking and collecting. We bring together bottles from trusted
          producers, emerging regions, and iconic estates so you can explore
          with confidence. Whether you’re here for everyday favourites or rare
          finds, we keep the experience clear and enjoyable.
        </p>

        <p>
          At Wine Exchange, we focus on transparency and quality. Every wine
          is selected with intention, and we make sure you know exactly what
          you’re getting — real descriptions, honest pricing, and zero
          guesswork.
        </p>

        <p>
          We also believe great wine belongs to everyone, not just experts.
          Our platform is built to help you browse easily, learn as you go,
          and pick bottles that match your taste.
        </p>
      </div>

      <img src="../../images/welcome.png" alt="Welcome image" />
    </section>

    <!-- reviews -->
    <section class="reviews">
      <h1 class="center-title">REVIEWS</h1>

      <article class="frame">
        <div class="review-header">
          <img src="../../images/bd.jpg" alt="Bob Duncan profile pic" class="profile-pic" />
          <h3>Bob Duncan, 44</h3>
          <img src="../../images/5star.png" alt="5 star" class="review-rating" />
        </div>

        <blockquote>
          <p>
            “Wine Exchange has a truly impressive variety of red, white,
            sparkling, and rosé wines. Browsing the site felt effortless, with
            clear categories and detailed descriptions that made choosing the
            right bottle simple and enjoyable.”
          </p>
          <p>
            “Checkout was quick and secure, and my order arrived in perfect
            condition, beautifully packaged to protect the bottles. It’s clear
            that customer care is a priority, and I’ll definitely be ordering
            again.”
          </p>
        </blockquote>
      </article>

      <article class="frame">
        <div class="review-header">
          <img src="../../images/jj.jpg" alt="Jodi Jones profile pic" class="profile-pic" />
          <h3>Jodi Jones, 55</h3>
          <img src="../../images/5star.png" alt="5 star" class="review-rating" />
        </div>

        <blockquote>
          <p>
            “I’ve ordered several times from Wine Exchange and they’ve always
            delivered on time. The prices are fair compared to other online
            shops, and the range of wines keeps me coming back to explore new
            options.”
          </p>
          <p>
            “Each delivery has been packaged with care, preventing any damage
            and making the unboxing feel special. Their recommendations have
            introduced me to wines I wouldn’t have tried otherwise, which
            makes the experience even more rewarding.”
          </p>
        </blockquote>
      </article>

      <article class="frame">
        <div class="review-header">
          <img src="../../images/hm.jpg" alt="Hari Maguire profile pic" class="profile-pic" />
          <h3>Harry Maguire, 54</h3>
          <img src="../../images/5star.png" alt="5 star" class="review-rating" />
        </div>

        <blockquote>
          <p>
            “I wasn’t sure which wine to choose, but the clear descriptions
            and tasting notes made the decision much easier. The rosé I
            purchased was crisp, refreshing, and exactly what I had hoped
            for.”
          </p>
          <p>
            “What impressed me most was how accessible the site feels even for
            someone who isn’t a wine expert. The overall experience was smooth
            and enjoyable, and I’m already planning to try more of their
            collection.”
          </p>
        </blockquote>
      </article>
    </section>

    <!-- FAQ -->

    <section class="faq">
      <h1 class="center-title">FAQ</h2>

        <div class="frame">
          <div class="faq-item">
            <h3>Q: How does buying work on Wine Exchange?</h3>

            <p>
              A: Purchasing is simple and straightforward. Every bottle listed
              is ready to order.
            </p>
          </div>

          <div class="faq-item">
            <h3>Q: Where do the wines come from?</h3>

            <p>
              A: All wines are sourced from reputable producers, merchants, and
              distributors.
            </p>
          </div>

          <div class="faq-item">
            <h3>Q: Do you offer international shipping?</h3>

            <p>
              A: Many regions are supported, and delivery options are shown at
              checkout.
            </p>
          </div>

          <div class="faq-item">
            <h3>Q: How do I find the right wine?</h3>

            <p>
              A: Explore curated collections, browse by type, and use
              helpful descriptions.
            </p>
          </div>
        </div>
    </section>
  </main>

  <!-- footer -->
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
          <li><a href="index.php">Home</a></li>
          <li><a href="search.php">Wines</a></li>
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
    // DARK MODE
    const darkButton = document.getElementById("dark-mode");
    if (localStorage.getItem("dark_mode") === "on") {
      document.documentElement.classList.add("darkmode");
    }

    darkButton.addEventListener("click", () => {
      document.documentElement.classList.toggle("darkmode");
      localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
    });
  </script>

  <script>
const loggedIn = <?php echo isset($_SESSION['customerID']) ? "true" : "false"; ?>;
const wishlistBtn = document.getElementById("wishlist-toggle");
        const wishlistSidebar = document.getElementById("wishlistSidebar");
        const closeWishlist = document.getElementById("closeWishlist");
        const wishlistOverlay = document.getElementById("wishlistOverlay");

        wishlistBtn.addEventListener("click", () => {
            wishlistSidebar.classList.add("active");
            wishlistOverlay.classList.add("active");
        });

        closeWishlist.addEventListener("click", () => {
            wishlistSidebar.classList.remove("active");
            wishlistOverlay.classList.remove("active");
        });

        wishlistOverlay.addEventListener("click", () => {
            wishlistSidebar.classList.remove("active");
            wishlistOverlay.classList.remove("active");
        });

        const wishlistContainer = document.getElementById("wishlist-items");
const wishlistCount = document.getElementById("wishlist-count");

function getGuestWishlist(){
    return JSON.parse(localStorage.getItem("wishlist")) || [];
}

function saveGuestWishlist(list){
    localStorage.setItem("wishlist",JSON.stringify(list));
}

function loadWishlist(){

    if(loggedIn){

        fetch("get_wishlist.php")
        .then(res => res.json())
        .then(data => {
            renderWishlist(data);
        });

    }else{

        const list = getGuestWishlist();
        renderWishlist(list);

    }

}

function renderWishlist(list){

    wishlistContainer.innerHTML="";

    if(list.length===0){
        wishlistContainer.innerHTML="<p>Your wishlist is empty.</p>";
        wishlistCount.textContent=0;
        return;
    }

    wishlistCount.textContent=list.length;

    list.forEach((wine,index)=>{

        let image;

        if(loggedIn){
            image = wine.imageUrl
                ? "../../images/" + wine.imageUrl
                : "../../images/placeholder.jpg";
        }else{
            image = wine.imageUrl || "../../images/placeholder.jpg";
        }

        const item=document.createElement("div");
        item.className="wishlist-item";

        item.innerHTML=`
        <img src="${image}" class="wishlist-img">

        <div class="wishlist-info">
            <div class="wishlist-name">${wine.wineName || wine.name}</div>
            <div class="wishlist-price">£${wine.price}</div>

            <div class="wishlist-actions">
                <a href="wineinfo.php?id=${wine.id || wine.wineId}" class="wishlist-view">View</a>
            </div>
        </div>

        <button class="remove-wishlist" data-id="${wine.wineId || wine.id}" data-index="${index}">
            <i class="fas fa-times"></i>
        </button>
        `;

        wishlistContainer.appendChild(item);
    });

}

document.addEventListener("click",function(e){

    const removeBtn=e.target.closest(".remove-wishlist");
    if(!removeBtn) return;

    const wineId=removeBtn.dataset.id;
    const index=removeBtn.dataset.index;

    if(loggedIn){

        fetch("remove_from_wishlist.php",{
            method:"POST",
            headers:{"Content-Type":"application/x-www-form-urlencoded"},
            body:"wineId="+wineId
        })
        .then(()=> loadWishlist());

    }else{

        let list=getGuestWishlist();
        list.splice(index,1);
        saveGuestWishlist(list);
        renderWishlist(list);

    }

});
loadWishlist();
</script>
</body>

</html>