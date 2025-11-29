<?php
    session_start();
    include '..\..\database\db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        include '..\..\database\db_connect.php';

        $order = trim($_SESSION['currentOrder']);
        $dtype = trim($_POST['dtype']);
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

        // Close the connection
        $stmt->close();
        $conn->close();

    }

?>
<head>
    <style>
        .form-container {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<form>
    <label for="ptype">How do you want to pay?</label>
    <select name="ptype" id="ptype" required>
        <option value="cash">Cash - Pay the Courier</option>
        <option value="card">Card - Pay Now</option>
    </select>
</form>




<!--Form for cash payment-->
<div id="cash" class="form-container">
    Cash Payment. You will pay the courier £<?php echo "amount"; ?>.
    <form method="post" action="confirm.php">
        <label>Do you agree to this?</label>
        <input type="checkbox" name="confirm" required><br><br>
        <button type="submit" name="cash">Proceed</button>
    </form>
</div>
<!--Form for card payment-->
<div id="card" class="form-container">
    Card Payment. You will pay us £<?php echo "amount"; ?>.
    <form method="post" action="confirm.php">
        <label>Cardholder's Name</label>
        <input type="text" name="cardname" required><br>
        <label>Card Number</label>
        <input type="number" name="cardnumber" maxlength="16" required><br>
        <label>Expiry Month</label>
        <input type="number" name="expmonth" maxlength="2" required><br>
        <label>Expiry Year</label>
        <input type="number" name="expyear" maxlength="4" required><br>
        <label>CVV</label>
        <input type="number" name="cvv" maxlength="4" required><br>
        <label>Do you agree to this?</label>
        <input type="checkbox" name="confirm" required><br><br>
        <button type="submit" name="card">Proceed</button>
    </form>
</div>

<!--Script switches between forms, depending on payment type-->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const selector = document.getElementById('ptype');
        const forms = document.querySelectorAll('.form-container');
        
        const selected = selector.value;
        if (selected) {
            document.getElementById(selected).style.display = 'block';
        }

        selector.addEventListener('change', function() {
        forms.forEach(form => form.style.display = 'none');
        const selected = this.value;
        if (selected) {
            document.getElementById(selected).style.display = 'block';
        }
        });
    });
</script>