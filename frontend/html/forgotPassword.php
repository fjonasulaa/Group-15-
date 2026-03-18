<?php

include '..\..\database\db_connect.php';
include 'users.php';

session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    $u = new Users();
    $customerId = $u->getCustomerIdByEmail($email);

    if ($customerId) {
        $token   = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

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
        $message = "If that email exists, a reset link has been sent.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

<<<<<<< HEAD
    darkButton.addEventListener("click", () => {
      document.documentElement.classList.toggle("darkmode");
      localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
    });
  </script>

<?php include 'footer.php'; ?>

</body>
</html>
=======
        body { background-color: var(--background-colour); }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 62px);
            padding: 40px 16px;
        }

        .form-box {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            background: var(--frame-colour);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-box h1 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 20px;
            color: var(--text-colour);
        }

        .form-box label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-colour);
        }

        .form-box input[type="email"] {
            width: 100%;
            padding: 12px;
            background: var(--background-colour);
            border-radius: 6px;
            border: 1px solid var(--border-colour);
            outline: none;
            font-size: 16px;
            color: var(--text-colour);
            margin-bottom: 20px;
            font-family: inherit;
        }

        .form-box button[type="submit"] {
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

        .form-box button[type="submit"]:hover { filter: brightness(0.8); }

        .form-box p {
            font-size: 14.5px;
            text-align: center;
            color: var(--text-colour);
        }

        .form-box p a {
            color: var(--primary-colour);
            text-decoration: none;
        }

        .form-box p a:hover { text-decoration: underline; }
    </style>
</head>
<body class="info">

    <?php include 'header.php'; ?>

    <div class="container">
        <div class="form-box">
            <h1>Forgot Password</h1>
            <?php if ($message): ?>
                <p style="margin-bottom:16px;"><?php echo htmlspecialchars($message); ?></p>
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
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
>>>>>>> 6508b9a64b176aa971c1a2447054d68d7f8356d6
