<?php
session_start();
require_once("../../database/db_connect.php");

/* -------------------------
   USER MUST BE LOGGED IN
------------------------- */

if (!isset($_SESSION['customerID'])) {
    header("Location: log-in.php");
    exit();
}

$customerId = $_SESSION['customerID'];


/* -------------------------
   GET WINE ID
------------------------- */

$wineId = null;

if (isset($_GET['id'])) {
    $wineId = $_GET['id'];
}
if (isset($_GET['wineId'])) {
    $wineId = $_GET['wineId'];
}
if (isset($_POST['wineId'])) {
    $wineId = $_POST['wineId'];
}

if (!$wineId) {
    die("No wine selected.");
}


/* -------------------------
   FETCH CUSTOMER NAME
------------------------- */

$nameQuery = $conn->prepare("SELECT firstName, surname FROM customer WHERE customerID = ?");
$nameQuery->bind_param("i", $customerId);
$nameQuery->execute();
$nameResult = $nameQuery->get_result()->fetch_assoc();

if (!$nameResult) {
    die("Customer not found.");
}

$customerName = ucfirst($nameResult['firstName']) . " " . ucfirst($nameResult['surname']);


/* -------------------------
   FETCH WINE NAME
------------------------- */

$wineQuery = $conn->prepare("SELECT wineName FROM wines WHERE wineId = ?");
$wineQuery->bind_param("i", $wineId);
$wineQuery->execute();
$wineResult = $wineQuery->get_result()->fetch_assoc();

if (!$wineResult) {
    die("Wine not found.");
}

$wineName = $wineResult['wineName'];


/* -------------------------
   HANDLE REVIEW SUBMISSION
------------------------- */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stars = $_POST['stars'] === null;
    $reviewText = $_POST['reviewText'] === null;

    if (!$stars || !$reviewText) {
        die("Rating and review text are required.");
    }

    $query = $conn->prepare("
        INSERT INTO reviews (customerId, wineId, stars, reviewText)
        VALUES (?, ?, ?, ?)
    ");

    $query->bind_param("iiis", $customerId, $wineId, $stars, $reviewText);

    if ($query->execute()) {
        header("Location: wineinfo.php?id=" . $wineId);
        exit();
    } else {
        echo "Error submitting review: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Review | Wine Exchange</title>

<link rel="icon" type="image/x-icon" href="../../images/icon.png">
<link rel="stylesheet" href="../css/styles.css">

<style>

/* -------------------------
   REVIEW BOX
------------------------- */

.review-container{
    max-width:600px;
    margin:120px auto 60px auto;
    background:#faf7f5;
    padding:40px;
    border-radius:14px;
    box-shadow:0 4px 18px rgba(0,0,0,0.12);
}

.review-title{
    text-align:center;
    font-family:"Playfair Display",serif;
    font-size:2rem;
    margin-bottom:10px;
    color:#4a1f2d;
}

.review-user{
    text-align:center;
    margin-bottom:25px;
}

/* -------------------------
   STAR RATING
------------------------- */

.rating{
    display:flex;
    flex-direction:row-reverse;
    justify-content:center;
    margin-bottom:25px;
}

.rating input{
    display:none;
}

.rating label{
    font-size:40px;
    color:#ccc;
    cursor:pointer;
    transition:0.2s;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label{
    color:#d4a017;
    transform:scale(1.25);
}

/* -------------------------
   REVIEW TEXT
------------------------- */

.review-label{
    font-weight:600;
    margin-bottom:8px;
    display:block;
}

.review-textarea{
    width:100%;
    padding:14px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:1rem;
}

/* -------------------------
   SUBMIT BUTTON
------------------------- */

.submit-review{
    width:100%;
    margin-top:25px;
    padding:14px;
    background:#7a1f1f;
    color:white;
    border:none;
    border-radius:8px;
    font-size:1.2rem;
    cursor:pointer;
}

.submit-review:hover{
    background:#5e1717;
}

/* -------------------------
   FOOTER
------------------------- */

.footer{
    background:#f4f4f4;
    padding:30px 10%;
    margin-top:40px;
    color:#333;
}

.footer-container{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
}

.footer-section{
    flex:1 1 250px;
    margin:10px;
}

.footer-links{
    list-style:none;
    padding:0;
}

.footer-links li{
    margin:5px 0;
}

.footer-links a{
    text-decoration:none;
    color:inherit;
}

.footer-bottom{
    text-align:center;
    margin-top:20px;
    padding-top:10px;
    border-top:1px solid #ccc;
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
<a href="basket.php">Basket</a>
<a href="contact-us.php">Contact Us</a>
<a href="reviews.html">Feedback</a>
</div>

<div class="navbar-right">

<form method="POST" action="search.php">
<input type="text" name="search" placeholder="Search">
<input type="hidden" name="submitted" value="true">
</form>

<a href="log-in.php">Login</a>
<a href="signup.php">Sign up</a>
<a href="account.php">Account</a>

<button id="dark-mode" class="dark-mode-button">
<img src="../../images/darkmode.png">
</button>

</div>

</div>


<!-- REVIEW BOX -->

<main>

<div class="review-container">

<h1 class="review-title">
Tell Us What You Think About <?php echo htmlspecialchars($wineName); ?>
</h1>

<p class="review-user">
Reviewing as <strong><?php echo $customerName; ?></strong>
</p>

<form method="POST">

<input type="hidden" name="wineId" value="<?php echo htmlspecialchars($wineId); ?>">

<div class="rating">

<input type="radio" name="stars" value="5" id="star5" required>
<label for="star5">★</label>

<input type="radio" name="stars" value="4" id="star4">
<label for="star4">★</label>

<input type="radio" name="stars" value="3" id="star3">
<label for="star3">★</label>

<input type="radio" name="stars" value="2" id="star2">
<label for="star2">★</label>

<input type="radio" name="stars" value="1" id="star1">
<label for="star1">★</label>

</div>

<label class="review-label">Your Review</label>

<textarea name="reviewText" rows="5" class="review-textarea" required></textarea>

<button type="submit" class="submit-review">
Submit Review
</button>

</form>

</div>

</main>


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
<li><a href="contact-us.php">Contact</a></li>
</ul>

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

</body>
</html>