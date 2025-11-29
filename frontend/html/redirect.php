<?php
#The purpose of this page is to place the items from the Basket into OrderWine.
#If you just enter this link, you will be redirected to index.html.
session_start();
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    //Create a new Order
    function newOrder($user){
        include '..\..\database\db_connect.php';
        //Determine totalAmount later.
        $totalAmount = 20.05;
        $stmt = $conn->prepare("INSERT INTO orders (customerId, totalAmount)
            VALUES (?, ?)");
        $stmt->bind_param("sd", $user, $totalAmount);
        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " .$stmt->error;
        }
        
        $_SESSION['currentOrder'] = $conn->insert_id; // Set current order to the newly created order

        // Close the connection
        $stmt->close();
        $conn->close();
    }
    //Place items into OrderWine.
    function newBasket($order){
        echo'I will place items from Basket into OrderWine.';
    }
    switch ($page) {
        case 'shipping':
            newOrder($_SESSION['currentUser']);
            newBasket($_SESSION['currentOrder']);
            header("Location: shipping.php");
            break;
        case 'Checkout':
            //Create a new guest customer in Customers table and set current user to that customer.
        include '..\..\database\db_connect.php';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            include '..\..\database\db_connect.php';

            $fname = trim($_POST['fname']);
            $lname = trim($_POST['lname']);
            $address = trim($_POST['address']);
            $postcode = trim($_POST['postcode']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $passwordHash = "guestAccount"; // Placeholder for guest accounts

            $stmt = $conn->prepare("INSERT INTO customer (firstName, surname, addressLine, postcode, email, phoneNumber, passwordHash)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $fname, $lname, $address, $postcode, $email, $phone, $passwordHash);
            // Run the query
            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " .$stmt->error;
            }
            $_SESSION['currentUser'] = $conn->insert_id; // Set current user to the newly created guest customer

            // Close the connection
            $stmt->close();
            $conn->close();

            newOrder($_SESSION['currentUser']);
            newBasket($_SESSION['currentOrder']);
        }else{
            echo'I will not do anything. User\'s info is already in Customers. If Basket is empty redirect to Basket.php.';
        }

            header("Location: shipping.php");
            break;
        default:
            header("Location: index.php");
            break;
    }
    exit;
} else {
    header("Location: index.html");
    exit;
}

?>