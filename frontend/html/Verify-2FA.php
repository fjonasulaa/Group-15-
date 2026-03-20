<?php
session_start();
require_once('../../database/db_connect.php');

// ── Guard ─────────────────────────────────────────────────────────────────
if (!isset($_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['2fa_user_id'])) {
    header('Location: log-in.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: 2FA.php');
    exit;
}

function fail(string $msg): void {
    $_SESSION['2fa_error'] = $msg;
    header('Location: 2FA.php');
    exit;
}

// ── CSRF ──────────────────────────────────────────────────────────────────
$submitted_token = $_POST['csrf_token'] ?? '';
$session_token   = $_SESSION['csrf_token'] ?? '';

if (!$session_token || !hash_equals($session_token, $submitted_token)) {
    fail('Invalid request. Please try again.');
}

// ── Rate limit: max 5 attempts ────────────────────────────────────────────
$_SESSION['2fa_attempts'] = ($_SESSION['2fa_attempts'] ?? 0) + 1;

if ($_SESSION['2fa_attempts'] > 5) {
    unset(
        $_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['2fa_user_id'],
        $_SESSION['2fa_attempts'], $_SESSION['csrf_token'], $_SESSION['2fa_redirect'],
        $_SESSION['2fa_email'], $_SESSION['2fa_name'], $_SESSION['2fa_last_sent']
    );
    $_SESSION['login_error'] = 'Too many failed attempts. Please log in again.';
    header('Location: log-in.php');
    exit;
}

// ── Expiry ────────────────────────────────────────────────────────────────
if (time() > $_SESSION['2fa_expires']) {
    unset($_SESSION['2fa_code'], $_SESSION['2fa_expires']);
    fail('Your code has expired. Please request a new one.');
}

// ── Validate code ─────────────────────────────────────────────────────────
$submitted = preg_replace('/\D/', '', $_POST['code'] ?? '');
$stored    = (string)$_SESSION['2fa_code'];

if (!hash_equals($stored, $submitted)) {
    $left = 5 - $_SESSION['2fa_attempts'];
    fail($left > 0 ? "Invalid code. {$left} attempt(s) remaining." : 'Invalid code.');
}

// ── Code is correct — check age before granting access ───────────────────
$user_id  = $_SESSION['2fa_user_id'];
$redirect = $_SESSION['2fa_redirect'] ?? 'account.php';

$result = $conn->query("SELECT dateOfBirth FROM customer WHERE customerID = $user_id");
$row    = $result ? $result->fetch_assoc() : null;

if ($row && !empty($row['dateOfBirth'])) {
    $dob = new DateTime($row['dateOfBirth']);
    $age = (new DateTime())->diff($dob)->y;

    if ($age < 18) {
        // Clean up session completely — do not log them in
        unset(
            $_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['2fa_user_id'],
            $_SESSION['2fa_attempts'], $_SESSION['csrf_token'], $_SESSION['2fa_redirect'],
            $_SESSION['2fa_email'], $_SESSION['2fa_name'], $_SESSION['2fa_last_sent']
        );
        // Send to a blocked page or back to login with a message
        $_SESSION['login_error'] = 'You must be 18 or older to access this site.';
        header('Location: log-in.php');
        exit;
    }
}

// ── All checks passed — log the user in ──────────────────────────────────
unset(
    $_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['2fa_user_id'],
    $_SESSION['2fa_attempts'], $_SESSION['csrf_token'], $_SESSION['2fa_redirect'],
    $_SESSION['2fa_email'], $_SESSION['2fa_name'], $_SESSION['2fa_last_sent']
);

session_regenerate_id(true);

$_SESSION['customerID']    = $user_id;
$_SESSION['authenticated'] = true;
$_SESSION['auth_time']     = time();

header('Location: ' . $redirect);
exit;