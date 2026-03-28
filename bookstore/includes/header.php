<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cart_count = count($_SESSION['cart'] ?? []);
?>

<header class="header">
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <i class="fas fa-book"></i>
                    <span>BookStore</span>
                </a>
            </div>

            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="index.php" class="nav-link">Books</a></li>
                <li><a href="index.php?category=1" class="nav-link">Categories</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>

            <div class="cart-icon">
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($cart_count > 0) { ?>
                        <span class="cart-badge"><?php echo $cart_count; ?></span>
                    <?php } ?>
                </a>
            </div>

            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
</header>
