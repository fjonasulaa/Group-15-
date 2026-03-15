<?php
    session_start();
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
    /* ── PAGE WRAPPER ── */
    .checkout-page {
        max-width: 1200px;
        margin: 110px auto 60px auto;
        padding: 0 30px;
        display: flex;
        gap: 40px;
        align-items: flex-start;
    }

    /* ── LEFT: FORM COLUMN ── */
    .checkout-form-col {
        flex: 1;
        min-width: 0;
    }

    .checkout-form-col h1 {
        font-size: 22px;
        font-weight: 600;
        margin: 0 0 24px;
    }

    .checkout-guest-banner {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f4f4f4;
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 28px;
        font-size: 14px;
        color: #444;
    }

    .checkout-guest-banner a {
        color: #7b1e3a;
        font-weight: 600;
        text-decoration: underline;
    }

    .checkout-section {
        border: 1px solid #e2e2e2;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .checkout-section-header {
        background: #f7f7f7;
        border-bottom: 1px solid #e2e2e2;
        padding: 13px 20px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #333;
    }

    .checkout-section-body {
        padding: 20px;
        background: white;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 16px;
    }

    .form-group:last-child { margin-bottom: 0; }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
        padding: 10px 12px;
        border: 1px solid #d0d0d0;
        border-radius: 7px;
        font-size: 14px;
        color: #111;
        background: white;
        transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #7b1e3a;
    }

    .form-group input.invalid {
        border-color: #e63946;
        background: #fff8f8;
    }

    /* Inline error messages */
    .error-inline {
        display: none;
        color: #e63946;
        font-size: 12px;
        margin-top: 5px;
        font-weight: 500;
    }

    /* ── RIGHT: SUMMARY COLUMN ── */
    .checkout-summary-col {
        width: 340px;
        flex-shrink: 0;
        position: sticky;
        top: 110px;
    }

    .summary-box {
        border: 1px solid #e2e2e2;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 14px;
    }

    .summary-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 18px;
        background: #f7f7f7;
        border-bottom: 1px solid #e2e2e2;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #333;
    }

    .summary-box-header .summary-header-price {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        letter-spacing: 0;
    }

    .summary-items {
        background: white;
        padding: 0 18px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        padding: 11px 0;
        border-bottom: 1px solid #f2f2f2;
        color: #333;
        gap: 10px;
    }

    .summary-item:last-child { border-bottom: none; }
    .summary-item span:first-child { flex: 1; }
    .summary-item span:last-child  { white-space: nowrap; font-weight: 500; }

    .summary-totals {
        background: white;
        padding: 14px 18px;
        border-top: 1px solid #e2e2e2;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
    }

    .summary-row:last-child { margin-bottom: 0; }

    .summary-free {
        font-weight: 600;
        color: #333;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.04em;
    }

    .summary-row.order-total {
        font-size: 15px;
        font-weight: 700;
        color: #111;
        border-top: 1px solid #e2e2e2;
        padding-top: 12px;
        margin-top: 8px;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .confirm-btn {
        display: block;
        width: 100%;
        padding: 15px;
        background: #7b1e3a;
        color: white;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 4px;
        transition: background 0.2s;
    }

    .confirm-btn:hover { background: #5e152c; }

    .summary-accordion {
        border: 1px solid #e2e2e2;
        border-radius: 10px;
        overflow: hidden;
    }

    .accordion-item { border-bottom: 1px solid #e2e2e2; }
    .accordion-item:last-child { border-bottom: none; }

    .accordion-toggle {
        width: 100%;
        background: white;
        border: none;
        padding: 13px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: #333;
        cursor: pointer;
        text-align: left;
    }

    .accordion-toggle:hover { background: #fafafa; }

    .accordion-toggle .chevron {
        font-size: 16px;
        color: #999;
        transition: transform 0.2s;
    }

    .accordion-toggle.open .chevron { transform: rotate(180deg); }

    .accordion-body {
        display: none;
        padding: 12px 18px 14px;
        font-size: 13px;
        color: #555;
        line-height: 1.6;
        background: #fafafa;
        border-top: 1px solid #f0f0f0;
    }

    .accordion-body.open { display: block; }

    .checkout-timestamp {
        font-size: 12px;
        color: #aaa;
        margin: 16px 0 4px;
    }

    /* ── DARK MODE ── */
    html.darkmode .checkout-guest-banner { background: #1e1e1e; color: #ccc; }
    html.darkmode .checkout-section,
    html.darkmode .summary-box,
    html.darkmode .summary-accordion { border-color: #333; }
    html.darkmode .checkout-section-header,
    html.darkmode .summary-box-header { background: #1a1a1a; border-color: #333; color: #ddd; }
    html.darkmode .checkout-section-body,
    html.darkmode .summary-items,
    html.darkmode .summary-totals,
    html.darkmode .accordion-toggle { background: #141414; color: #ddd; }
    html.darkmode .form-group label { color: #bbb; }
    html.darkmode .form-group input,
    html.darkmode .form-group select { background: #1e1e1e; border-color: #444; color: #eee; }
    html.darkmode .form-group input:focus,
    html.darkmode .form-group select:focus { border-color: #c0405e; }
    html.darkmode .form-group input.invalid { background: #2a1010; border-color: #e63946; }
    html.darkmode .summary-item { color: #ccc; border-bottom-color: #222; }
    html.darkmode .summary-box-header .summary-header-price { color: #fff; }
    html.darkmode .summary-row { color: #888; }
    html.darkmode .summary-row.order-total { color: #fff; border-top-color: #333; }
    html.darkmode .summary-totals { border-top-color: #333; }
    html.darkmode .accordion-toggle { color: #ccc; }
    html.darkmode .accordion-toggle:hover { background: #1e1e1e; }
    html.darkmode .accordion-body { background: #111; color: #aaa; border-top-color: #2a2a2a; }
    html.darkmode .accordion-item { border-bottom-color: #2a2a2a; }
    html.darkmode .checkout-form-col h1 { color: #fff; }

    /* ── RESPONSIVE ── */
    @media (max-width: 800px) {
        .checkout-page { flex-direction: column; }
        .checkout-summary-col { width: 100%; position: static; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>

<body>
    <!-- NAVBAR -->
    <div class="navbar">
        <img src="../../images/icon.png" alt="Wine Exchange Logo">
        <div class="navbar-links">
            <a href="index.html">Home</a>
            <a href="about.html">About Us</a>
            <a href="search.php">Wines</a>
            <a href="basket.php">Basket</a>
            <a href="contact-us.php">Contact Us</a>
        </div>
        <div class="navbar-right">
            <form method="POST" action="search.php">
                <input type="text" name="search" placeholder="Search">
                <input type="hidden" name="submitted" value="true"/>
            </form>
            <a href="log-in.php">Login</a>
            <a href="signup.php">Sign up</a>
            <a href="account.php">Account</a>
            <button id="dark-mode" class="dark-mode-button">
                <img src="../../images/darkmode.png" alt="Dark Mode" />
            </button>
        </div>
    </div>

    <div class="checkout-page">

        <!-- LEFT: FORM COLUMN -->
        <div class="checkout-form-col">
            <h1>Checkout</h1>

            <?php if (!isset($_SESSION['customerID'])): ?>
            <div class="checkout-guest-banner">
                <span>&#128100;</span>
                <span>To save time and keep track of your orders, <a href="log-in.php">sign in</a> / <a href="signup.php">register</a></span>
            </div>
            <?php endif; ?>

            <form id="checkoutForm" method="POST" action="redirect.php?page=Checkout" novalidate>

                <?php if (!isset($_SESSION['customerID'])): ?>
                <div class="checkout-section">
                    <div class="checkout-section-header">Delivery Details</div>
                    <div class="checkout-section-body">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fname">First Name *</label>
                                <input id="fname" name="fname" type="text" placeholder="John" autocomplete="given-name">
                                <div id="err-fname" class="error-inline">Please enter your first name.</div>
                            </div>
                            <div class="form-group">
                                <label for="lname">Last Name *</label>
                                <input id="lname" name="lname" type="text" placeholder="Smith" autocomplete="family-name">
                                <div id="err-lname" class="error-inline">Please enter your last name.</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Address *</label>
                            <input id="address" name="address" type="text" placeholder="123 Main Street" autocomplete="street-address">
                            <div id="err-address" class="error-inline">Please enter your address.</div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input id="city" name="city" type="text" placeholder="London" autocomplete="address-level2">
                                <div id="err-city" class="error-inline">Please enter your city.</div>
                            </div>
                            <div class="form-group">
                                <label for="postcode">Postcode *</label>
                                <input id="postcode" name="postcode" type="text" placeholder="SW1A 1AA" autocomplete="postal-code">
                                <div id="err-postcode" class="error-inline">Enter a valid UK postcode (e.g. SW1A 1AA).</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input id="email" name="email" type="email" placeholder="email@example.com" autocomplete="email">
                                <div id="err-email" class="error-inline">Enter a valid email address.</div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input id="phone" name="phone" type="text" placeholder="07123 456789" autocomplete="tel">
                                <div id="err-phone" class="error-inline">Enter a valid UK mobile number (e.g. 07123 456789).</div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php endif; ?>


                <?php if (!isset($_SESSION['customerID'])): ?>
                <!-- AGE VERIFICATION -->
                <div class="checkout-section">
                    <div class="checkout-section-header">Age Verification</div>
                    <div class="checkout-section-body">
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="dob">Date of Birth * <span style="font-weight:400;color:#999;">You must be 18 or over to purchase alcohol.</span></label>
                            <input id="dob" name="dob" type="date" autocomplete="bday">
                            <div id="err-dob" class="error-inline">You must be 18 or over to purchase alcohol.</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- SHIPPING -->
                <div class="checkout-section">
                    <div class="checkout-section-header">Shipping Method</div>
                    <div class="checkout-section-body">
                        <div class="form-group" style="margin-bottom:0;">
                            <select id="shipping" name="shipping">
                                <option value="standard">Standard Delivery (3–5 Working Days) — Free</option>
                                <option value="nextday">Next Day Delivery — £4.99</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- PAYMENT -->
                <div class="checkout-section">
                    <div class="checkout-section-header">Payment Method</div>
                    <div class="checkout-section-body">

                        <div class="form-group">
                            <label for="payment-method">Pay with</label>
                            <select id="payment-method">
                                <option value="applepay">Apple Pay</option>
                                <option value="card">Credit / Debit Card</option>
                            </select>
                        </div>

                        <!-- Card fields: hidden by default since Apple Pay is default option -->
                        <div id="card-details" style="display:none;">

                            <div class="form-group">
                                <label for="card-name">Cardholder Name *</label>
                                <input id="card-name" type="text" placeholder="John Smith" autocomplete="cc-name">
                                <div id="err-card-name" class="error-inline">Please enter the cardholder name.</div>
                            </div>

                            <div class="form-group">
                                <label for="card-number">Card Number * <span style="font-weight:400;color:#999;">(16 digits)</span></label>
                                <input id="card-number" type="text" placeholder="1234 5678 9012 3456" maxlength="19" inputmode="numeric" autocomplete="cc-number">
                                <div id="err-card-number" class="error-inline">Card number must be exactly 16 digits.</div>
                            </div>

                            <div class="form-row">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="card-expiry">Expiry Date * <span style="font-weight:400;color:#999;">(MM/YY)</span></label>
                                    <input id="card-expiry" type="text" placeholder="MM/YY" maxlength="5" inputmode="numeric" autocomplete="cc-exp">
                                    <div id="err-card-expiry" class="error-inline">Enter a valid future expiry date (MM/YY).</div>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="card-cvv">CVV * <span style="font-weight:400;color:#999;">(3 digits on back)</span></label>
                                    <input id="card-cvv" type="text" placeholder="123" maxlength="3" inputmode="numeric" autocomplete="cc-csc">
                                    <div id="err-card-cvv" class="error-inline">Enter the 3-digit CVV from the back of your card.</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <p class="checkout-timestamp">Current time: <span id="timestamp"></span></p>

                <input type="submit" id="confirmBtn" style="display:none;">
            </form>
        </div>

        <!-- RIGHT: SUMMARY COLUMN -->
        <div class="checkout-summary-col">

            <?php
                require_once('../../database/db_connect.php');
                $subtotal   = 0;
                $itemCount  = 0;
                $basketRows = [];
                foreach ($_SESSION['basket'] as $id => $qty) {
                    $safeId    = intval($id);
                    $sql       = "SELECT wineName, price FROM wines WHERE wineId = $safeId";
                    $result    = $conn->query($sql);
                    $row       = $result->fetch_assoc();
                    $lineTotal = $row['price'] * $qty;
                    $subtotal  += $lineTotal;
                    $itemCount += $qty;
                    $basketRows[] = ['name' => $row['wineName'], 'qty' => $qty, 'line' => $lineTotal];
                }
            ?>

            <div class="summary-box">
                <div class="summary-box-header">
                    <span>Basket Total (<?php echo $itemCount; ?> item<?php echo $itemCount !== 1 ? 's' : ''; ?>)</span>
                    <span class="summary-header-price">£<?php echo number_format($subtotal, 2); ?></span>
                </div>

                <div class="summary-items">
                    <?php foreach ($basketRows as $r): ?>
                    <div class="summary-item">
                        <span><?php echo htmlspecialchars($r['name']); ?> &times;<?php echo $r['qty']; ?></span>
                        <span>£<?php echo number_format($r['line'], 2); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-totals">
                    <div class="summary-row">
                        <span>Delivery</span>
                        <span id="summary-delivery" class="summary-free">Free</span>
                    </div>
                    <div class="summary-row order-total">
                        <span>Order Total</span>
                        <span id="summary-total">£<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                </div>
            </div>

            <button class="confirm-btn" onclick="document.getElementById('checkoutForm').requestSubmit();">
                Proceed
            </button>

            <div class="summary-accordion" style="margin-top:14px;">
                <div class="accordion-item">
                    <button class="accordion-toggle" type="button">
                        <span>How We Pack</span><span class="chevron">&#8964;</span>
                    </button>
                    <div class="accordion-body">All wines are packed in protective cardboard dividers inside a double-walled outer box to ensure safe delivery.</div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-toggle" type="button">
                        <span>Returns</span><span class="chevron">&#8964;</span>
                    </button>
                    <div class="accordion-body">If you're not happy with your order, contact us within 14 days and we'll arrange a collection and full refund.</div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-toggle" type="button">
                        <span>Drink Responsibly</span><span class="chevron">&#8964;</span>
                    </button>
                    <div class="accordion-body">Wine Exchange promotes responsible drinking. You must be 18 or over to purchase alcohol. Please drink responsibly.</div>
                </div>
            </div>

        </div>

    </div>

    <script>

        /*   VALIDATION HELPERS */

        function showError(inputEl, errId) {
            inputEl.classList.add('invalid');
            const err = document.getElementById(errId);
            if (err) err.style.display = 'block';
        }

        function clearError(inputEl, errId) {
            inputEl.classList.remove('invalid');
            const err = document.getElementById(errId);
            if (err) err.style.display = 'none';
        }

        // UK postcode: e.g. SW1A 1AA, M1 1AE, B1 1BB
        function isValidUKPostcode(p) {
            return /^([A-Z]{1,2}\d{1,2}[A-Z]?)\s*(\d[A-Z]{2})$/i.test(p.trim());
        }

        // UK mobile: 07... or +447...
        function isValidUKPhone(p) {
            return /^(?:0?7\d{9}|\+447\d{9}|00447\d{9})$/.test(p.replace(/\s+/g, ''));
        }

        // Basic email format
        function isValidEmail(e) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e.trim());
        }
        // Expiry: MM/YY, must be in the future
        function isValidExpiry(val) {
            const match = val.trim().match(/^(0[1-9]|1[0-2])\/?(\d{2})$/);
            if (!match) return false;
            const month = parseInt(match[1], 10);
            const year  = parseInt(match[2], 10) + 2000;
            // Card expires at the END of the expiry month
            return new Date(year, month, 1) > new Date();
        }

        // CVV: exactly 3 digits
        function isValidCVV(val) {
            return /^\d{3}$/.test(val.replace(/\D/g, ''));
        }

        const cardNumberInput = document.getElementById('card-number');
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function () {
                // 1. Strip EVERYTHING that isnt a digit
                const digits = this.value.replace(/\D/g, '').substring(0, 16);
                // 2. Rebuild cleanly: insert a space after every 4th digit
                let formatted = '';
                for (let i = 0; i < digits.length; i++) {
                    if (i > 0 && i % 4 === 0) formatted += ' ';
                    formatted += digits[i];
                }
                this.value = formatted;
            });
        }

        // Expiry: auto-insert slash after MM
        const cardExpiryInput = document.getElementById('card-expiry');
        if (cardExpiryInput) {
            cardExpiryInput.addEventListener('input', function () {
                let val = this.value.replace(/\D/g, '').substring(0, 4);
                if (val.length >= 3) {
                    val = val.substring(0, 2) + '/' + val.substring(2);
                }
                this.value = val;
            });
        }

        /* PAYMENT METHOD TOGGLE */
        const paymentSelect = document.getElementById('payment-method');
        const cardDetails   = document.getElementById('card-details');

        paymentSelect.addEventListener('change', function () {
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
                // Clear any lingering card errors when switching away
                ['card-name','card-number','card-expiry','card-cvv'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) clearError(el, 'err-' + id);
                });
            }
        });

        /* CLEAR ERRORS AS USER TYPES */
        [
            ['fname',       'err-fname'],
            ['lname',       'err-lname'],
            ['address',     'err-address'],
            ['city',        'err-city'],
            ['postcode',    'err-postcode'],
            ['email',       'err-email'],
            ['phone',       'err-phone'],
            ['dob',         'err-dob'],
            ['card-name',   'err-card-name'],
            ['card-number', 'err-card-number'],
            ['card-expiry', 'err-card-expiry'],
            ['card-cvv',    'err-card-cvv'],
        ].forEach(([id, errId]) => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', () => clearError(el, errId));
        });

        /* MAIN FORM VALIDATION */
        document.getElementById('checkoutForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let valid = true;
            const isGuest = !!document.getElementById('fname');

            // ── Guest delivery fields ──
            if (isGuest) {
                const fname    = document.getElementById('fname');
                const lname    = document.getElementById('lname');
                const address  = document.getElementById('address');
                const city     = document.getElementById('city');
                const postcode = document.getElementById('postcode');
                const email    = document.getElementById('email');
                const phone    = document.getElementById('phone');

                if (!fname.value.trim()) {
                    showError(fname, 'err-fname'); valid = false;
                }
                if (!lname.value.trim()) {
                    showError(lname, 'err-lname'); valid = false;
                }
                if (!address.value.trim()) {
                    showError(address, 'err-address'); valid = false;
                }
                if (!city.value.trim()) {
                    showError(city, 'err-city'); valid = false;
                }
                if (!isValidUKPostcode(postcode.value)) {
                    showError(postcode, 'err-postcode'); valid = false;
                }
                if (!isValidEmail(email.value)) {
                    showError(email, 'err-email'); valid = false;
                }
                if (!isValidUKPhone(phone.value)) {
                    showError(phone, 'err-phone'); valid = false;
                }
            }

            // ── Age verification (always required) ──
            const dob = document.getElementById('dob');
            if (dob) {
                const dobErr = 'err-dob';
                if (!dob.value) {
                    showError(dob, dobErr); valid = false;
                } else {
                    const today   = new Date();
                    const birth   = new Date(dob.value);
                    let age       = today.getFullYear() - birth.getFullYear();
                    const monthDiff = today.getMonth() - birth.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                        age--;
                    }
                    if (age < 18) {
                        showError(dob, dobErr); valid = false;
                    }
                }
            }

            // ── Card fields (only if card is selected) ──
            if (paymentSelect.value === 'card') {
                const cardName   = document.getElementById('card-name');
                const cardNumber = document.getElementById('card-number');
                const cardExpiry = document.getElementById('card-expiry');
                const cardCVV    = document.getElementById('card-cvv');

                // Cardholder name
                if (!cardName.value.trim()) {
                    showError(cardName, 'err-card-name'); valid = false;
                }

                // Card number: must be exactly 16 digits
                const rawNumber = cardNumber.value.replace(/\s+/g, '');
                if (!/^\d{16}$/.test(rawNumber)) {
                    showError(cardNumber, 'err-card-number'); valid = false;
                }

                // Expiry
                if (!isValidExpiry(cardExpiry.value)) {
                    showError(cardExpiry, 'err-card-expiry'); valid = false;
                }

                // CVV: exactly 3 digits
                if (!isValidCVV(cardCVV.value)) {
                    showError(cardCVV, 'err-card-cvv'); valid = false;
                }
            }

            if (!valid) {
                // Scroll to first red field
                const firstInvalid = document.querySelector('.invalid');
                if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // All good — submit
            this.submit();
        });

        /* LIVE ORDER SUMMARY UPDATE */
        const subtotalBase    = <?php echo number_format($subtotal, 2, '.', ''); ?>;
        const shippingSelect  = document.getElementById('shipping');
        const summaryDelivery = document.getElementById('summary-delivery');
        const summaryTotal    = document.getElementById('summary-total');

        function updateOrderSummary() {
            const cost = shippingSelect.value === 'nextday' ? 4.99 : 0;
            if (cost > 0) {
                summaryDelivery.textContent  = '£' + cost.toFixed(2);
                summaryDelivery.className    = '';
            } else {
                summaryDelivery.textContent  = 'Free';
                summaryDelivery.className    = 'summary-free';
            }
            summaryTotal.textContent = '£' + (subtotalBase + cost).toFixed(2);
        }

        shippingSelect.addEventListener('change', updateOrderSummary);

        /* ACCORDION */
        document.querySelectorAll('.accordion-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const body    = btn.nextElementSibling;
                const isOpen  = body.classList.contains('open');
                // Close all
                document.querySelectorAll('.accordion-body').forEach(b => b.classList.remove('open'));
                document.querySelectorAll('.accordion-toggle').forEach(b => b.classList.remove('open'));
                // Toggle clicked one
                if (!isOpen) {
                    body.classList.add('open');
                    btn.classList.add('open');
                }
            });
        });

        /*  TIMESTAMP */
        function updateTimestamp() {
            const n = new Date();
            document.getElementById("timestamp").textContent =
                n.getFullYear() + "-" +
                String(n.getMonth() + 1).padStart(2, '0') + "-" +
                String(n.getDate()).padStart(2, '0') + " " +
                String(n.getHours()).padStart(2, '0') + ":" +
                String(n.getMinutes()).padStart(2, '0') + ":" +
                String(n.getSeconds()).padStart(2, '0');
        }
        setInterval(updateTimestamp, 1000);
        updateTimestamp();

        /*  DARK MODE */
        const darkButton = document.getElementById("dark-mode");
        if (localStorage.getItem("dark_mode") === "on") {
            document.documentElement.classList.add("darkmode");
        }
        darkButton.addEventListener("click", () => {
            document.documentElement.classList.toggle("darkmode");
            localStorage.setItem("dark_mode",
                document.documentElement.classList.contains("darkmode") ? "on" : "off");
        });

    </script>
</body>
</html>
