<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isbn = $_POST['isbn'] ?? '';
    $title = $_POST['title'] ?? '';
    $price = $_POST['price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;

    if (!empty($isbn) && $price > 0 && $quantity > 0) {
        if (isset($_SESSION['cart'][$isbn])) {
            $_SESSION['cart'][$isbn]['quantity'] += (int)$quantity;
        } else {
            $_SESSION['cart'][$isbn] = [
                'title' => $title,
                'price' => (float)$price,
                'quantity' => (int)$quantity
            ];
        }
        
        // Redirect to cart
        header('Location: ../cart.php?success=1');
        exit;
    }
}

header('Location: ../index.php');
exit;
?>
