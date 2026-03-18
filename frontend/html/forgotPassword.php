<?php

include '..\..\database\db_connect.php';
include 'users.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    $u = new Users();
    $customerId = $u->getCustomerIdByEmail($email);

    if ($customerId) {
        // Generate secure token
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save token in database
        $u->savePasswordResetToken($email, $token, $expires);

        $resetLink = "http://localhost/Group-15-/frontend/html/resetPassword.php?token=$token";
        
        require '..\..\vendor\autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'wineexchangereset@gmail.com';
            $mail->Password   = 'ddhfcwjvdkvcpfei';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('wineexchangereset@gmail.com', 'Wine Exchange');
            $mail->addAddress($email);

            $mail->isHTML(false);
            $mail->Subject = 'Password Reset';
            $mail->Body    = "Click this link to reset your password:\n$resetLink";

            $mail->send();
            $message = "Reset link sent! Check your email.";
        } catch (Exception $e) {
            $message = "Mailer Error: {$mail->ErrorInfo}";
        }
        

    } else {
        // Don't reveal if email exists
        $message = "If that email exists, a reset link has been sent.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

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
    <meta charset="UTF-8">
    <title>Forgot Password | Wine Exchange</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="info">
    <div class="checkout-container" style="max-width: 500px;">
        <h1>Forgot Password</h1>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="email">Enter your email</label>
            <input type="email" name="email" id="email" required placeholder="you@example.com">
            <button type="submit">Send Reset Link</button>
        </form>
        <p style="margin-top: 15px;">
            <a href="log-in.php">Back to Login</a>
        </p>
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
