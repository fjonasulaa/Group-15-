<?php

session_start();

$error = $_SESSION['register_error'] ?? "";
unset($_SESSION["register_error"]);

    if (isset($_GET['orderId']) && isset($_SESSION['customerID'])) {
      include '../../database/db_connect.php';

$orderId = $_GET['orderId'];
$customerId = $_SESSION['customerID'];

$stmt = $conn->prepare("SELECT customerID, orderDate FROM orders WHERE orderID = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$stmt->bind_result($orderOwner, $orderDate);
$stmt->fetch();

if ($orderOwner != $customerId) {
    // User is trying to access someone else's order
    header("Location: index.php");
    exit;
}

// Check if order is older than 30 days
$today = new DateTime();
$orderDateObj = new DateTime($orderDate);
$diff = $today->diff($orderDateObj)->days;

if ($diff > 30) {
    header("Location: account.php");
    exit;
}

// Check if order has already been refunded/returned
$stmt2 = $conn->prepare("
    SELECT refundId 
    FROM refund 
    WHERE orderId = ?
");
$stmt2->bind_param("i", $orderId);
$stmt2->execute();
$stmt2->store_result();

if ($stmt2->num_rows > 0) {
    header("Location: account.php");
    exit;
}

    
} else {
    header("Location: index.php");
    exit;
}

function showError($errors) {
    return !empty($errors) ? "<p class='error-message'>$errors</p>" : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return | Wine Exchange</title>

    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: var(--background-colour);
            padding-top: 100px;
        }
        .container {
            margin: 0 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-box {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: var(--frame-colour);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 34px;
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            background: var(--background-colour);
            border-radius: 6px;
            border: none;
            outline: none;
            font-size: 16px;
            color: var(--text-colour);
            margin-bottom: 20px;
            border: 1px solid var(--border-colour);
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--primary-colour);
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            margin-bottom: 20px;
            transition: 0.5s;
        }

        button:hover {
            filter: brightness(0.8);
        }

        p {
            font-size: 14.5px;
            text-align: center;
            margin-bottom: 10px;
        }

        p a {
            color: var(--primary-colour);
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .error-message {
            padding: 12px;
            background: red;
            border-radius: 6px;
            font-size: 16px;
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
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
    <div class="container">
        <div class="form-box" id="signup-form">
            <form action="redirect.php?page=return" method ="post">
                <h2>Return</h2>
                <p>Order ID: <?= $_GET['orderId'] ?></p>
                <input type="hidden" name="orderId" value="<?= $_GET['orderId'] ?>">
                <p>We're sorry you weren't satisfied with your order. Could you please tell us why you are returning?</p>
                <?= showError($error); ?>
                <label for="reason">Reason for Return:</label>
                <select name="reason" id="reason" required>
                    <option value="wrong">I chose the wrong product</option>
                    <option value="broken">The product was broken/not to a good standard</option>
                    <option value="inaccurate">Inaccurate Information on website</option>
                    <option value="duplicate">I got a duplicate order</option>
                    <option value="gift">I ordered this as a gift and it was unwanted</option>
                    <option value="other">Other</option>
                </select>
                <p>Please go into more detail.</p>
                <label for="description">Description:</label>
                <textarea name="description" placeholder="Description" required></textarea>

                <button type="submit" name="create">Return</button>
            </form>
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
            localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
        });
  </script>

  <?php include 'footer.php'; ?>
</body>
</html>