<?php
/**
 * resend-2fa.php
 * Generates a fresh 6-digit code and sends it via PHPMailer (SMTP).
 * Called when the user clicks "Resend Code" on 2FA.php.
 */

session_start();
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailException;

// ── Guard: must be in a 2FA flow ──────────────────────────────────────────

if (!isset($_SESSION['2fa_user_id'])) {
    header('Location: login.php');
    exit;
}

// ── Resend rate limit: once every 60 seconds ──────────────────────────────

$last_sent = $_SESSION['2fa_last_sent'] ?? 0;

if ((time() - $last_sent) < 60) {
    $wait = 60 - (time() - $last_sent);
    $_SESSION['2fa_error'] = "Please wait {$wait} second(s) before requesting another code.";
    header('Location: 2FA.php');
    exit;
}

// ── Generate new 6-digit code ─────────────────────────────────────────────

$code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

$_SESSION['2fa_code']      = $code;
$_SESSION['2fa_expires']   = time() + 600; // 10 minutes
$_SESSION['2fa_attempts']  = 0;            // reset attempt counter
$_SESSION['2fa_last_sent'] = time();

// Generate a fresh CSRF token for the new form submission
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// ── Fetch the user's email ────────────────────────────────────────────────
// Replace this block with however you load users from your database.
// Example using PDO:

/*
$pdo  = new PDO('mysql:host=localhost;dbname=yourdb;charset=utf8mb4', 'user', 'pass');
$stmt = $pdo->prepare('SELECT email, first_name FROM users WHERE id = ?');
$stmt->execute([$_SESSION['2fa_user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit;
}

$recipient_email = $user['email'];
$recipient_name  = $user['first_name'] ?? 'User';
*/

// ── PLACEHOLDER — replace with your real user lookup above ───────────────
$recipient_email = 'user@example.com'; // TODO: load from DB
$recipient_name  = 'User';             // TODO: load from DB

// ── Send email via PHPMailer ──────────────────────────────────────────────

$mail = new PHPMailer(true);

try {
    // ── SMTP configuration ------------------------------------------------
    // Fill in your SMTP credentials. For Gmail use:
    //   Host: smtp.gmail.com | Port: 587 | SMTPSecure: STARTTLS
    // For a typical cPanel host use:
    //   Host: mail.yourdomain.com | Port: 587 | SMTPSecure: STARTTLS

    $mail->isSMTP();
    $mail->Host       = 'smtp.yourdomain.com';   // ← your SMTP host
    $mail->SMTPAuth   = true;
    $mail->Username   = 'noreply@yourdomain.com'; // ← SMTP username
    $mail->Password   = 'your_smtp_password';     // ← SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // ── Sender / recipient ------------------------------------------------
    $mail->setFrom('noreply@yourdomain.com', 'Your App Name');
    $mail->addAddress($recipient_email, $recipient_name);

    // ── Content -----------------------------------------------------------
    $mail->isHTML(true);
    $mail->Subject = 'Your verification code';
    $mail->Body    = build_email_html($recipient_name, $code);
    $mail->AltBody = build_email_text($recipient_name, $code);

    $mail->send();

    $_SESSION['2fa_success'] = 'A new code has been sent to your email address.';

} catch (MailException $e) {
    // Log the real error server-side but show a generic message to the user
    error_log('PHPMailer error [resend-2fa]: ' . $mail->ErrorInfo);
    $_SESSION['2fa_error'] = 'Failed to send the code. Please try again shortly.';
}

header('Location: 2FA.php');
exit;

// ── Email template helpers ────────────────────────────────────────────────

function build_email_html(string $name, string $code): string
{
    $safe_name = htmlspecialchars($name);
    $safe_code = htmlspecialchars($code);

    return <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title>Your Verification Code</title>
    </head>
    <body style="margin:0;padding:0;background:#FDFAF5;font-family:'Georgia',serif;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#FDFAF5;padding:40px 20px;">
        <tr>
          <td align="center">
            <table width="480" cellpadding="0" cellspacing="0"
                   style="background:#FFFFFF;border:1px solid #E8D5A3;border-radius:4px;
                          border-top:3px solid #6B1E30;box-shadow:0 4px 24px rgba(107,30,48,0.08);">
              <!-- Header -->
              <tr>
                <td align="center" style="padding:36px 40px 20px;">
                  <div style="width:48px;height:48px;background:linear-gradient(135deg,#6B1E30,#4E1522);
                              border-radius:50%;display:inline-flex;align-items:center;justify-content:center;
                              margin-bottom:16px;">
                    <span style="font-size:22px;">🔐</span>
                  </div>
                  <h1 style="margin:0;font-size:1.5rem;color:#2A1018;font-weight:600;letter-spacing:0.02em;">
                    Verify Your Identity
                  </h1>
                </td>
              </tr>
              <!-- Body -->
              <tr>
                <td style="padding:0 40px 28px;color:#7A4A55;font-size:0.9rem;line-height:1.7;text-align:center;">
                  <p style="margin:0 0 24px;">Hi {$safe_name},</p>
                  <p style="margin:0 0 24px;">
                    Use the code below to complete your sign-in.<br/>
                    It expires in <strong style="color:#6B1E30;">10 minutes</strong>.
                  </p>
                  <!-- Code box -->
                  <div style="background:#F5EDD8;border:1.5px solid #C9A84C;border-radius:4px;
                              padding:20px;letter-spacing:0.35em;font-size:2.2rem;
                              font-family:'Courier New',monospace;color:#2A1018;font-weight:700;
                              margin-bottom:24px;">
                    {$safe_code}
                  </div>
                  <p style="margin:0;font-size:0.78rem;color:#7A4A55;">
                    If you didn't request this, you can safely ignore this email.
                  </p>
                </td>
              </tr>
              <!-- Footer -->
              <tr>
                <td style="padding:16px 40px 28px;border-top:1px solid #E8D5A3;text-align:center;
                           font-size:0.72rem;color:#B09090;letter-spacing:0.03em;">
                  © Your App Name &nbsp;|&nbsp; This is an automated message, please do not reply.
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </body>
    </html>
    HTML;
}

function build_email_text(string $name, string $code): string
{
    return "Hi {$name},\n\n"
         . "Your verification code is: {$code}\n\n"
         . "It expires in 10 minutes.\n\n"
         . "If you did not request this, please ignore this email.";
}