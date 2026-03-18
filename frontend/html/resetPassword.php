<?php
include '..\..\database\db_connect.php';
include 'users.php';

$token = $_GET['token'] ?? '';
$u = new Users();
$user = $u->getUserByResetToken($token);

if (!$user) {
    die("Invalid or expired reset link.");
}

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newPassword = $_POST['password'] ?? '';

    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}$/';

    if (!preg_match($pattern, $newPassword)) {
        echo "<script>alert('Password must be at least 6 characters and contain at least one uppercase letter, one lowercase letter, and one special character.');</script>";
    } else {
        $u->updatePassword($user['customerId'], $newPassword);
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Reset Password</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500&display=swap');

:root {
    --wine-primary: #7b0f1a;
    --wine-dark: #5a0c14;
    --background: #f5f5f5;
    --card-bg: #ffffff;
    --text-dark: #333;
    --text-light: #777;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--background);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.reset-card {
    background: var(--card-bg);
    width: 100%;
    max-width: 420px;
    padding: 50px 40px;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.08);
    text-align: center;
    animation: fadeIn 0.6s ease;
}

.reset-card h1 {
    font-family: 'Playfair Display', serif;
    color: var(--wine-primary);
    font-size: 2rem;
    margin-bottom: 10px;
}

.subtitle {
    font-size: 0.9rem;
    color: var(--text-light);
    margin-bottom: 30px;
}

.input-group {
    text-align: left;
    margin-bottom: 25px;
}

.input-group label {
    font-weight: 500;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 6px;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 0.95rem;
    transition: 0.3s ease;
}

.input-group input:focus {
    border-color: var(--wine-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(123, 15, 26, 0.1);
}

.reset-btn {
    width: 100%;
    padding: 12px;
    border-radius: 30px;
    border: none;
    background: var(--wine-primary);
    color: white;
    font-weight: 500;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.reset-btn:hover {
    background: var(--wine-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.back-login {
    margin-top: 20px;
}

.back-login a {
    text-decoration: none;
    font-size: 0.85rem;
    color: var(--wine-primary);
    font-weight: 500;
}

.back-login a:hover {
    text-decoration: underline;
}

.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 30px 40px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    animation: fadeIn 0.4s ease;
}

.modal-content h2 {
    color: var(--wine-primary);
    font-family: 'Playfair Display', serif;
    margin-bottom: 10px;
}

.modal-content p {
    margin-bottom: 20px;
    color: var(--text-light);
}

.modal-content button {
    padding: 10px 25px;
    border-radius: 25px;
    border: none;
    background: var(--wine-primary);
    color: white;
    cursor: pointer;
    font-weight: 500;
}

.modal-content button:hover {
    background: var(--wine-dark);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

</head>
<body>

<div class="reset-card">
    <h1>Reset Your Password</h1>
    <p class="subtitle">Enter your new password below.</p>

    <form method="post">
        <div class="input-group">
            <label>New Password</label>
            <input type="password" name="password"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}"
                title="Must contain at least one uppercase letter, one lowercase letter, one special character, and be at least 6 characters long."
            required>
        </div>

        <button type="submit" class="reset-btn">Update Password</button>
    </form>

    <p class="back-login">
        <a href="log-in.php">← Back to Login</a>
    </p>
</div>

<?php if ($success): ?>
<div class="modal">
    <div class="modal-content">
        <h2>Password Updated!</h2>
        <p>Your password has been successfully changed.</p>
        <button onclick="window.location.href='log-in.php'">Continue to Login</button>
    </div>
</div>
<?php endif; ?>

</body>
</html>