<?php
    $servername= "cs2410-web01pvm.aston.ac.uk";
    $username= "cs2team15";
    $password= "yaBl9oDtzOh60RmdXZG64OB7v";
    $dbname= "cs2team15_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: ".$conn->connect_error);
    }
?>