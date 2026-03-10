<?php
session_start();
require_once('../../database/db_connect.php');

header('Content-Type: application/json');

if(!isset($_SESSION['customerID'])){
    echo json_encode([]);
    exit;
}

$customerID = $_SESSION['customerID'];

$stmt = $conn->prepare("
SELECT w.wineId, w.wineName, w.price, w.imageUrl
FROM wishlist wl
JOIN wines w ON wl.wineId = w.wineId
WHERE wl.customerID = ?
");

$stmt->bind_param("i",$customerID);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);