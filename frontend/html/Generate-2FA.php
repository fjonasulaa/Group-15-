<?php
/**
 * generate-2fa-code.php
 * Reusable helper — call this from your login.php after password validation
 * to generate a code, store it in the session, and send the email.
 *
 * Usage:
 *   require_once 'generate-2fa-code.php';
 *   send_2fa_code($user_id, $user_email, $user_name);
 *   header('Location: 2FA.php');
 *   exit;
 */

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

/**
 * Generates a 6-digit OTP, saves it to the session, and emails it.
 *
 * @param int    $user_id    The authenticated user's ID.
 * @param string $email      The user's email address.
 * @param string $name       The user's display name (for email greeting).
 * @return bool              True on success, false if the email failed to send.
 */
function send_2fa_code(int $user_id, string $email, string $name): bool
{
    // Generate code
    $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Store in session — NOT yet fully authenticated
    $_SESSION['2fa_code']      = $code;
    $_SESSION['2fa_expires']   = time() + 600; // 10 minutes
    $_SESSION['2fa_user_id']   = $user_id;
    $_SESSION['2fa_attempts']  = 0;
    $_SESSION['2fa_last_sent'] = time();
    $_SESSION['csrf_token']    = bin2hex(random_bytes(32));

    // Unset full auth in case of re-authentication flow
    unset($_SESSION['user_id'], $_SESSION['authenticated']);

    // Send email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.yourdomain.com';    // ← your SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@yourdomain.com'; // ← SMTP username
        $mail->Password   = 'your_smtp_password';     // ← SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('noreply@yourdomain.com', 'Your App Name');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Your verification code';
        $mail->Body    = build_2fa_email_html($name, $code);
        $mail->AltBody = "Hi {$name},\n\nYour code is: {$code}\n\nExpires in 10 minutes.";

        $mail->send();
        return true;

    } catch (MailException $e) {
        error_log('PHPMailer error [send_2fa_code]: ' . $mail->ErrorInfo);
        return false;
    }
}

function build_2fa_email_html(string $name, string $code): string
{
    $safe_name = htmlspecialchars($name);
    $safe_code = htmlspecialchars($code);

    return <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head><meta charset="UTF-8"/></head>
    <body style="margin:0;padding:0;background:#FDFAF5;font-family:'Georgia',serif;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#FDFAF5;padding:40px 20px;">
        <tr><td align="center">
          <table width="480" cellpadding="0" cellspacing="0"
                 style="background:#FFF;border:1px solid #E8D5A3;border-radius:4px;
                        border-top:3px solid #6B1E30;box-shadow:0 4px 24px rgba(107,30,48,0.08);">
            <tr>
              <td align="center" style="padding:36px 40px 20px;">
                <h1 style="margin:0;font-size:1.5rem;color:#2A1018;">Verify Your Identity</h1>
              </td>
            </tr>
            <tr>
              <td style="padding:0 40px 28px;color:#7A4A55;font-size:0.9rem;line-height:1.7;text-align:center;">
                <p style="margin:0 0 20px;">Hi {$safe_name}, use the code below to sign in. It expires in <strong style="color:#6B1E30;">10 minutes</strong>.</p>
                <div style="background:#F5EDD8;border:1.5px solid #C9A84C;border-radius:4px;padding:20px;
                            letter-spacing:0.35em;font-size:2.2rem;font-family:'Courier New',monospace;
                            color:#2A1018;font-weight:700;margin-bottom:24px;">
                  {$safe_code}
                </div>
                <p style="margin:0;font-size:0.78rem;">If you didn't request this, ignore this email.</p>
              </td>
            </tr>
            <tr>
              <td style="padding:16px 40px 28px;border-top:1px solid #E8D5A3;text-align:center;
                         font-size:0.72rem;color:#B09090;">
                © Your App Name &nbsp;|&nbsp; Automated message — do not reply.
              </td>
            </tr>
          </table>
        </td></tr>
      </table>
    </body>
    </html>
    HTML;
}