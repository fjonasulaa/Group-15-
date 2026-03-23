<?php

session_start();

include '../../database/db_connect.php';

if (isset($_SESSION['customerID'])) {
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

if (!isset($_GET['id'])) {
    die("No wine selected.");
}

$wineId = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM wines WHERE wineId = ?");
$stmt->bind_param("i", $wineId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Wine not found. <a href='inventory.php'>Back to Stock Management</a>");
}

$wine = $result->fetch_assoc();

$error = $_SESSION['register_error'] ?? "";
unset($_SESSION["register_error"]);

function showError($errors) {
    return !empty($errors) ? "<p class='error-message'>$errors</p>" : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wine | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>

    /* ── PAGE BASE ── */
    body {
        background: #ffffff;
        font-family: 'Jost', sans-serif;
        color: #2a1018;
        margin: 0;
    }

    /* ── BODY WRAPPER ── */
    .nw-body {
        max-width: 1100px;
        margin: 32px auto 60px;
        padding: 0 24px;
    }

    /* ── PAGE HEADER ── */
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

    /* ── BACK BUTTON ── */
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

    /* ── TWO-COLUMN GRID ── */
    .nw-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        align-items: start;
    }

    /* ── CARDS ── */
    .nw-card {
        background: #fff;
        border-radius: 10px;
        border: 0.5px solid rgba(107,26,46,0.12);
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
        border-bottom: 0.5px solid rgba(107,26,46,0.08);
    }

    /* ── FORM FIELDS ── */
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
        border: 0.5px solid rgba(107,26,46,0.2);
        border-radius: 4px;
        font-family: 'Jost', sans-serif;
        font-size: 13px;
        color: #2a1018;
        background: #f7f7f7;
        outline: none;
        transition: border-color 0.15s;
        margin: 0;
    }

    .nw-field input:focus,
    .nw-field select:focus,
    .nw-field textarea:focus {
        border-color: rgba(107,26,46,0.5);
        background: #fff;
    }

    .nw-field textarea {
        height: 90px;
        padding: 8px 10px;
        resize: vertical;
        line-height: 1.5;
    }

    /* ── CURRENT IMAGE PREVIEW ── */
    .nw-current-img {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 6px;
        border: 0.5px solid rgba(107,26,46,0.12);
        margin-bottom: 12px;
        background: #f7f7f7;
        display: block;
    }

    .nw-img-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #8a5a60;
        margin-bottom: 8px;
        display: block;
    }

    /* ── FILE UPLOAD SLOT ── */
    .nw-upload-slot {
        border: 0.5px dashed rgba(107,26,46,0.3);
        border-radius: 6px;
        padding: 16px;
        text-align: center;
        background: #fafafa;
        transition: background 0.15s, border-color 0.15s;
        margin-top: 12px;
    }

    .nw-upload-slot:hover {
        background: #f5f0f2;
        border-color: rgba(107,26,46,0.5);
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
        border: 0.5px solid rgba(107,26,46,0.2);
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
        margin: 0;
    }

    /* ── SUBMIT BUTTON ── */
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

    /* ── ERROR ── */
    .error-message {
        background: #fceaea;
        border: 0.5px solid #f0a8a8;
        color: #8a1c1c;
        border-radius: 5px;
        padding: 10px 14px;
        font-size: 13px;
        margin-bottom: 16px;
        text-align: center;
    }

    /* ── DARK MODE — CSS variables from styles.css handle this automatically ── */
    body             { background: var(--background-colour); color: var(--text-colour); }
    .nw-card         { background: var(--frame-colour); border-color: var(--border-colour); }
    .nw-card-title   { color: var(--text-colour); border-color: var(--border-colour); opacity: 0.6; }
    .nw-field label  { color: var(--text-colour); opacity: 0.7; }
    .nw-field input,
    .nw-field select,
    .nw-field textarea { background: var(--background-colour); border-color: var(--border-colour); color: var(--text-colour); }
    .nw-upload-slot  { background: var(--background-colour); border-color: var(--border-colour); }
    .nw-upload-slot input[type="file"] { background: var(--frame-colour); border-color: var(--border-colour); color: var(--text-colour); }
    .nw-upload-hint  { color: var(--text-colour); opacity: 0.6; }
    .nw-ready-hint   { color: var(--text-colour); opacity: 0.7; }
    .btn-outline-dark { border-color: var(--border-colour); color: var(--text-colour); }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .nw-body { padding: 0 16px; margin-top: 20px; }
        .nw-grid { grid-template-columns: 1fr; }
    }

    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="nw-body">

        <div class="nw-page-header">
            <h1 class="nw-page-title">Edit Wine</h1>
            <a href="inventory.php" class="btn-outline-dark">↩ Back to Inventory</a>
        </div>
        <div class="nw-gold-bar"></div>

        <form action="edit_Wine.php" method="post" enctype="multipart/form-data">

            <?= showError($error); ?>

            <input type="hidden" name="wineId" value="<?= $wine['wineId'] ?>">
            <input type="hidden" name="existingImage" value="<?= htmlspecialchars($wine['imageUrl']) ?>">

            <div class="nw-grid">

                <!-- LEFT COLUMN -->
                <div>
                    <div class="nw-card">
                        <div class="nw-card-title">Wine Details</div>
                        <div class="nw-field">
                            <label for="wineName">Wine Name</label>
                            <input type="text" name="wineName" placeholder="Wine Name" value="<?= htmlspecialchars($wine['wineName']) ?>" required>
                        </div>
                        <div class="nw-field">
                            <label for="wineRegion">Wine Region</label>
                            <input type="text" name="wineRegion" placeholder="Wine Region" value="<?= htmlspecialchars($wine['wineRegion']) ?>" required>
                        </div>
                        <div class="nw-field">
                            <label for="country">Country</label>
                            <input type="text" name="country" placeholder="Country" value="<?= htmlspecialchars($wine['country']) ?>">
                        </div>
                        <div class="nw-field">
                            <label for="category">Category</label>
                            <select name="category" id="category" required>
                                <option value="Red Wine"      <?= ($wine['category'] == "Red Wine")      ? "selected" : "" ?>>Red Wine</option>
                                <option value="White Wine"    <?= ($wine['category'] == "White Wine")    ? "selected" : "" ?>>White Wine</option>
                                <option value="Rosé Wine"     <?= ($wine['category'] == "Rosé Wine")     ? "selected" : "" ?>>Rosé Wine</option>
                                <option value="Dessert Wine"  <?= ($wine['category'] == "Dessert Wine")  ? "selected" : "" ?>>Dessert Wine</option>
                                <option value="Sparkling Wine"<?= ($wine['category'] == "Sparkling Wine")? "selected" : "" ?>>Sparkling Wine</option>
                                <option value="Fortified Wine"<?= ($wine['category'] == "Fortified Wine")? "selected" : "" ?>>Fortified Wine</option>
                            </select>
                        </div>
                        <div class="nw-field">
                            <label for="price">Price (£)</label>
                            <input type="number" id="price" name="price" min="0" step="0.01" placeholder="0.00" inputmode="decimal" value="<?= htmlspecialchars($wine['price']) ?>" required>
                        </div>
                        <div class="nw-field">
                            <label for="stock">Stock Quantity</label>
                            <input type="number" min="0" name="stock" value="<?= htmlspecialchars($wine['stock']) ?>" required>
                        </div>
                    </div>

                    <div class="nw-card">
                        <div class="nw-card-title">Description &amp; Ingredients</div>
                        <div class="nw-field">
                            <label for="ingredients">Ingredients</label>
                            <textarea name="ingredients" placeholder="Ingredients" required><?= htmlspecialchars($wine['ingredients']) ?></textarea>
                        </div>
                        <div class="nw-field">
                            <label for="description">Description</label>
                            <textarea name="description" placeholder="Description" required><?= htmlspecialchars($wine['description']) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <div class="nw-card">
                        <div class="nw-card-title">Image</div>

                        <?php if (!empty($wine['imageUrl'])): ?>
                            <span class="nw-img-label">Current image</span>
                            <img src="../../images/<?= htmlspecialchars($wine['imageUrl']) ?>"
                                 alt="Current Wine Image"
                                 class="nw-current-img">
                        <?php endif; ?>

                        <div class="nw-upload-slot">
                            <div class="nw-upload-icon">
                                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1" y="3" width="14" height="10" rx="2" stroke="#6b1a2e" stroke-width="1.2"/>
                                    <circle cx="5.5" cy="7" r="1.5" stroke="#6b1a2e" stroke-width="1"/>
                                    <path d="M1 11l4-3 3 2.5 2-1.5 5 4" stroke="#6b1a2e" stroke-width="1" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <p class="nw-upload-hint">Upload a new image to replace the current one</p>
                            <input type="file" id="imageUpload" name="image" accept="image/*">
                        </div>
                    </div>

                    <div class="nw-card" style="border-color: rgba(107,26,46,0.2);">
                        <div class="nw-card-title">Save changes</div>
                        <p class="nw-ready-hint">Review all fields before saving. Leaving the image field empty will keep the current image.</p>
                        <button type="submit" name="create" class="nw-submit">Save Changes</button>
                    </div>
                </div>

            </div>

        </form>

    </div>

    <?php include 'footer.php'; ?>

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

</body>
</html>