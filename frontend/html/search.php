<?php
session_start();
require_once('../../database/db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wine Exchange</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/searchStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            padding: 0;
        }

        .top-filter-bar {
            position: fixed;
            top: 0;
            right: -420px;
            width: 380px;
            height: 100%;
            background: #f4f1f2;
            padding: 30px;
            box-shadow: -5px 0 20px rgba(0,0,0,0.25);
            transition: right 0.4s ease;
            z-index: 2000;
            overflow-y: auto;
        }

        

        .top-filter-bar.active {
            right: 0;
        }

        .filter-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 1500;
        }

        .filter-overlay.active {
            display: block;
        }

        .close-filter {
            font-size: 22px;
            cursor: pointer;
            text-align: right;
            margin-bottom: 15px;
        }

        .filter-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #7b1e3a;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 6px;
        }

        .filter-form input,
        .filter-form select {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            background: white;
            color: black;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            background: #7b1e3a;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            border: 2px solid #7b1e3a;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            line-height: 1;
            height: 42px;
        }

        .reset-btn {
            background: #e0e0e0;
            padding: 12px 20px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        .results-header {
            padding: 20px 40px 10px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .box-container {
            padding: 10px 40px 40px;
        }

        .box {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 480px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .box img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .box-text {
            padding: 15px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .price {
            margin-top: auto;
            font-weight: bold;
        }

        /* DARK MODE */
        .darkmode body {
            background: #121212;
            color: #ffffff;
        }

        .darkmode .top-filter-bar {
            background: #1e1e1e;
        }

        .darkmode .filter-title {
            color: #ffffff;
        }

        .darkmode .filter-group label {
            color: #dddddd;
        }

        .darkmode .filter-form input,
        .darkmode .filter-form select {
            background: #2a2a2a;
            color: #ffffff;
            border: 1px solid #444;
        }

        .darkmode .filter-form input::placeholder {
            color: #bbbbbb;
        }

        .darkmode .filter-btn {
            background: #9b2d52;
        }

        .darkmode .reset-btn {
            background: #444;
            color: white;
        }

        .darkmode .results-header {
            color: #ffffff;
        }

        .darkmode .box {
            background: #1e1e1e;
            border: 1px solid #333;
        }

        .darkmode .box-text p {
            color: #ffffff;
        }

        .darkmode .sort-select,
        .darkmode select[name="sort"] {
            background: #2a2a2a;
            color: #ffffff;
            border: 1px solid #444;
        }

        .sort-dropdown {
            appearance: none;
            -webkit-appearance: none;
            padding: 10px 40px 10px 16px;
            border-radius: 10px;
            border: 2px solid #7b1e3a;
            font-size: 14px;
            font-weight: 600;
            background-color: white;
            color: #7b1e3a;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%237b1e3a' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            outline: none;
            display: block;
            margin: 0;
            height: 42px;
        }

        .sort-dropdown:hover {
            background-color: #7b1e3a;
            color: white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='white' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        }

        .sort-dropdown:focus {
            box-shadow: 0 0 0 3px rgba(123, 30, 58, 0.2);
            border-color: #7b1e3a;
        }
    </style>
</head>

<body class="info">

<?php require_once('header.php'); ?>

<div class="filter-overlay" id="filterOverlay"></div>

<div class="top-filter-bar">
    <div class="close-filter" id="closeFilter">
        <i class="fa fa-times"></i>
    </div>
    <div class="filter-title">Filter & Sort Wines</div>

    <form method="GET" class="filter-form">
        <!-- remembers the search value after filtering -->
        <input type="hidden" name="search"
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">

        <div class="filter-group">
            <label>Wine Type</label>
            <select name="category">
                <option value="">All Types</option>
                <option value="Red Wine" <?= (isset($_GET['category']) && $_GET['category']=="Red Wine") ? "selected" : "" ?>>Red</option>
                <option value="White Wine" <?= (isset($_GET['category']) && $_GET['category']=="White Wine") ? "selected" : "" ?>>White</option>
                <option value="Rosé Wine" <?= (isset($_GET['category']) && $_GET['category']=="Rosé Wine") ? "selected" : "" ?>>Rosé</option>
                <option value="Dessert Wine" <?= (isset($_GET['category']) && $_GET['category']=="Dessert Wine") ? "selected" : "" ?>>Dessert</option>
                <option value="Sparkling Wine" <?= (isset($_GET['category']) && $_GET['category']=="Sparkling Wine") ? "selected" : "" ?>>Sparkling</option>
                <option value="Fortified Wine" <?= (isset($_GET['category']) && $_GET['category']=="Fortified Wine") ? "selected" : "" ?>>Fortified</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Country</label>
            <select name="region">
                <option value="">All Countries</option>
                <option value="France" <?= (isset($_GET['region']) && $_GET['region']=="France") ? "selected" : "" ?>>France</option>
                <option value="Italy" <?= (isset($_GET['region']) && $_GET['region']=="Italy") ? "selected" : "" ?>>Italy</option>
                <option value="Portugal" <?= (isset($_GET['region']) && $_GET['region']=="Portugal") ? "selected" : "" ?>>Portugal</option>
                <option value="South Africa" <?= (isset($_GET['region']) && $_GET['region']=="South Africa") ? "selected" : "" ?>>South Africa</option>
                <option value="Australia" <?= (isset($_GET['region']) && $_GET['region']=="Australia") ? "selected" : "" ?>>Australia</option>
                <option value="United States" <?= (isset($_GET['region']) && $_GET['region']=="United States") ? "selected" : "" ?>>United States</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Min Price (£)</label>
            <input type="number" name="min_price" min="0"
                value="<?= isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '' ?>">
        </div>

        <div class="filter-group">
            <label>Max Price (£)</label>
            <input type="number" name="max_price" min="0"
                value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '' ?>">
        </div>

        <div class="filter-buttons">
            <button type="submit" class="filter-btn">Apply</button>
            <button type="submit" name="reset" class="reset-btn">Reset</button>
        </div>

    </form>
</div>


<?php
if (isset($_GET['reset'])) {
    $searchValue = isset($_GET['search']) ? $_GET['search'] : '';
    $_GET = [];
    if (!empty($searchValue)) {
        $_GET['search'] = $searchValue;
    }
}

$query = "SELECT * FROM wines WHERE 1=1";
$params = [];
$types = "";

if (!empty($_GET['search'])) {
    $query .= " AND (wineName LIKE ? OR category LIKE ? OR country LIKE ?)";
    $searchTerm = "%" . $_GET['search'] . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

if (!empty($_GET['category'])) {
    $query .= " AND category = ?";
    $params[] = $_GET['category'];
    $types .= "s";
}

if (!empty($_GET['region'])) {
    $query .= " AND country = ?";
    $params[] = $_GET['region'];
    $types .= "s";
}

if (!empty($_GET['min_price'])) {
    $query .= " AND price >= ?";
    $params[] = $_GET['min_price'];
    $types .= "d";
}

if (!empty($_GET['max_price'])) {
    $query .= " AND price <= ?";
    $params[] = $_GET['max_price'];
    $types .= "d";
}

$query .= " AND active = TRUE";

if (!empty($_GET['sort'])) {
    switch ($_GET['sort']) {
        case "price_asc":
            $query .= " ORDER BY price ASC";
            break;
        case "price_desc":
            $query .= " ORDER BY price DESC";
            break;
        case "name_asc":
            $query .= " ORDER BY wineName ASC";
            break;
    }
}

// pagination variables
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * 10;
$query .= " LIMIT 10 OFFSET ?";
$params[] = $offset;
$types .= "i";

$stat = $conn->prepare($query);

if (!empty($params)) {
    $stat->bind_param($types, ...$params);
}

$stat->execute();
$result = $stat->get_result();
?>

<div class="results-header">
    <h2 style="margin:0;">Our Wine Collection</h2>

    <div style="display:flex; align-items:center; gap:12px;">
        <button id="openFilter" class="filter-btn">
            <i class="fa fa-sliders"></i> Filter
        </button>

        <form method="GET" style="margin:0;">
            <input type="hidden" name="search"    value="<?= isset($_GET['search'])    ? htmlspecialchars($_GET['search'])    : '' ?>">
            <input type="hidden" name="category"  value="<?= isset($_GET['category'])  ? htmlspecialchars($_GET['category'])  : '' ?>">
            <input type="hidden" name="region"    value="<?= isset($_GET['region'])    ? htmlspecialchars($_GET['region'])    : '' ?>">
            <input type="hidden" name="min_price" value="<?= isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '' ?>">
            <input type="hidden" name="max_price" value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '' ?>">

            <select name="sort" onchange="this.form.submit()" class="sort-dropdown">
                <option value="">Sort By</option>
                <option value="price_asc"  <?= (isset($_GET['sort']) && $_GET['sort']=="price_asc")  ? "selected" : "" ?>>Price: Low to High</option>
                <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort']=="price_desc") ? "selected" : "" ?>>Price: High to Low</option>
                <option value="name_asc"   <?= (isset($_GET['sort']) && $_GET['sort']=="name_asc")   ? "selected" : "" ?>>Name: A to Z</option>
            </select>
        </form>
    </div>
</div>

<div class="box-container">
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<a class='box-link' href='wineinfo.php?id=" . $row['wineId'] . "'>";
        echo "<div class='box'>";
        echo "<img src='../../images/" . htmlspecialchars($row['imageUrl']) . "'>";
        echo "<div class='box-text'>";
        echo "<p><strong>" . htmlspecialchars($row['wineName']) . "</strong></p>";
        echo "<p>" . htmlspecialchars($row['category']) . "</p>";
        echo "<p class='price'>£ " . htmlspecialchars($row['price']) . "</p>";
        echo "</div></div></a>";
    }
} else {
    echo "No wines found.";
}
?>
</div>


<?php
// get filtered total for accurate pagination
$countQuery = str_replace("SELECT *", "SELECT COUNT(*)", $query);
$countQuery = preg_replace('/LIMIT.+$/s', '', $countQuery);
$countStmt = $conn->prepare($countQuery);
$countParams = array_slice($params, 0, -1);
$countTypes = substr($types, 0, -1);

// if there are no filters then skip
if (!empty($countParams)) $countStmt->bind_param($countTypes, ...$countParams);

$countStmt->execute();
$countStmt->bind_result($totalWines);
$countStmt->fetch();
$countStmt->close();

include 'pagination.php';

// call pagination function
renderPagination($totalWines, 10, $_GET);

$stat->close();
$conn->close();
?>



<script>
    // Filter sidebar
    const openBtn = document.getElementById("openFilter");
    const closeBtn = document.getElementById("closeFilter");
    const sidebar = document.querySelector(".top-filter-bar");
    const overlay = document.getElementById("filterOverlay");

    openBtn.addEventListener("click", () => {
        sidebar.classList.add("active");
        overlay.classList.add("active");
    });

    closeBtn.addEventListener("click", () => {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    });

    overlay.addEventListener("click", () => {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    });
</script>

<?php include 'footer.php'; ?>

</body>
</html>
