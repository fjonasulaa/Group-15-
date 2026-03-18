<?php require_once("admin_.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
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

        .btn-danger {
            background: #c0392b !important;
            color: #fff !important;
        }

        .btn-secondary {
            background: #555 !important;
            color: #fff !important;
        }

        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-backdrop.open {
            display: flex;
        }

        .modal {
            background: var(--frame-colour, #fff);
            color: var(--text-colour, #111);
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            position: relative;
            box-sizing: border-box;
        }

        .modal h2 {
            margin-bottom: 20px;
            text-align: center;
            color: var(--text-colour, #111);
            font-size: 28px;
        }

        .modal p {
            color: var(--text-colour, #111);
            font-size: 16px;
            line-height: 1.5;
        }

        .modal .close-btn {
            position: absolute;
            top: 14px;
            right: 18px;
            background: none !important;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: var(--text-colour, #111) !important;
            width: auto;
            padding: 0;
            margin: 0;
        }

        .delete-warning {
            color: #c0392b !important;
            font-weight: 600;
            margin-bottom: 16px;
            text-align: center;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .modal-actions button {
            flex: 1;
            width: 100%;
            padding: 12px !important;
            margin: 0 !important;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 15px !important;
            font-weight: 500;
            color: #fff !important;
            text-align: center;
            box-sizing: border-box;
        }
    </style>
</head>

<body class="admin">

    <?php include 'header.php'; ?>

    <div class="sidebar">
        <?php for ($i = 0; $i < count($customers); $i++): ?>
            <li>
                <a href="admin.php?customerID=<?= (int)$customers[$i]['customerID'] ?>"
                    style="<?= ($customers[$i]['role']) === 'adminPending' ? 'color:#c0392b; font-weight:bold;' : '' ?>">
                    <?= $customers[$i]['email'] ?>
                    <?php if (($customers[$i]['role']) === 'adminPending') echo ' (Admin Pending)'; ?>
                </a>
            </li>
        <?php endfor; ?>
    </div>

    <div class="sidebar-container">
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'profile')">Profile</button>
            <button class="tablinks" onclick="openTab(event, 'transactions')">Transactions</button>
            <button class="tablinks" onclick="openTab(event, 'returns')">Returns</button>
            <button class="tablinks" onclick="window.location.href='inventory.php'">Inventory</button>
            <button class="tablinks" onclick="window.location.href='logout.php'">Logout</button>
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
                                    <label for="username">CUSTOMER ID</label>
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

                            <div>
                                <label for="role">ROLE</label>
                                <select name="role" id="role">
                                    <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="adminPending" <?= $user['role'] === 'adminPending' ? 'selected' : '' ?>>Admin Pending</option>
                                </select>
                            </div>

                            <div class="row">
                                <button type="submit" name="saveDetails">Save Profile</button>
                                <button type="button" name="deleteuSER" onclick="openModal('deleteModal')">Delete Account</button>
                            </div>
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
                                <input type="password" name="confirmnewpassword" placeholder="Confirm New Password" required>
                            </div>
                            <button type="submit" name="changePassword">Change Password</button>
                        </form>
                    </div>
                </div>

                <!-- table -->
                <div class="transaction-wrapper tabcontent" id="transactions">
                    <h1 class="center-title">TRANSACTION HISTORY</h1>
                    <div class="transaction-table">
                        <div class="transaction-header">
                            <span>SHIPPING NUMBER</span>
                            <span>CARRIER</span>
                            <span>ORDER ID</span>
                            <span>AMOUNT</span>
                            <span>CUSTOMER</span>
                            <span>PAYMENT METHOD</span>
                            <span>PAYMENT STATUS</span>
                            <span>SHIPMENT</span>
                            <span>TRANSACTION DATE</span>
                        </div>

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
                                    <input type="text" name="carrier" value="<?= $transactions[$i]['carrier'] ?>" style="width:120px; height:24px; margin-top: 20px; font-size:14px;">
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
                                    
                                    <span>
                                        <select name="shippingStatus">
                                            <option value="Preparing" <?= $transactions[$i]['shippingStatus'] == 'Preparing' ? 'selected' : '' ?>>Preparing</option>
                                            <option value="In Transit" <?= $transactions[$i]['shippingStatus'] == 'In Transit' ? 'selected' : '' ?>>In Transit</option>
                                            <option value="Delivered" <?= $transactions[$i]['shippingStatus'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                        </select>
                                        <button type="submit" name="updateTransactionStatus">Save</button>
                                    </span>

                                    <span style="display:inline-block; margin-top:-38px;"><?= $transactions[$i]['transactionTimestamp'] ?></span>
                                </form>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- returns -->
                <div class="tabcontent" id="returns" style="display:none;">
                    <h1 class="center-title">RETURN REQUESTS</h1>
                    <div class="transaction-table">
                        <div class="transaction-header">
                            <span>REFUND ID</span>
                            <span>ORDER ID</span>
                            <span>REASON</span>
                            <span>DESCRIPTION</span>
                            <span>STATUS</span>
                            <span>ACTION</span>
                        </div>

                        <?php if (count($returns) === 0): ?>
                            <div class="transaction-row">
                                <span>—</span>
                                <span>—</span>
                                <span>No returns found</span>
                                <span>—</span>
                                <span>—</span>
                                <span>—</span>
                            </div>
                        <?php else: ?>
                            <?php foreach ($returns as $r): ?>
                                <form method="post" class="transaction-row">
                                    <input type="hidden" name="refundId" value="<?= $r['refundId'] ?>">
                                    <input type="hidden" name="orderId" value="<?= $r['orderId'] ?>">
                                    <span><?= $r['refundId'] ?></span>
                                    <span><?= $r['orderId'] ?></span>
                                    <span><?= htmlspecialchars($r['reason']) ?></span>
                                    <span><?= htmlspecialchars($r['description']) ?></span>
                                    <span><?= $r['status'] ?></span>
                                    <span>
                                        <?php if ($r['status'] === 'pending'): ?>
                                            <button name="approveReturn">Approve</button>
                                            <button name="rejectReturn" style="background:#b33;">Reject</button>
                                        <?php else: ?>
                                            <strong><?= $r['status'] ?></strong>
                                        <?php endif; ?>
                                    </span>
                                </form>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

        <?php if (!empty($deleteError)): ?>
        <script>
            window.addEventListener("load", function () {
                setTimeout(function () {
                    alert("<?= htmlspecialchars($deleteError, ENT_QUOTES) ?>");
                }, 100);
            });
        </script>
        <?php endif; ?>

        <div class="modal-backdrop" id="deleteModal">
            <div class="modal">
                <button class="close-btn" onclick="closeModal('deleteModal')">&times;</button>
                <h2>Delete Account</h2>
                <p class="delete-warning">⚠ This action is permanent and cannot be undone.</p>
                <p style="text-align:center; margin-bottom: 20px;">
                    Are you sure you want to delete this account?
                </p>
                <form method="post">
                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
                        <button type="submit" name="deleteuSER" class="btn-danger">Yes, Delete Account</button>
                    </div>
                </form>
            </div>
        </div>

        <?php include 'footer.php'; ?>
    </div>

    <script>
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

    <script>
        function openModal(id) { document.getElementById(id).classList.add("open"); }
        function closeModal(id) { document.getElementById(id).classList.remove("open"); }
        document.querySelectorAll(".modal-backdrop").forEach(backdrop => {
            backdrop.addEventListener("click", e => {
                if (e.target === backdrop) closeModal(backdrop.id);
            });
        });
    </script>

</body>
</html>