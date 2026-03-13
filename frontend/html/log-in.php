<?php
session_start();
$lockoutTime = 30; // seconds to block login
$maxAttempts = 2;  // number of failed attempts allowed

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['last_attempt_time'])) {
    $_SESSION['last_attempt_time'] = 0;
}

if ($_SESSION['login_attempts'] >= $maxAttempts) {

    // If unlock_time is not set, create it
    if (!isset($_SESSION['unlock_time'])) {
        $_SESSION['unlock_time'] = time() + $lockoutTime;
    }

    $remaining = $_SESSION['unlock_time'] - time();

    if ($remaining > 0) {
        echo "<script>
            alert('Too many failed attempts. Try again in {$remaining} seconds');

            // Auto-refresh when time is up
            setTimeout(function() {
                window.location.reload();
            }, " . ($remaining * 1000) . ");
        </script>";

        exit;
    } else {
        // Reset after lockout expires
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['unlock_time']);
        echo "<script>window.location = 'log-in.php';</script>";
        exit;
    }
}
require_once("users.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['last_attempt_time'] = time();

    $u = new Users();
    $customerId = $u->login($_POST["email"], $_POST["password"]);

    if ($customerId !== null) {
        $_SESSION['customerID'] = $customerId;
        $_SESSION['login_attempts'] = 0; // reset on success
        echo '<script>window.location="account.php";</script>';
        exit;
    } else {
        $_SESSION['login_attempts']++;
        echo '<script>alert("Login Failed");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Wine Exchange</title>

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

        h2, h1 {
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

        /* Footer styling copied from signup page */
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
        .footer-bottom {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 14px;
        }
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
        <a href="search.php">Wines</a>
        <a href="wishlist.html">Wishlist</a>
        <a href="basket.php">Basket</a>
        <a href="contact-us.php">Contact Us</a>
    </div>

    <div class="navbar-right">
        <a href="log-in.php">Login</a>
        <a href="signup.php">Sign up</a>
        <a href="account.php">Account</a>

        <!-- Dark Mode Button -->
        <button id="dark-mode" class="dark-mode-button">
            <img src="../../images/darkmode.png" alt="Dark Mode" />
        </button>
    </div>
</div>



    <!-- LOGIN FORM -->
    <div class="container">
        <div class="form-box">
            <form method="post" action="">
                <h1>Login</h1>

                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="password" name="password" placeholder="Enter your password" required>

                <button type="submit" name="login">Log In</button>

                <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                <p><a href="forgotPassword.php">Forgot your password?</a></p>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
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
            © 2024 Wine Exchange. All rights reserved.
        </div>
    </footer>

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

</body>
</html>











