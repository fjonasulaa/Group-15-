<?php
session_start();

$lockoutTime = 30;
$maxAttempts = 2;

if (!isset($_SESSION['login_attempts']))    $_SESSION['login_attempts']    = 0;
if (!isset($_SESSION['last_attempt_time'])) $_SESSION['last_attempt_time'] = 0;

// ── Lockout check ─────────────────────────────────────────────────────────
if ($_SESSION['login_attempts'] >= $maxAttempts) {
    if (!isset($_SESSION['unlock_time'])) {
        $_SESSION['unlock_time'] = time() + $lockoutTime;
    }
    $remaining = $_SESSION['unlock_time'] - time();
    if ($remaining > 0) {
        echo "<script>
            alert('Too many failed attempts. Try again in {$remaining} seconds');
            setTimeout(function(){ window.location.reload(); }, " . ($remaining * 1000) . ");
        </script>";
        exit;
    } else {
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['unlock_time']);
        echo "<script>window.location='log-in.php';</script>";
        exit;
    }
}

require_once("users.php");
require '..\..\vendor\autoload.php'; // same autoload as forgotPassword.php

// ── Generate & store 2FA code, send via Gmail SMTP ───────────────────────
function setup_2fa(int $userId, string $email, string $name, string $redirect): bool
{
    $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    $_SESSION['2fa_code']      = $code;
    $_SESSION['2fa_expires']   = time() + 600;
    $_SESSION['2fa_user_id']   = $userId;
    $_SESSION['2fa_email']     = $email;
    $_SESSION['2fa_name']      = $name;
    $_SESSION['2fa_attempts']  = 0;
    $_SESSION['2fa_last_sent'] = time();
    $_SESSION['2fa_redirect']  = $redirect;
    $_SESSION['csrf_token']    = bin2hex(random_bytes(32));

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Same SMTP credentials as forgotPassword.php
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'wineexchangereset@gmail.com';
        $mail->Password   = 'ddhfcwjvdkvcpfei';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('wineexchangereset@gmail.com', 'Wine Exchange');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Your Wine Exchange Verification Code';
        $mail->Body    = "
        <div style='font-family:Georgia,serif;background:#FDFAF5;padding:40px 20px;'>
          <div style='max-width:480px;margin:0 auto;background:#fff;border:1px solid #E8D5A3;
                      border-top:3px solid #6B1E30;border-radius:4px;padding:36px 40px;text-align:center;'>
            <h2 style='color:#2A1018;font-size:1.5rem;margin-bottom:8px;'>Verify Your Identity</h2>
            <p style='color:#7A4A55;margin-bottom:24px;'>
              Hi {$name}, use the code below to complete your sign-in.<br/>
              It expires in <strong style='color:#6B1E30;'>10 minutes</strong>.
            </p>
            <div style='background:#F5EDD8;border:1.5px solid #C9A84C;border-radius:4px;padding:20px;
                        letter-spacing:0.35em;font-size:2.2rem;font-family:Courier New,monospace;
                        color:#2A1018;font-weight:700;margin-bottom:24px;'>{$code}</div>
            <p style='font-size:0.78rem;color:#7A4A55;'>If you didn't request this, ignore this email.</p>
          </div>
        </div>";
        $mail->AltBody = "Hi {$name},\n\nYour verification code is: {$code}\n\nIt expires in 10 minutes.";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('2FA mail error: ' . $mail->ErrorInfo);
        return false;
    }
}

// ── Handle POST ───────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['last_attempt_time'] = time();

    $u          = new Users();
    $customerId = $u->login($_POST['email'], $_POST['password']);

    if ($customerId !== null) {
        $_SESSION['login_attempts'] = 0;
        $customerId = (int)$customerId;

        $result = $conn->query("SELECT role, email, firstName FROM customer WHERE customerID = $customerId");
        $row    = $result ? $result->fetch_assoc() : null;

        $redirect = ($row && $row['role'] === 'admin') ? 'admin.php' : 'account.php';
        $email    = $row['email']     ?? $_POST['email'];
        $name     = $row['firstName'] ?? 'User';

        $sent = setup_2fa($customerId, $email, $name, $redirect);

        if ($sent) {
            echo '<script>window.location="2FA.php";</script>';
        } else {
            echo '<script>alert("Could not send verification email. Please try again.");</script>';
        }
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
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: var(--background-colour); }
        .container {
            margin: 0 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 62px);
            padding: 40px 0;
        }
        .form-box {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: var(--frame-colour);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { font-size: 34px; text-align: center; margin-bottom: 20px; color: var(--text-colour); }
        button[type="submit"] {
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
        button[type="submit"]:hover { filter: brightness(0.8); }
        p { font-size: 14.5px; text-align: center; margin-bottom: 10px; color: var(--text-colour); }
        p a { color: var(--primary-colour); text-decoration: none; }
        p a:hover { text-decoration: underline; }
        .signupWine { text-align: center; margin-bottom: 10px; }
        .signupWine img { width: 60px; height: 60px; object-fit: contain; }
        .input { position: relative; margin-bottom: 20px; }
        .input i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: var(--text-colour);
            opacity: 0.6; font-size: 14px; height: 16px;
            display: flex; align-items: center;
        }
        .input input {
            width: 100%; padding: 12px 12px 12px 40px;
            background: var(--background-colour); border-radius: 6px;
            border: 1px solid var(--border-colour); outline: none;
            font-size: 16px; color: var(--text-colour); margin-bottom: 0;
        }
        .input:focus-within i { color: var(--primary-colour); opacity: 1; }
        .divider { display: flex; align-items: center; margin: 20px 0; color: var(--text-colour); font-size: 14px; opacity: 0.8; }
        .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: var(--border-colour); }
        .divider span { margin: 0 10px; white-space: nowrap; }
        .darkmode .divider::before, .darkmode .divider::after { background: var(--background-colour); }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="form-box">
        <form method="post" action="">
            <div class="signupWine">
                <img src="../../images/icon.png" alt="Wine Exchange Logo">
            </div>
            <h2>Login</h2>

            <div class="input">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter your email" autocomplete="email" required>
            </div>
            <div class="input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
            </div>

            <button type="submit" name="login">Log In</button>

            <div id="g_id_onload"
                 data-client_id="966067449001-4ajt4ll22p3p2kefig7e2rj4ih7oipml.apps.googleusercontent.com"
                 data-callback="handleGoogleLogin">
            </div>
            <div class="divider"><span>or continue with</span></div>
            <div class="g_id_signin"
                 data-type="standard" data-shape="rectangular"
                 data-theme="outline" data-text="signin_with"
                 data-size="large" data-logo_alignment="left">
            </div>

            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            <p><a href="forgotPassword.php">Forgot your password?</a></p>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    function handleGoogleLogin(response) {
        fetch("google-login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ credential: response.credential })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) window.location = data.redirect || "account.php";
            else alert(data.message || "Google login failed");
        })
        .catch(() => alert("An error occurred during Google sign in."));
    }
</script>
</body>
</html>