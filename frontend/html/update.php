<?php
    session_start();

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