<style>

.img-display {
    overflow: hidden;
    width: 100%;
    aspect-ratio: 1 / 1; 
    border-radius: 10px;
    background: #fff; 
}

.image-showcase {
    display: flex;
    width: 100%;
    transition: transform 0.5s ease;
}
.image-showcase img {
    min-width: 100%;
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease; 
    cursor: zoom-in;
}
.select-image { display: flex; gap: 10px; margin-top: 10px; }
.select-image .item { width: 25%; }
.select-image img {
    width: 100%;
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 5px;
}
.select-image .item:hover img { border-color: #7b1e3a; }

.image-showcase img:hover {
    transform: scale(1.5);
}

.product-imgs {
    width: 100%;
    max-width: 500px; 
    margin: 0 auto;
    display: flex;
    flex-direction: column;
}
    
.stock-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.stock-in {
    background: #d4f8d4;
    color: #1b7a1b;
    border: 1px solid #8fd98f;
}

.stock-out {
    background: #ffe0e0;
    color: #b30000;
    border: 1px solid #ff8a8a;
}
</style>

<?php
session_start();
require_once("../../database/db_connect.php");

$msg = "";

if (!isset($_GET['id'])) {
    die("No wine selected.");
}

$wineId = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM wines WHERE wineId = ?");
$stmt->bind_param("i", $wineId);
$stmt->execute();
$wine = $stmt->get_result()->fetch_assoc();

if (!$wine) {
    die("Wine not found.");
}

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
                    <?php if ($wine['stock'] > 0): ?>
                        <span class="stock-badge stock-in">In Stock</span>
                    <?php else: ?>
                        <span class="stock-badge stock-out">Out of Stock</span>
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

<?php include 'footer.php'; ?>

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
const imgBtns = [...document.querySelectorAll('.select-image a')];
let imgId = 1;

imgBtns.forEach((imgItem) => {
    imgItem.addEventListener('click', (event) => {
        event.preventDefault();
        imgId = imgItem.dataset.id;
        slideImage();
    });
});

function slideImage() {
    const displayWidth = document.querySelector('.image-showcase img:first-child').clientWidth;
    document.querySelector('.image-showcase').style.transform = `translateX(${-(imgId - 1) * displayWidth}px)`;
}

window.addEventListener('resize', slideImage);
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
</script>

</body>
</html>