<?php
session_start();

$successMsg = "";
$errorMsg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get form inputs
    $name    = trim($_POST["name"] ?? "");
    $email   = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // Validation
    if ($name === "" || $email === "" || $subject === "" || $message === "") {
        $errorMsg = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Please enter a valid email address.";
    } else {
        // Email destination
        $to = "contactwinexchange@gmail.com"; 

        // Email content
        $fullSubject = "Wine Exchange Contact Form: " . $subject;

        $body  = "Name: " . $name . "\n";
        $body .= "Email: " . $email . "\n\n";
        $body .= "Message:\n" . $message . "\n";

        $headers = "From: " . $email . "\r\n" .
                   "Reply-To: " . $email . "\r\n";

        // Attempt to send
        if (mail($to, $fullSubject, $body, $headers)) {
            $successMsg = "Your message has been sent successfully!";
            $name = $email = $subject = $message = ""; // clear form
        } else {
            $errorMsg = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us - Wine Exchange</title>
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
      <a href="basket.html">Basket</a>
      <a href="contact-us.php">Contact</a>
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
          value="<?php echo htmlspecialchars($name ?? ""); ?>"
          required
        >

        <label for="email">Email</label>
        <input 
          type="email" 
          id="email" 
          name="email"
          value="<?php echo htmlspecialchars($email ?? ""); ?>"
          required
        >

        <label for="subject">Subject</label>
        <input 
          type="text" 
          id="subject" 
          name="subject"
          value="<?php echo htmlspecialchars($subject ?? ""); ?>"
          required
        >

        <label for="message">Message</label>
        <textarea 
          id="message" 
          name="message" 
          rows="5"
          style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid var(--border-colour);"
          required><?php echo htmlspecialchars($message ?? ""); ?></textarea>

        <button type="submit" class="place-order" style="margin-top: 15px;">
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

