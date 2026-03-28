<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['isbn']) && isset($_POST['quantity'])) {
    $isbn = $_POST['isbn'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$isbn]);
    } else {
        $_SESSION['cart'][$isbn]['quantity'] = $quantity;
    }
}

header('Location: ../cart.php');
exit;
?>
