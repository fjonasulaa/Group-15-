<?php
session_start();

$error = $_SESSION['register_error'] ?? "";
unset($_SESSION["register_error"]);

function showError($errors) {
    return !empty($errors) ? "<p class='error-message'>" . htmlspecialchars($errors) . "</p>" : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Wine Exchange</title>
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
            min-height: calc(100vh - 62px);
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
        h2 { font-size: 20px; text-align: center; margin-bottom: 20px; color: var(--text-colour); }

        .signupWine {
            text-align: center;
            margin-bottom: 10px;
        }

        .signupWine img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .input {
            position: relative;
            margin-bottom: 20px;
        }

        .input i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-colour);
            opacity: 0.6;
            font-size: 14px;
            height: 16px;
            display: flex;
            align-items: center;
        }

        .input input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            background: var(--background-colour);
            border-radius: 6px;
            border: 1px solid var(--border-colour);
            outline: none;
            font-size: 16px;
            color: var(--text-colour);
            margin-bottom: 0;
        }

        .input:focus-within i {
            color: var(--primary-colour);
            opacity: 1;
        }

        button[type="submit"] {
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
        button[type="submit"]:hover { filter: brightness(0.8); }
        p { font-size: 14.5px; text-align: center; margin-bottom: 10px; color: var(--text-colour); }
        p a { color: var(--primary-colour); text-decoration: none; }
        p a:hover { text-decoration: underline; }
        .error-message {
            padding: 12px;
            background: red;
            border-radius: 6px;
            font-size: 16px;
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        .signupWine h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 36px;
            color: var(--text-colour);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: var(--text-colour);
            font-size: 14px;
            opacity: 0.8;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border-colour);
        }

        .divider span {
            margin: 0 10px;
            white-space: nowrap;
        }

        .darkmode .divider::before,
        .darkmode .divider::after {
            background: var(--background-colour);
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="form-box" id="signup-form">
        <form action="sign_up.php" method="post">
            <div class="signupWine">
                <img src="../../images/icon.png" alt="Wine Exchange Logo">
                <h3>Wine Exchange</h3>
            </div>
            <h2>Register Account</h2>
            <?= showError($error); ?>

            <div class="input">
                <i class="fas fa-user"></i>
                <input type="text" name="firstName" placeholder="First Name" autocomplete="given-name" required>
            </div>

            <div class="input">
                <i class="fas fa-user"></i>
                <input type="text" name="surname" placeholder="Surname" autocomplete="family-name" required>
            </div>

            <div class="input">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" autocomplete="email" required>
            </div>

            <div class="input">
                <i class="fas fa-phone"></i>
                <input type="tel" name="pnumber" placeholder="Phone Number" autocomplete="tel">
            </div>

            <div class="input">
                <i class="fas fa-location-dot"></i>
                <input type="text" name="addressline" placeholder="Address Line" autocomplete="street-address">
            </div>

            <div class="input">
                <i class="fas fa-map-pin"></i>
                <input type="text" name="postcode" placeholder="Postcode" autocomplete="postal-code">
            </div>

            <div class="input">
                <i class="fas fa-calendar-days"></i>
                <input type="date" name="dob" autocomplete="bday" required>
            </div>
            <div class="input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password"
                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}"
                    title="Must contain at least one uppercase letter, one lowercase letter, one special character, and be at least 6 characters long."
                    autocomplete="new-password"
                    required>
            </div>

            <button type="submit" name="signup">Sign up</button>
            <p>Already have an account? <a href="log-in.php">Login</a></p>
            <p>Trying to sign up as an admin? <a href="admin_signup.php">Click here</a></p>

            <div id="g_id_onload"
                 data-client_id="966067449001-4ajt4ll22p3p2kefig7e2rj4ih7oipml.apps.googleusercontent.com"
                 data-callback="handleGoogleSignup">
            </div>
            <div class="divider">
                <span>or continue with</span>
            </div>
            <div class="g_id_signin"
                 data-type="standard"
                 data-shape="rectangular"
                 data-theme="outline"
                 data-text="signup_with"
                 data-size="large"
                 data-logo_alignment="left">
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    function handleGoogleSignup(response) {
        fetch("google-login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ credential: response.credential })
        })
        .then(async res => {
            const text = await res.text();
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error("Unexpected response: " + text);
            }
        })
        .then(data => {
            if (data.success) {
                window.location = data.redirect || "account.php";
            } else {
                alert(data.message || "Google sign up failed");
            }
        })
        .catch(error => {
            console.error("Google sign-up error:", error);
            alert(error.message || "An error occurred during Google sign up.");
        });
    }
</script>

</body>
</html>