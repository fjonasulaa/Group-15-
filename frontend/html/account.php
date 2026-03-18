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
        shipping.carrier,
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

// ── Navbar account link
$accountLink = 'account.php';
$result = $conn->query("SELECT role FROM customer WHERE customerID = $cid");
if ($result && $row = $result->fetch_assoc()) {
    if ($row['role'] === 'admin') {
        $accountLink = 'admin.php';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>

    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>

      /* ── NAVBAR ── */
      .navbar {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: #6b1a2e;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
        height: 62px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        transition: background 0.3s ease, border-color 0.3s ease;
      }
      .darkmode .navbar {
        background: #12060a;
        border-bottom: 1px solid rgba(255,255,255,0.07);
      }
      .navbar-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        flex: 0 0 220px;
      }
      .navbar-logo img {
        width: 32px; height: 32px;
        object-fit: contain;
        filter: brightness(0) invert(1);
        opacity: 0.9;
        flex-shrink: 0;
        transition: opacity 0.2s ease;
      }
      .navbar-logo:hover img { opacity: 1; }
      .navbar-logo-text {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.06em;
        line-height: 1;
        color: #ffffff;
        white-space: nowrap;
        transition: color 0.3s ease;
      }
      .darkmode .navbar-logo-text { color: #e8c8b0; }
      .navbar-logo-text span {
        display: block;
        font-size: 9px;
        font-family: 'Jost', sans-serif;
        font-weight: 400;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        margin-top: 3px;
        color: rgba(255,255,255,0.5);
        transition: color 0.3s ease;
      }
      .darkmode .navbar-logo-text span { color: rgba(232,200,176,0.45); }
      .navbar-links {
        display: flex;
        align-items: center;
        gap: 2px;
        flex: 1;
        justify-content: center;
      }
      .navbar-links a {
        position: relative;
        display: block;
        padding: 8px 13px;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        text-decoration: none;
        border-radius: 3px;
        color: rgba(255,255,255,0.78);
        transition: color 0.2s ease, background 0.2s ease;
      }
      .navbar-links a:hover { color: #ffffff; background: rgba(255,255,255,0.12); }
      .darkmode .navbar-links a { color: rgba(232,200,176,0.65); }
      .darkmode .navbar-links a:hover { color: #e8c8b0; background: rgba(255,255,255,0.07); }
      .navbar-links a::after {
        content: '';
        position: absolute;
        bottom: 4px; left: 13px; right: 13px;
        height: 1px;
        background: rgba(255,255,255,0.5);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.25s ease;
      }
      .navbar-links a:hover::after { transform: scaleX(1); }
      .darkmode .navbar-links a::after { background: rgba(232,200,176,0.5); }
      .navbar-gift-link {
        background: rgba(255,255,255,0.15) !important;
        border: 1px solid rgba(255,255,255,0.3) !important;
        border-radius: 20px !important;
        padding: 5px 14px !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        transition: background 0.2s ease, transform 0.15s ease, border-color 0.2s ease !important;
      }
      .navbar-gift-link:hover {
        background: rgba(255,255,255,0.26) !important;
        border-color: rgba(255,255,255,0.55) !important;
        transform: translateY(-1px) !important;
        color: #ffffff !important;
      }
      .navbar-gift-link::after { display: none !important; }
      .darkmode .navbar-gift-link {
        background: rgba(232,200,176,0.12) !important;
        border-color: rgba(232,200,176,0.28) !important;
        color: #e8c8b0 !important;
      }
      .darkmode .navbar-gift-link:hover {
        background: rgba(232,200,176,0.22) !important;
        border-color: rgba(232,200,176,0.5) !important;
        color: #e8c8b0 !important;
      }
      .navbar-right {
        display: flex;
        align-items: center;
        gap: 3px;
        flex: 0 0 220px;
        justify-content: flex-end;
      }
      .navbar-right form { position: relative; margin-right: 4px; }
      .navbar-right form input[type="text"] {
        height: 34px;
        padding: 0 10px 0 28px;
        border-radius: 4px;
        border: 1px solid rgba(255,255,255,0.22);
        background: rgba(255,255,255,0.11);
        color: #ffffff;
        font-size: 12px;
        font-family: 'Jost', sans-serif;
        letter-spacing: 0.04em;
        width: 110px;
        outline: none;
        transition: border-color 0.2s ease, background 0.2s ease, width 0.3s ease;
      }
      .navbar-right form input[type="text"]::placeholder { color: rgba(255,255,255,0.42); }
      .navbar-right form input[type="text"]:focus {
        border-color: rgba(255,255,255,0.48);
        background: rgba(255,255,255,0.16);
        width: 140px;
      }
      .darkmode .navbar-right form input[type="text"] {
        border-color: rgba(232,200,176,0.18);
        background: rgba(255,255,255,0.06);
        color: #e8c8b0;
      }
      .darkmode .navbar-right form input[type="text"]::placeholder { color: rgba(232,200,176,0.35); }
      .darkmode .navbar-right form input[type="text"]:focus {
        border-color: rgba(232,200,176,0.4);
        background: rgba(255,255,255,0.09);
      }
      .navbar-right form::before {
        content: '\f002';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 10px; top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,0.42);
        font-size: 11px;
        pointer-events: none;
        transition: color 0.3s ease;
      }
      .darkmode .navbar-right form::before { color: rgba(232,200,176,0.35); }
      .nav-divider {
        width: 1px; height: 22px;
        background: rgba(255,255,255,0.2);
        margin: 0 5px;
        flex-shrink: 0;
        transition: background 0.3s ease;
      }
      .darkmode .nav-divider { background: rgba(255,255,255,0.1); }
      .wishlist-nav-button {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px; height: 36px;
        border-radius: 4px;
        border: 1px solid transparent;
        background: transparent;
        color: rgba(255,255,255,0.8);
        font-size: 14px;
        cursor: pointer;
        transition: color 0.2s ease, background 0.2s ease, border-color 0.2s ease;
      }
      .wishlist-nav-button:hover {
        color: #ffffff;
        background: rgba(255,255,255,0.12);
        border-color: rgba(255,255,255,0.18);
      }
      .darkmode .wishlist-nav-button { color: rgba(232,200,176,0.7); }
      .darkmode .wishlist-nav-button:hover {
        color: #e8c8b0;
        background: rgba(255,255,255,0.08);
        border-color: rgba(232,200,176,0.18);
      }
      .wishlist-count {
        position: absolute;
        top: 4px; right: 2px;
        background: #ffffff;
        color: #6b1a2e;
        font-size: 9px;
        font-weight: 700;
        font-family: 'Jost', sans-serif;
        padding: 1px 4px;
        border-radius: 50px;
        min-width: 15px;
        text-align: center;
        line-height: 1.5;
        transition: background 0.3s ease, color 0.3s ease;
      }
      .darkmode .wishlist-count { background: #e8c8b0; color: #12060a; }
      .dark-mode-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px; height: 36px;
        border-radius: 4px;
        border: 1px solid transparent;
        background: transparent;
        cursor: pointer;
        padding: 0;
        transition: background 0.2s ease, border-color 0.2s ease;
      }
      .dark-mode-button:hover {
        background: rgba(255,255,255,0.12);
        border-color: rgba(255,255,255,0.18);
      }
      .darkmode .dark-mode-button:hover {
        background: rgba(255,255,255,0.08);
        border-color: rgba(232,200,176,0.18);
      }
      .dark-mode-button img {
        width: 16px; height: 16px;
        object-fit: contain;
        filter: brightness(0) invert(1);
        opacity: 0.78;
        transition: opacity 0.2s ease, filter 0.3s ease;
      }
      .dark-mode-button:hover img { opacity: 1; }
      .darkmode .dark-mode-button img {
        filter: brightness(0) invert(1) sepia(0.3) saturate(1.5) hue-rotate(340deg);
        opacity: 0.75;
      }
      .wishlist-sidebar {
        position: fixed;
        top: 0; right: -420px;
        width: 380px; height: 100%;
        background: #f4f1f2;
        padding: 30px;
        box-shadow: -5px 0 20px rgba(0,0,0,.25);
        z-index: 2000;
        overflow-y: auto;
        transition: right .4s ease;
      }
      .wishlist-sidebar.active { right: 0; }
      .wishlist-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.5);
        display: none; z-index: 1500;
      }
      .wishlist-overlay.active { display: block; }
      .close-wishlist { font-size: 22px; cursor: pointer; text-align: right; margin-bottom: 15px; }
      .close-wishlist i { transition: color .2s ease, transform .2s ease; display: inline-block; }
      #wishlist-items { display: flex; flex-direction: column; gap: 15px; margin-top: 20px; }
      .wishlist-item {
        display: flex; gap: 12px; align-items: center;
        background: white; border-radius: 10px; padding: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,.08);
        position: relative;
        transition: transform .25s ease, box-shadow .25s ease;
      }
      .wishlist-item:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.12); }
      .wishlist-img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; }
      .wishlist-info { flex: 1; }
      .wishlist-name { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
      .wishlist-price { color: #7b1e3a; font-weight: bold; margin-bottom: 8px; }
      .wishlist-actions { display: flex; gap: 8px; }
      .wishlist-view {
        padding: 4px 10px; font-size: 12px;
        border-radius: 6px; background: #eee;
        text-decoration: none; color: #333;
        transition: background .2s ease;
      }
      .wishlist-view:hover { background: #ddd; }
      .remove-wishlist {
        position: absolute; top: 6px; right: 6px;
        border: none; background: none;
        font-size: 14px; cursor: pointer; color: #999;
        transition: color .2s ease, transform .2s ease;
      }
      .remove-wishlist:hover { color: red; transform: scale(1.2); }
      html.darkmode .wishlist-sidebar { background: #121212; color: #fff; }
      html.darkmode .wishlist-item { background: #1e1e1e; border: 1px solid #333; box-shadow: none; }
      html.darkmode .wishlist-name { color: #fff; }
      html.darkmode .wishlist-price { color: #ff6b6b; }
      html.darkmode .wishlist-view { background: #2c2c2c; color: #fff; }
      html.darkmode .wishlist-view:hover { background: #3a3a3a; }
      html.darkmode .remove-wishlist { color: #bbb; }
      html.darkmode .remove-wishlist:hover { color: #ff4d4d; }
      html.darkmode #wishlist-items p { color: #ccc; }
      @media (max-width: 900px) { .navbar-links { display: none; } }
      @media (max-width: 640px) {
        .navbar { padding: 0 16px; }
        .navbar-right form input[type="text"] { width: 110px; }
        .navbar-right form input[type="text"]:focus { width: 130px; }
        .navbar-logo-text { display: none; }
      }

      /* ── PAGE STYLES ── */
        .account-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
        }

        .account-text { flex: 1; }

        .account-image {
            flex: 0 0 200px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .account-image img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 4px solid var(--primary-colour);
            padding: 3px;
            background-color: var(--frame-colour); 
        }

        .orderstable td { vertical-align: middle; height: 60px; }
        .status-returned { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .status-rejected { color: #b33; font-weight: bold; }
        .status-not-eligible { color: grey; font-style: italic; text-align: center; }

        body { background-color: var(--background-colour); }

        .accountcontainer {
            max-width: 1400px;
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

        .accountinfo-actions button:last-child { border-right: none; }

        h1, h2 { text-align: center; margin-bottom: 20px; }

        .accountinfo p { font-size: 16px; margin: 8px 0; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--background-colour);
            border-radius: 6px;
            margin-bottom: 20px;
        }

        th, td { text-align: left; padding: 12px; border-bottom: 1px solid var(--border-colour); }
        th { background: var(--primary-colour); color: #fff; }

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

        button:hover, .btn:hover { filter: brightness(0.8); }
        .btn-danger { background: #c0392b; }
        .btn-secondary { background: #555; }

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
        .modal-backdrop.open { display: flex; }

        .modal {
            background: var(--frame-colour, #fff);
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            position: relative;
        }

        .modal h2 { margin-bottom: 20px; }

        .modal .close-btn {
            position: absolute;
            top: 14px; right: 18px;
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: inherit;
            width: auto;
            padding: 0;
        }

        .edit-form label { display: block; margin-bottom: 4px; font-weight: 600; font-size: 14px; }

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

        .edit-form button[type="submit"] { width: 100%; }

        .delete-warning { color: #c0392b; font-weight: 600; margin-bottom: 16px; text-align: center; }

        .modal-actions { display: flex; gap: 10px; }
        .modal-actions button { flex: 1; }

        @media (max-width: 600px) {
            .accountinfo-actions { flex-direction: column; border-radius: 0 0 6px 6px; }
            .accountinfo-actions button { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.15); border-radius: 0; }
            .accountinfo-actions button:last-child { border-bottom: none; }
        }
    </style>
</head>
<body>

    <!-- WISHLIST OVERLAY & SIDEBAR -->
    <div class="wishlist-overlay" id="wishlistOverlay"></div>
    <div class="wishlist-sidebar" id="wishlistSidebar">
      <div class="close-wishlist" id="closeWishlist"><i class="fa fa-times"></i></div>
      <h3>Your Wishlist</h3>
      <div id="wishlist-items">
        <p>Your wishlist is empty.</p>
      </div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar">

      <!-- Logo -->
      <a href="index.php" class="navbar-logo">
        <img src="../../images/icon.png" alt="Wine Exchange Logo">
        <div class="navbar-logo-text">
          Wine Exchange
          <span>Est. 2010</span>
        </div>
      </a>

      <!-- Centre links -->
      <div class="navbar-links">
        <a href="index.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="search.php">Wines</a>
        <a href="Gift-quiz.php" class="navbar-gift-link">Find a Gift</a>
        <a href="contact-us.php">Contact Us</a>
      </div>

      <!-- Right: search + icons -->
      <div class="navbar-right">
        <form method="POST" action="search.php">
          <input type="text" name="search" placeholder="Search wines…">
          <input type="hidden" name="submitted" value="true">
        </form>

        <div class="nav-divider"></div>

        <button onclick="location.href='<?= $accountLink ?>'" class="wishlist-nav-button" aria-label="Account">
          <i class="fas fa-user"></i>
        </button>
        <button onclick="location.href='basket.php'" class="wishlist-nav-button" aria-label="Basket">
          <i class="fas fa-shopping-basket"></i>
          <span id="basket-count" class="wishlist-count" style="display:none;">0</span>
        </button>
        <button id="wishlist-toggle" class="wishlist-nav-button" aria-label="Wishlist">
          <i class="fas fa-heart"></i>
          <span id="wishlist-count" class="wishlist-count">0</span>
        </button>

        <div class="nav-divider"></div>

        <button id="dark-mode" class="dark-mode-button" aria-label="Toggle dark mode">
          <img src="../../images/darkmode.png" alt="Dark Mode">
        </button>
      </div>

    </nav>

    <div class="accountcontainer">

        <h1>Welcome, <span><?= htmlspecialchars($user['firstName']); ?></span></h1>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert-success">✓ Your details have been updated successfully.</div>
        <?php endif; ?>

        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
                $userID = $_SESSION['customerID'];
                $file = $_FILES['profileImage'];

                if ($file['error'] === 0) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

                    if (in_array($file['type'], $allowedTypes)) {

                        if ($file['size'] <= 2 * 1024 * 1024) {
                            $fileName = uniqid() . "_" . basename($file['name']);
                            $uploadPath = "../../uploads/" . $fileName;

                            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                                $dbPath = "uploads/" . $fileName;
                                $stmt = $conn->prepare("UPDATE customer SET userProfileImage = ? WHERE customerID = ?");
                                $stmt->bind_param("si", $dbPath, $userID);
                                $stmt->execute();
                            }
                        }
                    }
                }
            }
            $stmt = $conn->prepare("SELECT * FROM customer WHERE customerID = ?");
            $stmt->bind_param("i", $_SESSION['customerID']);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        ?>

        <!-- Account Info -->
        <div class="accountinfo">
            <h2>Account Information</h2>
            <div class="account-flex">
                
                <div class="account-text">
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['firstName']); ?></p>
                    <p><strong>Surname:</strong> <?= htmlspecialchars($user['surname']); ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($user['addressLine']); ?></p>
                    <p><strong>Postcode:</strong> <?= htmlspecialchars($user['postcode']); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user['dateOfBirth']); ?></p>
                </div>

                <div class="account-image">
                    <form method="POST" enctype="multipart/form-data">
                        
                        <label for="profileUpload">
                            <img 
                                src="../../<?= htmlspecialchars($user['userProfileImage'] ?? 'images/guestPfp.jpg'); ?>" 
                                alt="Profile Image" 
                                id="profilePreview"
                                style="cursor: pointer;">
                        </label>

                        <input 
                            type="file" 
                            name="profileImage" 
                            id="profileUpload" 
                            accept="image/*" 
                            style="display: none;" 
                            onchange="this.form.submit()">
                    </form>
                </div>
            </div>
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
                    <th>Carrier</th>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Shipping Status</th>
                    <th>Transaction Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?= !empty($row['trackingNumber']) ? htmlspecialchars($row['trackingNumber']) : 'Awaiting'; ?></td>
                        <td><?= !empty($row['carrier']) ? htmlspecialchars($row['carrier']) : 'Awaiting'; ?></td>
                        <td><?= htmlspecialchars($row['orderId']); ?></td>
                        <td>£<?= number_format((float)$row['amount'], 2); ?></td>
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
        if (localStorage.getItem('dark_mode') === 'on') {
            document.documentElement.classList.add('darkmode');
        }
        document.getElementById('dark-mode').addEventListener('click', function() {
            document.documentElement.classList.toggle('darkmode');
            localStorage.setItem('dark_mode', document.documentElement.classList.contains('darkmode') ? 'on' : 'off');
        });

        // Modal helpers
        function openModal(id) { document.getElementById(id).classList.add('open'); }
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }

        document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop) closeModal(backdrop.id);
            });
        });

        // Wishlist
        var loggedIn = <?php echo isset($_SESSION['customerID']) ? 'true' : 'false'; ?>;

        var wlSidebar = document.getElementById('wishlistSidebar');
        var wlOverlay = document.getElementById('wishlistOverlay');
        var wlItems   = document.getElementById('wishlist-items');
        var wlCount   = document.getElementById('wishlist-count');
        var wlToggle  = document.getElementById('wishlist-toggle');
        var wlClose   = document.getElementById('closeWishlist');

        wlToggle.addEventListener('click', function() { wlSidebar.classList.add('active'); wlOverlay.classList.add('active'); });

        function closeWL() { wlSidebar.classList.remove('active'); wlOverlay.classList.remove('active'); }
        wlClose.addEventListener('click', closeWL);
        wlOverlay.addEventListener('click', closeWL);

        function getGuestWishlist() {
            try { return JSON.parse(localStorage.getItem('wishlist')) || []; }
            catch(e) { return []; }
        }
        function saveGuestWishlist(list) { localStorage.setItem('wishlist', JSON.stringify(list)); }

        function loadWishlist() {
            if (loggedIn) {
                fetch('get_wishlist.php').then(function(r) { return r.json(); }).then(renderWishlist);
            } else {
                renderWishlist(getGuestWishlist());
            }
        }

        function renderWishlist(list) {
            wlItems.innerHTML = '';
            if (!list || !list.length) {
                wlItems.innerHTML = '<p>Your wishlist is empty.</p>';
                wlCount.textContent = 0;
                return;
            }
            wlCount.textContent = list.length;
            list.forEach(function(wine, i) {
                var imgSrc = loggedIn
                    ? (wine.imageUrl ? '../../images/' + wine.imageUrl : '../../images/placeholder.jpg')
                    : (wine.imageUrl || '../../images/placeholder.jpg');
                var wineId = wine.wineId || wine.id;
                var name   = wine.wineName || wine.name;
                var el = document.createElement('div');
                el.className = 'wishlist-item';
                el.style.animationDelay = (i * 0.06) + 's';
                el.innerHTML =
                    '<img src="' + imgSrc + '" class="wishlist-img">' +
                    '<div class="wishlist-info">' +
                        '<div class="wishlist-name">' + name + '</div>' +
                        '<div class="wishlist-price">&pound;' + wine.price + '</div>' +
                        '<div class="wishlist-actions"><a href="wineinfo.php?id=' + wineId + '" class="wishlist-view">View</a></div>' +
                    '</div>' +
                    '<button class="remove-wishlist" data-id="' + wineId + '" data-index="' + i + '"><i class="fas fa-times"></i></button>';
                wlItems.appendChild(el);
            });
        }

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.remove-wishlist');
            if (!btn) return;
            var id = btn.dataset.id, idx = btn.dataset.index;
            if (loggedIn) {
                fetch('remove_from_wishlist.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'wineId=' + id
                }).then(loadWishlist);
            } else {
                var wl = getGuestWishlist();
                wl.splice(idx, 1);
                saveGuestWishlist(wl);
                renderWishlist(wl);
            }
        });

        loadWishlist();
    </script>

</body>
<script src="chatbot.js"></script>
</html>