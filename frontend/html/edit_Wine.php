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

    $imageName= "default.jpg";
    if(isset($_FILES['image'])){  
        $target_dir = "../../images/"; 
        $imageName = time()."_".basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $imageName;
    
    
    	$check = getimagesize($_FILES["image"]["tmp_name"]);
    	if($check !== false) {  
        
        	if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            	//echo "Image uploaded successfully!";
        	} else {
            	//echo "Error uploading image.";
                $imageName = "default.jpg";
        	}
   		} else {
        	//echo "File is not an image.";
            $imageName = "default.jpg";
    	}
    }

    

    $stmt = $conn->prepare("INSERT INTO wines (wineName, wineRegion, ingredients, country, category, price, description, stock, imageUrl) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdiss", $wineName, $wineRegion, $ingredients, $country, $category, $price, $description, $stock, $imageName);
    $stmt->execute();

    header("Location: inventory.php");
    exit();
}


?>