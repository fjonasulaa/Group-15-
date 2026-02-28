<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Results | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/searchStyles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<?php
if (isset($_POST['submitted'])) {

    require_once('..\..\database\db_connect.php');

    $search = "%{$_POST['search']}%";

    $stat = $conn->prepare("SELECT * FROM wines WHERE wineName LIKE ? OR wineRegion LIKE ? OR category LIKE ?");
    $stat->bind_param("sss", $search, $search, $search);
    $stat->execute();
    $result = $stat->get_result();

    if ($result->num_rows > 0) {

        echo "<div class='box-container'>";

        while ($row = $result->fetch_assoc()) {
            if (!$row['active']) {
                continue; // Skip inactive wines
            }
            echo "<a class='box-link' href='wineinfo.php?id=" . $row['wineId'] . "'>";
            echo "<div class='box'>";
            echo "<img src='../../images/" . htmlspecialchars($row['imageUrl']) . "' alt='" . htmlspecialchars($row['wineName']) . "'>";
            echo "<div class='box-text'>";
            echo "<p><strong>" . htmlspecialchars($row['category']) . "</strong></p>";
            echo "<p>" . htmlspecialchars($row['wineName']) . "</p>";
            echo "<p>£ " . htmlspecialchars($row['price']) . "</p>";
            echo "</div>";
            echo "</div>";
            echo "</a>";
        }
        echo "</div>";

    } else {
        echo "<p>No wines found for '{$_POST['search']}'.</p>\n";
    }

    $stat->close();
    $conn->close();
}
?>
































