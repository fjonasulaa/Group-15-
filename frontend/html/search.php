<?php
if (isset($_POST['submitted'])) {

    require_once('..\..\database\db_connect.php');

    $search = "%{$_POST['search']}%";

    $stat = $conn->prepare("SELECT * FROM wines WHERE wineName LIKE ? OR wineRegion LIKE ? OR category LIKE ?");
    $stat->bind_param("sss", $search, $search, $search);
    $stat->execute();
    $result = $stat->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='box'>";
            echo "<p><strong>" . $row['category'] . "</strong></p>\n";
            echo "<p><a href='wineinfo.php?rid=" . $row['wineId'] . "'>" . $row['wineName'] . "</a> (" . $row['price'] . ")</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No wines found for '{$_POST['search']}'.</p>\n";
    }

    $stat->close();
    $conn->close();
}
?>
































