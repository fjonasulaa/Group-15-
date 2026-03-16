<?php
// confirm-newsletter.php

declare(strict_types=1);

$db_host = 'localhost';
$db_name = 'winedb';
$db_user = 'YOUR_DB_USER';     // ← change
$db_pass = 'YOUR_DB_PASSWORD'; // ← change

$token = isset($_GET['token']) ? trim($_GET['token']) : '';

$message = '';
$success = false;

if ($token === '') {
    $message = 'Invalid confirmation link.';
} else {
    try {
        $pdo = new PDO(
            "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
            $db_user,
            $db_pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $stmt = $pdo->prepare(
            'SELECT id, confirmed FROM newsletter_subscribers WHERE confirm_token = ?'
        );
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $message = 'This confirmation link is invalid or has already been used.';
        } elseif ($row['confirmed']) {
            $message = 'Your subscription is already confirmed. Welcome!';
            $success = true;
        } else {
            $pdo->prepare(
                'UPDATE newsletter_subscribers SET confirmed = 1, confirm_token = NULL WHERE id = ?'
            )->execute([$row['id']]);
            $message = 'Your subscription has been confirmed. Welcome to the Wine Exchange newsletter!';
            $success = true;
        }
    } catch (PDOException $e) {
        $message = 'Something went wrong. Please try again later.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Newsletter Confirmation | Wine Exchange</title>
</head>
<body>
  <h2><?= $success ? '🎉 Confirmed!' : 'Oops' ?></h2>
  <p><?= htmlspecialchars($message) ?></p>
  <a href="index.php">← Back to Wine Exchange</a>
</body>
</html>