<?php

include '..\..\database\db_connect.php';
include 'users.php';

$token = $_GET['token'] ?? '';
$u = new Users();
$user = $u->getUserByResetToken($token);

if (!$user) {
    die("Invalid or expired reset link.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'] ?? '';
    if ($newPassword) {
        $u->updatePassword($user['customerId'], $newPassword);
        echo "Password updated successfully! <a href='log-in.php'>Log in</a>";
        exit;
    }
}
?>

<h2>Reset Password</h2>
<form method="post">
    <label>New Password</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Reset Password</button>
</form>