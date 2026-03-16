<?php require_once("admin_.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />

    <style>
        .footer {
            background-color: #f4f4f4;
            padding: 30px 10%;
            margin-top: 40px;
            color: #333;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .footer-section {
            flex: 1 1 250px;
            margin: 10px;
        }

        .footer-section h3 {
            margin-bottom: 10px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin: 5px 0;
        }

        .footer-links a {
            text-decoration: none;
            color: inherit;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        /* Contact button */
        .footer-button {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .footer-button:hover {
            opacity: 0.9;
        }

        /* Footer bottom bar */
        .footer-bottom {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 14px;
        }

        /* DARK MODE SUPPORT */
        .darkmode .footer {
            background-color: #1e1e1e;
            color: #eee;
        }

        .darkmode .footer-bottom {
            border-top: 1px solid #555;
        }

        .darkmode .footer-links a {
            color: #ddd;
        }

        .transaction-row select[name="paymentStatus"] {
            margin-top: 20px;

        }

        .transaction-row select {
            width: 120px;
            padding: 4px;
            font-size: 14px;
        }

        .transaction-row button {
            padding: 4px 8px;
            font-size: 14px;
            margin-left: 5px;
            background-color: #57af4c;
            color: white;
            border: none;
            outline: none;
        }
    </style>
</head>

<body class="admin">

    <div class="sidebar">
        <?php for ($i = 0; $i < count($customers); $i++): ?>
            <li>
                <a href="admin.php?customerID=<?= (int)$customers[$i]['customerID'] ?>">
                    <?= $customers[$i]['email'] ?>
                </a>
            </li>
        <?php endfor; ?>
    </div>

    <!-- NAVBAR -->
    <div class="navbar">
        <a href="index.php"><img src="../../images/icon.png" alt="Wine Exchange Logo"></a>
        <div class="navbar-links">
            <a href="index.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="wines.html">Wines</a>
            <a href="basket.php">Basket</a>
            <a href="contact-us.php">Contact Us</a>
        </div>

        <div class="navbar-right">
            <form method="POST" action="search.php">
                <input type="text" name="search" placeholder="Search">

                <input type="hidden" name="submitted" value="true" />
            </form>
            <a href="log-in.php">Login</a>
            <a href="signup.php">Sign up</a>
            <a href="account.php">Account</a>
            <a href="admin.html"><img src="../../images/admin-pic.png" alt="Admin controls"></a>
            <button id="dark-mode" class="dark-mode-button">
                <img src="../../images/darkmode.png" alt="Dark Mode" />
            </button>
        </div>
    </div>

    <div class="sidebar-container">
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'profile')">Profile</button>
            <button class="tablinks" onclick="openTab(event, 'transactions')">Transactions</button>
        </div>

        <main>
            <div class="tab-container">
                <a href="inventory.php">Manage Inventory</a>

                <div class="profile-wrapper tabcontent active" id="profile">
                    <h1 class="center-title"><?= $user['firstName'] ?> <?= $user['surname'] ?> Account</h1>

                    <!-- profile info -->
                    <div class="profile frame">
                        <form method="post">
                            <div class="row">
                                <div>
                                    <label for="username">Customer ID</label>
                                    <input type="text" name="customerID" value="<?= $user['customerID'] ?>" readonly>
                                </div>

                                <div>
                                    <label for="email">EMAIL</label>
                                    <input type="email" name="email" value="<?= $user['email'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div>
                                    <label for="firstName">FIRST NAME</label>
                                    <input id="firstName" type="text" name="firstName" value="<?= $user['firstName'] ?>">
                                </div>

                                <div>
                                    <label for="surname">SURNAME</label>
                                    <input id="surname" type="text" name="surname" value="<?= $user['surname'] ?>">
                                </div>
                            </div>

                            <div>
                                <label for="addressline">ADDRESS LINE</label>
                                <input type="text" name="addressline" value="<?= $user['addressLine'] ?>">
                            </div>

                            <div class="row">
                                <div>
                                    <label for="postcode">POSTCODE</label>
                                    <input type="text" name="postcode" value="<?= $user['postcode'] ?>">
                                </div>
                                <div>
                                    <label for="pnumber">PHONE NUMBER</label>
                                    <input type="tel" name="pnumber" value="<?= $user['phoneNumber'] ?>">
                                </div>
                            </div>

                            <button type="submit" name="saveDetails">Save Profile</button>
                        </form>
                    </div>

                    <div class="password frame">
                        <form method="post">
                            <div>
                                <label for="newpassword">NEW PASSWORD</label>
                                <input type="password" name="newpassword" placeholder="Enter New Password" required>
                            </div>

                            <div>
                                <label for="confirmnewpassword">CONFIRM NEW</label>
                                <input type="password" name="confirmnewpassword" placeholder="Confirm New Password"
                                    required>
                            </div>

                            <button type="submit" name="changePassword">Change Password</button>
                        </form>
                    </div>

                    <button onclick="window.location.href='inventory.php'">Inventory</button>
                    <button onclick="window.location.href='logout.php'">Logout</button>

                </div>

                <!-- table -->
                <div class="transaction-wrapper tabcontent" id="transactions">
                    <h1 class="center-title">TRANSACTION HISTORY</h1>
                    <div class="transaction-table">
                        <div class="transaction-header">
                            <span>SHIPPING NUMBER</span>
                            <span>ORDER ID</span>
                            <span>AMOUNT</span>
                            <span>CUSTOMER</span>
                            <span>PAYMENT METHOD</span>
                            <span>PAYMENT STATUS</span>
                            <span>TRANSACTION DATE</span>
                            <span>SHIPMENT</span>
                        </div>

                        <!-- Example row -->
                        <?php if (count($transactions) == 0): ?>
                            <div class="transaction-row">
                                <span>—</span>
                                <span>—</span>
                                <span class="transaction-amount">£0.00</span>
                                <span><?= $user['firstName'] . ' ' . $user['surname'] ?></span>
                                <span>—</span>
                                <span>—</span>
                                <span>—</span>
                                <span>—</span>
                            </div>

                        <?php else: ?>

                            <?php for ($i = 0; $i < count($transactions); $i++): ?>
                                <form method="post" class="transaction-row">
                                    <input type="hidden" name="orderId" value="<?= $transactions[$i]['orderId'] ?>">
                                    <input type="text" name="trackingNumber" value="<?= $transactions[$i]['trackingNumber'] ?>" style="width:120px; height:24px; margin-top: 20px; font-size:14px;">
                                    <span><?= $transactions[$i]['orderId'] ?></span>
                                    <span>£<?= number_format((float)$transactions[$i]['amount'], 2) ?></span>
                                    <span><?= $user['firstName'] . ' ' . $user['surname'] ?></span>
                                    <span><?= $transactions[$i]['method'] ?></span>

                                    <span>
                                        <select name="paymentStatus">
                                            <option value="Pending" <?= $transactions[$i]['paymentStatus'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Paid" <?= $transactions[$i]['paymentStatus'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                                        </select>
                                    </span>

                                    <span><?= $transactions[$i]['transactionTimestamp'] ?></span>

                                    <span>
                                        <select name="shippingStatus">
                                            <option value="Preparing" <?= $transactions[$i]['shippingStatus'] == 'Preparing' ? 'selected' : '' ?>>Preparing</option>
                                            <option value="In Transit" <?= $transactions[$i]['shippingStatus'] == 'In Transit' ? 'selected' : '' ?>>In Transit</option>
                                            <option value="Delivered" <?= $transactions[$i]['shippingStatus'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                        </select>

                                        <button type="submit" name="updateTransactionStatus">Save</button>
                                    </span>
                                </form>

                
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

        <!-- footer -->
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
                        <li><a href="about.html">About Us</a></li>
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

            <div class="footer-bottom">
                © 2026 Wine Exchange. All rights reserved.
            </div>
        </footer>
    </div>

    <script>
        // DARK MODE
        const darkButton = document.getElementById("dark-mode");
        if (localStorage.getItem("dark_mode") === "on") {
            document.documentElement.classList.add("darkmode");
        }

        darkButton.addEventListener("click", () => {
            document.documentElement.classList.toggle("darkmode");
            localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
        });
    </script>

    <script>
        // OPEN TAB
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;

            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
            document.documentElement.scrollTop = 0;
        }
    </script>

    <script>
        const links = document.querySelectorAll('.sidebar a');

        links.forEach(link => {
            link.addEventListener('click', function() {
                links.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>

</html>