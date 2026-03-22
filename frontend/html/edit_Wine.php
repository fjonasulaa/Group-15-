<?php
session_start();
require_once('../../database/db_connect.php');

if (isset($_POST['create'])) {

    $wineId      = $_POST['wineId'];
    $wineName    = $_POST['wineName'];
    $wineRegion  = $_POST['wineRegion'];
    $ingredients = $_POST['ingredients'];
    $country     = $_POST['country'];
    $category    = $_POST['category'];
    $price       = $_POST['price'];
    $description = $_POST['description'];
    $stock       = $_POST['stock'];

    $imageNames = [];

    for ($i = 1; $i <= 4; $i++) {

        $field    = "image$i";
        $existing = $_POST["existingImage$i"];
        $imageName = $existing;

        if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === 0) {

            $target_dir = "../../images/";
            $newName = time() . "_{$i}_" . basename($_FILES[$field]["name"]);
            $target_file = $target_dir . $newName;

            $check = getimagesize($_FILES[$field]["tmp_name"]);
            if ($check !== false && move_uploaded_file($_FILES[$field]["tmp_name"], $target_file)) {
                $imageName = $newName;
            }
        }

        $imageNames[] = $imageName;
    }

    $stmt = $conn->prepare("
        UPDATE wines
        SET wineName=?, wineRegion=?, ingredients=?, country=?, category=?, price=?, description=?, stock=?,
            imageUrl=?, img2=?, img3=?, img4=?
        WHERE wineId=?
    ");

    $stmt->bind_param(
        "sssssdsissssi",
        $wineName,
        $wineRegion,
        $ingredients,
        $country,
        $category,
        $price,
        $description,
        $stock,
        $imageNames[0],
        $imageNames[1],
        $imageNames[2],
        $imageNames[3],
        $wineId
    );

    $stmt->execute();
    header("Location: inventory.php");
    exit();
}
?>