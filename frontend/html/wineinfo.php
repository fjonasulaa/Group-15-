<?php
session_start();
require_once("../../database/db_connect.php");

$msg = "";

// need a wine ID to do anything here
if (!isset($_GET['id'])) {
    die("No wine selected.");
}

$wineId = intval($_GET['id']);

// pull wine details from DB
$stmt = $conn->prepare("SELECT * FROM wines WHERE wineId = ?");
$stmt->bind_param("i", $wineId);
$stmt->execute();
$wine = $stmt->get_result()->fetch_assoc();

if (!$wine) {
    die("Wine not found.");
}

$mainImage = "../../images/" . $wine['imageUrl'];

// handle basket form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_basket'])) {

    $wineId = intval($_POST['wineId']);
    $qty = max(1, intval($_POST['quantity']));
    $stock = intval($wine['stock']);

    if ($qty > $stock) {
        $msg = "Only $stock left in stock.";
    } else {

        if (!isset($_SESSION['basket'])) {
            $_SESSION['basket'] = [];
        }

        // check how many they've already got in the basket
        $alreadyInBasket = $_SESSION['basket'][$wineId] ?? 0;

        if ($alreadyInBasket + $qty > $stock) {
            $msg = "You already have $alreadyInBasket in your basket. Only $stock available.";
        } else {
            $_SESSION['basket'][$wineId] = $alreadyInBasket + $qty;
            $msg = $qty . "x " . $wine['wineName'] . " added to basket!";
        }
    }
}

// grab reviews for this wine
// TODO: could paginate this later if a wine gets loads of reviews
$reviewStmt = $conn->prepare("
    SELECT r.*, c.firstName, c.surname
    FROM reviews r
    JOIN customer c ON r.customerId = c.customerID
    WHERE r.wineId = ?
");
$reviewStmt->bind_param("i", $wineId);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();

// calculate average star rating
$totalStars = 0;
$reviewCount = $reviewResult->num_rows;
$reviewData = [];

if ($reviewCount > 0) {
    while ($row = $reviewResult->fetch_assoc()) {
        $totalStars += $row['stars'];
        $reviewData[] = $row;
    }
    $avgRating = round($totalStars / $reviewCount, 1);
} else {
    $avgRating = 0;
}

// show success toast if they just submitted a review
$reviewJustSubmitted = isset($_GET['review']) && $_GET['review'] === "success";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?php echo htmlspecialchars($wine['wineName']); ?> | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="info">

<!-- wishlist sidebar + overlay -->
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

<!-- NAV -->
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
        <input type="text" placeholder="Search">
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

<?php if ($reviewJustSubmitted): ?>
    <!-- little toast popup after submitting a review -->
    <div class="review-popup">
        Thank you for leaving a review! Your feedback helps other wine lovers 🍷
    </div>
<?php endif; ?>

<div class="separator info"></div>

<div class="wrap-cards">
    <div class="info-card">

        <div class="images">
            <div class="front-image">
                <img src="<?php echo htmlspecialchars($mainImage); ?>"
                     alt="<?php echo htmlspecialchars($wine['wineName']); ?>"
                     style="width:100%; border-radius:10px;">
            </div>
        </div>

        <div class="content">
            <h2 class="title"><?php echo htmlspecialchars($wine['wineName']); ?></h2>

            <div class="price">
                <p class="price">
                    Price: <span>£<?= $wine['price'] ?></span>
                </p>

                <!-- star rating links down to reviews section -->
                <a href="#reviews-section" class="stars-link">
                    <div class="inline-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= ($i <= round($avgRating)) ? "<i class='fas fa-star'></i>" : "<i class='far fa-star'></i>"; ?>
                        <?php endfor; ?>
                        <span>(<?= $reviewCount ?>)</span>
                    </div>
                </a>
            </div>

            <?php if ($msg): ?>
                <p style="color:green;"><?php echo htmlspecialchars($msg); ?></p>
            <?php endif; ?>

            <div class="purchase">
                <form method="post" style="display:flex; gap:10px; align-items:center;">
                    <input type="hidden" name="wineId" value="<?php echo intval($wineId); ?>">
                    <input type="number" name="quantity" min="1" value="1" style="width:70px;">

                    <button type="submit" name="add_to_basket" class="button">
                        Add to Basket <i class="fas fa-shopping-cart"></i>
                    </button>

                    <button type="button"
                        class="button wishlist-button"
                        data-id="<?php echo $wineId; ?>"
                        data-name="<?php echo htmlspecialchars($wine['wineName']); ?>"
                        data-price="<?php echo number_format($wine['price'], 2); ?>"
                        data-image="<?php echo htmlspecialchars($mainImage); ?>">
                        <i class="fas fa-heart"></i> Add to Wishlist
                    </button>
                </form>
            </div>

            <div class="details">
                <h2>Details:</h2>
                <p><?php echo nl2br(htmlspecialchars($wine['description'])); ?></p>
            </div>

            <!-- ingredients hover reveal -->
            <div class="container">
                <div class="image-container">
                    <article class="image-article">
                        <img src="/Group-15-/images/ingredientsBG.jpg" alt="ingredientsBG" class="image-card">
                        <div class="hover-data">
                            <span class="ingredients"><?php echo htmlspecialchars($wine['ingredients']); ?></span>
                        </div>
                    </article>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // used by wishlist JS below
    const loggedIn = <?php echo isset($_SESSION['customerID']) ? "true" : "false"; ?>;

    // dark mode toggle - remember preference in localStorage
    const darkBtn = document.getElementById("dark-mode");

    if (localStorage.getItem("dark_mode") === "on") {
        document.documentElement.classList.add("darkmode");
    }

    darkBtn.addEventListener("click", () => {
        document.documentElement.classList.toggle("darkmode");
        const isDark = document.documentElement.classList.contains("darkmode");
        localStorage.setItem("dark_mode", isDark ? "on" : "off");
    });

    // wishlist sidebar
    const wishlistToggle = document.getElementById("wishlist-toggle");
    const sidebar = document.getElementById("wishlistSidebar");
    const overlay = document.getElementById("wishlistOverlay");
    const closeBtn = document.getElementById("closeWishlist");

    wishlistToggle.addEventListener("click", () => {
        sidebar.classList.add("active");
        overlay.classList.add("active");
    });

    function closeSidebar() {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    }

    closeBtn.addEventListener("click", closeSidebar);
    overlay.addEventListener("click", closeSidebar);
</script>

<hr class="reviews-divider">

<div class="reviews-container">

    <div id="reviews-section" class="reviews-header">
        <h2>All Reviews</h2>
        <p class="reviews-subtitle">
            Wine is best shared. Your honest reviews help others explore, discover, and choose with confidence.
        </p>
    </div>

    <div class="reviews-top-bar">
        <form action="write_review.php" method="GET">
            <input type="hidden" name="wineId" value="<?php echo htmlspecialchars($wineId); ?>">
            <button type="submit" class="write-review-btn">Write a Review</button>
        </form>
    </div>

    <?php if ($reviewCount == 0): ?>
        <p class="no-reviews">No reviews yet. Be the first to review this wine!</p>
    <?php else: ?>
        <div class="reviews-grid">
            <?php foreach ($reviewData as $rev): ?>
                <div class="review-card">

                    <div class="review-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= ($i <= $rev['stars']) ? "<i class='fas fa-star'></i>" : "<i class='far fa-star'></i>"; ?>
                        <?php endfor; ?>
                    </div>

                    <p class="review-text">
                        <?= htmlspecialchars($rev['reviewText']) ?>
                    </p>

                    <p class="review-meta">
                        <strong><?= htmlspecialchars(ucfirst($rev['firstName']) . " " . ucfirst($rev['surname'])) ?></strong>
                        • <?= date("F j, Y", strtotime($rev['created_at'])) ?>
                    </p>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!-- subtle 3D tilt effect on review cards -->
<script>
document.querySelectorAll('.review-card').forEach(card => {

    card.addEventListener('mousemove', e => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        card.style.transform = `translateY(-6px) rotateX(${y / 40}deg) rotateY(${x / 40}deg)`;
    });

    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0) rotateX(0) rotateY(0)';
    });

});
</script>

<!-- auto-dismiss the review success toast after 3s -->
<script>
setTimeout(() => {
    const toast = document.querySelector(".review-popup");
    if (toast) {
        toast.style.animation = "fadeOut 0.8s forwards";
        setTimeout(() => toast.remove(), 800);
    }
}, 3000);
</script>

</body>
</html>

<style>

/* review submitted toast */
.review-popup {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%) scale(0);
    background: #fffae5;
    border: 2px solid #ffcc00;
    border-radius: 10px;
    padding: 15px 25px;
    font-weight: 600;
    font-size: 1rem;
    color: #5a3e00;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    text-align: center;
    z-index: 9999;
    animation: popIn 0.5s forwards;
}

@keyframes popIn {
    0%   { transform: translateX(-50%) scale(0); opacity: 0; }
    70%  { transform: translateX(-50%) scale(1.2); opacity: 1; }
    100% { transform: translateX(-50%) scale(1); opacity: 1; }
}

@keyframes fadeOut {
    from { opacity: 1; }
    to   { opacity: 0; }
}

/* ---- footer ---- */
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

.footer-links {
    list-style: none;
    padding: 0;
}

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

/* dark mode footer */
.darkmode .footer { background-color: #1e1e1e; color: #eee; }
.darkmode .footer-bottom { border-top: 1px solid #555; }
.darkmode .footer-links a { color: #ddd; }

/* ---- wishlist items ---- */
#wishlist-items {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}

.wishlist-item {
    display: flex;
    gap: 12px;
    align-items: center;
    background: white;
    border-radius: 10px;
    padding: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    position: relative;
}

.wishlist-img {
    width: 65px;
    height: 65px;
    object-fit: cover;
    border-radius: 8px;
}

.wishlist-info { flex: 1; }

.wishlist-name {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.wishlist-price {
    color: #7b1e3a;
    font-weight: bold;
    margin-bottom: 8px;
}

.wishlist-actions { display: flex; gap: 8px; }

.wishlist-view {
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 6px;
    background: #eee;
    text-decoration: none;
    color: #333;
}

.wishlist-view:hover { background: #ddd; }

.wishlist-basket {
    border: none;
    background: #7b1e3a;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    cursor: pointer;
}

.wishlist-basket:hover { background: #5e152c; }

.remove-wishlist {
    position: absolute;
    top: 6px;
    right: 6px;
    border: none;
    background: none;
    font-size: 14px;
    cursor: pointer;
    color: #999;
}

.remove-wishlist:hover { color: red; }

/* ---- wishlist sidebar ---- */
.wishlist-sidebar {
    position: fixed;
    top: 0;
    right: -420px;
    width: 380px;
    height: 100%;
    background: #f4f1f2;
    padding: 30px;
    box-shadow: -5px 0 20px rgba(0,0,0,0.25);
    transition: right 0.4s ease;
    z-index: 2000;
    overflow-y: auto;
}

.wishlist-sidebar.active { right: 0; }

.wishlist-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    z-index: 1500;
}

.wishlist-overlay.active { display: block; }

.close-wishlist {
    font-size: 22px;
    cursor: pointer;
    text-align: right;
    margin-bottom: 15px;
}

/* wishlist heart + badge in nav */
.wishlist-nav-button {
    position: relative;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #e63946;
    margin-left: 10px;
}

.wishlist-count {
    position: absolute;
    top: -6px;
    right: -8px;
    background: #e63946;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 50px;
    min-width: 18px;
    text-align: center;
}

/* wishlist button on product page */
.wishlist-button {
    padding: 8px 14px;
    border: 2px solid #e63946;
    background: white;
    color: #e63946;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.wishlist-button i { margin-right: 5px; }
.wishlist-button:hover { background-color: #e63946; color: white; }
.wishlist-button.active { background-color: #e63946; color: white; }

/* dark mode wishlist */
html.darkmode .wishlist-sidebar { background: #121212; color: #fff; }
html.darkmode .wishlist-sidebar h3 { color: #fff; }
html.darkmode .wishlist-item { background: #1e1e1e; border: 1px solid #333; box-shadow: none; }
html.darkmode .wishlist-name { color: #fff; }
html.darkmode .wishlist-price { color: #ff6b6b; }
html.darkmode .wishlist-view { background: #2c2c2c; color: #fff; }
html.darkmode .wishlist-view:hover { background: #3a3a3a; }
html.darkmode .wishlist-basket { background: #e63946; }
html.darkmode .wishlist-basket:hover { background: #c92d3a; }
html.darkmode .remove-wishlist { color: #bbb; }
html.darkmode .remove-wishlist:hover { color: #ff4d4d; }
html.darkmode #wishlist-items p { color: #ccc; }
</style>

<script>
// TODO: maybe pull wishlist into a separate file, getting a bit long in here

const wishlistButton = document.querySelector(".wishlist-button");
const wishlistContainer = document.getElementById("wishlist-items");
const countBadge = document.getElementById("wishlist-count");

// guest wishlist is just localStorage
function getGuestWishlist() {
    return JSON.parse(localStorage.getItem("wishlist")) || [];
}

function saveGuestWishlist(list) {
    localStorage.setItem("wishlist", JSON.stringify(list));
}

function loadWishlist() {
    if (loggedIn) {
        fetch("get_wishlist.php")
            .then(res => res.json())
            .then(data => {
                renderWishlist(data);
                syncWishlistButton(data);
            });
    } else {
        const list = getGuestWishlist();
        renderWishlist(list);
        syncWishlistButton(list);
    }
}

function renderWishlist(list) {
    wishlistContainer.innerHTML = "";

    if (list.length === 0) {
        wishlistContainer.innerHTML = "<p>Your wishlist is empty.</p>";
        countBadge.textContent = 0;
        return;
    }

    countBadge.textContent = list.length;

    list.forEach((wine, idx) => {

        // image path is slightly different for logged-in vs guest
        let img;
        if (loggedIn) {
            img = wine.imageUrl ? "../../images/" + wine.imageUrl : "../../images/placeholder.jpg";
        } else {
            img = wine.imageUrl || "../../images/placeholder.jpg";
        }

        const item = document.createElement("div");
        item.className = "wishlist-item";

        item.innerHTML = `
            <img src="${img}" class="wishlist-img">
            <div class="wishlist-info">
                <div class="wishlist-name">${wine.wineName || wine.name}</div>
                <div class="wishlist-price">£${wine.price}</div>
                <div class="wishlist-actions">
                    <a href="wineinfo.php?id=${wine.id || wine.wineId}" class="wishlist-view">View</a>
                </div>
            </div>
            <button class="remove-wishlist" data-id="${wine.wineId || wine.id}" data-index="${idx}">
                <i class="fas fa-times"></i>
            </button>
        `;

        wishlistContainer.appendChild(item);
    });
}

// add to wishlist
wishlistButton.addEventListener("click", function() {

    const wine = {
        id: this.dataset.id,
        name: this.dataset.name,
        price: this.dataset.price,
        imageUrl: this.dataset.image
    };

    if (loggedIn) {
        fetch("add_to_wishlist.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "wineId=" + wine.id
        })
        .then(res => res.json())
        .then(() => loadWishlist());

    } else {
        let list = getGuestWishlist();

        // skip if already in there
        if (!list.some(item => item.id === wine.id)) {
            list.push({
                id: wine.id,
                wineName: wine.name,
                price: wine.price,
                imageUrl: wine.imageUrl
            });
            saveGuestWishlist(list);
        }

        renderWishlist(list);
    }

    this.classList.add("active");
    this.innerHTML = '<i class="fas fa-heart"></i> Added to Wishlist';
});

// remove from wishlist - delegated so it works on dynamically rendered items
document.addEventListener("click", function(e) {

    const removeBtn = e.target.closest(".remove-wishlist");
    if (!removeBtn) return;

    const wineId = removeBtn.dataset.id;
    const idx = removeBtn.dataset.index;

    if (loggedIn) {
        fetch("remove_from_wishlist.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "wineId=" + wineId
        }).then(() => {
            loadWishlist();
            resetWishlistButton(wineId);
        });
    } else {
        let list = getGuestWishlist();
        list.splice(idx, 1);
        saveGuestWishlist(list);
        renderWishlist(list);
        resetWishlistButton(wineId);
    }
});

function resetWishlistButton(wineId) {
    if (wishlistButton.dataset.id === wineId) {
        wishlistButton.classList.remove("active");
        wishlistButton.innerHTML = '<i class="fas fa-heart"></i> Add to Wishlist';
    }
}

// update the heart button state to match whether this wine is already wishlisted
function syncWishlistButton(list) {
    const id = wishlistButton.dataset.id;
    const found = list.some(item =>
        (item.wineId && item.wineId == id) ||
        (item.id && item.id == id)
    );

    if (found) {
        wishlistButton.classList.add("active");
        wishlistButton.innerHTML = '<i class="fas fa-heart"></i> Added to Wishlist';
    } else {
        wishlistButton.classList.remove("active");
        wishlistButton.innerHTML = '<i class="fas fa-heart"></i> Add to Wishlist';
    }
}

loadWishlist();
</script>

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