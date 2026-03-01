<?php

session_start();

if (!isset($_SESSION['customerID'])) {
  header("Location: log-in.html");
  exit();
}

$cid = $_SESSION['customerID'];
require_once("../../database/db_connect.php");

$orders = $conn->query("SELECT * FROM orders WHERE customerId = $cid ORDER BY orderDate DESC");

$userQuery = $conn->query("SELECT * FROM customer WHERE customerID = $cid");
$user = $userQuery->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />

    <style>

        body {
            background-color: var(--background-colour);
            padding-top: 100px;
        }

        .accountcontainer{
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: var(--frame-colour);
            border-radius: 10x;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

        }

        .accountinfo {
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            background-color: var(--background-colour);
            margin-bottom: 30px;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .accountinfo p {
            font-size: 16px;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--background-colour);
            border-radius: 6px;
            margin-bottom: 20px;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid var(--border-colour);
        }

        th {
            background: var(--primary-colour);
            color: #fff;
        }

        button {
            padding: 12px;
            background: var(--primary-colour);
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            transition: 0.5s;
            display: block;
            width: 300px;
            margin: 0 auto;
        }

        button:hover {
            filter: brightness(0.8);
        }

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
      <a href="websiteReviews.html">Reviews</a>
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

    <div class="accountcontainer">
        <h1>Welcome, <span><?= $user['firstName'];?></span></h1>
        <div class="accountinfo">
            <h2>Account Information</h2>
            <p><Strong>Name:</Strong> <?= $user['firstName']; ?></p>
            <p><Strong>Surname:</Strong> <?= $user['surname']; ?></p>
            <p><Strong>Address:</Strong> <?= $user['addressLine']; ?></p>
            <p><Strong>Postcode:</Strong> <?= $user['postcode']; ?></p>
            <p><Strong>Email:</Strong> <?= $user['email']; ?></p>
            <p><Strong>Date of Birth:</Strong> <?= $user['dateOfBirth']; ?></p>
        </div>

        <div class="orderstable">
            <h2>Order History</h2>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>£ Total</th>
                </tr>
                <?php while ($row = $orders->fetch_assoc()): ?>
                  <tr>
                    <td><?= $row['orderId']; ?></td>
                    <td><?= $row['orderDate']; ?></td>
                    <td><?= $row['totalAmount']; ?></td>
                  </tr>
                  <?php endwhile; ?>
            </table>
        </div>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </div>  

    <!-- footer -->
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
</body>
</html>