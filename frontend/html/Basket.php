<?php
    session_start();
    require_once('../../database/db_connect.php');
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket | Wine Exchange</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<style>
.place-order {
    background: #7b1e3a;
    color: white;
    padding: 14px 40px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: bold;
    transition: background 0.3s ease;
}
.place-order:hover {
    background: #5e152c;
}
.basket-wrapper {
    width: 90%;
    margin: 120px auto 40px auto; 
    display: flex;               
    flex-direction: column;       
    min-height: auto;
}
.basket-header {
    display: grid;
    grid-template-columns: 1fr 160px 100px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ccc;
    font-weight: bold;
    letter-spacing: 1px;
    color: #333;
}
.basket-row {
    display: grid;
    grid-template-columns: 160px 1fr 160px 100px;
    padding: 25px 0;
    align-items: center;
    border-bottom: 1px solid #eee;
}
.basket-row img {
    width: 140px;
    border-radius: 8px;
}
.basket-info-title { font-size: 1.3rem; font-weight: 600; margin-bottom: 5px; }
.basket-info-sub { color: #666; font-size: 0.9rem; margin-bottom: 4px; }
.qty-control { display: flex; align-items: center; gap: 15px; }
.qty-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #aaa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    cursor: pointer;
}
.remove-link { color: #444; text-decoration: underline; font-size: 0.9rem; margin-top: 8px; display: inline-block; }
.basket-total-price { font-size: 1.2rem; font-weight: bold; }
.checkout-button-wrapper {
    position: relative;
    margin-top: 100px;
    margin-bottom: 20px;
    display: flex;
    justify-content: flex-end;
    width: 100%;
    clear: both;
    z-index: 1;
}
</style>

<body class="info">

    <!-- NAVBAR -->
    <?php include 'header.php'; ?>

    <!-- BASKET CONTENT -->
    <div class="basket-wrapper">
        <h2>Your Basket</h2>
        <br>
        <div class="basket-header">
            <span>PRODUCT</span>
            <span style="text-align:center;">QUANTITY</span>
            <span style="text-align:right;">TOTAL</span>
        </div>

        <?php
        if (!empty($_SESSION['basket'])) {
            foreach ($_SESSION['basket'] as $id => $qty) {
                $sql = "SELECT wineName, price, imageUrl, stock FROM wines WHERE wineId = $id";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                $wineName = $row['wineName'];
                $price = $row['price'];
                $imageUrl = $row['imageUrl'];

                echo "
                    <div class='basket-row' data-product-id='$id' data-price='$price' data-stock='{$row['stock']}'>
                        <img src='../../images/$imageUrl' alt='Product Image'>

                        <div>
                            <div class='basket-info-title'>$wineName</div>
                            <a href='#' class='remove-link'>Remove Item</a>
                        </div>

                        <div class='qty-control' style='justify-content:center;'>
                            <div class='qty-btn'>-</div>
                            <span>$qty</span>
                            <div class='qty-btn'>+</div>
                        </div>

                        <div class='basket-total-price' style='text-align:right;'>£" . number_format($price * $qty, 2) . "</div>
                    </div>
                ";
            }
        } else {
            echo "<br><p>Your basket is empty.</p><br>";
        }
        ?>

        <!-- PLACE ORDER BUTTON -->
        <?php if (!empty($_SESSION['basket'])): ?>
        <div class="checkout-button-wrapper">
            <a href="checkout.php" class="place-order">Place Order</a>
        </div>
        <?php endif; ?>
    </div>

    <!-- FOOTER -->
    <?php include 'footer.php'; ?>

    <script>
        // Quantity buttons functionality
        const rows = document.querySelectorAll('.basket-row');

        function updateServer(productId, newQuantity){
            fetch("update.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `product_id=${productId}&quantity=${newQuantity}`
            });
        }

        rows.forEach(row => {
            const productId = row.getAttribute('data-product-id');
            const qtyBtns = row.querySelectorAll('.qty-btn');
            const qtyDisplay = row.querySelector('.qty-control span');
            const priceElement = row.querySelector('.basket-total-price');

            let qty = parseInt(qtyDisplay.textContent);
            const basePrice = parseFloat(row.getAttribute('data-price'));
            const maxStock = parseInt(row.getAttribute('data-stock'));

            qtyBtns[0].addEventListener('click', () => {
                if (qty > 1) {
                    qty--;
                    qtyDisplay.textContent = qty;
                    priceElement.textContent = '£' + (basePrice * qty).toFixed(2);
                    updateServer(productId, qty);
                }
            });

            qtyBtns[1].addEventListener('click', () => {
                if (qty < maxStock) {
                    qty++;
                    qtyDisplay.textContent = qty;
                    priceElement.textContent = '£' + (basePrice * qty).toFixed(2);
                    updateServer(productId, qty);
                } else {
                    alert("You cannot add more than the available stock.");
                }
            });

            row.querySelector('.remove-link').addEventListener('click', e => {
                e.preventDefault();
                row.remove();
                updateServer(productId, 0);
            });
        });
    </script>

</body>
</html>