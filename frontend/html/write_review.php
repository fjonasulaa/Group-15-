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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Review | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />

    <style>
        .review-container {
            max-width: 500px;
            margin: 60px auto;
            background: #faf7f5;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .review-container h1 {
            text-align: center;
            font-family: "Playfair Display", serif;
            margin-bottom: 25px;
            color: #4a1f2d;
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            margin-bottom: 20px;
        }

        .rating input {
            display: none;
        }

        .rating label {
            font-size: 32px;
            color: #ccc;
            cursor: pointer;
            transition: 0.2s;
        }

        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: #d4a017;
            transform: scale(1.2);
        }

        .review-container label {
            font-weight: 600;
            display: block;
            margin-top: 15px;
        }

        .review-container textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 5px;
            font-size: 1rem;
        }

        .submit-review {
            width: 100%;
            margin-top: 25px;
            padding: 14px;
            background: #7a1f1f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.2s;
            font-family: "Playfair Display", serif;
        }

        .submit-review:hover {
            background: #5e1717;
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
                <input type="hidden" name="submitted" value="true" />
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
        <div class="review-container">
            <h1>Tell Us What You Think</h1>

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

                <label>Your Review</label>
                <textarea name="reviewText" rows="5" required></textarea>

                <button type="submit" class="submit-review">Submit Review</button>
            </form>
        </div>

        <?php include("footer.php"); ?>
    </main>

</body>
</html>
