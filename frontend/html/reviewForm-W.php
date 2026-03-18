<?php
session_start();
require_once("../../database/db_connect.php");

if (!isset($_SESSION['customerID'])) {
    header("Location: log-in.php");
    exit();
}

$customerId = $_SESSION['customerID'];
$nameQuery = $conn->prepare("SELECT firstName, surname FROM customer WHERE customerID = ?");
$nameQuery->bind_param("i", $customerId);
$nameQuery->execute();
$nameResult = $nameQuery->get_result()->fetch_assoc();

if (!$nameResult) {
    die("Customer not found.");
}

$customerName = ucfirst($nameResult['firstName']) . " " . ucfirst($nameResult['surname']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stars = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $wReviewHeading = isset($_POST['heading']) ? trim($_POST['heading']) : '';
    $rwReviewText = isset($_POST['message']) ? trim($_POST['message']) : '';

    if ($stars == 0 || $wReviewHeading === '' || $rwReviewText === '') {
        die("Rating and review text are required.");
    }

    $query = $conn->prepare("
        INSERT INTO websiteReviews (customerId, wStars, wReviewHeading, wReviewText, reviewDate)
        VALUES (?, ?, ?, ?, CURRENT_DATE)
    ");

    $query->bind_param("iiss", $customerId, $stars, $wReviewHeading, $rwReviewText);

    if ($query->execute()) {
        header("Location: index.php?review=success");
        exit();
    } else {
        echo "Error submitting review: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Review | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
</head>

<body>
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
          <form method= "POST" action = "search.php">
            <input type="text" name="search" placeholder="Search">

            <input type= "hidden" name= "submitted" value= "true"/>
          </form>
          <a href="log-in.php">Login</a>
          <a href="signup.php">Sign up</a>
          <a href="account.php">Account</a>
          <button id="dark-mode" class="dark-mode-button">
            <img src="../../images/darkmode.png" alt="Dark Mode" />
          </button>
        </div>
      </div>
      
      <main>
      <div class="checkout-container" style="max-width: 500px;">
        <h1>Tell Us What You Think</h1>

        <form class="review-heading" method="post" action="">

          <div class="rating">
            <input type="radio" name="rating" value="5" id="star5" required>
            <label for="star5">★</label>

            <input type="radio" name="rating" value="4" id="star4">
            <label for="star4">★</label>

            <input type="radio" name="rating" value="3" id="star3">
            <label for="star3">★</label>

            <input type="radio" name="rating" value="2" id="star2">
            <label for="star2">★</label>

            <input type="radio" name="rating" value="1" id="star1">
            <label for="star1">★</label>
          </div>

          <label for="heading">Review Heading</label>
          <input
            type="text"
            id="heading"
            name="heading"
            required
          />

          <label for="message">Review Text</label>
          <textarea id="message" name="message" rows="5" required></textarea>

          <button type="submit" name="review" class="submit-review">
            Submit Review
          </button>
        </form>
      </div>
      <style>


  </main>
  
<script>
    const darkButton = document.getElementById("dark-mode");
    if (localStorage.getItem("dark_mode") === "on") {
        document.documentElement.classList.add("darkmode");
    }
    darkButton.addEventListener("click", () => {
        document.documentElement.classList.toggle("darkmode");
        localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
    });
</script>
<?php include 'footer.php'; ?>

</body>
