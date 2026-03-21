<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

require __DIR__ . "/../../vendor/phpmailer/phpmailer/src/Exception.php";
require __DIR__ . "/../../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require __DIR__ . "/../../vendor/phpmailer/phpmailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$db_host = 'localhost';
$db_name = 'cs2team15_db2';
$db_user = 'cs2team15';
$db_pass = 'yaBl9oDtzOh60RmdXZG64OB7v';

$site_name  = 'Wine Exchange';
$from_email = 'fjonasula28@gmail.com';

header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Validate email
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Connect to DB
try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Check for existing subscriber
$stmt = $pdo->prepare('SELECT id FROM newsletter_subscribers WHERE email = ?');
$stmt->execute([$email]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    ob_clean();
    echo json_encode([
        'success'   => false,
        'duplicate' => true,
        'message'   => 'This email address is already subscribed to our newsletter.'
    ]);
    exit;
}

// Insert as confirmed immediately
$stmt = $pdo->prepare(
    'INSERT INTO newsletter_subscribers (email, confirmed, confirm_token) VALUES (?, 1, NULL)'
);
$stmt->execute([$email]);

// Send welcome email
sendWelcomeEmail($email, $from_email, $site_name);

ob_clean();
echo json_encode([
    'success' => true,
    'message' => 'Thank you for subscribing to our newsletter!'
]);

function sendWelcomeEmail(string $email, string $fromEmail, string $siteName): void
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'fjonasula28@gmail.com';
        $mail->Password   = 'fauw hphl bbxh vacb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($fromEmail, $siteName);
        $mail->addAddress($email);
        $mail->Subject = "Welcome to the $siteName Newsletter!";
        $mail->isHTML(true);
        $mail->Body =
            '<div style="font-family:sans-serif;max-width:520px;margin:auto;color:#333">'
          . '<div style="background:linear-gradient(135deg,#4a0e24,#9e2d4f);padding:32px;text-align:center;border-radius:12px 12px 0 0">'
          . '<h1 style="color:#fff;margin:0;font-size:24px">Wine Exchange</h1>'
          . '</div>'
          . '<div style="background:#fff;padding:36px;border:1px solid #ece8e9;border-top:none;border-radius:0 0 12px 12px">'
          . '<h2 style="color:#2a0a14;margin-top:0">Thank you for subscribing!</h2>'
          . '<p style="color:#555;line-height:1.6">Welcome to the <strong>Wine Exchange</strong> newsletter. We\'re glad to have you with us!</p>'
          . '<p style="color:#555;line-height:1.6">You\'ll be the first to hear about our latest wines, exclusive offers, and upcoming events.</p>'
          . '<p style="text-align:center;margin:32px 0">'
          . '<a href="https://cs2team15.cs2410-web01pvm.aston.ac.uk/frontend/html/index.php" style="display:inline-block;padding:14px 32px;background:#7b1e3a;color:#fff;text-decoration:none;border-radius:10px;font-weight:bold;font-size:15px">Visit Wine Exchange</a>'
          . '</p>'
          . '<hr style="border:none;border-top:1px solid #ece8e9;margin:28px 0">'
          . '<p style="font-size:12px;color:#aaa;margin:0">If you didn\'t sign up for this newsletter, you can safely ignore this email.</p>'
          . '</div></div>';
        $mail->AltBody =
            "Thank you for subscribing to the Wine Exchange newsletter!\n\n"
          . "You'll be the first to hear about our latest wines, exclusive offers, and upcoming events.\n\n"
          . "Visit us at: https://cs2team15.cs2410-web01pvm.aston.ac.uk/frontend/html/index.php";
        $mail->send();
    } catch (Exception $e) {
        error_log('Mailer error: ' . $mail->ErrorInfo);
    }
}