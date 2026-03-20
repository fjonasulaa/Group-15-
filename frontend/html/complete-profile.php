<?php
session_start();
require_once('../../database/db_connect.php');

// ── Allow access if: new Google signup OR logged-in user with incomplete profile
$is_new_google  = isset($_SESSION['google_pending']);
$is_returning   = isset($_SESSION['customerID'], $_SESSION['needs_profile_completion']);

if (!$is_new_google && !$is_returning) {
    header("Location: log-in.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dob         = $_POST['dob']         ?? '';
    $addressline = trim($_POST['addressline'] ?? '');
    $postcode    = trim($_POST['postcode']    ?? '');
    $pnumber     = trim($_POST['pnumber']     ?? '');

    // ── Age check ─────────────────────────────────────────────────────────
    $birth = new DateTime($dob);
    $today = new DateTime();
    $age   = $today->diff($birth)->y;

    if ($age < 18) {
        $error = "You must be 18 or older to create an account.";
    } elseif (empty($addressline) || empty($postcode)) {
        $error = "Address and postcode are required.";
    } else {

        if ($is_new_google) {
            // ── New Google user — INSERT into DB ──────────────────────────
            $firstName   = $conn->real_escape_string($_SESSION['google_pending']['firstName']);
            $surname     = $conn->real_escape_string($_SESSION['google_pending']['surname']);
            $email       = $conn->real_escape_string($_SESSION['google_pending']['email']);
            $addressline = $conn->real_escape_string($addressline);
            $postcode    = $conn->real_escape_string($postcode);
            $pnumber     = $conn->real_escape_string($pnumber);
            $dob         = $conn->real_escape_string($dob);

            $conn->query("INSERT INTO customer (firstName, surname, email, passwordHash, dateOfBirth, phoneNumber, addressLine, postcode)
                          VALUES ('$firstName', '$surname', '$email', '', '$dob', '$pnumber', '$addressline', '$postcode')");

            if ($conn->error) {
                $error = "Database error: " . $conn->error;
            } else {
                $customerId = $conn->insert_id;
                $_SESSION['customerID']    = $customerId;
                $_SESSION['authenticated'] = true;
                $_SESSION['auth_time']     = time();
                unset($_SESSION['google_pending']);
                header("Location: account.php");
                exit;
            }

        } else {
            // ── Returning user — UPDATE their existing record ─────────────
            $customerId  = (int)$_SESSION['customerID'];
            $addressline = $conn->real_escape_string($addressline);
            $postcode    = $conn->real_escape_string($postcode);
            $pnumber     = $conn->real_escape_string($pnumber);
            $dob         = $conn->real_escape_string($dob);

            $conn->query("UPDATE customer
                          SET dateOfBirth = '$dob',
                              addressLine = '$addressline',
                              postcode    = '$postcode',
                              phoneNumber = '$pnumber'
                          WHERE customerID = $customerId");

            if ($conn->error) {
                $error = "Database error: " . $conn->error;
            } else {
                unset($_SESSION['needs_profile_completion']);
                header("Location: account.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: var(--background-colour); padding-top: 100px; }
        .container {
            margin: 0 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-box {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: var(--frame-colour);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { font-size: 28px; text-align: center; margin-bottom: 10px; }
        .subtitle { font-size: 14px; text-align: center; color: #888; margin-bottom: 20px; }
        input {
            width: 100%;
            padding: 12px;
            background: var(--background-colour);
            border-radius: 6px;
            border: 1px solid var(--border-colour);
            outline: none;
            font-size: 16px;
            color: var(--text-colour);
            margin-bottom: 20px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: var(--primary-colour);
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            margin-bottom: 20px;
            transition: 0.5s;
        }
        button:hover { filter: brightness(0.8); }
        .error-message {
            padding: 12px;
            background: red;
            border-radius: 6px;
            font-size: 16px;
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }
        label { font-size: 13px; margin-bottom: 5px; display: block; color: #888; }
        .footer { background-color: #f4f4f4; padding: 30px 10%; margin-top: 40px; color: #333; }
        .footer-container { display: flex; justify-content: space-between; flex-wrap: wrap; }
        .footer-section { flex: 1 1 250px; margin: 10px; }
        .footer-section h3 { margin-bottom: 10px; }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin: 5px 0; }
        .footer-links a { text-decoration: none; color: inherit; }
        .footer-links a:hover { text-decoration: underline; }
        .footer-button { display: inline-block; margin-top: 10px; padding: 8px 15px; background-color: #4CAF50; color: white; border-radius: 4px; text-decoration: none; }
        .footer-button:hover { opacity: 0.9; }
        .footer-bottom { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #ccc; font-size: 14px; }
        .darkmode .footer { background-color: #1e1e1e; color: #eee; }
        .darkmode .footer-bottom { border-top: 1px solid #555; }
        .darkmode .footer-links a { color: #ddd; }
    </style>
</head>
<body>

<div class="navbar">
    <img src="../../images/icon.png" alt="Wine Exchange Logo">
    <div class="navbar-links">
        <a href="index.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="search.php">Wines</a>
        <a href="basket.php">Basket</a>
        <a href="contact-us.php">Contact Us</a>
    </div>
    <div class="navbar-right">
        <a href="log-in.php">Login</a>
        <a href="signup.php">Sign up</a>
        <a href="account.php">Account</a>
        <button id="dark-mode" class="dark-mode-button">
            <img src="../../images/darkmode.png" alt="Dark Mode" />
        </button>
    </div>
</div>

<div class="container">
    <div class="form-box">
        <h2><?= $is_new_google ? 'Almost there!' : 'Complete Your Profile' ?></h2>
        <p class="subtitle">
            <?= $is_new_google
                ? 'We just need a few more details to complete your account.'
                : 'Please verify your details to continue.' ?>
        </p>

        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Date of Birth (must be 18+)</label>
            <input type="date" name="dob" autocomplete="bday" required>

            <label>Address Line</label>
            <input type="text" name="addressline" placeholder="123 Example Street" autocomplete="street-address" required>

            <label>Postcode</label>
            <input type="text" name="postcode" placeholder="AB1 2CD" autocomplete="postal-code" required>

            <label>Phone Number (optional)</label>
            <input type="tel" name="pnumber" placeholder="+44 7000 000000" autocomplete="tel">

            <button type="submit"><?= $is_new_google ? 'Complete Sign Up' : 'Continue to Account' ?></button>
        </form>
    </div>
</div>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Wine Exchange</h3>
            <p>123 Vineyard Lane<br>London, UK</p>
            <p>Phone: +44 1234 567890</p>
            <p>Email: <a href="mailto:contactwinexchange@gmail.com">contactwinexchange@gmail.com</a></p>
            <p>Open: Mon–Fri, 9am–6pm</p>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul class="footer-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="search.php">Wines</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact-us.php">Contact</a></li>
            </ul>
            <a href="contact-us.php" class="footer-button">Contact Us</a>
        </div>
        <div class="footer-section">
            <h3>Follow Us</h3>
            <ul class="footer-links">
                <li><a href="#">Instagram</a></li>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Twitter</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">© 2024 Wine Exchange. All rights reserved.</div>
</footer>

<script>
    const darkButton = document.getElementById("dark-mode");
    if (localStorage.getItem("dark_mode") === "on") {
        document.documentElement.classList.add("darkmode");
    }
    darkButton.addEventListener("click", () => {
        document.documentElement.classList.toggle("darkmode");
        localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
    });
</script>
</body>
</html>