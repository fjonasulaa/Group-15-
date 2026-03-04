<?php
session_start();

require_once('../../database/db_connect.php');

function get_int_from($arr, $key, $default = 0) {
    return isset($arr[$key]) ? intval($arr[$key]) : $default;
}

$wineId = get_int_from($_POST, 'wineId', null);
if (!$wineId) {
    $wineId = get_int_from($_GET, 'id', 0);
}

if ($wineId <= 0) {
    echo "No wine selected.";
    exit;
}

if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = [];
}



$stmt = $conn->prepare("SELECT * FROM wines WHERE wineId = ?");
if (!$stmt) {
    echo "Database error (prepare failed).";
    exit;
}
$stmt->bind_param("i", $wineId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Wine not found.";
    exit;
}

$wine = $result->fetch_assoc();

$mainImage = $wine['imageUrl']
    ? "/Group-15-/images/" . $wine['imageUrl']
    : "../../images/placeholder.jpg";

$stock = $wine['stock'];

$addMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_basket'])) {
    $quantity = get_int_from($_POST, 'quantity', 1);
    if ($quantity < 1) $quantity = 1;
    if ($quantity > $wine['stock']) {
        $addMessage = "Only {$wine['stock']} in stock. You tried to add {$quantity}.";
    } else {

    if (isset($_SESSION['basket'][$wineId])) {
        $_SESSION['basket'][$wineId] += $quantity;
    } else {
        $_SESSION['basket'][$wineId] = $quantity;
    }
    

    $addMessage = "Added {$quantity} × {$wine['wineName']} to your basket.";
    }

}
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
    <!-- NAV -->
    <div class="navbar">
        <img src="../../images/icon.png" alt="Wine Exchange Logo">
        <div class="navbar-links">
            <a href="index.html">Home</a>
            <a href="about.html">About Us</a>
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
        </div>
    </div>

    <div class="separator info"></div>

    <div class="wrap-cards">
        <div class="info-card">
            <!-- MAIN IMAGE -->
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
                    <p class="price">Price: <span>£<?php echo number_format($wine['price'], 2); ?></span></p>
                </div>

                <?php if ($addMessage): ?>
                    <p style="color:green;"><?php echo htmlspecialchars($addMessage); ?></p>
                <?php endif; ?>

                <div class="purchase">
                    <form method="post" style="display:flex; gap:10px; align-items:center;">
                        <input type="hidden" name="wineId" value="<?php echo intval($wineId); ?>">
                        <input type="number" name="quantity" min="1" value="1" style="width:70px;">
                        <button type="submit" name="add_to_basket" class="button">
                            Add to Basket <i class="fas fa-shopping-cart"></i>
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

<script>
    // DARK MODE
    const darkButton = document.getElementById("dark-mode");
    if (localStorage.getItem("dark_mode") === "on") {
        document.documentElement.classList.add("darkmode");
    }
    darkButton.addEventListener("click", () => {
        document.documentElement.classList.toggle("darkmode");
        localStorage.setItem(
            "dark_mode",
            document.documentElement.classList.contains("darkmode") ? "on" : "off"
        );
    });
</script>
</body>
</html>

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
