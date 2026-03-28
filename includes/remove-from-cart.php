<?php
include '../config/db.php';

if (isset($_GET['isbn'])) {
    unset($_SESSION['cart'][$_GET['isbn']]);
}

header('Location: ../cart.php');
exit;
?>
