<?php

session_start();

if (!isset($_SESSION['customerID'])) {
  header("Location: index.php");
  exit();
}

$cid = $_SESSION['customerID'];
require_once("../../database/db_connect.php");

// ── Handle Edit Details 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $firstName   = trim($_POST['firstName']);
    $surname     = trim($_POST['surname']);
    $addressLine = trim($_POST['addressLine']);
    $postcode    = trim($_POST['postcode']);
    $email       = trim($_POST['email']);
    $dateOfBirth = trim($_POST['dateOfBirth']);

    $update = $conn->prepare("
        UPDATE customer
        SET firstName = ?, surname = ?, addressLine = ?, postcode = ?, email = ?, dateOfBirth = ?
        WHERE customerID = ?
    ");
    $update->bind_param("ssssssi", $firstName, $surname, $addressLine, $postcode, $email, $dateOfBirth, $cid);
    $update->execute();

    header("Location: account.php?updated=1");
    exit();
}

// ── Handle Delete Account 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $conn->query("DELETE FROM refund      WHERE orderId IN (SELECT orderId FROM orders WHERE customerId = $cid)");
    $conn->query("DELETE FROM shipping    WHERE orderId IN (SELECT orderId FROM orders WHERE customerId = $cid)");
    $conn->query("DELETE FROM payment     WHERE orderId IN (SELECT orderId FROM orders WHERE customerId = $cid)");
    $conn->query("DELETE FROM orderswines WHERE orderId IN (SELECT orderId FROM orders WHERE customerId = $cid)");
    $conn->query("DELETE FROM orders      WHERE customerId = $cid");
    $conn->query("DELETE FROM customer    WHERE customerID = $cid");

    session_destroy();
    header("Location: index.php?deleted=1");
    exit();
}

// ── Fetch Orders 
$stmt = $conn->prepare("
    SELECT
        shipping.trackingNumber,
        orders.orderId,
        orders.orderDate,
        payment.amount,
        payment.method,
        payment.paymentStatus,
        payment.transactionTimestamp,
        shipping.shippingStatus
    FROM orders
    LEFT JOIN payment  ON payment.orderId  = orders.orderId
    LEFT JOIN shipping ON shipping.orderId = orders.orderId
    WHERE orders.customerId = ?
    ORDER BY payment.transactionTimestamp DESC, orders.orderId DESC
");
$stmt->bind_param("i", $cid);
$stmt->execute();
$transactions = $stmt->get_result();

// ── Fetch User 
$userQuery = $conn->prepare("SELECT * FROM customer WHERE customerID = ?");
$userQuery->bind_param("i", $cid);
$userQuery->execute();
$user = $userQuery->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>

    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />

    <style>
        .orderstable td {
            vertical-align: middle;
            height: 60px;
        }
        .status-returned {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-rejected {
            color: #b33;
            font-weight: bold;
        }
        .status-not-eligible {
            color: grey;
            font-style: italic;
            text-align: center;
        }

        body {
            background-color: var(--background-colour);
            padding-top: 100px;
        }

        .accountcontainer {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: var(--frame-colour);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .accountinfo {
            padding: 20px 24px;
            border-radius: 6px 6px 0 0;
            background-color: var(--background-colour);
        }

        .accountinfo-actions {
            display: flex;
            gap: 0;
            border-radius: 0 0 6px 6px;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .accountinfo-actions button {
            flex: 1;
            border-radius: 0;
            border-right: 1px solid rgba(255,255,255,0.15);
            font-size: 14px;
            padding: 11px 8px;
        }

        .accountinfo-actions button:last-child {
            border-right: none;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .accountinfo p {
            font-size: 16px;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--background-colour);
            border-radius: 6px;
            margin-bottom: 20px;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid var(--border-colour);
        }

        th {
            background: var(--primary-colour);
            color: #fff;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-top: 10px;
        }

        button, .btn {
            padding: 12px;
            background: var(--primary-colour);
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 15px;
            color: #fff;
            font-weight: 500;
            transition: filter 0.3s;
            width: 100%;
            text-align: center;
            display: block;
            box-sizing: border-box;
        }

        button:hover, .btn:hover {
            filter: brightness(0.8);
        }

        .btn-danger {
            background: #c0392b;
        }

        .btn-secondary {
            background: #555;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-backdrop.open {
            display: flex;
        }

        .modal {
            background: var(--frame-colour, #fff);
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            position: relative;
        }

        .modal h2 {
            margin-bottom: 20px;
        }

        .modal .close-btn {
            position: absolute;
            top: 14px;
            right: 18px;
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: inherit;
            width: auto;
            padding: 0;
        }

        .edit-form label {
            display: block;
            margin-bottom: 4px;
            font-weight: 600;
            font-size: 14px;
        }

        .edit-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border: 1px solid var(--border-colour, #ccc);
            border-radius: 6px;
            font-size: 15px;
            background: var(--background-colour, #fff);
            color: inherit;
            box-sizing: border-box;
        }

        .edit-form button[type="submit"] {
            width: 100%;
        }

        .delete-warning {
            color: #c0392b;
            font-weight: 600;
            margin-bottom: 16px;
            text-align: center;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .modal-actions button {
            flex: 1;
        }

        @media (max-width: 600px) {
            .accountinfo-actions {
                flex-direction: column;
                border-radius: 0 0 6px 6px;
            }
            .accountinfo-actions button {
                border-right: none;
                border-bottom: 1px solid rgba(255,255,255,0.15);
                border-radius: 0;
            }
            .accountinfo-actions button:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
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
            <form method="POST" action="search.php">
                <input type="text" name="search" placeholder="Search">
                <input type="hidden" name="submitted" value="true" />
            </form>
            <a href="log-in.php">Login</a>
            <a href="signup.php">Sign up</a>
            <a href="account.php">Account</a>
            <button id="dark-mode" class="dark-mode-button">
                <img src="../../images/darkmode.png" alt="Dark Mode" />
            </button>
        </div>
    </div>

    <div class="accountcontainer">

        <h1>Welcome, <span><?= htmlspecialchars($user['firstName']); ?></span></h1>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert-success">✓ Your details have been updated successfully.</div>
        <?php endif; ?>

        <!-- Account Info -->
        <div class="accountinfo">
            <h2>Account Information</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['firstName']); ?></p>
            <p><strong>Surname:</strong> <?= htmlspecialchars($user['surname']); ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($user['addressLine']); ?></p>
            <p><strong>Postcode:</strong> <?= htmlspecialchars($user['postcode']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user['dateOfBirth']); ?></p>
        </div>

        <!-- Action buttons -->
        <div class="accountinfo-actions">
            <button onclick="openModal('editModal')">✏️ Edit My Details</button>
            <button class="btn-secondary" onclick="window.location.href='logout.php'">🚪 Logout</button>
            <button class="btn-danger" onclick="openModal('deleteModal')">🗑️ Delete Account</button>
        </div>

        <!-- Order History -->
        <div class="orderstable">
            <h2>Order History</h2>
            <table>
                <tr>
                    <th>Tracking Number</th>
                    <th>Order ID</th>
                    <th>£ Total</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Shipping Status</th>
                    <th>Transaction Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['trackingNumber']); ?></td>
                        <td><?= htmlspecialchars($row['orderId']); ?></td>
                        <td><?= htmlspecialchars($row['amount']); ?></td>
                        <td><?= htmlspecialchars($row['method']); ?></td>
                        <td><?= htmlspecialchars($row['paymentStatus']); ?></td>
                        <td><?= htmlspecialchars($row['shippingStatus']); ?></td>
                        <td><?= htmlspecialchars($row['transactionTimestamp']); ?></td>
                        <td>
                            <?php
                                $oid = (int)$row['orderId'];
                                $refundQuery = $conn->query("SELECT status FROM refund WHERE orderId = $oid LIMIT 1");
                                $refund      = $refundQuery->fetch_assoc();
                                $refundStatus = $refund['status'] ?? null;
                                $within30    = $row['orderDate'] > date('Y-m-d', strtotime('-30 days'));

                                if ($refundStatus === 'accepted') {                             
                                    echo "<span class='status-returned'>Return Approved</span>";
                                } elseif ($refundStatus === 'pending') {
                                    echo "<span class='status-not-eligible'>Return Pending Approval</span>";
                                } elseif ($refundStatus === 'denied') {
                                    echo "<span class='status-not-eligible' style='color:#b33;'>Return Rejected</span>";
                                } elseif ($within30) {
                                    echo "<button onclick=\"window.location.href='return.php?orderId=$oid'\">Return</button>";
                                } else {
                                    echo "<span class='status-not-eligible'>Not eligible</span>";
                                }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>

    <!-- ── EDIT DETAILS MODAL -->
    <div class="modal-backdrop" id="editModal">
        <div class="modal">
            <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
            <h2>Edit My Details</h2>
            <form class="edit-form" method="POST" action="account.php">
                <input type="hidden" name="action" value="edit">

                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName"
                       value="<?= htmlspecialchars($user['firstName']); ?>" required>

                <label for="surname">Surname</label>
                <input type="text" id="surname" name="surname"
                       value="<?= htmlspecialchars($user['surname']); ?>" required>

                <label for="addressLine">Address</label>
                <input type="text" id="addressLine" name="addressLine"
                       value="<?= htmlspecialchars($user['addressLine']); ?>" required>

                <label for="postcode">Postcode</label>
                <input type="text" id="postcode" name="postcode"
                       value="<?= htmlspecialchars($user['postcode']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($user['email']); ?>" required>

                <label for="dateOfBirth">Date of Birth</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth"
                       value="<?= htmlspecialchars($user['dateOfBirth']); ?>" required>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- ── DELETE ACCOUNT MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal">
            <button class="close-btn" onclick="closeModal('deleteModal')">&times;</button>
            <h2>Delete Account</h2>
            <p class="delete-warning">⚠ This action is permanent and cannot be undone.</p>
            <p style="text-align:center; margin-bottom: 20px;">
                All your orders and account data will be permanently removed.
                Are you sure you want to continue?
            </p>
            <form method="POST" action="account.php">
                <input type="hidden" name="action" value="delete">
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
                    <button type="submit" class="btn-danger">Yes, Delete My Account</button>
                </div>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <?php include 'footer.php'; ?>

    <script>
        // Dark mode
        const darkButton = document.getElementById("dark-mode");
        if (localStorage.getItem("dark_mode") === "on") {
            document.documentElement.classList.add("darkmode");
        }
        darkButton.addEventListener("click", () => {
            document.documentElement.classList.toggle("darkmode");
            localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
        });

        // Modal helpers
        function openModal(id) {
            document.getElementById(id).classList.add("open");
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove("open");
        }

        // Close modal when clicking outside the box
        document.querySelectorAll(".modal-backdrop").forEach(backdrop => {
            backdrop.addEventListener("click", e => {
                if (e.target === backdrop) closeModal(backdrop.id);
            });
        });
    </script>

</body>
</html>