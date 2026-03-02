<?php

session_start();

include '../../database/db_connect.php';

if (!isset($_GET['id'])) {
    die("No wine selected.");
}

$wineId = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM wines WHERE wineId = ?");
$stmt->bind_param("i", $wineId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Wine not found.");
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
    <link rel="stylesheet" href="../css/styles.css" />

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: var(--background-colour);
            padding-top: 100px;
        }
        .container {
            margin: 0 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-box {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: var(--frame-colour);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 34px;
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            background: var(--background-colour);
            border-radius: 6px;
            border: none;
            outline: none;
            font-size: 16px;
            color: var(--text-colour);
            margin-bottom: 20px;
            border: 1px solid var(--border-colour);
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--primary-colour);
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            margin-bottom: 20px;
            transition: 0.5s;
        }

        button:hover {
            filter: brightness(0.8);
        }

        p {
            font-size: 14.5px;
            text-align: center;
            margin-bottom: 10px;
        }

        p a {
            color: var(--primary-colour);
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .error-message {
            padding: 12px;
            background: red;
            border-radius: 6px;
            font-size: 16px;
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

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
</head>
<body>

    <!-- NAVBAR -->
  <div class="navbar">
    <img src="../../images/icon.png" alt="Wine Exchange Logo">
    <div class="navbar-links">
      <a href="index.html">Home</a>
      <a href="about.html">About Us</a>
      <a href="wines.html">Wines</a>
      <a href="basket.php">Basket</a>
      <a href="contact-us.php">Contact Us</a>
    </div>

    <div class="navbar-right">
      <form method= "POST" action = "search.php">
            <input type="text" name="search" placeholder="Search">

            <input type= "hidden" name= "submitted" value= "true"/>
      </form>
      <a href="log-in.php">Login</a>
      <a href="signup.php">Sign up</a>
      <a href="account.php">Account</a>
      <button id="dark-mode" class="dark-mode-button">
        <img src="../../images/darkmode.png" alt="Dark Mode" />
      </button>
    </div>
  </div>
    <div class="container">
        <div class="form-box" id="signup-form">
            <form action="edit_Wine.php" method ="post" enctype="multipart/form-data">
                <h2>Edit Wine</h2>
                <?= showError($error); ?>
                <input type="hidden" name="wineId" value="<?= $wine['wineId'] ?>">
                <label for="wineName">Wine Name:</label>
                <input type="text" name="wineName" placeholder="Wine Name" value="<?= htmlspecialchars($wine['wineName']) ?>" required>
                <label for="wineRegion">Wine Region:</label>
                <input type="text" name="wineRegion" placeholder="Wine Region" value="<?= htmlspecialchars($wine['wineRegion']) ?>" required>
                <label for="ingredients">Ingredients:</label>
                <textarea name="ingredients" placeholder="Ingredients" required><?= htmlspecialchars($wine['ingredients']) ?></textarea>
                <label for="country">Country:</label>
                <input type="text" name="country" placeholder="Country" value="<?= htmlspecialchars($wine['country']) ?>">
                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <option value="Red Wine" <?= ($wine['category'] == "Red Wine") ? "selected" : "" ?>>Red Wine</option>
                    <option value="White Wine" <?= ($wine['category'] == "White Wine") ? "selected" : "" ?>>White Wine</option>
                    <option value="Rosé Wine" <?= ($wine['category'] == "Rosé Wine") ? "selected" : "" ?>>Rosé Wine</option>
                    <option value="Dessert Wine" <?= ($wine['category'] == "Dessert Wine") ? "selected" : "" ?>>Dessert Wine</option>
                    <option value="Sparkling Wine" <?= ($wine['category'] == "Sparkling Wine") ? "selected" : "" ?>>Sparkling Wine</option>
                    <option value="Fortified Wine" <?= ($wine['category'] == "Fortified Wine") ? "selected" : "" ?>>Fortified Wine</option>
                </select>
                <label for="price">Price (£):</label>
                <input type="number" id="price" name="price" min="0" step="0.01" placeholder="0.00" inputmode="decimal" value="<?= htmlspecialchars($wine['price']) ?>" required>
                <label for="description">Description:</label>
                <textarea name="description" placeholder="Description" required><?= htmlspecialchars($wine['description']) ?></textarea>
                <input type="hidden" name="existingImage" value="<?= htmlspecialchars($wine['imageUrl']) ?>">
                <label for="image">Image:</label>
                <?php if (!empty($wine['imageUrl'])): ?>
                  <p>Current Image:</p>
                  <img src="../../images/<?= htmlspecialchars($wine['imageUrl']) ?>" 
                        alt="Current Wine Image" 
                        style="width:150px; border-radius:6px; display: block; margin-left: auto; margin-right: auto; margin-bottom: 20px;">
                <?php endif; ?>
                <input type="file" id="imageUpload" name="image" accept="image/*">
                <label for="stock">Stock Quantity:</label>
                <input type='number' min = '0' name = 'stock' value= '<?= htmlspecialchars($wine['stock']) ?>' required>
                <button type="submit" name="create">Edit Wine</button>
            </form>
        </div>
    </div>

    <!-- footer -->
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
      © 2024 Wine Exchange. All rights reserved.
    </div>
  </footer>

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