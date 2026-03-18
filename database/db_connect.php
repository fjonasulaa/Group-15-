<?php
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "winedb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: ".$conn->connect_error);
    }
    $conn->set_charset("utf8mb4");

    //If testing db functionality locally, swap credentials to ones below,
    //go to http://localhost/phpmyadmin. Create new database:winedb. Import winedb.sql.
    //ORIGINAL CREDENTIALS MUST BE PRESENT FOR SUBMISSION!
    #$servername = "localhost";
    #$username   = "root";
    #$password   = "";
    #$dbname     = "winedb";

    //Original Credentials
    #$servername= "cs2410-web01pvm.aston.ac.uk";
    #$username= "cs2team15";
    #$password= "yaBl9oDtzOh60RmdXZG64OB7v";
    #$dbname= "cs2team15_db";

?>