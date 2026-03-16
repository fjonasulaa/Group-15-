<?php

session_start();

$error = $_SESSION['register_error'] ?? "";
unset($_SESSION["register_error"]);


function showError($errors) {
    return !empty($errors) ? "<p class='error-message'>$errors</p>" : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <script src="https://accounts.google.com/gsi/client" async defer></script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up | Wine Exchange</title>

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
            <form action="sign_up.php" method ="post">
                <h2>Admin Sign up</h2>
                <p>Admin accounts are subject to verification before activation.</p>
                <p>Customers, please sign up <a href="signup.php">here</a>.</p>
                <?= showError($error); ?>
                <input type="text" name="firstName" placeholder="First Name" required>
                <input type="text" name="surname" placeholder="Surname" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="tel" name="pnumber" placeholder="Phone Number">
                <input type="text" name="addressline" placeholder="Address Line">
                <input type="text" name="postcode" placeholder="Postcode">
                <input type="date" name="dob" placeholder="Date of Birth">
                <input type="password" name="password" placeholder="Password" 
                  pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}"
                  title="Must contain at least one uppercase letter, one lowercase letter, one special character, and be at least 6 characters long."
                required>
                <button type="submit" name="signup">Sign up</button>
                <p>Already have an account? <a href="log-in.php">Login</a></p>

                <div id="g_id_onload"
                  data-client_id="966067449001-4ajt4ll22p3p2kefig7e2rj4ih7oipml.apps.googleusercontent.com"
                  data-callback="handleGoogleSignup">
                </div>

                <div class="g_id_signin"
                  data-type="standard"
                  data-shape="rectangular"
                  data-theme="outline"
                  data-text="signup_with"
                  data-size="large"
                  data-logo_alignment="left">
                </div>
            </form>
        </div>
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
          <li><a href="index.php">Home</a></li>
          <li><a href="search.php">Wines</a></li>
          <li><a href="about.php">About Us</a></li>
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

    <script>
      function handleGoogleSignup(response) {
          fetch("google-login.php", {
            method: "POST",
            headers: {
            "Content-Type": "application/json"
            },
            body: JSON.stringify({
            credential: response.credential
          })
      })
      .then(async res => {
        const text = await res.text();
        console.log("Raw response from google-login.php:", text);

        try {
            return JSON.parse(text);
        } catch (e) {
            throw new Error("google-login.php did not return valid JSON. Response was: " + text);
        }
      })
      .then(data => {
        if (data.success) {
            window.location = "account.php";
        } else {
            alert(data.message || "Google sign up failed");
        }
    })
      .catch(error => {
          console.error("Google sign-up error:", error);
          alert(error.message || "An error occurred during Google sign up.");
      });
  }
  </script>
</body>
</html>
