<?php
    session_start();
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
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Stock Management | Wine Exchange</title>

<link rel="icon" href="../../images/icon.png">
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

/* ── PAGE HEADER ── */
.inv-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
}

.inv-page-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 30px;
    font-weight: 700;
    color: #6b1a2e;
    letter-spacing: 0.02em;
    line-height: 1;
}

.inv-page-actions {
    display: flex;
    gap: 8px;
}

/* ── BUTTONS ── */
.btn {
    font-family: 'Jost', sans-serif;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 8px 16px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: opacity 0.2s;
}
.btn:hover { opacity: 0.85; }
.btn-gold { background: #c9a84c; color: #fff; }
.btn-outline { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; }
.btn-outline-dark { background: transparent; border: 1px solid rgba(107,26,46,0.4); color: #6b1a2e; }

/* ── BODY WRAPPER ── */
.inv-body {
    max-width: 1100px;
    margin: 32px auto 40px;
    padding: 0 24px;
}

/* ── STAT CARDS ── */
.inv-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 28px;
}

.stat-card {
    background: #fff;
    border-radius: 8px;
    border: 0.5px solid rgba(107,26,46,0.15);
    padding: 16px 20px;
}

.stat-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #8a5a60;
    margin-bottom: 6px;
}

.stat-value {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 28px;
    font-weight: 700;
    color: #6b1a2e;
    line-height: 1;
}

.stat-sub {
    font-size: 11px;
    color: #a07878;
    margin-top: 4px;
}

/* ── TOOLBAR ── */
.inv-toolbar {
    display: flex;
    align-items: stretch;
    margin-bottom: 16px;
    height: 44px;
}

.inv-toolbar {
    display: flex;
    align-items: stretch;
    height: 44px;
}

.inv-search {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 0.5px solid rgba(107,26,46,0.25);
    border-right: none;
    border-radius: 6px 0 0 6px;
    padding: 0 14px;
    flex: 1;
    height: 44px;
    box-sizing: border-box;
}

.inv-search i {
    color: #8a5a60;
    font-size: 13px;
    flex-shrink: 0;
    line-height: 1;
}

.inv-search input {
    border: none;
    outline: none;
    font-family: 'Jost', sans-serif;
    font-size: 13px;
    color: #2a1018;
    background: transparent;
    padding: 0;
    margin: 0;
    width: 100%;
    height: 100%;
    line-height: 44px;
}

.inv-search input::placeholder { color: #a07878; }

.inv-search-btn {
    background: #6b1a2e;
    color: #fff;
    border: none;
    border-radius: 0 6px 6px 0;
    padding: 0 24px;
    font-family: 'Jost', sans-serif;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    letter-spacing: 0.06em;
    transition: opacity 0.2s;
    white-space: nowrap;
    height: 44px;
    box-sizing: border-box;
}
.inv-search-btn:hover { opacity: 0.85; }

/* ── TABLE ── */
.inv-table-wrap {
    background: #fff;
    border-radius: 10px;
    border: 0.5px solid rgba(107,26,46,0.12);
    overflow: hidden;
}

.inv-section-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: #8a5a60;
    padding: 10px 16px 8px;
    border-bottom: 0.5px solid rgba(107,26,46,0.08);
    background: #f5f5f5;
}

.inv-table {
    width: 100%;
    border-collapse: collapse;
}

.inv-table thead tr {
    background: #6b1a2e;
}

.inv-table thead th {
    font-family: 'Jost', sans-serif;
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.7);
    padding: 13px 16px;
    text-align: left;
}

.inv-table tbody tr {
    border-bottom: 0.5px solid rgba(107,26,46,0.08);
    transition: background 0.15s;
}

.inv-table tbody tr:hover { background: #f7f7f7; }
.inv-table tbody tr:last-child { border-bottom: none; }

.inv-table td {
    padding: 14px 16px;
    font-size: 13px;
    vertical-align: middle;
}

/* ── WINE CELL ── */
.wine-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.wine-img {
    width: 38px;
    height: 52px;
    border-radius: 4px;
    object-fit: cover;
    background: #f0e8e8;
    flex-shrink: 0;
}

.wine-name {
    font-weight: 500;
    font-size: 13px;
    color: #2a1018;
}

.wine-region {
    font-size: 11px;
    color: #8a5a60;
    margin-top: 2px;
}

/* ── PRICE ── */
.price-cell {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 17px;
    font-weight: 600;
    color: #6b1a2e;
}

/* ── STOCK BADGES ── */
.stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.04em;
    white-space: nowrap;
}

.stock-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    flex-shrink: 0;
}

.stock-ok  { background: #edf7ef; color: #2d6e3e; border: 0.5px solid #a8d8b2; }
.stock-low { background: #fff5e6; color: #8a5a00; border: 0.5px solid #f0cc7a; }
.stock-out { background: #fceaea; color: #8a1c1c; border: 0.5px solid #f0a8a8; }

/* ── ACTION BUTTONS ── */
.action-btns {
    display: flex;
    gap: 6px;
}

.act-btn {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 6px 12px;
    border-radius: 4px;
    border: 0.5px solid;
    cursor: pointer;
    transition: all 0.15s;
    font-family: 'Jost', sans-serif;
    background: transparent;
}

.act-edit { border-color: rgba(107,26,46,0.3); color: #6b1a2e; }
.act-edit:hover { background: #6b1a2e; color: #fff; }
.act-del { border-color: rgba(180,30,30,0.25); color: #b01c1c; }
.act-del:hover { background: #b01c1c; color: #fff; }

/* ── MODAL ── */
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

.modal-backdrop.open { display: flex; }

.modal {
    background: #fff;
    color: #2a1018;
    border-radius: 10px;
    padding: 30px;
    width: 100%;
    max-width: 460px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    position: relative;
    box-sizing: border-box;
    border-top: 3px solid #6b1a2e;
}

.modal h2 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 26px;
    font-weight: 700;
    color: #6b1a2e;
    text-align: center;
    margin-bottom: 16px;
}

.modal p { font-size: 14px; line-height: 1.6; }

.modal .close-btn {
    position: absolute;
    top: 14px; right: 18px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #8a5a60;
    padding: 0;
}

.modal .close-btn:hover { color: #2a1018; }

.delete-warning {
    color: #b01c1c;
    font-weight: 500;
    margin-bottom: 12px;
    text-align: center;
    font-size: 13px;
}

.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 22px;
}

.modal-actions button {
    flex: 1;
    padding: 11px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-family: 'Jost', sans-serif;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.btn-danger    { background: #b01c1c; color: #fff; }
.btn-secondary { background: #eee; color: #555; }
.btn-danger:hover    { opacity: 0.88; }
.btn-secondary:hover { background: #ddd; }

/* ── DARK MODE ── */
.darkmode body { background: #0e080c; color: #e8d8d0; }
.darkmode .inv-topbar { background: #200810; }
.darkmode .stat-card { background: #1e0e14; border-color: rgba(200,120,140,0.15); }
.darkmode .stat-label { color: #9a7080; }
.darkmode .stat-sub   { color: #7a5060; }
.darkmode .inv-table-wrap { background: #1e0e14; border-color: rgba(200,120,140,0.12); }
.darkmode .inv-section-label { background: #140810; color: #9a7080; }
.darkmode .inv-table thead tr { background: #3a0e1e; }
.darkmode .inv-table tbody tr:hover { background: #241018; }
.darkmode .inv-table tbody tr { border-color: rgba(200,120,140,0.08); }
.darkmode .inv-table td { color: #e8d8d0; }
.darkmode .wine-name { color: #e8d8d0; }
.darkmode .price-cell { color: #e0a0b0; }
.darkmode .inv-search { background: #1a0c12; border-color: rgba(200,120,140,0.2); border-right: none; }
.darkmode .inv-search input { color: #e8d8d0; }
.darkmode .modal { background: #1e0e14; color: #e8d8d0; }
.darkmode .modal .close-btn { color: #9a7080; }
.darkmode .btn-secondary { background: #333; color: #ccc; }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .inv-topbar { padding: 12px 16px; }
    .inv-body { padding: 0 16px; margin-top: 20px; }
    .inv-stats { grid-template-columns: repeat(2, 1fr); }
    .inv-toolbar { flex-direction: column; align-items: stretch; }
    .inv-search { max-width: 100%; }
}

@media (max-width: 500px) {
    .inv-stats { grid-template-columns: 1fr 1fr; }
    .inv-table thead th:nth-child(4) { display: none; }
    .inv-table td:nth-child(4) { display: none; }
}

</style>

</head>

<body>

<?php include 'header.php'; ?>

<div class="inv-body">

    <!-- Page header -->
    <div class="inv-page-header">
        <h1 class="inv-page-title">Stock Management</h1>
        <div class="inv-page-actions">
            <a href="admin.php" class="btn btn-outline-dark">↩ Admin Dashboard</a>
            <a href="newWine.php" class="btn btn-gold">+ Add New Wine</a>
        </div>
    </div>

    <?php
        include '..\..\database\db_connect.php';

        /* ── Stats ── */
        $totalWines  = 0;
        $totalStock  = 0;
        $lowStock    = 0;
        $outOfStock  = 0;

        $statsResult = $conn->query("SELECT Stock FROM wines WHERE active = 1");
        if ($statsResult) {
            while ($sRow = $statsResult->fetch_assoc()) {
                $totalWines++;
                $totalStock += (int)$sRow['Stock'];
                if ((int)$sRow['Stock'] === 0) $outOfStock++;
                elseif ((int)$sRow['Stock'] < 10) $lowStock++;
            }
        }
    ?>

    <!-- Stat cards -->    <div class="inv-stats">
        <div class="stat-card">
            <div class="stat-label">Total Wines</div>
            <div class="stat-value"><?= $totalWines ?></div>
            <div class="stat-sub">active listings</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Stock</div>
            <div class="stat-value"><?= number_format($totalStock) ?></div>
            <div class="stat-sub">bottles available</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Low Stock</div>
            <div class="stat-value" style="color:#b88000;"><?= $lowStock ?></div>
            <div class="stat-sub">wines below 10 units</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Out of Stock</div>
            <div class="stat-value" style="color:#b01c1c;"><?= $outOfStock ?></div>
            <div class="stat-sub">wines unavailable</div>
        </div>
    </div>

    <!-- Toolbar: search -->
    <form method="GET" class="inv-toolbar">
        <div class="inv-search">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search wine name…"
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <button type="submit" class="inv-search-btn">Search</button>
    </form>

    <!-- Table -->
    <?php
        $sql = "SELECT wineId, imageUrl, wineName, price, Stock FROM wines WHERE active = 1";

        if (!empty($_GET['search'])) {
            $search = "%" . $conn->real_escape_string($_GET['search']) . "%";
            $sql .= " AND wineName LIKE '$search'";
        }

        $result     = $conn->query($sql);
        $totalCount = $result ? $result->num_rows : 0;
    ?>

    <div class="inv-table-wrap">
        <div class="inv-section-label"><?= $totalCount ?> wine<?= $totalCount !== 1 ? 's' : '' ?> listed</div>

        <table class="inv-table">
            <thead>
                <tr>
                    <th>Wine</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if (!$result || $totalCount === 0) {
                    echo "<tr><td colspan='4' style='padding:24px 16px; color:#8a5a60; font-size:13px;'>No wines found.</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        $stock = (int)$row['Stock'];

                        if ($stock === 0) {
                            $badgeClass = 'stock-out';
                            $badgeText  = 'Out of stock';
                        } elseif ($stock < 10) {
                            $badgeClass = 'stock-low';
                            $badgeText  = $stock . ' in stock';
                        } else {
                            $badgeClass = 'stock-ok';
                            $badgeText  = $stock . ' in stock';
                        }

                        echo "<tr>";
                        echo "<td>
                                <div class='wine-cell'>
                                    <img class='wine-img'
                                         src='../../images/" . htmlspecialchars($row['imageUrl']) . "'
                                         alt='" . htmlspecialchars($row['wineName']) . "'>
                                    <div>
                                        <div class='wine-name'>" . htmlspecialchars($row['wineName']) . "</div>
                                    </div>
                                </div>
                              </td>";
                        echo "<td><span class='price-cell'>£" . htmlspecialchars($row['price']) . "</span></td>";
                        echo "<td><span class='stock-badge $badgeClass'><span class='stock-dot'></span>$badgeText</span></td>";
                        echo "<td>
                                <form action='redirect.php?page=inventory' method='POST' style='display:inline'>
                                    <input type='hidden' name='wineId' value='" . (int)$row['wineId'] . "'>
                                    <div class='action-btns'>
                                        <button type='submit' name='action' value='update' class='act-btn act-edit'>Edit</button>
                                        <button type='button' class='act-btn act-del'
                                            onclick=\"openDeleteModal('" . (int)$row['wineId'] . "', '" . htmlspecialchars($row['wineName'], ENT_QUOTES) . "')\">
                                            Remove
                                        </button>
                                    </div>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                }
            ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Delete modal -->
<div class="modal-backdrop" id="deleteWineModal">
    <div class="modal">
        <button class="close-btn" onclick="closeDeleteModal()">&#times;</button>
        <h2>Delete Wine</h2>
        <p class="delete-warning">⚠ This action is permanent and cannot be undone.</p>
        <p style="text-align:center; margin-bottom:8px; font-size:14px;">
            Are you sure you want to remove <strong id="deleteWineName"></strong> from inventory?
        </p>
        <form method="POST" action="redirect.php?page=inventory">
            <input type="hidden" name="wineId" id="deleteWineId">
            <input type="hidden" name="action" value="remove">
            <input type="hidden" name="confirm" value="yes">
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn-danger">Yes, Delete Wine</button>
            </div>
        </form>
    </div>
</div>

<script>
function openDeleteModal(wineId, wineName) {
    document.getElementById('deleteWineId').value = wineId;
    document.getElementById('deleteWineName').textContent = wineName;
    document.getElementById('deleteWineModal').classList.add('open');
}

function closeDeleteModal() {
    document.getElementById('deleteWineModal').classList.remove('open');
}

document.getElementById('deleteWineModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>