<?php
session_start();
require_once('../../database/db_connect.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['credential'])) {
    echo json_encode(["success" => false, "message" => "No credential received"]);
    exit;
}

$idToken   = $data['credential'];
$CLIENT_ID = "966067449001-4ajt4ll22p3p2kefig7e2rj4ih7oipml.apps.googleusercontent.com";

// ── Verify token with Google ──────────────────────────────────────────────
$response = file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($idToken));

if (!$response) {
    echo json_encode(["success" => false, "message" => "Could not reach Google to verify token"]);
    exit;
}

$payload = json_decode($response, true);

if (!isset($payload['email']) || $payload['aud'] !== $CLIENT_ID) {
    echo json_encode(["success" => false, "message" => "Invalid token"]);
    exit;
}

$email     = $conn->real_escape_string($payload['email']);
$firstName = $conn->real_escape_string($payload['given_name']  ?? 'Google');
$surname   = $conn->real_escape_string($payload['family_name'] ?? 'User');

// ── Check if user exists ──────────────────────────────────────────────────
$result = $conn->query("SELECT customerID, role, dateOfBirth FROM customer WHERE email = '$email'");

if ($result && $result->num_rows > 0) {
    // ── Existing user ─────────────────────────────────────────────────────
    $row        = $result->fetch_assoc();
    $customerId = (int)$row['customerID'];

    // Age verification — must be 18+
    if (!empty($row['dateOfBirth'])) {
        $dob     = new DateTime($row['dateOfBirth']);
        $now     = new DateTime();
        $age     = $now->diff($dob)->y;

        if ($age < 18) {
            echo json_encode([
                "success" => false,
                "message" => "You must be 18 or older to access this site."
            ]);
            exit;
        }
    }

    // Route through 2FA — do NOT log in yet
    $redirect = ($row['role'] === 'admin') ? 'admin.php' : 'account.php';

    // Send 2FA code via PHPMailer (same setup as forgotPassword.php)
    require '..\..\vendor\autoload.php';

    $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    $_SESSION['2fa_code']      = $code;
    $_SESSION['2fa_expires']   = time() + 600;
    $_SESSION['2fa_user_id']   = $customerId;
    $_SESSION['2fa_email']     = $payload['email'];
    $_SESSION['2fa_name']      = $firstName;
    $_SESSION['2fa_attempts']  = 0;
    $_SESSION['2fa_last_sent'] = time();
    $_SESSION['2fa_redirect']  = $redirect;
    $_SESSION['csrf_token']    = bin2hex(random_bytes(32));

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
        $mail->addAddress($payload['email'], $firstName);
        $mail->isHTML(true);
        $mail->Subject = 'Your Wine Exchange Verification Code';
        $mail->Body    = "
        <div style='font-family:Georgia,serif;background:#FDFAF5;padding:40px 20px;'>
          <div style='max-width:480px;margin:0 auto;background:#fff;border:1px solid #E8D5A3;
                      border-top:3px solid #6B1E30;border-radius:4px;padding:36px 40px;text-align:center;'>
            <h2 style='color:#2A1018;font-size:1.5rem;margin-bottom:8px;'>Verify Your Identity</h2>
            <p style='color:#7A4A55;margin-bottom:24px;'>
              Hi {$firstName}, use the code below to complete your sign-in.<br/>
              It expires in <strong style='color:#6B1E30;'>10 minutes</strong>.
            </p>
            <div style='background:#F5EDD8;border:1.5px solid #C9A84C;border-radius:4px;padding:20px;
                        letter-spacing:0.35em;font-size:2.2rem;font-family:Courier New,monospace;
                        color:#2A1018;font-weight:700;margin-bottom:24px;'>{$code}</div>
            <p style='font-size:0.78rem;color:#7A4A55;'>If you didn't request this, ignore this email.</p>
          </div>
        </div>";
        $mail->AltBody = "Hi {$firstName},\n\nYour verification code is: {$code}\n\nIt expires in 10 minutes.";
        $mail->send();

        echo json_encode(["success" => true, "redirect" => "2FA.php"]);

    } catch (Exception $e) {
        error_log('Google login 2FA mail error: ' . $mail->ErrorInfo);
        // If email fails, still send to 2FA page — dev banner will show code in testing
        echo json_encode(["success" => true, "redirect" => "2FA.php"]);
    }

} else {
    // ── New Google user — redirect to complete profile ────────────────────
    // Age verification happens on complete-profile.php when they enter their DOB
    $_SESSION['google_pending'] = [
        'firstName' => $firstName,
        'surname'   => $surname,
        'email'     => $email
    ];
    echo json_encode(["success" => true, "redirect" => "complete-profile.php"]);
}
exit;
?>