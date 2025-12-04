<?php
require_once('../../database/db_connect.php');

if (!isset($_GET['id'])) {
    echo "No wine selected.";
    exit;
}

$wineId = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM wines WHERE wineId = ?");
$stmt->bind_param("i", $wineId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Wine not found.";
    exit;
}

$wine = $result->fetch_assoc();

$mainImage = $wine['imageUrl'] 
    ? "../../images/" . $wine['imageUrl'] 
    : "../../images/placeholder.jpg";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($wine['wineName']); ?> - Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="info">

    <!-- NAVIGATION -->
    <div class="navbar">
        <img src="../../images/icon.png" alt="Wine Exchange Logo">
        <div class="navbar-links">
            <a href="index.html">Home</a>
            <a href="about.html">About Us</a>
            <a href="wines.html">Wines</a>
            <a href="basket.html">Basket</a>
        </div>

        <div class="navbar-right">
            <form method= "POST" action = "search.php">
                <input type="text" name="search" placeholder="Search">

                <input type= "hidden" name= "submitted" value= "true"/>
            </form>
            <a href="log-in.php">Login</a>
            <a href="signup.php">Sign up</a>
            <button id="dark-mode" class="dark-mode-button">
                <img src="../../images/darkmode.png" alt="Dark Mode" />
            </button>
        </div>
    </div>

    <div class="separator info"></div>

    <div class="wrap-cards">
        <div class="info-card">

            <!-- SINGLE MAIN IMAGE -->
            <div class="images">
                <div class="front-image">
                    <img src="<?php echo $mainImage; ?>" 
                         alt="<?php echo htmlspecialchars($wine['wineName']); ?>" 
                         style="width:100%; border-radius: 10px;">
                </div>
            </div>

            <div class="content">
                <h2 class="title"><?php echo htmlspecialchars($wine['wineName']); ?></h2>

                <div class="price">
                    <p class="price">Price: <span>£<?php echo number_format($wine['price'], 2); ?></span></p>
                </div>

                <div class="purchase">
                    <input type="number" min="1" value="1">
                    <button type="button" class="button">
                        Add to Basket <i class="fas fa-shopping-cart"></i>
                    </button>
                </div>

                <div class="details">
                    <h2>Details:</h2>
                    <p><?php echo nl2br(htmlspecialchars($wine['description'])); ?></p>
                </div>

                <div class="container">
                    <div class="image-container">
                        <article class="image-article">
                            <img src="../../images/ingredientsBG.jpg" 
                                 alt="ingredientsBG" 
                                 class="image-card">
                            <div class="hover-data">
                                <span class="ingredients"><?php echo htmlspecialchars($wine['ingredients']); ?></span>
                            </div>
                        </article>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // DARK MODE
        const darkButton = document.getElementById("dark-mode");
        if (localStorage.getItem("dark_mode") === "on") {
            document.documentElement.classList.add("darkmode");
        }
        darkButton.addEventListener("click", () => {
            document.documentElement.classList.toggle("darkmode");
            localStorage.setItem(
                "dark_mode", 
                document.documentElement.classList.contains("darkmode") ? "on" : "off"
            );
        });
    </script>
</body>
</html>
