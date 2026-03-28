<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - BookStore</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="main-content">
        <div class="container">
            <h1>Checkout</h1>

            <?php
            if (empty($_SESSION['cart'])) {
                echo '<div class="error-message"><p>Your cart is empty. <a href="index.php">Continue shopping</a></p></div>';
            } else {
                $cart_total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $cart_total += $item['price'] * $item['quantity'];
                }
                
                $shipping = 10.00;
                $tax = $cart_total * 0.08;
                $final_total = $cart_total + $shipping + $tax;
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $first_name = $_POST['first_name'] ?? '';
                    $last_name = $_POST['last_name'] ?? '';
                    $email = $_POST['email'] ?? '';
                    $phone = $_POST['phone'] ?? '';
                    $address = $_POST['address'] ?? '';
                    
                    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($address)) {
                        try {
                            // Müşteri Kontrolü
                            $cust_check = $conn->prepare("SELECT Customer_ID FROM CUSTOMER WHERE Email = ?");
                            $cust_check->execute([$email]);
                            $existing = $cust_check->fetch();
                            
                            if ($existing) {
                                $customer_id = $existing['Customer_ID'];
                            } else {
                                // Yeni Müşteri Ekleme
                                $cust_stmt = $conn->prepare("INSERT INTO CUSTOMER (First_Name, Last_Name, Email, Phone, Shipping_Address) VALUES (?, ?, ?, ?, ?)");
                                $cust_stmt->execute([$first_name, $last_name, $email, $phone, $address]);
                                $customer_id = $conn->lastInsertId();
                            }
                            
                            // Sipariş Oluşturma (Tablo adı ORDERS olarak düzeltildi)
                            // Employee_ID ve Shipper_ID zorunluysa varsayılan değer atıyoruz (Örn: 1)
                            $order_stmt = $conn->prepare("INSERT INTO ORDERS (Order_Date, Total_Amount, Status, Customer_ID, Employee_ID, Shipper_ID) VALUES (NOW(), ?, 'Pending', ?, 1, 1)");
                            $order_stmt->execute([$final_total, $customer_id]);
                            $order_id = $conn->lastInsertId();
                            
                            // Sipariş Detayları
                            $detail_stmt = $conn->prepare("INSERT INTO ORDER_DETAILS (Order_ID, ISBN, Quantity, Unit_Price) VALUES (?, ?, ?, ?)");
                            
                            // Stok Güncelleme
                            $stock_stmt = $conn->prepare("UPDATE BOOK SET Stock_Qty = Stock_Qty - ? WHERE ISBN = ?");

                            foreach ($_SESSION['cart'] as $isbn => $item) {
                                $detail_stmt->execute([$order_id, $isbn, $item['quantity'], $item['price']]);
                                $stock_stmt->execute([$item['quantity'], $isbn]);
                            }
                            
                            // Sepeti Boşalt
                            $_SESSION['cart'] = [];
                            
                            header("Location: order-success.php?order_id=$order_id");
                            exit;
                        } catch(Exception $e) {
                            $error = "Error processing order: " . $e->getMessage();
                        }
                    } else {
                        $error = "Please fill in all required fields.";
                    }
                }
            ?>

            <div class="checkout-container">
                <div class="checkout-form">
                    <h2>Billing Information</h2>
                    <?php if (isset($error)) { echo '<div class="error-message"><p>' . htmlspecialchars($error) . '</p></div>'; } ?>
                    
                    <form action="" method="POST" class="form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>

                        <div class="form-group">
                            <label for="address">Shipping Address *</label>
                            <textarea id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="checkout-summary">
                            <h3>Order Summary</h3>
                            <div class="checkout-items">
                                <?php
                                foreach ($_SESSION['cart'] as $isbn => $item) {
                                    $subtotal = $item['price'] * $item['quantity'];
                                    echo '<div class="checkout-item">';
                                    echo '<span>' . htmlspecialchars($item['title']) . ' x ' . $item['quantity'] . '</span>';
                                    echo '<span>$' . number_format($subtotal, 2) . '</span>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                            <div class="checkout-totals">
                                <div class="total-row"><span>Subtotal:</span><span>$<?php echo number_format($cart_total, 2); ?></span></div>
                                <div class="total-row"><span>Shipping:</span><span>$<?php echo number_format($shipping, 2); ?></span></div>
                                <div class="total-row"><span>Tax (8%):</span><span>$<?php echo number_format($tax, 2); ?></span></div>
                                <div class="total-row total"><span>Total:</span><span>$<?php echo number_format($final_total, 2); ?></span></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary btn-block">Complete Order</button>
                        <a href="index.php" class="btn btn-lg btn-secondary btn-block">Continue Shopping</a>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>