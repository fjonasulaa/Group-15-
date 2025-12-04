<?php
if (isset($_POST['submitted'])) {

    require_once('winedb.php');

    $search = $_POST['search'];

    try {
        $query = "SELECT * FROM wines WHERE wineName LIKE ? OR wineRegion LIKE ? OR category LIKE ?";
        $stat = $db->prepare($query);
        $stat->execute(array("%$search%", "%$search%", "%$search%"));

        if ($stat && $stat->rowCount() > 0) {
            while ($row = $stat->fetch()) {
                echo "<div class='box'>";
                echo "<p><strong>" . $row['category'] . "</strong></p>\n";
                echo "<p><a href='wineinfo.php?rid=" . $row['wineId'] . "'>" . $row['wineName'] . "</a> (" . $row['price'] . ")</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No wines found for '$search'.</p>\n";
        }
    } catch (PDOexception $ex) {
        echo "Sorry, a database error occurred! <br>";
        echo "Error details: <em>" . $ex->getMessage() . "</em>";
    }
}
?>
































