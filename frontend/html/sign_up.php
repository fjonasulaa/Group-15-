<?php

session_start();
require_once('../../database/db_connect.php');
require_once('generate-2fa-code.php'); // ← ADD THIS

if (isset($_POST['signup'])) {
    $name        = $_POST['firstName'];
    $surname     = $_POST['surname'];
    $email       = $_POST['email'];
    $pnumber     = $_POST['pnumber'];
    $dob         = $_POST['dob'];
    $addressline = $_POST['addressline'];
    $postcode    = $_POST['postcode'];

    // ── Age check ─────────────────────────────────────────────────────────
    $birth = new DateTime($dob);
    $today = new DateTime();
    $age   = $today->diff($birth)->y;

    if ($age < 18) {
        $_SESSION['register_error'] = 'Must be 18 or older to create account';
        header("Location: signup.php");
        exit();
    }

    // ── Password strength ─────────────────────────────────────────────────
    $password = $_POST['password'] ?? '';
    $pattern  = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).+$/';

    if (!preg_match($pattern, $password)) {
        $_SESSION['register_error'] = "Password must contain at least one uppercase letter, one lowercase letter, and one special character.";
        header("Location: signup.php");
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // ── Duplicate email check ─────────────────────────────────────────────
    $checkEmail = $conn->prepare("SELECT email FROM customer WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        header("Location: signup.php");
        exit();
    }

    // ── Insert new customer ───────────────────────────────────────────────
    $stmt = $conn->prepare("INSERT INTO customer 
        (firstName, surname, dateOfBirth, addressLine, postcode, email, phoneNumber, passwordHash) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssss",
        $name, $surname, $dob, $addressline,
        $postcode, $email, $pnumber, $passwordHash
    );
    $stmt->execute();

    $customerID = $conn->insert_id;

    // ── Admin pending role ────────────────────────────────────────────────
    if (isset($_GET['admin']) && $_GET['admin'] === 'true') {
        $conn->query("UPDATE customer SET role = 'adminPending' WHERE customerID = $customerID");
    }

    // ── DO NOT set $_SESSION['customerID'] here ───────────────────────────
    // Route through 2FA exactly like a normal login.
    // After registration all new users go to account.php (never admin).
    $_SESSION['2fa_redirect'] = 'account.php';

    $sent = send_2fa_code($customerID, $email, $name);

    if ($sent) {
        header("Location: 2FA.php");
    } else {
        // Email failed — still create the account but warn the user
        $_SESSION['register_error'] = 'Account created but we could not send your verification code. Please log in.';
        header("Location: log-in.php");
    }
    exit();
}
?>