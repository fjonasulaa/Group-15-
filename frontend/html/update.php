<?php
#The prupose of this page is to update the quantity of items in the basket stored in session.
#If quantity is set to 0, the item is removed from the basket.
    session_start();
    //Prevent updates if basket is empty.
    if (empty($_SESSION['basket'])) {
        header("Location: index.html");
        exit;
    }

    $product_id = intval($_POST['product_id']);
    $quantity   = intval($_POST['quantity']);

    //If quantity > 0, update quantity in session, else remove from session.
    if ($quantity > 0) {
        $_SESSION['basket'][$product_id] = $quantity;
    } else {
        unset($_SESSION['basket'][$product_id]);
    }
    echo json_encode([
        'success' => true,
        'product_id' => $product_id,
        'quantity' => $quantity
    ]);
?>