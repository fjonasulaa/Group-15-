<?php

session_start();
require_once('../../database/db_connect.php');

if (isset($_POST['create'])) {
    $wineName = $_POST['wineName'];
    $wineRegion = $_POST['wineRegion'];
    $ingredients = $_POST['ingredients'];
    $country = $_POST['country'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    $imageName = $_POST['existingImage'];


    if (!empty($_FILES['image']['name'])) {

        $target_dir = "../../images/";
        $newName = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $newName;

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $imageName = $newName; // Replace old image
            }
        }
    }


    

    $stmt = $conn->prepare("
        UPDATE wines 
        SET wineName=?, wineRegion=?, ingredients=?, country=?, category=?, price=?, description=?, stock=?, imageUrl=?
        WHERE wineId=?
    ");
    $stmt->bind_param("sssssdsisi", $wineName, $wineRegion, $ingredients, $country, $category, $price, $description, $stock, $imageName, $_POST['wineId']);

    $stmt->execute();


    header("Location: inventory.php");
    exit();
}


?>