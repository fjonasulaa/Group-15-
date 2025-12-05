<?php
#The purpose of this page is to place the items from the Basket into OrderWine.
#If you just enter this link, you will be redirected to index.html.
session_start();
if (empty($_SESSION['basket'])) {
        header("Location: index.html");
        exit;
    }
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'Checkout':
            //Create a new guest customer in Customers table and set current user to that customer.
        include '..\..\database\db_connect.php';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            include '..\..\database\db_connect.php';
            if (isset($_SESSION['customerID'])){
                $_SESSION['currentUser'] = $_SESSION['customerID'];
            }else{
            //Guest Checkout
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
        }
            //New Order
            $totalAmount = 0.00;

            foreach ($_SESSION['basket'] as $wineId => $quantity) {
                $stmt = $conn->prepare("SELECT price FROM wines WHERE wineId = ?");
                $stmt->bind_param("i", $wineId);
                $stmt->execute();
                $stmt->bind_result($price);
                $stmt->fetch();
                $stmt->close();

                $totalAmount += $price * $quantity;
            }
            if ($_POST['shipping'] === 'nextday') {
                $totalAmount += 4.99;
            }
            include '..\..\database\db_connect.php';
            $stmt = $conn->prepare("INSERT INTO orders (customerId, totalAmount)
            VALUES (?, ?)");
            $stmt->bind_param("sd", $_SESSION['currentUser'], $totalAmount);
            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " .$stmt->error;
            }
        
            $_SESSION['currentOrder'] = $conn->insert_id; // Set current order to the newly created order
            //Insert basket
            $stmt = $conn->prepare("INSERT INTO orderswines (orderId, wineId, quantity) VALUES (?, ?, ?)");
            foreach ($_SESSION['basket'] as $wineId => $quantity) {
                $stmt->bind_param("iii", $_SESSION['currentOrder'], $wineId, $quantity);
                $stmt->execute();
            }
            
            //Shipping info
            $order = trim($_SESSION['currentOrder']);
            $dtype = trim($_POST['shipping']);
            $status=trim("Processing");
            $stmt = $conn->prepare("INSERT INTO shipping (orderId, deliveryType, shippingStatus)
            VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $order, $dtype, $status);
            // Run the query
            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " .$stmt->error;
            }
            //Payment info
            $order = trim($_SESSION['currentOrder']);
            $method = trim($_POST['payment-method']);
            $amount = $totalAmount;
            $status=trim("Processing");

            $stmt = $conn->prepare("INSERT INTO payment (orderId, Method, amount, paymentStatus)
            VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $order, $method, $amount, $status);
            // Run the query
            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " .$stmt->error;
            }

            // Close the connection
            $stmt->close();
            $conn->close();
            //Empty basket
            unset($_SESSION['basket']);
            unset($_SESSION['currentUser']);
        }else{
            echo'I will not do anything. User\'s info is already in Customers. If Basket is empty redirect to Basket.php.';
        }

            header("Location: confirm.html");
            break;
        default:
            header("Location: index.html");
            break;
    }
    exit;
} else {
    header("Location: index.html");
    exit;
}

?>