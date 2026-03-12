<head>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<?php
#The purpose of this page is to place the items from the Basket into OrderWine.
#If you just enter this link, you will be redirected to index.php.
#UPDATE: This page also handles inventory management.
session_start();
if (isset($_GET['page']) && $_GET['page'] === 'Checkout' && empty($_SESSION['basket'])) {
        header("Location: index.php");
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
        //One last check to ensure there's enough of a wine.
        foreach ($_SESSION['basket'] as $wineId => $quantity) {
            $stmt = $conn->prepare("SELECT stock FROM wines WHERE wineId = ?");
            $stmt->bind_param("i", $wineId);
            $stmt->execute();
            $stmt->bind_result($stock);
            $stmt->fetch();
            $stmt->close();

            if ($quantity > $stock) {
                die("Error: Not enough stock for wine ID $wineId.");
            }
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
            // Reduce stock for each purchased wine
            $updateStock = $conn->prepare("UPDATE wines SET stock = stock - ? WHERE wineId = ?");

            foreach ($_SESSION['basket'] as $wineId => $quantity) {
                $updateStock->bind_param("ii", $quantity, $wineId);
                $updateStock->execute();
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
        //Stock management. Updates/deletes a wine.
        case 'inventory':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include '../../database/db_connect.php';
            $action = $_POST['action'];
            if ($action === 'update') {
                //Update wine details
                header("Location: editWine.php?id=" . $_POST['wineId'] );
                //Update wine stock - LEGACY
                /*$wineId = intval($_POST['wineId']);
                $stock = intval($_POST['stock']);

                $sql = "UPDATE wines SET stock = ? WHERE wineId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $stock, $wineId);

                if ($stmt->execute()) {
                    header("Location: inventory.php");
                    exit;
                } else {
                    echo "Error updating stock.";
                }
                */
            }

            if ($action === 'remove') {
                $wineId = intval($_POST['wineId']);

                /*
                ///delete wine - LEGACY
                $wineId = intval($_POST['wineId']);
                $sql = "UPDATE wines SET active = FALSE WHERE wineId = ?;";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $wineId);

                if ($stmt->execute()) {
                    header("Location: inventory.php");
                    exit;
                } else {
                    echo "Error deleting wine.";
                }
                */
                if ($action === 'remove' && !isset($_POST['confirm'])) {
                    echo "
                        <h3>Confirm deletion</h3>
                        <p>Are you sure you want to delete wine ID: {$wineId}?</p>
                        <form method='POST' action='redirect.php?page=inventory'>
                            <input type='hidden' name='wineId' value='{$wineId}'>
                            <input type='hidden' name='action' value='remove'>
                            <input type='hidden' name='confirm' value='yes'>
                            <button type='submit'>Yes, delete it</button>
                        </form>
                        <a href='inventory.php'>Cancel</a>
                    ";
                    exit;
                }
                if ($action === 'remove' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                    //delete wine
                $sql = "UPDATE wines SET active = FALSE WHERE wineId = ?;";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $wineId);

                if ($stmt->execute()) {
                    header("Location: inventory.php");
                    exit;
                } else {
                    echo "Error deleting wine.";
                }
                }

            }

                
            }
            break;
        case 'return':
            include '../../database/db_connect.php';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderId = intval($_POST['orderId']);

$sql = "SELECT w.wineId, w.wineName, w.stock, ow.quantity
        FROM orderswines ow
        JOIN wines w ON ow.wineId = w.wineId
        WHERE ow.orderId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

$wines = [];
while ($row = $result->fetch_assoc()) {
    $wines[] = $row;
}

// Example: iterate and print stock
foreach ($wines as $wine) {
    $wineId   = (int)$wine['wineId'];
    $quantity = (int)$wine['quantity'];

    $updateSql = "UPDATE wines SET stock = stock + ? WHERE wineId = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ii", $quantity, $wineId);
    $updateStmt->execute();

}
                $reason = trim($_POST['reason']);
                $description = trim($_POST['description']);

                $sql = "INSERT INTO refund (orderId, reason, description) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $orderId, $reason, $description);

                if ($stmt->execute()) {
                    header("Location: return-confirm.html");
                    exit;
                } else {
                    echo "Error processing return.";
                }
            }
        default:
            header("Location: index.php");
            break;
    }
    exit;
} else {
    header("Location: index.php");
    exit;
}

?>