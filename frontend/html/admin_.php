<?php

session_start();


ob_start();
require_once('users.php');
ob_end_clean();

$deleteError = '';

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
    $customerID = $_SESSION['customerID'];
}




$user = [
    'customerID' => '',
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
    $role = $_POST['role'];

    if ($firstName !== '' && $surname !== '' && $email !== '') {
        $stmt = $conn->prepare("
    UPDATE customer
    SET firstName=?, surname=?, email=?, addressLine=?, postcode=?, phoneNumber=?, role=?
    WHERE customerID=?
");

$stmt->bind_param(
    "sssssssi",
    $firstName,
    $surname,
    $email,
    $addressLine,
    $postcode,
    $phoneNumber,
    $role,
    $customerID
);

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



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateTransactionStatus'])) {

    $orderId = (int)$_POST['orderId'];
    $paymentStatus = $_POST['paymentStatus'];
    $shippingStatus = $_POST['shippingStatus'];
    $trackingNumber = $_POST['trackingNumber'];
    $carrier = $_POST['carrier'];

    $PaymentStatu = ['Pending', 'Paid'];
    $ShippingStatu = ['Preparing', 'In Transit', 'Delivered'];

    if (in_array($paymentStatus, $PaymentStatu) && in_array($shippingStatus, $ShippingStatu)) {

        $stmt = $conn->prepare("
            UPDATE payment
            SET paymentStatus=?
            WHERE orderId=?");

        $stmt->bind_param("si", $paymentStatus, $orderId);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("
            UPDATE shipping
            SET shippingStatus=?, trackingNumber=?, carrier=?
            WHERE orderId=?");

        $stmt->bind_param("sssi", $shippingStatus, $trackingNumber, $carrier, $orderId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin.php?customerID=" . $customerID);
    exit();

}
// Returns
if (isset($_POST['approveReturn'])) {
    $refundId = intval($_POST['refundId']);
    $orderId = intval($_POST['orderId']);

    $sql = "SELECT wineId, quantity FROM orderswines WHERE orderId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $wineId = (int)$row['wineId'];
        $qty = (int)$row['quantity'];

        $update = $conn->prepare("UPDATE wines SET stock = stock + ? WHERE wineId = ?");
        $update->bind_param("ii", $qty, $wineId);
        $update->execute();
    }

    $updateRefund = $conn->prepare("UPDATE refund SET status = 'accepted' WHERE refundId = ?");
    $updateRefund->bind_param("i", $refundId);
    $updateRefund->execute();
}

if (isset($_POST['rejectReturn'])) {
    $refundId = intval($_POST['refundId']);

    $updateRefund = $conn->prepare("UPDATE refund SET status = 'denied' WHERE refundId = ?");
    $updateRefund->bind_param("i", $refundId);
    $updateRefund->execute();
}




$result1 = $conn->query("SELECT customerID, email FROM customer ORDER BY customerID DESC");
while ($row = $result1->fetch_assoc()) {
    $customers[] = $row;
}






$stmt = $conn->prepare("
    SELECT customerID, firstName, surname, email, addressLine, postcode, phoneNumber, role
    FROM customer
    WHERE customerID=?
");
$stmt->bind_param("i", $customerID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("

    SELECT
    shipping.trackingNumber,
    shipping.carrier,
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

$returnQuery = $conn->prepare("
    SELECT r.refundId, r.orderId, r.reason, r.description, r.status, o.customerId
    FROM refund r
    JOIN orders o ON r.orderId = o.orderId
    ORDER BY r.refundId DESC
");
$returnQuery->execute();
$returns = $returnQuery->get_result()->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteuSER'])) {

    if ($customerID == $_SESSION['customerID']){

        $deleteError = "Own account cannot be deleted!!";

    }
    else
    {
    
        $stmt = $conn->prepare("DELETE FROM customer WHERE customerID = ?");
        $stmt->bind_param("i", $customerID);
        $stmt->execute();
        $stmt->close();



        header("Location: admin.php?customerID=" . $_SESSION['customerID']);

        exit();
    }
}