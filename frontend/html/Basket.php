<?php
    session_start();
    require_once('../../database/db_connect.php');
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket | Wine Exchange</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

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
    background:#121212;
    color:#ffffff;
}

html.darkmode .wishlist-sidebar h3{
    color:#ffffff;
}

html.darkmode #wishlist-items p{
    color:#cccccc;
}

html.darkmode .wishlist-item{
    background:#1e1e1e;
    border:1px solid #333;
    box-shadow:none;
}

html.darkmode .wishlist-name{
    color:#ffffff;
}

html.darkmode .wishlist-price{
    color:#ff6b6b;
}

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

html.darkmode .remove-wishlist{
    color:#bbbbbb;
}

html.darkmode .remove-wishlist:hover{
    color:#ff4d4d;
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

.place-order {
    background: #7b1e3a;
    color: white;
    padding: 14px 40px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: bold;
    transition: background 0.3s ease;
}
.place-order:hover {
    background: #5e152c;
}
.basket-wrapper {
    width: 90%;
    margin: 120px auto 40px auto; 
    display: flex;               
    flex-direction: column;       
    min-height: auto;
}
.basket-header {
    display: grid;
    grid-template-columns: 1fr 160px 100px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ccc;
    font-weight: bold;
    letter-spacing: 1px;
    color: #333;
}
.basket-row {
    display: grid;
    grid-template-columns: 160px 1fr 160px 100px;
    padding: 25px 0;
    align-items: center;
    border-bottom: 1px solid #eee;
}
.basket-row img {
    width: 140px;
    border-radius: 8px;
}
.basket-info-title { font-size: 1.3rem; font-weight: 600; margin-bottom: 5px; }
.basket-info-sub { color: #666; font-size: 0.9rem; margin-bottom: 4px; }
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
.basket-total-price { font-size: 1.2rem; font-weight: bold; }
.checkout-button-wrapper {
    position: relative;
    margin-top: 100px;
    margin-bottom: 20px;
    display: flex;
    justify-content: flex-end;
    width: 100%;
    clear: both;
    z-index: 1;
}

</style>

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
        <img src="../../images/icon.png" alt="Wine Exchange Logo">
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
                <input type="hidden" name="submitted" value="true"/>
            </form>
            <a href="log-in.php">Login</a>
            <a href="signup.php">Sign up</a>
            <a href="account.php">Account</a>
            <button id="dark-mode" class="dark-mode-button">
                <img src="../../images/darkmode.png" alt="Dark Mode" />
            </button>
            <button id="wishlist-toggle" class="wishlist-nav-button">
                <i class="fas fa-heart"></i>
                <span id="wishlist-count" class="wishlist-count">0</span>
            </button>
        </div>
    </div>

    <!-- BASKET CONTENT -->
    <div class="basket-wrapper">
        <h2>Your Basket</h2>
        <br>
        <div class="basket-header">
            <span>PRODUCT</span>
            <span style="text-align:center;">QUANTITY</span>
            <span style="text-align:right;">TOTAL</span>
        </div>

        <?php
        if (!empty($_SESSION['basket'])) {
            include '..\..\database\db_connect.php';
            foreach ($_SESSION['basket'] as $id => $qty) {
                $sql = "SELECT wineName, price, imageUrl, stock FROM wines WHERE wineId = $id";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                $wineName = $row['wineName'];
                $price = $row['price'];
                $imageUrl = $row['imageUrl'];

                echo "
                    <div class='basket-row' data-product-id='$id' data-price='$price' data-stock='{$row['stock']}'>
                        <img src='../../images/$imageUrl' alt='Product Image'>

                        <div>
                            <div class='basket-info-title'>$wineName</div>
                            <a href='#' class='remove-link'>Remove Item</a>
                        </div>

                        <div class='qty-control' style='justify-content:center;'>
                            <div class='qty-btn'>-</div>
                            <span>$qty</span>
                            <div class='qty-btn'>+</div>
                        </div>

                        <div class='basket-total-price' style='text-align:right;'>£" . number_format($price * $qty, 2) . "</div>
                    </div>
                ";
            }
        } else {
            echo "<br><p>Your basket is empty.</p><br>";
        }
        ?>
        <!-- PLACE ORDER BUTTON -->
    <?php if (!empty($_SESSION['basket'])): ?>
    <div class="checkout-button-wrapper">
        <a href="checkout.php" class="place-order">Place Order</a>
    </div>
    <?php endif; ?>
    </div>

    

    <!-- FOOTER -->
    <?php include 'footer.php'; ?>

    <script>
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

        // Quantity buttons functionality
        const rows = document.querySelectorAll('.basket-row');

        function updateServer(productId, newQuantity){
            fetch("update.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `product_id=${productId}&quantity=${newQuantity}`
            });
        }

        rows.forEach(row => {
            const productId = row.getAttribute('data-product-id');
            const qtyBtns = row.querySelectorAll('.qty-btn');
            const qtyDisplay = row.querySelector('.qty-control span');
            const priceElement = row.querySelector('.basket-total-price');

            let qty = parseInt(qtyDisplay.textContent);
            const basePrice = parseFloat(row.getAttribute('data-price'));
            const maxStock = parseInt(row.getAttribute('data-stock'));

            qtyBtns[0].addEventListener('click', () => {
                if (qty > 1) {
                    qty--;
                    qtyDisplay.textContent = qty;
                    priceElement.textContent = '£' + (basePrice * qty).toFixed(2);
                    updateServer(productId, qty);
                }
            });

            qtyBtns[1].addEventListener('click', () => {
                if (qty < maxStock) {
                    qty++;
                    qtyDisplay.textContent = qty;
                    priceElement.textContent = '£' + (basePrice * qty).toFixed(2);
                    updateServer(productId, qty);
                } else {
                    alert("You cannot add more than the available stock.");
                }
            });

            row.querySelector('.remove-link').addEventListener('click', e => {
                e.preventDefault();
                row.remove();
                updateServer(productId, 0);
            });
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
            localStorage.setItem("wishlist", JSON.stringify(list));
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
            wishlistContainer.innerHTML = "";

            if(list.length === 0){
                wishlistContainer.innerHTML = "<p>Your wishlist is empty.</p>";
                wishlistCount.textContent = 0;
                return;
            }

            wishlistCount.textContent = list.length;

            list.forEach((wine, index) => {
                let image;

                if(loggedIn){
                    image = wine.imageUrl
                        ? "../../images/" + wine.imageUrl
                        : "../../images/placeholder.jpg";
                }else{
                    image = wine.imageUrl || "../../images/placeholder.jpg";
                }

                const item = document.createElement("div");
                item.className = "wishlist-item";

                item.innerHTML = `
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

        document.addEventListener("click", function(e){
            const removeBtn = e.target.closest(".remove-wishlist");
            if(!removeBtn) return;

            const wineId = removeBtn.dataset.id;
            const index = removeBtn.dataset.index;

            if(loggedIn){
                fetch("remove_from_wishlist.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/x-www-form-urlencoded"},
                    body: "wineId=" + wineId
                })
                .then(() => loadWishlist());
            }else{
                let list = getGuestWishlist();
                list.splice(index, 1);
                saveGuestWishlist(list);
                renderWishlist(list);
            }
        });

        loadWishlist();
    </script>

</body>
</html>