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

        .box {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 480px; /* allows growth */
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

/* Keep image size consistent */
.box img {
    width: 100%;
    height: 240px;  /* SAME IMAGE HEIGHT */
    object-fit: cover;
}

/* Text layout */
.box-text {
    padding: 15px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.price {
    margin-top: auto; /* pushes price to bottom */
    font-weight: bold;
}




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

        /* WISHLIST ITEMS */

#wishlist-items{
    display:flex;
    flex-direction:column;
    gap:15px;
    margin-top:20px;
}

.wishlist-item{
    display:flex;
    gap:12px;
    align-items:center;
    background:white;
    border-radius:10px;
    padding:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    position:relative;
}

.wishlist-img{
    width:65px;
    height:65px;
    object-fit:cover;
    border-radius:8px;
}

.wishlist-info{
    flex:1;
}

.wishlist-name{
    font-weight:600;
    font-size:14px;
    margin-bottom:4px;
}

.wishlist-price{
    color:#7b1e3a;
    font-weight:bold;
    margin-bottom:8px;
}

.wishlist-actions{
    display:flex;
    gap:8px;
}

.wishlist-view{
    padding:4px 10px;
    font-size:12px;
    border-radius:6px;
    background:#eee;
    text-decoration:none;
    color:#333;
}

.wishlist-view:hover{
    background:#ddd;
}

.wishlist-basket{
    border:none;
    background:#7b1e3a;
    color:white;
    padding:4px 8px;
    border-radius:6px;
    cursor:pointer;
}

.wishlist-basket:hover{
    background:#5e152c;
}

.remove-wishlist{
    position:absolute;
    top:6px;
    right:6px;
    border:none;
    background:none;
    font-size:14px;
    cursor:pointer;
    color:#999;
}

.remove-wishlist:hover{
    color:red;
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
.wishlist-button {
    padding: 8px 14px;
    border: 2px solid #e63946;
    background: white;
    color: #e63946;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.wishlist-button i {
    margin-right: 5px;
}

.wishlist-button:hover {
    background-color: #e63946;
    color: white;
}
.wishlist-button.active {
    background-color: #e63946;
    color: white;
}

/* WISHLIST NAV HEART */

.wishlist-nav-button{
    background:none;
    border:none;
    font-size:20px;
    cursor:pointer;
    color:#e63946;
    margin-left:10px;
}

/* SIDEBAR */

.wishlist-sidebar{
    position:fixed;
    top:0;
    right:-420px;
    width:380px;
    height:100%;
    background:#f4f1f2;
    padding:30px;
    box-shadow:-5px 0 20px rgba(0,0,0,0.25);
    transition:right 0.4s ease;
    z-index:2000;
    overflow-y:auto;
}

.wishlist-sidebar.active{
    right:0;
}

.wishlist-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.5);
    display:none;
    z-index:1500;
}

.wishlist-overlay.active{
    display:block;
}

.close-wishlist{
    font-size:22px;
    cursor:pointer;
    text-align:right;
    margin-bottom:15px;
}
/* DARK MODE WISHLIST */

html.darkmode .wishlist-sidebar{
    background:#141414;
    color:#ffffff;
}

html.darkmode .wishlist-sidebar h3{
    color:#ffffff;
}

html.darkmode #wishlist-items p{
    color:#dddddd;
}

html.darkmode .wishlist-item{
    background:#1f1f1f;
    border:1px solid #333;
}

html.darkmode .wishlist-name{
    color:#ffffff;
}

html.darkmode .wishlist-price{
    color:#ff6b6b;
}

html.darkmode .wishlist-view{
    background:#333;
    color:#ffffff;
}

html.darkmode .wishlist-view:hover{
    background:#444;
}

html.darkmode .remove-wishlist{
    color:#bbbbbb;
}

html.darkmode .remove-wishlist:hover{
    color:#ff4d4d;
}

/* DARK MODE WISHLIST STYLES */

html.darkmode .wishlist-sidebar{
    background:#121212;
    color:#ffffff;
}

/* cards */
html.darkmode .wishlist-item{
    background:#1e1e1e;
    border:1px solid #333;
    box-shadow:none;
}

/* wine name */
html.darkmode .wishlist-name{
    color:#ffffff;
}

/* price */
html.darkmode .wishlist-price{
    color:#ff6b6b;
}

/* buttons */
html.darkmode .wishlist-view{
    background:#2c2c2c;
    color:#ffffff;
}

html.darkmode .wishlist-view:hover{
    background:#3a3a3a;
}

html.darkmode .wishlist-basket{
    background:#e63946;
}

html.darkmode .wishlist-basket:hover{
    background:#c92d3a;
}

/* remove X */
html.darkmode .remove-wishlist{
    color:#bbbbbb;
}

html.darkmode .remove-wishlist:hover{
    color:#ff4d4d;
}

/* empty text */
html.darkmode #wishlist-items p{
    color:#cccccc;
}

/* WISHLIST COUNTER BADGE */

.wishlist-nav-button{
    position:relative;
}

.wishlist-count{
    position:absolute;
    top:-6px;
    right:-8px;
    background:#e63946;
    color:white;
    font-size:11px;
    font-weight:bold;
    padding:2px 6px;
    border-radius:50px;
    min-width:18px;
    text-align:center;
}

    </style>
</head>

<body>

<body class="info">
    <div class="wishlist-overlay" id="wishlistOverlay"></div>

<div class="wishlist-sidebar" id="wishlistSidebar">

    <div class="close-wishlist" id="closeWishlist">
        <i class="fa fa-times"></i>
    </div>

    <h3>Your Wishlist</h3>

    <div id="wishlist-items">
        <p>Your wishlist is empty.</p>
    </div>

</div>

<div class="filter-overlay" id="filterOverlay"></div>
<div class="navbar">
    <a href="index.html"><img src="../../images/icon.png" alt="Logo"></a>

    <div class="navbar-links">
        <a href="index.html">Home</a>
        <a href="about.html">About Us</a>
        <a href="search.php">Wines</a>
        <a href="basket.php">Basket</a>
        <a href="contact-us.php">Contact Us</a>
        
    </div>

    <div class="navbar-right">
        
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search"
                value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>">
        </form>

        <a href="log-in.php">Login</a>
        <a href="signup.php">Sign up</a>
        <a href="account.php">Account</a>
        <button id="dark-mode" class="dark-mode-button">
        <img src="../../images/darkmode.png" alt="Dark Mode" />
      </button>

      <button id="wishlist-toggle" class="wishlist-nav-button">
                <i class="fas fa-heart"></i>
                <span id="wishlist-count" class="wishlist-count">0</span>
            </button>
    </div>
</div>


<div class="top-filter-bar">
    <div class="close-filter" id="closeFilter">
    <i class="fa fa-times"></i>
</div>
    <div class="filter-title">Filter & Sort Wines</div>

    <form method="POST" class="filter-form">

        <!-- remembers the search value after filtering -->
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
            <label>Country</label>
            <select name="region">
                <option value="">All Countries</option>
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
            <input type="number" name="min_price" min = '0'
                value="<?= isset($_POST['min_price']) ? htmlspecialchars($_POST['min_price']) : '' ?>">
        </div>

        <div class="filter-group">
            <label>Max Price (£)</label>
            <input type="number" name="max_price" min = '0'
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

// search bar
if (!empty($_POST['search'])) {
    $query .= " AND (wineName LIKE ? OR category LIKE ? OR country LIKE ?)";
    $searchTerm = "%" . $_POST['search'] . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

// filters
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

//Excludes deleted wines from search results
$query .= " AND active = TRUE";

// sort
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

<div style="padding:20px 40px;">
    <button id="openFilter" class="filter-btn">
        <i class="fa fa-sliders"></i> Filter
    </button>
</div>
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
        echo "<p class='price'>£ " . htmlspecialchars($row['price']) . "</p>";
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


<script>
const loggedIn = <?php echo isset($_SESSION['customerID']) ? "true" : "false"; ?>;

    // DARK MODE
    const darkButton = document.getElementById("dark-mode");
    if (localStorage.getItem("dark_mode") === "on") {
      document.documentElement.classList.add("darkmode");
    }

    darkButton.addEventListener("click", () => {
      document.documentElement.classList.toggle("darkmode");
      localStorage.setItem("dark_mode", document.documentElement.classList.contains("darkmode") ? "on" : "off");
    });

    // filter side bar script
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


    const wishlistBtn = document.getElementById("wishlist-toggle");
        const wishlistSidebar = document.getElementById("wishlistSidebar");
        const closeWishlist = document.getElementById("closeWishlist");
        const wishlistOverlay = document.getElementById("wishlistOverlay");

        wishlistBtn.addEventListener("click", () => {
            wishlistSidebar.classList.add("active");
            wishlistOverlay.classList.add("active");
        });

        closeWishlist.addEventListener("click", () => {
            wishlistSidebar.classList.remove("active");
            wishlistOverlay.classList.remove("active");
        });

        wishlistOverlay.addEventListener("click", () => {
            wishlistSidebar.classList.remove("active");
            wishlistOverlay.classList.remove("active");
        });

        const wishlistContainer = document.getElementById("wishlist-items");
const wishlistCount = document.getElementById("wishlist-count");

function getGuestWishlist(){
    return JSON.parse(localStorage.getItem("wishlist")) || [];
}

function saveGuestWishlist(list){
    localStorage.setItem("wishlist",JSON.stringify(list));
}

function loadWishlist(){

    if(loggedIn){

        fetch("get_wishlist.php")
        .then(res => res.json())
        .then(data => {
            renderWishlist(data);
        });

    }else{

        const list = getGuestWishlist();
        renderWishlist(list);

    }

}

function renderWishlist(list){

    wishlistContainer.innerHTML="";

    if(list.length===0){
        wishlistContainer.innerHTML="<p>Your wishlist is empty.</p>";
        wishlistCount.textContent=0;
        return;
    }

    wishlistCount.textContent=list.length;

    list.forEach((wine,index)=>{

        let image;

        if(loggedIn){
            image = wine.imageUrl
                ? "../../images/" + wine.imageUrl
                : "../../images/placeholder.jpg";
        }else{
            image = wine.imageUrl || "../../images/placeholder.jpg";
        }

        const item=document.createElement("div");
        item.className="wishlist-item";

        item.innerHTML=`
        <img src="${image}" class="wishlist-img">

        <div class="wishlist-info">
            <div class="wishlist-name">${wine.wineName || wine.name}</div>
            <div class="wishlist-price">£${wine.price}</div>

            <div class="wishlist-actions">
                <a href="wineinfo.php?id=${wine.id || wine.wineId}" class="wishlist-view">View</a>
            </div>
        </div>

        <button class="remove-wishlist" data-id="${wine.wineId || wine.id}" data-index="${index}">
            <i class="fas fa-times"></i>
        </button>
        `;

        wishlistContainer.appendChild(item);
    });

}

document.addEventListener("click",function(e){

    const removeBtn=e.target.closest(".remove-wishlist");
    if(!removeBtn) return;

    const wineId=removeBtn.dataset.id;
    const index=removeBtn.dataset.index;

    if(loggedIn){

        fetch("remove_from_wishlist.php",{
            method:"POST",
            headers:{"Content-Type":"application/x-www-form-urlencoded"},
            body:"wineId="+wineId
        })
        .then(()=> loadWishlist());

    }else{

        let list=getGuestWishlist();
        list.splice(index,1);
        saveGuestWishlist(list);
        renderWishlist(list);

    }

});
loadWishlist();
  </script>

</body>
</html>

<style>
/* Footer styling */
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
</style>

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
        <li><a href="index.html">Home</a></li>
        <li><a href="wines.html">Wines</a></li>
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


