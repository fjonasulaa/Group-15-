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

</body>
</html>
