<?php
session_start();
require_once('../../database/db_connect.php');

header('Content-Type: application/json');

if(!isset($_SESSION['customerID'])){
    echo json_encode(["status"=>"guest"]);
    exit;
}

$customerID = $_SESSION['customerID'];
$wineId = intval($_POST['wineId']);

$stmt = $conn->prepare("DELETE FROM wishlist WHERE customerID=? AND wineId=?");
$stmt->bind_param("ii",$customerID,$wineId);
$stmt->execute();

echo json_encode(["status"=>"success"]);