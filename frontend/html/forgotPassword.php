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
</body>
</html>
