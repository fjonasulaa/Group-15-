<?php
    session_start();
    //Prevents user from making an empty order by accessing checkout.php directly.
    if (empty($_SESSION['basket'])) {
        header("Location: index.html");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
</head>
<style>
    #checkout-btn {
    margin-top: 2rem;
    text-align: center;
}
</style>

<body>
    <!-- NAVBAR -->
    <div class="navbar">
        <img src="../../images/icon.png" alt="Wine Exchange Logo">
        <div class="navbar-links">
            <a href="index.html">Home</a>
            <a href="about.html">About Us</a>
            <a href="wines.html">Wines</a>
            <a href="basket.php">Basket</a>
            <a href="contact-us.php">Contact Us</a>
            <a href="websiteReviews.html">Reviews</a>
        </div>

        <div class="navbar-right">
            <form method= "POST" action = "search.php">
                <input type="text" name="search" placeholder="Search">

                <input type= "hidden" name= "submitted" value= "true"/>
            </form>
            <a href="log-in.php">Login</a>
            <a href="signup.php">Sign up</a>
            <a href="account.php">Account</a>
            <button id="dark-mode" class="dark-mode-button">
                <img src="../../images/darkmode.png" alt="Dark Mode" />
            </button>
        </div>
    </div>


    <!-- CHECKOUT -->
    <form id="checkoutForm" method="POST" action="redirect.php?page=Checkout" novalidate>
        <div class="checkout-container">
            <h1>Checkout</h1>
            <!--Only show if user isn't logged in.-->
            <?php if (!isset($_SESSION['customerID'])):?>
            <label for="fname">First Name</label>
            <input id="fname" name="fname" type="text" placeholder="John">
            <div id="err-fname" class="error-inline">First name is required.</div>

            <label for="lname">Last Name</label>
            <input id="lname" name="lname" type="text" placeholder="Smith">
            <div id="err-lname" class="error-inline">Last name is required.</div>

            <label for="address">Address</label>
            <input id="address" name ="address" type="text" placeholder="123 Main Street">
            <div id="err-address" class="error-inline">Address is required.</div>

            <div class="row">
                <div class="column">
                    <label for="city">City</label>
                    <input id="city" name="city" type="text" placeholder="London">
                    <div id="err-city" class="error-inline">City is required.</div>
                </div>
                <div class="column">
                    <label for="postcode">Postcode</label>
                    <input id="postcode" name="postcode" type="text" placeholder="SW1A 1AA">
                    <div id="err-postcode" class="error-inline">Enter a valid UK postcode (e.g. SW1A 1AA).</div>
                </div>
            </div>

            <div class="row">
                <div class="column">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" placeholder="email@example.com">
                    <div id="err-email" class="error-inline">Enter a valid email address.</div>
                </div>
                <div class="column">
                    <label for="phone">Phone Number</label>
                    <input id="phone" name="phone" type="text" placeholder="07123 456789">
                    <div id="err-phone" class="error-inline">Enter a valid UK phone number (07... or +447...).</div>
                </div>
            </div>
            <?php endif; ?>

            <label for="shipping">Shipping Method</label>
            <select id="shipping" name="shipping">
                <option value="standard">Standard (3–5 days) — Free</option>
                <option value="nextday">Next day — £4.99</option>
            </select>

            <label for="payment-method">Payment Method</label>
            <select id="payment-method">
                <option value="applepay">Apple Pay</option>
                <option value="card">Credit/Debit card</option>
            </select>

            <!-- Hidden card fields (now with ids) -->
            <div id="card-details">
                <label for="card-name">Card Holder Name</label>
                <input id="card-name" type="text" placeholder="John Smith">
                <div id="err-card-name" class="error-inline">Cardholder name is required.</div>

                <label for="card-number">Card Number</label>
                <input id="card-number" type="text" placeholder="1234 5678 9012 3456" maxlength="23"
                    inputmode="numeric">
                <div id="err-card-number" class="error-inline">Enter a valid Visa or Mastercard number.</div>

                <div class="row">
                    <div class="column">
                        <label for="card-expiry">Expiry Date (MM/YY)</label>
                        <input id="card-expiry" type="text" placeholder="MM/YY" maxlength="5">
                        <div id="err-card-expiry" class="error-inline">Enter a valid expiry date in the future (MM/YY).
                        </div>
                    </div>
                    <div class="column">
                        <label for="card-cvv">Security Code (CVV)</label>
                        <input id="card-cvv" type="text" placeholder="123" maxlength="4" inputmode="numeric">
                        <div id="err-card-cvv" class="error-inline">Enter a 3-digit CVV.</div>
                    </div>
                </div>
            </div>

            <p>Current time: <span id="timestamp"></span></p>

            <?php echo('<input type="submit" value="Confirm">') ?>
        </div>
    </form>

    <script>
        /* ------------------- helpers ------------------- */
        function showInlineError(inputEl, errId) {
            inputEl.classList.add('invalid');
            const err = document.getElementById(errId);
            if (err) err.style.display = 'block';
        }
        function clearInlineError(inputEl, errId) {
            inputEl.classList.remove('invalid');
            const err = document.getElementById(errId);
            if (err) err.style.display = 'none';
        }
        function isValidUKPostcode(p) {
            if (!p) return false;
            const cleaned = p.trim().toUpperCase().replace(/\s+/, ' ');
            const re = /^([A-Z]{1,2}\d{1,2}[A-Z]?)\s*(\d[A-Z]{2})$/i;
            return re.test(cleaned);
        }
        function isValidUKPhone(p) {
            if (!p) return false;
            const cleaned = p.replace(/\s+/g, '');
            const re = /^(?:0?7\d{9}|\+447\d{9}|00447\d{9})$/;
            return re.test(cleaned);
        }
        function isValidEmail(e) {
            if (!e) return false;
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(e);
        }
        function luhnCheck(numStr) {
            const digits = numStr.replace(/\D/g, '');
            let sum = 0, flip = false;
            for (let i = digits.length - 1; i >= 0; i--) {
                let d = parseInt(digits[i], 10);
                if (flip) {
                    d = d * 2;
                    if (d > 9) d -= 9;
                }
                sum += d;
                flip = !flip;
            }
            return (sum % 10) === 0;
        }
        function isVisaOrMastercard(num) {
            const digits = num.replace(/\D/g, '');
            if (!/^\d+$/.test(digits)) return false;
            if (/^4\d{15}$/.test(digits)) return true; // Visa
            if (/^(5[1-5]\d{14}|22[2-9]\d{12}|2[3-6]\d{13}|27[01]\d{12}|2720\d{12})$/.test(digits)) return true; // Mastercard
            return false;
        }
        function isValidExpiry(mmYY) {
            if (!mmYY) return false;
            const m = mmYY.trim();
            const re = /^(0[1-9]|1[0-2])\/?([0-9]{2})$/;
            const match = m.match(re);
            if (!match) return false;
            const month = parseInt(match[1], 10);
            const year = parseInt(match[2], 10) + 2000;
            // expiry is end of the month
            const expiry = new Date(year, month, 1); // first of next month
            const now = new Date();
            // set to start of current month to compare month granularity
            return expiry > now;
        }

        /* ------------------- DOM wiring ------------------- */
        const paymentSelect = document.getElementById('payment-method');
        const cardDetails = document.getElementById('card-details');

        paymentSelect.addEventListener('change', () => {
            if (paymentSelect.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
                // clear card errors
                ['card-name', 'card-number', 'card-expiry', 'card-cvv'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) clearInlineError(el, 'err-' + id.replace('card-', 'card-'));
                });
            }
        });

        /* timestamp */
        function updateTimestamp() {
            const now = new Date();
            const formatted = now.getFullYear() + "-" +
                String(now.getMonth() + 1).padStart(2, '0') + "-" +
                String(now.getDate()).padStart(2, '0') + " " +
                String(now.getHours()).padStart(2, '0') + ":" +
                String(now.getMinutes()).padStart(2, '0') + ":" +
                String(now.getSeconds()).padStart(2, '0');
            document.getElementById("timestamp").textContent = formatted;
        }
        setInterval(updateTimestamp, 1000);
        updateTimestamp();

        /* removes validation error as user types to correct it */
        [['fname', 'err-fname'], ['lname', 'err-lname'], ['address', 'err-address'], ['city', 'err-city'],
        ['postcode', 'err-postcode'], ['email', 'err-email'], ['phone', 'err-phone'],
        ['card-name', 'err-card-name'], ['card-number', 'err-card-number'],
        ['card-expiry', 'err-card-expiry'], ['card-cvv', 'err-card-cvv']].forEach(([id, err]) => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('input', () => clearInlineError(el, err));
        });

        /* ------------------- main validation on Confirm ------------------- */
        document.getElementById('confirmBtn').addEventListener('click', function (e) {
            e.preventDefault();

            // gather fields
            const fullname = document.getElementById('fullname');
            const address = document.getElementById('address');
            const city = document.getElementById('city');
            const postcode = document.getElementById('postcode');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');

            // clear previous
            [fullname, address, city, postcode, email, phone].forEach(el => {
                clearInlineError(el, 'err-' + el.id);
            });

            let valid = true;

            if (!fname.value.trim()) { showInlineError(fname, 'err-fname'); valid = false; }
            if (!lname.value.trim()) { showInlineError(lname, 'err-lname'); valid = false; }
            if (!address.value.trim()) { showInlineError(address, 'err-address'); valid = false; }
            if (!city.value.trim()) { showInlineError(city, 'err-city'); valid = false; }

            if (!isValidUKPostcode(postcode.value)) {
                showInlineError(postcode, 'err-postcode'); valid = false;
            }

            if (!isValidEmail(email.value)) {
                showInlineError(email, 'err-email'); valid = false;
            }

            if (!isValidUKPhone(phone.value)) {
                showInlineError(phone, 'err-phone'); valid = false;
            }

            // payment-specific
            if (paymentSelect.value === 'card') {
                const cardName = document.getElementById('card-name');
                const cardNumber = document.getElementById('card-number');
                const cardExpiry = document.getElementById('card-expiry');
                const cardCVV = document.getElementById('card-cvv');

                // clear card errors
                [cardName, cardNumber, cardExpiry, cardCVV].forEach(el => {
                    clearInlineError(el, 'err-' + el.id.replace(/-/g, '-'));
                });

                if (!cardName.value.trim()) {
                    showInlineError(cardName, 'err-card-name'); valid = false;
                }

                const numStr = cardNumber.value.replace(/\s+/g, '');
                if (!/^\d{15,16}$/.test(numStr) || !luhnCheck(numStr) || !isVisaOrMastercard(numStr)) {
                    showInlineError(cardNumber, 'err-card-number'); valid = false;
                }

                if (!isValidExpiry(cardExpiry.value)) {
                    showInlineError(cardExpiry, 'err-card-expiry'); valid = false;
                }

                const cvvClean = cardCVV.value.replace(/\D/g, '');
                if (!/^\d{3}$/.test(cvvClean)) {
                    showInlineError(cardCVV, 'err-card-cvv'); valid = false;
                }
            }

            if (!valid) {
                // scroll to first error for convenience
                const first = document.querySelector('.invalid');
                if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                alert('Please fix the highlighted fields before confirming.');
                return;
            }

            // All validations passed

            alert('Order confirmed');

        });

        // DARK MODE
        const darkButton = document.getElementById("dark-mode");
        if (localStorage.getItem("dark_mode") === "on") {
            document.documentElement.classList.add("darkmode");
        }

        darkButton.addEventListener("click", () => {
            document.documentElement.classList.toggle("darkmode");
            localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
        });

        //Code to remove checkout button until guest fills in details.
    </script>

</body>

</html>