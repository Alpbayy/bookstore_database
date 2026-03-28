<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - BookStore</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="main-content">
        <div class="container">
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <h1>Order Successful!</h1>
                
                <?php
                if (isset($_GET['order_id'])) {
                    $order_id = $_GET['order_id'];
                    
                    try {
                        $stmt = $conn->prepare("SELECT * FROM Order WHERE Order_ID = ?");
                        $stmt->execute([$order_id]);
                        $order = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($order) {
                            echo '<p>Your order has been placed successfully!</p>';
                            echo '<div class="order-info">';
                            echo '<p><strong>Order ID:</strong> #' . htmlspecialchars($order['Order_ID']) . '</p>';
                            echo '<p><strong>Date:</strong> ' . htmlspecialchars($order['Order_Date']) . '</p>';
                            echo '<p><strong>Total Amount:</strong> $' . number_format($order['Total_Amount'], 2) . '</p>';
                            echo '<p><strong>Status:</strong> ' . htmlspecialchars($order['Status']) . '</p>';
                            echo '</div>';
                            
                            // Get customer info
                            $cust_stmt = $conn->prepare("SELECT * FROM Customer WHERE Customer_ID = ?");
                            $cust_stmt->execute([$order['Customer_ID']]);
                            $customer = $cust_stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if ($customer) {
                                echo '<div class="order-details">';
                                echo '<h3>Shipping To:</h3>';
                                echo '<p>' . htmlspecialchars($customer['First_Name'] . ' ' . $customer['Last_Name']) . '</p>';
                                echo '<p>' . htmlspecialchars($customer['Shipping_Address']) . '</p>';
                                echo '<p>Email: ' . htmlspecialchars($customer['Email']) . '</p>';
                                echo '</div>';
                            }
                            
                            // Get order items
                            $items_stmt = $conn->prepare("SELECT od.*, b.Title FROM Order_Details od JOIN Book b ON od.ISBN = b.ISBN WHERE od.Order_ID = ?");
                            $items_stmt->execute([$order_id]);
                            $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($items) > 0) {
                                echo '<div class="order-items">';
                                echo '<h3>Items Ordered:</h3>';
                                echo '<table class="items-table">';
                                echo '<tr><th>Book Title</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th></tr>';
                                
                                foreach ($items as $item) {
                                    $subtotal = $item['Quantity'] * $item['Unit_Price'];
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($item['Title']) . '</td>';
                                    echo '<td>' . $item['Quantity'] . '</td>';
                                    echo '<td>$' . number_format($item['Unit_Price'], 2) . '</td>';
                                    echo '<td>$' . number_format($subtotal, 2) . '</td>';
                                    echo '</tr>';
                                }
                                
                                echo '</table>';
                                echo '</div>';
                            }
                        }
                    } catch(Exception $e) {
                        echo '<p>Error loading order details.</p>';
                    }
                } else {
                    echo '<p>Invalid order ID.</p>';
                }
                ?>
                
                <div class="order-actions">
                    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                    <a href="my-orders.php" class="btn btn-secondary">View My Orders</a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
