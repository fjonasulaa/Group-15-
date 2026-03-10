<?php
session_start();
require_once("../../database/db_connect.php");

// Must be logged in
if (!isset($_SESSION['customerID'])) {
    header("Location: log-in.php");
    exit;
}

if (!isset($_GET['wineId'])) {
    die("No wine selected.");
}

$wineId = intval($_GET['wineId']);
$customerId = $_SESSION['customerID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stars = intval($_POST['stars']);
    $text = trim($_POST['reviewText']);

    $stmt = $conn->prepare("INSERT INTO reviews (customerId, wineId, stars, reviewText) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $customerId, $wineId, $stars, $text);
    $stmt->execute();

    header("Location: wineinfo.php?id=" . $wineId);
    exit;
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
    <textarea name="reviewText" required></textarea>

    <br><br>

    <button type="submit">Submit Review</button>
</form>

</body>
</html>
