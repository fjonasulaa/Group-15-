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
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            padding-top: 110px;
        }

        .top-filter-bar {
            width: 100%;
            padding: 30px 40px;
            background: #f4f1f2;
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

        .filter-form input,
        .filter-form select {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            background: #7b1e3a;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
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
            padding: 20px 40px;
            font-weight: 600;
        }

        .box-container {
            padding: 40px;
        }
    </style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <a href="index.html"><img src="../../images/icon.png" alt="Logo"></a>

    <div class="navbar-links">
        <a href="index.html">Home</a>
        <a href="about.html">About Us</a>
        <a href="wines.html">Wines</a>
        <a href="basket.php">Basket</a>
        <a href="contact-us.php">Contact Us</a>
    </div>

    <div class="navbar-right">
        <!-- SEARCH FORM -->
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search"
                value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>">
        </form>

        <a href="log-in.php">Login</a>
        <a href="signup.php">Sign up</a>
        <a href="account.php">Account</a>
    </div>
</div>


<!-- ================= FILTER BAR ================= -->
<div class="top-filter-bar">
    <div class="filter-title">Filter & Sort Wines</div>

    <form method="POST" class="filter-form">

        <!-- Keep search value when filtering -->
        <input type="hidden" name="search"
            value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>">

        <div class="filter-group">
            <label>Wine Type</label>
            <select name="category">
                <option value="">All Types</option>
                <option value="Red Wine" <?= (isset($_POST['category']) && $_POST['category']=="Red Wine") ? "selected" : "" ?>>Red</option>
                <option value="White Wine" <?= (isset($_POST['category']) && $_POST['category']=="White Wine") ? "selected" : "" ?>>White</option>
                <option value="Rosé Wine" <?= (isset($_POST['category']) && $_POST['category']=="Rosé Wine") ? "selected" : "" ?>>Rosé</option>
                <option value="Dessert Wine" <?= (isset($_POST['category']) && $_POST['category']=="Dessert Wine") ? "selected" : "" ?>>Dessert</option>
                <option value="Sparkling Wine" <?= (isset($_POST['category']) && $_POST['category']=="Sparkling Wine") ? "selected" : "" ?>>Sparkling</option>
                <option value="Fortified Wine" <?= (isset($_POST['category']) && $_POST['category']=="Fortified Wine") ? "selected" : "" ?>>Fortified</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Region</label>
            <select name="region">
                <option value="">All Regions</option>
                <option value="France" <?= (isset($_POST['region']) && $_POST['region']=="France") ? "selected" : "" ?>>France</option>
                <option value="Italy" <?= (isset($_POST['region']) && $_POST['region']=="Italy") ? "selected" : "" ?>>Italy</option>
                <option value="Portugal" <?= (isset($_POST['region']) && $_POST['region']=="Portugal") ? "selected" : "" ?>>Portugal</option>
                <option value="South Africa" <?= (isset($_POST['region']) && $_POST['region']=="South Africa") ? "selected" : "" ?>>South Africa</option>
                <option value="Australia" <?= (isset($_POST['region']) && $_POST['region']=="Australia") ? "selected" : "" ?>>Australia</option>
                <option value="United States" <?= (isset($_POST['region']) && $_POST['region']=="United States") ? "selected" : "" ?>>United States</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Min Price (£)</label>
            <input type="number" name="min_price"
                value="<?= isset($_POST['min_price']) ? htmlspecialchars($_POST['min_price']) : '' ?>">
        </div>

        <div class="filter-group">
            <label>Max Price (£)</label>
            <input type="number" name="max_price"
                value="<?= isset($_POST['max_price']) ? htmlspecialchars($_POST['max_price']) : '' ?>">
        </div>

        <div class="filter-group">
            <label>Sort By</label>
            <select name="sort">
                <option value="">Default</option>
                <option value="price_asc" <?= (isset($_POST['sort']) && $_POST['sort']=="price_asc") ? "selected" : "" ?>>Price: Low to High</option>
                <option value="price_desc" <?= (isset($_POST['sort']) && $_POST['sort']=="price_desc") ? "selected" : "" ?>>Price: High to Low</option>
                <option value="name_asc" <?= (isset($_POST['sort']) && $_POST['sort']=="name_asc") ? "selected" : "" ?>>Name: A to Z</option>
            </select>
        </div>

        <div class="filter-buttons">
            <button type="submit" class="filter-btn">Apply</button>
            <button type="submit" name="reset" class="reset-btn">Reset</button>
        </div>

    </form>
</div>


<?php
require_once('../../database/db_connect.php');

/* RESET (keep search) */
if (isset($_POST['reset'])) {

    // Save search value
    $searchValue = isset($_POST['search']) ? $_POST['search'] : '';

    $_POST = [];

    // Restore search after resetting filter
    if (!empty($searchValue)) {
        $_POST['search'] = $searchValue;
    }
}

$query = "SELECT * FROM wines WHERE 1=1";
$params = [];
$types = "";

/* SEARCH */
if (!empty($_POST['search'])) {
    $query .= " AND (wineName LIKE ? OR category LIKE ? OR country LIKE ?)";
    $searchTerm = "%" . $_POST['search'] . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

/* FILTERS */
if (!empty($_POST['category'])) {
    $query .= " AND category = ?";
    $params[] = $_POST['category'];
    $types .= "s";
}

if (!empty($_POST['region'])) {
    $query .= " AND country = ?";
    $params[] = $_POST['region'];
    $types .= "s";
}

if (!empty($_POST['min_price'])) {
    $query .= " AND price >= ?";
    $params[] = $_POST['min_price'];
    $types .= "d";
}

if (!empty($_POST['max_price'])) {
    $query .= " AND price <= ?";
    $params[] = $_POST['max_price'];
    $types .= "d";
}

/* SORTING */
if (!empty($_POST['sort'])) {
    switch ($_POST['sort']) {
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

$stat = $conn->prepare($query);

if (!empty($params)) {
    $stat->bind_param($types, ...$params);
}

$stat->execute();
$result = $stat->get_result();
?>

<div class="results-header">
    <?= $result->num_rows ?> wines found
</div>

<div class="box-container">
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<a class='box-link' href='wineinfo.php?id=" . $row['wineId'] . "'>";
        echo "<div class='box'>";
        echo "<img src='../../images/" . htmlspecialchars($row['imageUrl']) . "'>";
        echo "<div class='box-text'>";
        echo "<p><strong>" . htmlspecialchars($row['category']) . "</strong></p>";
        echo "<p>" . htmlspecialchars($row['wineName']) . "</p>";
        echo "<p>£ " . htmlspecialchars($row['price']) . "</p>";
        echo "</div></div></a>";
    }
} else {
    echo "No wines found.";
}
?>
</div>

<?php
$stat->close();
$conn->close();
?>

</body>
</html>
































