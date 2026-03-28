<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details - BookStore</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="main-content">
        <div class="container">
            <?php
            if (isset($_GET['isbn'])) {
                $isbn = $_GET['isbn'];
                
                try {
                    $query = "SELECT b.*, c.Name as Category_Name, p.Name as Publisher_Name, p.Address, p.Website
                              FROM Book b 
                              LEFT JOIN Category c ON b.Category_ID = c.Category_ID 
                              LEFT JOIN Publisher p ON b.Publisher_ID = p.Publisher_ID 
                              WHERE b.ISBN = ?";
                    
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$isbn]);
                    $book = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($book) {
                        // Get authors
                        $auth_stmt = $conn->prepare("SELECT a.* FROM Author a 
                                                     JOIN Book_Author ba ON a.Author_ID = ba.Author_ID 
                                                     WHERE ba.ISBN = ?");
                        $auth_stmt->execute([$isbn]);
                        $authors = $auth_stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="breadcrumb">
                            <a href="index.php">Home</a> / <a href="index.php?category=<?php echo $book['Category_ID']; ?>"><?php echo htmlspecialchars($book['Category_Name']); ?></a> / <?php echo htmlspecialchars($book['Title']); ?>
                        </div>

                        <div class="book-detail-container">
                            <div class="book-detail-image">
                                <img src="assets/images/placeholder-book.jpg" alt="<?php echo htmlspecialchars($book['Title']); ?>">
                                <div class="book-info-quick">
                                    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['ISBN']); ?></p>
                                    <p><strong>Published:</strong> <?php echo $book['Pub_Year']; ?></p>
                                    <p><strong>Stock:</strong> <span class="stock-qty"><?php echo $book['Stock_Qty']; ?> available</span></p>
                                </div>
                            </div>

                            <div class="book-detail-info">
                                <h1><?php echo htmlspecialchars($book['Title']); ?></h1>
                                
                                <?php if (count($authors) > 0) { ?>
                                    <p class="authors">
                                        <strong>Authors:</strong>
                                        <?php 
                                        foreach ($authors as $idx => $author) {
                                            if ($idx > 0) echo ', ';
                                            echo htmlspecialchars($author['First_Name'] . ' ' . $author['Last_Name']);
                                        }
                                        ?>
                                    </p>
                                <?php } ?>
                                
                                <p class="publisher-info">
                                    <strong>Publisher:</strong> <?php echo htmlspecialchars($book['Publisher_Name']); ?><br>
                                    <small><?php echo htmlspecialchars($book['Address']); ?></small>
                                </p>

                                <p class="category-tag"><?php echo htmlspecialchars($book['Category_Name']); ?></p>

                                <div class="book-price-section">
                                    <span class="detail-price">$<?php echo number_format($book['Price'], 2); ?></span>
                                    <?php if ($book['Stock_Qty'] > 0) { ?>
                                        <span class="stock-status in-stock"><i class="fas fa-check-circle"></i> In Stock</span>
                                    <?php } else { ?>
                                        <span class="stock-status out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                                    <?php } ?>
                                </div>

                                <?php if ($book['Stock_Qty'] > 0) { ?>
                                    <div class="add-to-cart-section">
                                        <form action="includes/add-to-cart.php" method="POST">
                                            <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>">
                                            <input type="hidden" name="title" value="<?php echo htmlspecialchars($book['Title']); ?>">
                                            <input type="hidden" name="price" value="<?php echo $book['Price']; ?>">
                                            <div class="quantity-selector">
                                                <label for="quantity">Quantity:</label>
                                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $book['Stock_Qty']; ?>">
                                            </div>
                                            <button type="submit" class="btn btn-lg btn-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                                        </form>
                                    </div>
                                <?php } else { ?>
                                    <div class="out-of-stock-notice">
                                        <p>This book is currently out of stock. Please check back later.</p>
                                    </div>
                                <?php } ?>

                                <div class="book-details-section">
                                    <h3>Additional Information</h3>
                                    <ul>
                                        <li><strong>Publication Year:</strong> <?php echo $book['Pub_Year']; ?></li>
                                        <li><strong>Category:</strong> <?php echo htmlspecialchars($book['Category_Name']); ?></li>
                                        <li><strong>Publisher Website:</strong> 
                                            <?php 
                                            if ($book['Website']) {
                                                echo '<a href="' . htmlspecialchars($book['Website']) . '" target="_blank">' . htmlspecialchars($book['Website']) . '</a>';
                                            } else {
                                                echo 'Not available';
                                            }
                                            ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <?php if (count($authors) > 0) { ?>
                            <section class="authors-section">
                                <h2>About the Authors</h2>
                                <div class="authors-grid">
                                    <?php foreach ($authors as $author) { ?>
                                        <div class="author-card">
                                            <h4><?php echo htmlspecialchars($author['First_Name'] . ' ' . $author['Last_Name']); ?></h4>
                                            <?php if ($author['Biography']) { ?>
                                                <p><?php echo htmlspecialchars(substr($author['Biography'], 0, 200)) . '...'; ?></p>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </section>
                        <?php } ?>

                        <?php
                    } else {
                        echo '<div class="error-message"><p>Book not found.</p></div>';
                    }
                } catch(Exception $e) {
                    echo '<div class="error-message"><p>Error loading book details: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
                }
            } else {
                echo '<div class="error-message"><p>Invalid book selected.</p></div>';
            }
            ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>
