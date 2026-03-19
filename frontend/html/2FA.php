<?php
session_start();
require_once('../../database/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: var(--background-colour); }
        .container {
            margin: 0 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 40px 0;
        }
        .form-box {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: var(--frame-colour);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 { font-size: 34px; text-align: center; margin-bottom: 20px; color: var(--text-colour); }
        p { font-size: 14.5px; text-align: center; margin-bottom: 20px; color: var(--text-colour); }
        p a { color: var(--primary-colour); text-decoration: none; }
        p a:hover { text-decoration: underline; }
        .digits {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .digits input {
            width: 52px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            background: var(--background-colour);
            border: 1px solid var(--border-colour);
            border-radius: 6px;
            outline: none;
            color: var(--text-colour);
            margin-bottom: 0;
            transition: border-color 0.2s ease;
        }
        .digits input:focus {
            border-color: var(--primary-colour);
        }
        button[type="submit"] {
            width: 50%;
            padding: 12px 40px;
            background: var(--primary-colour);
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            margin: 0 auto 20px auto;
            transition: 0.5s;
            display: block;
        }
        button[type="submit"]:hover { filter: brightness(0.8); }
        .back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--primary-colour);
            text-decoration: none;
            font-size: 14.5px;
            margin-bottom: 20px;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-box">
        <a href="log-in.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Login</a>

        <form method="POST" action="verify-2fa.php">
            <h1>Verify Login</h1>
            <p>Enter the 6-digit code sent to your email</p>

            <div class="digits">
                <input type="text" name="digit1" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" name="digit2" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" name="digit3" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" name="digit4" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" name="digit5" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" name="digit6" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
            </div>

            <button type="submit">Confirm</button>
            <p>Didn't receive the code? <a href="resend-2fa.php" class="resend-link">Resend Code</a></p>
            <p id="resent-msg" style="display:none; color:green;">Code resent! <a href="#" class="resend-again">Send again?</a></p>
        </form>
    </div>
</div>

<script>
    if (localStorage.getItem("dark_mode") === "on") {
        document.documentElement.classList.add("darkmode");
    }

    const digits = document.querySelectorAll('.digits input');
    digits.forEach((input, i) => {
        input.addEventListener('input', () => {
            if (input.value && i < digits.length - 1) digits[i + 1].focus();
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && i > 0) digits[i - 1].focus();
        });
    });
</script>

<script>
    document.querySelector('.resend-link').addEventListener('click', function(e) {
        e.preventDefault();
        this.closest('p').style.display = 'none';
        document.getElementById('resent-msg').style.display = 'block';
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('resend-again')) {
            e.preventDefault();
            // trigger resend again
            window.location.href = 'resend-2fa.php';
        }
    });
</script>

</body>
</html>