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

// ── Code correct — log the user in ───────────────────────────────────────
$user_id  = $_SESSION['2fa_user_id'];
$redirect = $_SESSION['2fa_redirect'] ?? 'account.php';

unset(
    $_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['2fa_user_id'],
    $_SESSION['2fa_attempts'], $_SESSION['csrf_token'], $_SESSION['2fa_redirect'],
    $_SESSION['2fa_email'], $_SESSION['2fa_name'], $_SESSION['2fa_last_sent']
);

session_regenerate_id(true);

$_SESSION['customerID']    = $user_id;
$_SESSION['authenticated'] = true;
$_SESSION['auth_time']     = time();

// ── Check if profile is complete ──────────────────────────────────────────
// Always require a real DOB + address — even if they filled it in before,
// we re-verify so fake/missing data can be corrected.
$result = $conn->query("SELECT dateOfBirth, addressLine, postcode FROM customer WHERE customerID = $user_id");
$row    = $result ? $result->fetch_assoc() : null;

$dob_missing     = empty($row['dateOfBirth']);
$address_missing = empty($row['addressLine']) || empty($row['postcode']);

if ($dob_missing || $address_missing) {
    // Mark session so complete-profile.php knows this is a returning user
    $_SESSION['needs_profile_completion'] = true;
    header('Location: complete-profile.php');
    exit;
}

// ── Profile complete — check age ──────────────────────────────────────────
$dob = new DateTime($row['dateOfBirth']);
$age = (new DateTime())->diff($dob)->y;

if ($age < 18) {
    // Destroy session and block access
    session_destroy();
    session_start();
    $_SESSION['login_error'] = 'You must be 18 or older to access this site.';
    header('Location: log-in.php');
    exit;
}

// ── All good ──────────────────────────────────────────────────────────────
header('Location: ' . $redirect);
exit;