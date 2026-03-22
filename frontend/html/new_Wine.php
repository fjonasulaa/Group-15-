<?php

session_start();
include '../../database/db_connect.php';

if (isset($_POST['create'])) {
    $wineName = $_POST['wineName'];
    $wineRegion = $_POST['wineRegion'];
    $ingredients = $_POST['ingredients'];
    $country = $_POST['country'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];


$imageNames = [];
$upload_time = time();

for ($i = 1; $i <= 4; $i++) {
    $field = "image$i";
    var_dump($_FILES[$field]['name']); // ← add this temporarily
    $imageName = "default.jpg";

    if (isset($_FILES[$field]) && $_FILES[$field]['error'] === 0) {
        $target_dir = "../../images/";
        
        // Use pathinfo on just the name, and grab extension separately
        $originalName = $_FILES[$field]['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        
        $imageName = $upload_time . "_" . $i . "_" . $safeName . "." . $ext;
        $target_file = $target_dir . $imageName;

        $check = getimagesize($_FILES[$field]["tmp_name"]);
        if ($check !== false) {
            if (!move_uploaded_file($_FILES[$field]["tmp_name"], $target_file)) {
                $imageName = "default.jpg";
            }
        } else {
            $imageName = "default.jpg";
        }
    }

    $imageNames[] = $imageName;
}
error_log(print_r($_FILES, true));
    

    $stmt = $conn->prepare("
    INSERT INTO wines 
    (wineName, wineRegion, ingredients, country, category, price, description, imageUrl, img2, img3, img4, stock)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
$stmt->bind_param(
    "sssssdsssssi",
    $wineName,
    $wineRegion,
    $ingredients,
    $country,
    $category,
    $price,
    $description,
    $imageNames[0],
    $imageNames[1],
    $imageNames[2],
    $imageNames[3],
    $stock
);
$stmt->execute();

    header("Location: inventory.php");
    exit();
}


?>