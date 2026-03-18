<?php

session_start();

$error = $_SESSION['register_error'] ?? "";
unset($_SESSION["register_error"]);

if (isset($_SESSION['customerID'])) {
    include '..\..\database\db_connect.php';
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
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { background-color: var(--background-colour); }

        .container {
            margin: 0 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 62px);
            padding: 40px 0;
        }

        .form-box {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: var(--frame-colour);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 { font-size: 34px; text-align: center; margin-bottom: 20px; color: var(--text-colour); }

        label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-colour); }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            background: var(--background-colour);
            border-radius: 6px;
            border: 1px solid var(--border-colour);
            outline: none;
            font-size: 16px;
            color: var(--text-colour);
            margin-bottom: 20px;
            font-family: inherit;
        }

        textarea { resize: vertical; min-height: 90px; }

        button[type="submit"] {
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

        button[type="submit"]:hover { filter: brightness(0.8); }

        .error-message {
            padding: 12px;
            background: red;
            border-radius: 6px;
            font-size: 16px;
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <div class="form-box" id="signup-form">
            <form action="new_Wine.php" method="post" enctype="multipart/form-data">
                <h2>New Wine</h2>
                <?= showError($error); ?>
                <label for="wineName">Wine Name:</label>
                <input type="text" name="wineName" placeholder="Wine Name" required>
                <label for="wineRegion">Wine Region:</label>
                <input type="text" name="wineRegion" placeholder="Wine Region" required>
                <label for="ingredients">Ingredients:</label>
                <textarea name="ingredients" placeholder="Ingredients" required></textarea>
                <label for="country">Country:</label>
                <select name="country" id="country">
                    <option value="France">France</option>
                    <option value="Italy">Italy</option>
                    <option value="Portugal">Portugal</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Australia">Australia</option>
                    <option value="United States">United States</option>
                </select>
                <label for="category">Category:</label>
                <select name="category" id="category">
                    <option value="Red Wine">Red Wine</option>
                    <option value="White Wine">White Wine</option>
                    <option value="Rosé Wine">Rosé Wine</option>
                    <option value="Dessert Wine">Dessert Wine</option>
                    <option value="Sparkling Wine">Sparkling Wine</option>
                    <option value="Fortified Wine">Fortified Wine</option>
                </select>
                <label for="price">Price (£):</label>
                <input type="number" id="price" name="price" min="0" step="0.01" placeholder="0.00" inputmode="decimal" required>
                <label for="description">Description:</label>
                <textarea name="description" placeholder="Description" required></textarea>
                <label for="imageUpload">Image:</label>
                <input type="file" id="imageUpload" name="image" accept="image/*" required>
                <label for="stock">Stock Quantity:</label>
                <input type="number" min="0" name="stock" value="0" required>
                <button type="submit" name="create">Create Wine</button>
            </form>
        </div>
    </div>

<<<<<<< HEAD
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
=======
    <?php include 'footer.php'; ?>

>>>>>>> 6508b9a64b176aa971c1a2447054d68d7f8356d6
</body>
</html>