<?php
session_start();
require_once("classes/users.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = new Users();

    $loggedIn = $u->login($_POST["email"], sha1($_POST["password"]));

    if ($loggedIn === true) {
        $_SESSION["uid"] = $u->getuid($_POST["email"]);
        echo '<script>window.location="user-dashboard.php";</script>';
        exit;
    } else {
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
  <link rel="stylesheet" href="styles.css">
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
    </div>

    <div class="navbar-right">
      <input type="text" placeholder="Search">
      <a href="login.php">Login</a>
      <a href="signup.php">Sign up</a>
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
          <a href="register-page.php">Sign up</a>
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
