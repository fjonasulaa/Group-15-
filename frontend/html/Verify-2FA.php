<?php
/**
 * verify-2fa.php
 * Validates the submitted 6-digit OTP, then redirects to the
 * correct destination (admin.php or account.php) stored in session.
 */

session_start();

// ── Guard: must be in a 2FA flow ──────────────────────────────────────────

if (!isset($_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['2fa_user_id'])) {
    header('Location: login.php');
    exit;
}

// ── Only accept POST ──────────────────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: 2FA.php');
    exit;
}

// ── Helper ────────────────────────────────────────────────────────────────

function redirect_with_error(string $msg): void {
    $_SESSION['2fa_error'] = $msg;
    header('Location: 2FA.php');
    exit;
}

// ── CSRF check ────────────────────────────────────────────────────────────

$submitted_token = $_POST['csrf_token'] ?? '';
$session_token   = $_SESSION['csrf_token'] ?? '';

if (!$session_token || !hash_equals($session_token, $submitted_token)) {
    redirect_with_error('Invalid request. Please try again.');
}

// ── Rate limiting: max 5 attempts ─────────────────────────────────────────

$_SESSION['2fa_attempts'] = ($_SESSION['2fa_attempts'] ?? 0) + 1;

if ($_SESSION['2fa_attempts'] > 5) {
    unset(
        $_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['2fa_user_id'],
        $_SESSION['2fa_attempts'], $_SESSION['csrf_token'], $_SESSION['2fa_redirect']
    );
    $_SESSION['login_error'] = 'Too many failed attempts. Please log in again.';
    header('Location: login.php');
    exit;
}

// ── Check expiry ──────────────────────────────────────────────────────────

if (time() > $_SESSION['2fa_expires']) {
    unset($_SESSION['2fa_code'], $_SESSION['2fa_expires']);
    redirect_with_error('Your code has expired. Please request a new one.');
}

// ── Validate code ─────────────────────────────────────────────────────────

$submitted_code = preg_replace('/\D/', '', $_POST['code'] ?? '');
$stored_code    = $_SESSION['2fa_code'];

if (!hash_equals((string)$stored_code, (string)$submitted_code)) {
    $remaining = 5 - $_SESSION['2fa_attempts'];
    redirect_with_error(
        $remaining > 0
            ? "Invalid code. You have {$remaining} attempt(s) remaining."
            : 'Invalid code.'
    );
}

// ── Success — promote user to fully authenticated ─────────────────────────

$user_id  = $_SESSION['2fa_user_id'];
$redirect = $_SESSION['2fa_redirect'] ?? 'account.php'; // admin.php or account.php

// Clean up all 2FA session data
unset(
    $_SESSION['2fa_code'],
    $_SESSION['2fa_expires'],
    $_SESSION['2fa_user_id'],
    $_SESSION['2fa_attempts'],
    $_SESSION['csrf_token'],
    $_SESSION['2fa_redirect'],
    $_SESSION['2fa_last_sent']
);

// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);

// Mark user as fully logged in — matches what the rest of your app expects
$_SESSION['customerID']    = $user_id;
$_SESSION['authenticated'] = true;
$_SESSION['auth_time']     = time();

// Store role in session so auth_guard.php can gate admin pages.
require_once('../../database/db_connect.php');
$r = $conn->query("SELECT role, firstName, surname, email FROM customer WHERE customerID = $user_id");
if ($r && $row = $r->fetch_assoc()) {
    $_SESSION['role']      = $row['role'];
    $_SESSION['firstname'] = $row['firstName'];
    $_SESSION['surname']   = $row['surname'];
    $_SESSION['email']     = $row['email'];
}

header('Location: ' . $redirect);
exit;