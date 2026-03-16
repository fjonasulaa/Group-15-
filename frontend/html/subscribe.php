<?php
// subscribe.php
// Handles newsletter sign-up: duplicate check, DB insert, confirmation email

declare(strict_types=1);

// ── CONFIG ──────────────────────────────────────────────────────────────────
$db_host = 'localhost';
$db_name = 'winedb';
$db_user = 'YOUR_DB_USER';       // ← change
$db_pass = 'YOUR_DB_PASSWORD';   // ← change

$from_email  = 'no-reply@wineexchange.com';   // ← change to your sending address
$from_name   = 'Wine Exchange';
$site_url    = 'https://wineexchange.com';    // ← change to your domain (no trailing slash)
// ────────────────────────────────────────────────────────────────────────────

header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Validate email
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Check for existing subscriber
$stmt = $pdo->prepare('SELECT id, confirmed FROM newsletter_subscribers WHERE email = ?');
$stmt->execute([$email]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    // Already subscribed
    echo json_encode([
        'success'   => false,
        'duplicate' => true,
        'message'   => 'This email address is already subscribed to our newsletter.'
    ]);
    exit;
}

// Generate a confirmation token
$token = bin2hex(openssl_random_pseudo_bytes(32));

// Insert new subscriber
$stmt = $pdo->prepare(
    'INSERT INTO newsletter_subscribers (email, confirmed, confirm_token) VALUES (?, 0, ?)'
);
$stmt->execute([$email, $token]);

// Send confirmation email
$confirm_link = $site_url . '/confirm-newsletter.php?token=' . urlencode($token);

$subject = 'Please confirm your Wine Exchange newsletter subscription';
$body    = <<<EOT
Hello,

Thank you for subscribing to the Wine Exchange newsletter!

Please click the link below to confirm your subscription:
$confirm_link

If you did not subscribe, you can safely ignore this email.

Warm regards,
The Wine Exchange Team
EOT;

$headers  = "From: $from_name <$from_email>\r\n";
$headers .= "Reply-To: $from_email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = mail($email, $subject, $body, $headers);

if ($sent) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for subscribing! Please check your inbox to confirm your subscription.'
    ]);
} else {
    // Subscriber was saved even if mail fails — log the issue
    error_log("Newsletter confirmation email failed to send to: $email");
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for subscribing! (Note: confirmation email could not be sent — please contact us.)'
    ]);
}