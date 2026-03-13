<?php

session_start();


ob_start();
require_once('users.php');
ob_end_clean();


if (!isset($_SESSION['customerID'])) {
    header("Location: log-in.php");
    exit();
}




$cid = $_SESSION['customerID'];

$result = $conn->query("SELECT role FROM customer WHERE customerID = $cid");
$role = $result->fetch_assoc();

if ($role['role'] != 'admin') {
    header("Location: index.php");
    exit();
}




if (isset($_GET['customerID'])) {
    $customerID = (int)$_GET['customerID'];
} 
else 
{
    $customerID = 1;
}




$user = [
    'firstName' => '',
    'surname' => '',
    'email' => '',
    'addressLine' => '',
    'postcode' => '',
    'phoneNumber' => ''
];
$customers = [];
$transactions = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveDetails'])) {

    $firstName = $_POST['firstName'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $addressLine = $_POST['addressline'];
    $postcode = $_POST['postcode'];
    $phoneNumber = $_POST['pnumber'];

    if ($firstName !== '' && $surname !== '' && $email !== '') {
        $stmt = $conn->prepare("
            UPDATE customer
            SET firstName=?, surname=?, email=?, addressLine=?, postcode=?, phoneNumber=?
            WHERE customerID=?");





        $stmt->bind_param("ssssssi", $firstName, $surname, $email, $addressLine, $postcode, $phoneNumber, $customerID);
        $stmt->execute();
        $stmt->close();
    }
    
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePassword'])) {

    if (isset($_POST['newpassword']) && isset($_POST['confirmnewpassword'])) {
        $newPassword = $_POST['newpassword'];
        $confirmNewPassword = $_POST['confirmnewpassword'];
        
        if ($newPassword === $confirmNewPassword) {
            $u = new Users();
            $u->updatePassword($customerID, $newPassword);
        }
    }
}

$result1 = $conn->query("SELECT customerID, email FROM customer ORDER BY customerID DESC");
while ($row = $result1->fetch_assoc()) {
    $customers[] = $row;
}




$stmt = $conn->prepare("
    SELECT firstName, surname, email, addressLine, postcode, phoneNumber
    FROM customer
    WHERE customerID=?
");
$stmt->bind_param("i", $customerID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("
    SELECT
        payment.paymentId,
        orders.orderId,
        payment.amount,
        payment.method,
        payment.paymentStatus,
        payment.transactionTimestamp,
        shipping.shippingStatus
    FROM orders
    LEFT JOIN payment ON payment.orderId = orders.orderId
    LEFT JOIN shipping ON shipping.orderId = orders.orderId
    WHERE orders.customerId = ?
    ORDER BY payment.transactionTimestamp DESC, orders.orderId DESC
");

$stmt->bind_param("i", $customerID);
$stmt->execute();

$result2 = $stmt->get_result();

while ($row = $result2->fetch_assoc()) {
    $transactions[] = $row;
}

$stmt->close();