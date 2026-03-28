<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı dosyasının yolu doğru mu kontrol ediyoruz
if (!file_exists('config/db.php')) {
    die("HATA: 'config/db.php' dosyası bulunamadı! Klasör yapını kontrol et.");
}

include 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore - Your Online Book Shop</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="main-content">
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to BookStore</h1>
                <p>Discover Your Next Favorite Book</p>
                <div class="search-container">
                    <form action="index.php" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Search books, authors, genres..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
        </section>

        <section class="filters-section">
            <div class="container">
                <div class="filters">
                    <h3>Filter by Category</h3>
                    <div class="category-list">
                        <a href="index.php" class="category-btn <?php echo !isset($_GET['category']) ? 'active' : ''; ?>">
                            All Categories
                        </a>
                        <?php
                        try {
                            // DÜZELTME: Tablo adı CATEGORY (Büyük harf)
                            $stmt = $conn->query("SELECT * FROM CATEGORY ORDER BY Name");
                            while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $active = isset($_GET['category']) && $_GET['category'] == $category['Category_ID'] ? 'active' : '';
                                echo "<a href='index.php?category=" . $category['Category_ID'] . "' class='category-btn $active'>";
                                echo htmlspecialchars($category['Name']);
                                echo "</a>";
                            }
                        } catch(Exception $e) {
                            echo "Kategoriler yüklenirken hata: " . $e->getMessage();
                        }
                        ?>
                    </div>
                </div>

                <div class="sort-section">
                    <label for="sort">Sort by:</label>
                    <select id="sort" name="sort" onchange="window.location.href='index.php?sort=' + this.value + (new URLSearchParams(window.location.search).get('category') ? '&category=' + new URLSearchParams(window.location.search).get('category') : '')">
                        <option value="">Newest</option>
                        <option value="price-low" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price-high" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="title" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'title' ? 'selected' : ''; ?>>Title: A to Z</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="books-section">
            <div class="container">
                <div class="books-grid">
                    <?php
                    // DÜZELTME: Tablo isimleri BOOK, CATEGORY, PUBLISHER (Hepsi Büyük Harf)
                    $query = "SELECT DISTINCT b.*, c.Name as Category_Name, p.Name as Publisher_Name 
                              FROM BOOK b 
                              LEFT JOIN CATEGORY c ON b.Category_ID = c.Category_ID 
                              LEFT JOIN PUBLISHER p ON b.Publisher_ID = p.Publisher_ID 
                              WHERE 1=1";
                    
                    $params = array();
                    
                    // Filter by category
                    if (isset($_GET['category']) && !empty($_GET['category'])) {
                        $query .= " AND b.Category_ID = ?";
                        $params[] = $_GET['category'];
                    }
                    
                    // Search filter
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = '%' . $_GET['search'] . '%';
                        $query .= " AND (b.Title LIKE ? OR p.Name LIKE ?)";
                        $params[] = $search;
                        $params[] = $search;
                    }
                    
                    // Sorting
                    if (isset($_GET['sort'])) {
                        switch($_GET['sort']) {
                            case 'price-low':
                                $query .= " ORDER BY b.Price ASC";
                                break;
                            case 'price-high':
                                $query .= " ORDER BY b.Price DESC";
                                break;
                            case 'title':
                                $query .= " ORDER BY b.Title ASC";
                                break;
                            default:
                                $query .= " ORDER BY b.Pub_Year DESC";
                        }
                    } else {
                        $query .= " ORDER BY b.Pub_Year DESC";
                    }
                    
                    try {
                        $stmt = $conn->prepare($query);
                        $stmt->execute($params);
                        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($books) > 0) {
                            foreach ($books as $book) {
                                $inStock = $book['Stock_Qty'] > 0;
                                ?>
                                <div class="book-card <?php echo !$inStock ? 'out-of-stock' : ''; ?>">
                                    <div class="book-image">
                                        <img src="assets/images/placeholder-book.jpg" alt="<?php echo htmlspecialchars($book['Title']); ?>">
                                        <?php if (!$inStock) { echo '<span class="out-of-stock-badge">Out of Stock</span>'; } ?>
                                    </div>
                                    <div class="book-info">
                                        <h3><?php echo htmlspecialchars(substr($book['Title'], 0, 40)); ?></h3>
                                        <p class="book-author">by <?php echo htmlspecialchars($book['Publisher_Name']); ?></p>
                                        <p class="book-category"><?php echo htmlspecialchars($book['Category_Name']); ?></p>
                                        <p class="book-year"><?php echo $book['Pub_Year']; ?></p>
                                        <div class="book-price">
                                            <span class="price">$<?php echo number_format($book['Price'], 2); ?></span>
                                            <span class="stock <?php echo $inStock ? 'in-stock' : ''; ?>">
                                                <?php echo $inStock ? 'In Stock' : 'Out of Stock'; ?>
                                            </span>
                                        </div>
                                        <div class="book-actions">
                                            <a href="book-detail.php?isbn=<?php echo urlencode($book['ISBN']); ?>" class="btn btn-view">View Details</a>
                                            <?php if ($inStock) { ?>
                                                <form action="includes/add-to-cart.php" method="POST" style="display: inline;">
                                                    <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>">
                                                    <input type="hidden" name="title" value="<?php echo htmlspecialchars($book['Title']); ?>">
                                                    <input type="hidden" name="price" value="<?php echo $book['Price']; ?>">
                                                    <button type="submit" class="btn btn-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                                                </form>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="no-books"><p>No books found via database query. (Tablo boş veya sorgu hatası)</p></div>';
                        }
                    } catch(Exception $e) {
                        echo '<div class="error-message"><p>Veritabanı Hatası: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>