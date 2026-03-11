<?php
session_start();
require_once "../../php/db_connection.php";

// Make sure the user is logged in
if (!isset($_SESSION['customerID'])) {
    die("You must be logged in to leave a review.");
}

$customerId = $_SESSION['customerID'];
$wineId = $_GET['wineId'] ?? null;

// Fetch customer name for storing in reviews table
$nameQuery = $conn->prepare("SELECT firstName, surname FROM customers WHERE customerID = ?");
$nameQuery->bind_param("i", $customerId);
$nameQuery->execute();
$nameResult = $nameQuery->get_result()->fetch_assoc();

$customerName = $nameResult['firstName'] . " " . $nameResult['surname'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stars = $_POST['stars'];
    $reviewText = $_POST['reviewText'];

    // Insert review
    $query = $conn->prepare("
        INSERT INTO reviews (customerId, customerName, wineId, stars, reviewText)
        VALUES (?, ?, ?, ?, ?)
    ");

    $query->bind_param("isiss", $customerId, $customerName, $wineId, $stars, $reviewText);

    if ($query->execute()) {
        header("Location: wineinfo.php?wineId=" . $wineId);
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

