<?php
session_start();

// ── Guard ─────────────────────────────────────────────────────────────────
if (!isset($_SESSION['2fa_user_id'])) {
    header('Location: log-in.php');
    exit;
}

// ── Rate limit: once every 60 seconds ────────────────────────────────────
$last = $_SESSION['2fa_last_sent'] ?? 0;
if ((time() - $last) < 60) {
    $wait = 60 - (time() - $last);
    $_SESSION['2fa_error'] = "Please wait {$wait} second(s) before requesting another code.";
    header('Location: 2FA.php');
    exit;
}

// ── Generate new code ─────────────────────────────────────────────────────
$code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

$_SESSION['2fa_code']      = $code;
$_SESSION['2fa_expires']   = time() + 600;
$_SESSION['2fa_attempts']  = 0;
$_SESSION['2fa_last_sent'] = time();
$_SESSION['csrf_token']    = bin2hex(random_bytes(32));

$email = $_SESSION['2fa_email'] ?? '';
$name  = $_SESSION['2fa_name']  ?? 'User';

// ── Send via same Gmail SMTP as forgotPassword.php ────────────────────────
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
    $mail->addAddress($email, $name);
    $mail->isHTML(true);
    $mail->Subject = 'Your New Wine Exchange Verification Code';
    $mail->Body    = "
    <div style='font-family:Georgia,serif;background:#FDFAF5;padding:40px 20px;'>
      <div style='max-width:480px;margin:0 auto;background:#fff;border:1px solid #E8D5A3;
                  border-top:3px solid #6B1E30;border-radius:4px;padding:36px 40px;text-align:center;'>
        <h2 style='color:#2A1018;font-size:1.5rem;margin-bottom:8px;'>Your New Code</h2>
        <p style='color:#7A4A55;margin-bottom:24px;'>
          Hi {$name}, here is your new verification code.<br/>
          It expires in <strong style='color:#6B1E30;'>10 minutes</strong>.
        </p>
        <div style='background:#F5EDD8;border:1.5px solid #C9A84C;border-radius:4px;padding:20px;
                    letter-spacing:0.35em;font-size:2.2rem;font-family:Courier New,monospace;
                    color:#2A1018;font-weight:700;margin-bottom:24px;'>{$code}</div>
        <p style='font-size:0.78rem;color:#7A4A55;'>If you didn't request this, ignore this email.</p>
      </div>
    </div>";
    $mail->AltBody = "Hi {$name},\n\nYour new verification code is: {$code}\n\nIt expires in 10 minutes.";

    $mail->send();
    $_SESSION['2fa_success'] = 'A new code has been sent to your email address.';

} catch (Exception $e) {
    error_log('Resend 2FA mail error: ' . $mail->ErrorInfo);
    $_SESSION['2fa_error'] = 'Failed to send code. Please try again.';
}

header('Location: 2FA.php');
exit;