<?php
    session_start();
    include '..\..\database\db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        include '..\..\database\db_connect.php';

        $order = trim($_SESSION['currentOrder']);
        if(isset($_POST['cash'])){
            $method = trim("Cash");
        } elseif(isset($_POST['card'])){
            $method = trim("Card");
        }
        $amount = 20.05; //Determine totalAmount later.
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

    }
?>
Thank you for your order! We will be in touch with you soon.
Meanwhile, why don't you check out our other products?
<div class="btn-container" style="padding: 25px;">
    <a href="index.html" class="btn">Home Page</a>
</div>