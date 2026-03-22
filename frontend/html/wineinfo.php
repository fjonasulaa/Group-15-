<style>

.product-imgs {
    width: 100%;
    max-width: 500px; 
    margin: 0 auto;
    display: flex;
    flex-direction: column;
}

.img-display {
    overflow: hidden; 
    width: 100%;
    aspect-ratio: 1 / 1; 
    border-radius: 10px;
    background: transparent;
    position: relative;
}

.image-showcase {
    display: flex;
    width: 100%;
    height: 100%;
    transition: transform 0.5s ease;
}

.image-showcase img {
    min-width: 100%;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
    cursor: zoom-in;
    transform-origin: center; 
}

.image-showcase img:hover {
    transform: scale(1.5);
    position: relative;
    z-index: 10;
}

.select-image { 
    display: flex; 
    gap: 10px; 
    margin-top: 10px; 
}

.select-image .item { 
    width: 25%; 
}

.select-image img {
    width: 100%;
    aspect-ratio: 1 / 1; 
    object-fit: cover;
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 5px;
    transition: border-color 0.3s;
}

.select-image .item:hover img { 
    border-color: #7b1e3a; 
}
    
.stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.stock-in {
    color: #2e7d32;
    background: transparent;
    border-radius: 0;
}

.stock-out {
    color: #7b1e3a;
    background: transparent;
    border-radius: 0;
}

 #share-btn { position: relative; }

        #share-popover {
            display: none;
            position: absolute;
            bottom: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid #e0d6d0;
            border-radius: 10px;
            padding: 12px 16px;
            width: 200px;
            z-index: 100;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }

        #share-popover.open { display: block; }

        #share-popover p {
            font-size: 12px;
            color: #7a5c5c;
            margin: 0 0 10px;
            text-align: center;
        }

        #share-popover a,
        #share-popover button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #e0d6d0;
            background: transparent;
            color: #2a1a1e;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            margin-bottom: 6px;
            box-sizing: border-box;
            transition: background 0.15s;
        }

        #share-popover a:last-child,
        #share-popover button:last-child { margin-bottom: 0; }

        #share-popover a:hover,
        #share-popover button:hover { background: #f4ede8; }

        .darkmode #share-popover {
            background: #2a1a1e;
            border-color: #3a2820;
        }

        .darkmode #share-popover a,
        .darkmode #share-popover button {
            color: #f0e6de;
            border-color: #3a2820;
        }

        .darkmode #share-popover a:hover,
        .darkmode #share-popover button:hover { background: #3a2820; }
</style>

<?php
session_start();
require_once("../../database/db_connect.php");

$msg = "";

$basketAdded = false;

if (!isset($_GET['id'])) {
    die("No wine selected.");
}

$wineId = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM wines WHERE wineId = ?");
$stmt->bind_param("i", $wineId);
$stmt->execute();
$wine = $stmt->get_result()->fetch_assoc();

$inBasket = $_SESSION['basket'][$wineId] ?? 0;
$remainingStock = $wine['stock'] - $inBasket;

if (!$wine) {
    die("Wine not found.");
}




// add to recently viewed
if (!isset($_SESSION['recently_viewed'])) {
    $_SESSION['recently_viewed'] = [];
}

$_SESSION['recently_viewed'] = array_filter($_SESSION['recently_viewed'], fn($id) => $id != $wineId);
array_unshift($_SESSION['recently_viewed'], $wineId);
$_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, 10);




$mainImage = "../../images/" . $wine['imageUrl'];

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

        $alreadyInBasket = $_SESSION['basket'][$wineId] ?? 0;

        if ($alreadyInBasket + $qty > $stock) {
            $msg = "You already have $alreadyInBasket in your basket. Only $stock available.";
        } else {
            $_SESSION['basket'][$wineId] = $alreadyInBasket + $qty;
            $msg = $qty . "x " . $wine['wineName'] . " added to basket!";
            $basketAdded = true;
        }
    }
}

$reviewStmt = $conn->prepare("
    SELECT r.*, c.firstName, c.surname
    FROM reviews r
    JOIN customer c ON r.customerId = c.customerID
    WHERE r.wineId = ?
");
$reviewStmt->bind_param("i", $wineId);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();

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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="info">

<?php include 'header.php'; ?>

<?php if ($reviewJustSubmitted): ?>
    <div class="review-popup">
        Thank you for leaving a review! Your feedback helps other wine lovers 🍷
    </div>
<?php endif; ?>

<div class="separator info"></div>

<div class="wrap-cards">
    <div class="info-card">

        <div class="product-imgs">
            <div class="img-display">
                <div class="image-showcase">
                    <img src="../../images/<?php echo htmlspecialchars($wine['imageUrl']); ?>" alt="wine 1">
                    <img src="../../images/<?php echo htmlspecialchars($wine['img2']); ?>" alt="wine 2">
                    <img src="../../images/<?php echo htmlspecialchars($wine['img3']); ?>" alt="wine 3">
                    <img src="../../images/<?php echo htmlspecialchars($wine['img4']); ?>" alt="wine 4">
                </div>
            </div>
            <div class="select-image">
                <div class="item">
                    <a href="#" data-id="1">
                        <img src="../../images/<?php echo htmlspecialchars($wine['imageUrl']); ?>" alt="wine">
                    </a>
                </div>
                <div class="item">
                    <a href="#" data-id="2">
                        <img src="../../images/<?php echo htmlspecialchars($wine['img2']); ?>" alt="wine">
                    </a>
                </div>
                <div class="item">
                    <a href="#" data-id="3">
                        <img src="../../images/<?php echo htmlspecialchars($wine['img3']); ?>" alt="wine">
                    </a>
                </div>
                <div class="item">
                    <a href="#" data-id="4">
                        <img src="../../images/<?php echo htmlspecialchars($wine['img4']); ?>" alt="wine">
                    </a>
                </div>
            </div>
        </div>

        <div class="content">
            <h2 class="title"><?php echo htmlspecialchars($wine['wineName']); ?></h2>
            <div class="price">
                <p class="price">
                    Price: <span>£<?= $wine['price'] ?></span>
                </p>

                <p class="stock">
                    <?php if ($remainingStock > 0): ?>
                        <span class="stock-badge stock-in">
                            <i class="fas fa-check-circle"></i> In Stock
                        </span>
                    <?php else: ?>
                        <span class="stock-badge stock-out">
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </span>
                    <?php endif; ?>
                </p>

                <a href="#reviews-section" class="stars-link">
                    <div class="inline-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= ($i <= round($avgRating)) ? "<i class='fas fa-star'></i>" : "<i class='far fa-star'></i>"; ?>
                        <?php endfor; ?>
                        <span>(<?= $reviewCount ?>)</span>
                    </div>
                </a>
            </div>


            <div class="purchase">
                <form method="post" style="display:flex; gap:10px; align-items:center;">
                    <input type="hidden" name="wineId" value="<?php echo intval($wineId); ?>">
                    <input 
                        type="number" 
                        name="quantity" 
                        min="1" 
                        max="<?php echo max(0, $remainingStock); ?>" 
                        value="1" 
                        style="width:70px;"
                        <?php if ($remainingStock <= 0) echo 'disabled'; ?>
                    >

                    <button 
                        type="submit" 
                        name="add_to_basket" 
                        class="button basket-button 
                        <?php echo $basketAdded ? 'added' : ''; ?>
                        <?php echo ($remainingStock <= 0) ? 'out-of-stock' : ''; ?>"
    
                        <?php if ($remainingStock <= 0) echo 'disabled'; ?>
                    >
                        <?php if ($remainingStock <= 0): ?>
                            <span class="btn-text">
                                <i class="fas fa-times"></i> Out of Stock
                            </span>

                            <?php elseif ($basketAdded): ?>
                                <span class="btn-text">
                                    <i class="fas fa-check"></i> Added
                            </span>

                        <?php else: ?>
                            <span class="btn-text">
                                Add to Basket <i class="fas fa-shopping-cart"></i>
                            </span>
                            <?php endif; ?>
                    </button>

                    <button type="button"
                        class="button wishlist-button"
                        data-id="<?php echo $wineId; ?>"
                        data-name="<?php echo htmlspecialchars($wine['wineName']); ?>"
                        data-price="<?php echo number_format($wine['price'], 2); ?>"
                        data-image="<?php echo htmlspecialchars($mainImage); ?>">
                        <i class="fas fa-heart"></i> Add to Wishlist
                    </button>

                    <div style="position:relative;">
                        <button type="button" class="button share-button" id="share-btn">
                            <i class="fa-solid fa-share"></i> Share
                        </button>

                        <div id="share-popover">
                            <p>Share this wine</p>
                            <a id="share-whatsapp" href="#" target="_blank">
                                <i class="fa-brands fa-whatsapp"></i> WhatsApp
                            </a>
                            <a id="share-twitter" href="#" target="_blank">
                                <i class="fa-brands fa-x-twitter"></i> X (Twitter)
                            </a>
                            <a id="share-email" href="#">
                                <i class="fa-solid fa-envelope"></i> Email
                            </a>
                            <button type="button" id="share-copy">
                                <i class="fa-solid fa-copy"></i> <span id="share-copy-label">Copy link</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="details">
                <h2>Details:</h2>
                <p><?php echo nl2br(htmlspecialchars($wine['description'])); ?></p>
            </div>

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
<?php include 'footer.php'; ?>

</body>
</html>

<style>
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

.button,
.wishlist-button {
    background-color: #7b1e3a !important;
    border: 2px solid #7b1e3a !important;
    color: white !important;
    border-radius: 6px;
    padding: 8px 14px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.button:hover,
.wishlist-button:hover {
    background-color: #5f172d !important;
    border-color: #5f172d !important;
}

.wishlist-button.active {
    background-color: #7b1e3a !important;
    color: white !important;
}

.basket-button {
    width: 145px;              
    display: inline-flex;
    justify-content: center;
    align-items: center;
}

.basket-button .btn-text {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.basket-button.added {
    background-color: #7b1e3a !important;
    border-color: #7b1e3a !important;
    color: white !important;
    animation: basketPop 0.45s ease;
}

.basket-button.added .fa-check {
    animation: tickPop 0.4s ease;
}

@keyframes basketPop {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.08);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes tickPop {
    0% {
        transform: scale(0) rotate(-20deg);
        opacity: 0;
    }
    60% {
        transform: scale(1.2) rotate(10deg);
        opacity: 1;
    }
    100% {
        transform: scale(1) rotate(0);
        opacity: 1;
    }
}

.basket-button.out-of-stock {
    background-color: #555 !important;
    border-color: #555 !important;
    color: #ccc !important;
    cursor: not-allowed;
    opacity: 0.7;
}

.basket-button.out-of-stock:hover {
    background-color: #555 !important;
    border-color: #555 !important;
}
</style>

<script>
// navbar.php already defines: loggedIn, getGuestWishlist(), saveGuestWishlist(),
// loadWishlist(), renderWishlist() — we just handle the page button here.

const wishlistButton = document.querySelector(".wishlist-button");

function syncWishlistButton(list) {
    const id = wishlistButton.dataset.id;
    const found = list.some(item => (item.wineId && item.wineId == id) || (item.id && item.id == id));
    if (found) {
        wishlistButton.classList.add("active");
        wishlistButton.innerHTML = '<i class="fas fa-heart"></i> Added to Wishlist';
    } else {
        wishlistButton.classList.remove("active");
        wishlistButton.innerHTML = '<i class="fas fa-heart"></i> Add to Wishlist';
    }
}

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
        }).then(res => res.json()).then(() => {
            loadWishlist();
            this.classList.add("active");
            this.innerHTML = '<i class="fas fa-heart"></i> Added to Wishlist';
        });
    } else {
        let list = getGuestWishlist();
        if (!list.some(item => item.id === wine.id)) {
            list.push({ id: wine.id, wineName: wine.name, price: wine.price, imageUrl: wine.imageUrl });
            saveGuestWishlist(list);
            loadWishlist();
        }
        this.classList.add("active");
        this.innerHTML = '<i class="fas fa-heart"></i> Added to Wishlist';
    }
});

// sync button state once navbar's loadWishlist has run
document.addEventListener("DOMContentLoaded", () => {
    if (loggedIn) {
        fetch("get_wishlist.php").then(r => r.json()).then(syncWishlistButton);
    } else {
        syncWishlistButton(getGuestWishlist());
    }
});

// image carousel
const imgs = document.querySelectorAll('.select-image a');
const imgBtns = [...imgs];
let imgId = 1;

imgBtns.forEach((imgItem) => {
    imgItem.addEventListener('click', (event) => {
        event.preventDefault();
        imgId = imgItem.dataset.id;
        slideImage();
    });
});

function slideImage(){
    display.classList.remove('zoomed');
    showcase.classList.remove('no-transition');
    display.scrollTo(0, 0);

    const displayWidth = document.querySelector('.img-display').clientWidth;
    showcase.style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
}

window.addEventListener('resize', slideImage);

const display = document.querySelector('.img-display');
const showcase = document.querySelector('.image-showcase');
const allImages = document.querySelectorAll('.image-showcase img');

display.addEventListener('click', (e) => {
    const activeImg = allImages[imgId - 1];

    if (display.classList.contains('zoomed')) {
        if (!isDown) {
            display.classList.remove('zoomed');
            activeImg.classList.remove('active-zoom');
            showcase.style.transition = "transform 0.5s ease";
            display.scrollTo(0, 0);
        }
    } else {
        display.classList.add('zoomed');
        activeImg.classList.add('active-zoom');
        showcase.style.transition = "none"; 
    }
});

function slideImage(){
    display.classList.remove('zoomed');
    allImages.forEach(img => img.classList.remove('active-zoom'));
    
    const displayWidth = display.clientWidth;
    showcase.style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
}
</script>

<script>
// review toast auto-dismiss
setTimeout(() => {
    const toast = document.querySelector(".review-popup");
    if (toast) {
        toast.style.animation = "fadeOut 0.8s forwards";
        setTimeout(() => toast.remove(), 800);
    }
}, 3000);

// 3D tilt on review cards
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
    
document.addEventListener("DOMContentLoaded", () => {
    const basketBtn = document.querySelector(".basket-button.added");
    if (basketBtn) {
        setTimeout(() => {
            basketBtn.classList.remove("added");
            basketBtn.innerHTML = '<span class="btn-text">Add to Basket <i class="fas fa-shopping-cart"></i></span>';
        }, 1500);
    }
});

const qtyInput = document.querySelector('input[name="quantity"]');

if (qtyInput) {
    const max = parseInt(qtyInput.max);

    qtyInput.addEventListener("input", () => {
        let value = parseInt(qtyInput.value);

        if (value > max) {
            qtyInput.value = max;
        }

        if (value < 1 || isNaN(value)) {
            qtyInput.value = 1;
        }
    });
}
    
</script>

<!-- Share wine code -->
<span id="share-wine-title" data-name="<?= htmlspecialchars($wine['wineName']) ?>"></span>
<script src="share.js"></script>

</body>
</html>

