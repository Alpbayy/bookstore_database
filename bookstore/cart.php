<?php include '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - BookStore</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="main-content">
        <div class="container">
            <h1>Shopping Cart</h1>

            <?php if (!empty($_SESSION['cart'])) { ?>
                <div class="cart-container">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ($_SESSION['cart'] as $isbn => $item) {
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <form action="update-cart.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($isbn); ?>">
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="100" onchange="this.form.submit();">
                                        </form>
                                    </td>
                                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                                    <td>
                                        <a href="remove-from-cart.php?isbn=<?php echo urlencode($isbn); ?>" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Remove
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <div class="cart-summary">
                        <h3>Order Summary</h3>
                        <div class="summary-item">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="summary-item">
                            <span>Shipping:</span>
                            <span>$10.00</span>
                        </div>
                        <div class="summary-item">
                            <span>Tax (8%):</span>
                            <span>$<?php echo number_format($total * 0.08, 2); ?></span>
                        </div>
                        <div class="summary-total">
                            <span>Total:</span>
                            <span>$<?php echo number_format($total + 10 + ($total * 0.08), 2); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-lg btn-primary">Proceed to Checkout</a>
                        <a href="../index.php" class="btn btn-lg btn-secondary">Continue Shopping</a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h2>Your cart is empty</h2>
                    <p>Add some books to get started!</p>
                    <a href="../index.php" class="btn btn-primary">Start Shopping</a>
                </div>
            <?php } ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
