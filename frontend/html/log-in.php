<?php
session_start();
require_once("users.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = new Users();

    $customerId = $u->login($_POST["email"], $_POST["password"]);

    if ($customerId !== null) {
        // login success
        $_SESSION["customerId"] = $customerId;
        echo '<script>window.location="user-dashboard.php";</script>';
        exit;
    } else {
        // login failed
        echo '<script>alert("Login Failed");</script>';
    }
}
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Wine Exchange - Login</title>

  <!-- Main site CSS -->
  <link rel="stylesheet" href="../css/styles.css" /">
</head>

<body class="info">

  <!-- Navbar -->
  <div class="navbar">
    <img src="../../images/icon.png" alt="Wine Exchange Logo">
    <div class="navbar-links">
      <a href="index.html">Home</a>
      <a href="about.html">About Us</a>
      <a href="wines.html">Wines</a>
      <a href="basket.html">Basket</a>
      <a href="contact-us.php">Contact Us</a>
    </div>
    
    <div class="navbar-right">
      <form method= "POST" action = "search.php">
            <input type="text" name="search" placeholder="Search">

            <input type= "hidden" name= "submitted" value= "true"/>
      </form>
      <a href="login.html">Login</a>
      <a href="signup.html">Sign up</a>
      <button id="dark-mode" class="dark-mode-button">
        <img src="../../images/darkmode.png" alt="Dark Mode" />
      </button>
    </div>
  </div>
  
  <!-- Main content -->
  <main>
    <div class="checkout-container" style="max-width: 500px;">
      <h1>Login</h1>

      <form class="login-form" method="post" action="">
        <label for="email">Email</label>
        <input
          type="text"
          id="email"
          name="email"
          placeholder="Enter your email"
          required
        />

        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Enter your password"
          required
        />

        <button type="submit" name="login" class="place-order">
          Log In
        </button>

        <p style="margin-top: 15px;">
          Don't have an account?
          <a href="signup.php">Sign up</a>
        </p>
      </form>
    </div>
  </main>
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






