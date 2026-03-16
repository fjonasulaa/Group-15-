<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

$db_host = 'localhost';
$db_name = 'winedb';
$db_user = 'root';
$db_pass = '';

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

// Insert new subscriber (confirmed immediately, no email required)
$stmt = $pdo->prepare(
    'INSERT INTO newsletter_subscribers (email, confirmed, confirm_token) VALUES (?, 1, NULL)'
);
$stmt->execute([$email]);

ob_clean();
echo json_encode([
    'success' => true,
    'message' => 'Thank you for subscribing to our newsletter!'
]);
