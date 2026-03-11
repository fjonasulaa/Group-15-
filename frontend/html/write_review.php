<?php
session_start();
require_once("../../database/db_connect.php");

// 1. User must be logged in
if (!isset($_SESSION['customerID'])) {
    die("You must be logged in to leave a review.");
}

$customerId = $_SESSION['customerID'];

// 2. Determine wineId
$wineId = null;

// Accept BOTH ?id= and ?wineId=
if (isset($_GET['id'])) {
    $wineId = $_GET['id'];
}
if (isset($_GET['wineId'])) {
    $wineId = $_GET['wineId'];
}

// Form submit → POST overrides GET
if (isset($_POST['wineId'])) {
    $wineId = $_POST['wineId'];
}

if (!$wineId) {
    die("No wine selected.");
}

// 3. Fetch customer name
$nameQuery = $conn->prepare("SELECT firstName, surname FROM customer WHERE customerID = ?");
$nameQuery->bind_param("i", $customerId);
$nameQuery->execute();
$nameResult = $nameQuery->get_result()->fetch_assoc();

if (!$nameResult) {
    die("Customer not found.");
}

$customerName = ucfirst($nameResult['firstName']) . " " . ucfirst($nameResult['surname']);

// 4. Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stars = isset($_POST['stars']) ? $_POST['stars'] : null;
    $reviewText = isset($_POST['reviewText']) ? $_POST['reviewText'] : null;

    if (!$stars || !$reviewText) {
        die("Rating and review text are required.");
    }

    // Insert review
    $query = $conn->prepare("
        INSERT INTO reviews (customerId, wineId, stars, reviewText)
        VALUES (?, ?, ?, ?)
    ");

    // i = int, s = string
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
<html>
<head>
    <title>Write a Review</title>
</head>
<body>

<h2>Write a Review</h2>

<form method="POST">
    <!-- KEEP wineId across POST -->
    <input type="hidden" name="wineId" value="<?php echo htmlspecialchars($wineId); ?>">

    <label>Rating:</label><br>
    <select name="stars" required>
        <option value="5">★★★★★</option>
        <option value="4">★★★★☆</option>
        <option value="3">★★★☆☆</option>
        <option value="2">★★☆☆☆</option>
        <option value="1">★☆☆☆☆</option>
    </select>
    <br><br>

    <label>Your Review:</label><br>
    <textarea name="reviewText" rows="5" cols="40" required></textarea>
    <br><br>

    <button type="submit">Submit Review</button>
</form>

</body>
</html>
