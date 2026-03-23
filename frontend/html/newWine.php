<?php

session_start();

$error = $_SESSION['register_error'] ?? "";
unset($_SESSION["register_error"]);

if (isset($_SESSION['customerID'])) {
    include '../../database/db_connect.php';
    $stmt = $conn->prepare("SELECT role FROM customer WHERE customerID = ?");
    $stmt->bind_param("i", $_SESSION['customerID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['role'] !== 'admin') {
        header("Location: index.html");
        exit;
    }
} else {
    header("Location: log-in.php");
    exit;
}

function showError($errors) {
    return !empty($errors) ? "<p class='error-message'>$errors</p>" : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Wine | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>

    /* ── LIGHT MODE ── */
    html, body {
        background: #ffffff;
        font-family: 'Jost', sans-serif;
        color: #2a1018;
        margin: 0;
    }

    .nw-body {
        max-width: 1100px;
        margin: 32px auto 60px;
        padding: 0 24px;
    }

    .nw-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .nw-page-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 30px;
        font-weight: 700;
        color: #6b1a2e;
        letter-spacing: 0.02em;
        line-height: 1;
        margin: 0;
    }

    .nw-gold-bar {
        height: 2px;
        background: linear-gradient(90deg, #c9a84c 0%, #e8d08a 50%, #c9a84c 100%);
        margin-bottom: 28px;
        border-radius: 1px;
    }

    .btn-outline-dark {
        font-family: 'Jost', sans-serif;
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 8px 16px;
        border-radius: 4px;
        background: transparent;
        border: 1px solid rgba(107,26,46,0.4);
        color: #6b1a2e;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: opacity 0.2s;
    }
    .btn-outline-dark:hover { opacity: 0.85; }

    .nw-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        align-items: start;
    }

    .nw-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e8e0e0;
        padding: 24px 28px;
        margin-bottom: 20px;
    }
    .nw-card:last-child { margin-bottom: 0; }

    .nw-card-title {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #8a5a60;
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0e8e8;
    }

    .nw-field { margin-bottom: 14px; }
    .nw-field:last-child { margin-bottom: 0; }

    .nw-field label {
        display: block;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #8a5a60;
        margin-bottom: 5px;
        font-weight: 400;
    }

    .nw-field input,
    .nw-field select,
    .nw-field textarea {
        width: 100%;
        box-sizing: border-box;
        height: 36px;
        padding: 0 10px;
        border: 1px solid #e0d0d0;
        border-radius: 4px;
        font-family: 'Jost', sans-serif;
        font-size: 13px;
        color: #2a1018;
        background: #f7f4f4;
        outline: none;
        transition: border-color 0.15s;
        margin: 0;
    }

    .nw-field input:focus,
    .nw-field select:focus,
    .nw-field textarea:focus {
        border-color: #6b1a2e;
        background: #fff;
    }

    .nw-field textarea {
        height: 90px;
        padding: 8px 10px;
        resize: vertical;
        line-height: 1.5;
    }

    .nw-upload-slot {
        border: 1px dashed #d0b8b8;
        border-radius: 6px;
        padding: 20px 16px;
        text-align: center;
        background: #fafafa;
        transition: background 0.15s, border-color 0.15s;
    }
    .nw-upload-slot:hover {
        background: #f5f0f2;
        border-color: #6b1a2e;
    }

    .nw-upload-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fdf0f2;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
    }
    .nw-upload-icon svg { width: 16px; height: 16px; }

    .nw-upload-hint {
        font-size: 11px;
        color: #8a5a60;
        margin-bottom: 10px;
        letter-spacing: 0.04em;
    }

    .nw-upload-slot input[type="file"] {
        height: auto;
        padding: 5px 8px;
        font-size: 11px;
        color: #8a5a60;
        background: #fff;
        border: 1px solid #e0d0d0;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
        margin: 0;
    }

    .nw-submit {
        width: 100%;
        height: 44px;
        background: #6b1a2e;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-family: 'Jost', sans-serif;
        font-size: 13px;
        font-weight: 500;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .nw-submit:hover { opacity: 0.88; }

    .nw-ready-hint {
        font-size: 13px;
        color: #8a5a60;
        margin-bottom: 16px;
        line-height: 1.6;
    }

    .error-message {
        background: #fceaea;
        border: 1px solid #f0a8a8;
        color: #8a1c1c;
        border-radius: 5px;
        padding: 10px 14px;
        font-size: 13px;
        margin-bottom: 16px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .nw-body { padding: 0 16px; margin-top: 20px; }
        .nw-grid { grid-template-columns: 1fr; }
    }

    /* ── DARK MODE — same pattern as about.php ── */
    html.darkmode body {
        background: #1a0e12;
        color: #e8ddd8;
    }

    html.darkmode .nw-page-title {
        color: #f0d98c;
    }

    html.darkmode .btn-outline-dark {
        border-color: rgba(201,168,76,0.3);
        color: #c9b8b8;
    }

    html.darkmode .nw-card {
        background: #261118;
        border-color: rgba(201,168,76,0.15);
    }

    html.darkmode .nw-card-title {
        color: #c9a84c;
        border-bottom-color: rgba(201,168,76,0.15);
        opacity: 1;
    }

    html.darkmode .nw-field label {
        color: #c9b8b8;
    }

    html.darkmode .nw-field input,
    html.darkmode .nw-field select,
    html.darkmode .nw-field textarea {
        background: #1a0e12;
        border-color: rgba(201,168,76,0.2);
        color: #e8ddd8;
    }

    html.darkmode .nw-field input:focus,
    html.darkmode .nw-field select:focus,
    html.darkmode .nw-field textarea:focus {
        border-color: #c9a84c;
        background: #261118;
    }

    html.darkmode .nw-upload-slot {
        background: #1a0e12;
        border-color: rgba(201,168,76,0.2);
    }

    html.darkmode .nw-upload-slot:hover {
        background: #261118;
        border-color: #c9a84c;
    }

    html.darkmode .nw-upload-icon {
        background: #261118;
    }

    html.darkmode .nw-upload-hint {
        color: #8a6068;
    }

    html.darkmode .nw-upload-slot input[type="file"] {
        background: #261118;
        border-color: rgba(201,168,76,0.2);
        color: #c9b8b8;
    }

    html.darkmode .nw-ready-hint {
        color: #c9b8b8;
    }

    html.darkmode .nw-submit {
        background: #9e2d4f;
    }

    html.darkmode .nw-submit:hover {
        background: #c03a60;
        opacity: 1;
    }

    html.darkmode .error-message {
        background: #2a1018;
        border-color: #6b1a2e;
        color: #f0a8a8;
    }

    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="nw-body">

        <div class="nw-page-header">
            <h1 class="nw-page-title">New Wine</h1>
            <a href="inventory.php" class="btn-outline-dark">↩ Back to Inventory</a>
        </div>
        <div class="nw-gold-bar"></div>

        <form action="new_Wine.php" method="post" enctype="multipart/form-data">

            <?= showError($error); ?>

            <div class="nw-grid">

                <!-- LEFT COLUMN -->
                <div>
                    <div class="nw-card">
                        <div class="nw-card-title">Wine Details</div>
                        <div class="nw-field">
                            <label for="wineName">Wine Name</label>
                            <input type="text" name="wineName" placeholder="Wine Name" required>
                        </div>
                        <div class="nw-field">
                            <label for="wineRegion">Wine Region</label>
                            <input type="text" name="wineRegion" placeholder="Wine Region" required>
                        </div>
                        <div class="nw-field">
                            <label for="country">Country</label>
                            <select name="country" id="country">
                                <option value="France">France</option>
                                <option value="Italy">Italy</option>
                                <option value="Portugal">Portugal</option>
                                <option value="South Africa">South Africa</option>
                                <option value="Australia">Australia</option>
                                <option value="United States">United States</option>
                            </select>
                        </div>
                        <div class="nw-field">
                            <label for="category">Category</label>
                            <select name="category" id="category">
                                <option value="Red Wine">Red Wine</option>
                                <option value="White Wine">White Wine</option>
                                <option value="Rosé Wine">Rosé Wine</option>
                                <option value="Dessert Wine">Dessert Wine</option>
                                <option value="Sparkling Wine">Sparkling Wine</option>
                                <option value="Fortified Wine">Fortified Wine</option>
                            </select>
                        </div>
                        <div class="nw-field">
                            <label for="price">Price (£)</label>
                            <input type="number" id="price" name="price" min="0" step="0.01" placeholder="0.00" inputmode="decimal" required>
                        </div>
                        <div class="nw-field">
                            <label for="stock">Stock Quantity</label>
                            <input type="number" min="0" name="stock" value="0" required>
                        </div>
                    </div>

                    <div class="nw-card">
                        <div class="nw-card-title">Description &amp; Ingredients</div>
                        <div class="nw-field">
                            <label for="ingredients">Ingredients</label>
                            <textarea name="ingredients" placeholder="Ingredients" required></textarea>
                        </div>
                        <div class="nw-field">
                            <label for="description">Description</label>
                            <textarea name="description" placeholder="Description" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <div class="nw-card">
                        <div class="nw-card-title">Image</div>
                        <div class="nw-upload-slot">
                            <div class="nw-upload-icon">
                                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1" y="3" width="14" height="10" rx="2" stroke="#6b1a2e" stroke-width="1.2"/>
                                    <circle cx="5.5" cy="7" r="1.5" stroke="#6b1a2e" stroke-width="1"/>
                                    <path d="M1 11l4-3 3 2.5 2-1.5 5 4" stroke="#6b1a2e" stroke-width="1" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <p class="nw-upload-hint">JPG, PNG or WEBP</p>
                            <input type="file" id="imageUpload" name="image" accept="image/*" required>
                        </div>
                    </div>

                    <div class="nw-card">
                        <div class="nw-card-title">Ready to publish</div>
                        <p class="nw-ready-hint">Fill in all required fields and upload an image before creating the wine listing.</p>
                        <button type="submit" name="create" class="nw-submit">Create Wine</button>
                    </div>
                </div>

            </div>

        </form>

    </div>

    <?php include 'footer.php'; ?>

    <script src="chatbot.js"></script>

</body>
</html>