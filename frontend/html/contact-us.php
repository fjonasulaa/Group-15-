<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../../vendor/phpmailer/phpmailer/src/Exception.php";
require __DIR__ . "/../../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require __DIR__ . "/../../vendor/phpmailer/phpmailer/src/SMTP.php";


$successMsg = "";
$errorMsg = "";

// Helper function for old PHP versions
function getPost($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name    = getPost("name");
    $email   = getPost("email");
    $subject = getPost("subject");
    $message = getPost("message");

    if ($name === "" || $email === "" || $subject === "" || $message === "") {
        $errorMsg = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Please enter a valid email address.";
    } else {

        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'fjonasula28@gmail.com';
            $mail->Password   = 'fauw hphl bbxh vacb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email headers
            $mail->setFrom('fjonasula28@gmail.com', 'Wine Exchange');
            $mail->addAddress('fjonasula28@gmail.com');
            $mail->addReplyTo($email, $name);

            // Content
            $mail->Subject = "Wine Exchange Contact Form: $subject";
            $mail->Body    =
                "Name: $name\n" .
                "Email: $email\n\n" .
                "Message:\n$message";

            $mail->send();
            $successMsg = "Your message has been sent successfully!";
            $name = $email = $subject = $message = "";

        } catch (Exception $e) {
            $errorMsg = "Email could not be sent. Error: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us | Wine Exchange</title>
  <link rel="icon" type="image/x-icon" href="../../images/icon.png">
  <link rel="stylesheet" href="../css/styles.css" />
</head>

<body class="info">

  <!-- Navbar -->
  <div class="navbar">
    <img src="../../images/icon.png" alt="Wine Exchange Logo">

    <div class="navbar-links">
      <a href="index.html">Home</a>
      <a href="about.html">About Us</a>
      <a href="wines.html">Wines</a>
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

  <!-- Contact Form Content -->
  <main>
    <div class="checkout-container" style="max-width: 600px;">
      <h1>Contact Us</h1>

      <?php if ($errorMsg !== ""): ?>
        <p style="color: red;"><?php echo htmlspecialchars($errorMsg); ?></p>
      <?php endif; ?>

      <?php if ($successMsg !== ""): ?>
        <p style="color: green;"><?php echo htmlspecialchars($successMsg); ?></p>
      <?php endif; ?>

      <form method="post" action="">
        <label for="name">Name</label>
        <input 
          type="text" 
          id="name" 
          name="name"
          value="<?php echo htmlspecialchars(isset($name) ? $name : ""); ?>"
          required
        >

        <label for="email">Email</label>
        <input 
          type="email" 
          id="email" 
          name="email"
          value="<?php echo htmlspecialchars(isset($email) ? $email : ""); ?>"
          required
        >

        <label for="subject">Subject</label>
        <input 
          type="text" 
          id="subject" 
          name="subject"
          value="<?php echo htmlspecialchars(isset($subject) ? $subject : ""); ?>"
          required
        >

        <label for="message">Message</label>
        <textarea 
          id="message" 
          name="message" 
          rows="5"
          style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid var(--border-colour);"
          required><?php echo htmlspecialchars(isset($message) ? $message : ""); ?></textarea>

        <button type="submit" class="send-message" style="margin-top: 15px;">
          Send Message
        </button>
      </form>
    </div>
  </main>

  <!-- DARK MODE SCRIPT -->
  <script>
      const darkButton = document.getElementById("dark-mode");

      if (localStorage.getItem("dark_mode") === "on") {
          document.documentElement.classList.add("darkmode");
      }

      darkButton.addEventListener("click", () => {
          document.documentElement.classList.toggle("darkmode");
          localStorage.setItem(
              "dark_mode",
              document.documentElement.classList.contains("darkmode") ? "on" : "off"
          );
      });
  </script>
<style>
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
    © 2026 Wine Exchange. All rights reserved.
  </div>
</footer>

</body>
</html>
