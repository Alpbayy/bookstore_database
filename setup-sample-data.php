<?php
/**
 * Sample Data Insertion Script
 * Run this once to populate the database with sample books
 */

include 'config/db.php';

try {
    // Insert Publishers
    $publishers = [
        ['Penguin Books', '375 Hudson Street, New York', 'www.penguin.com'],
        ['HarperCollins', '195 Broadway, New York', 'www.harpercollins.com'],
        ['Simon & Schuster', '1230 Avenue of the Americas', 'www.simonandschuster.com'],
        ['Random House', '1745 Broadway, New York', 'www.randomhouse.com']
    ];

    foreach ($publishers as $pub) {
        $stmt = $conn->prepare("INSERT INTO Publisher (Name, Address, Website) VALUES (?, ?, ?)");
        $stmt->execute($pub);
    }
    echo "✓ Publishers inserted\n";

    // Insert Categories
    $categories = [
        ['Fiction', 'Fictional novels and stories for entertainment'],
        ['Non-Fiction', 'Educational and factual books'],
        ['Science Fiction', 'Science fiction and futuristic novels'],
        ['Mystery', 'Mystery and detective novels'],
        ['Romance', 'Romantic fiction and love stories'],
        ['Biography', 'Life stories and biographies'],
        ['Technology', 'Books about technology and programming'],
        ['History', 'Historical narratives and accounts']
    ];

    foreach ($categories as $cat) {
        $stmt = $conn->prepare("INSERT INTO Category (Name, Description) VALUES (?, ?)");
        $stmt->execute($cat);
    }
    echo "✓ Categories inserted\n";

    // Insert Authors
    $authors = [
        ['J.K.', 'Rowling', 'British author, best known for Harry Potter series'],
        ['George R.R.', 'Martin', 'American writer, author of A Song of Ice and Fire'],
        ['Stephen', 'King', 'American author, master of horror and suspense'],
        ['J.R.R.', 'Tolkien', 'British author, creator of Middle-earth'],
        ['Isaac', 'Asimov', 'American writer and biochemist, science fiction pioneer'],
        ['Margaret', 'Atwood', 'Canadian author, known for The Handmaid\'s Tale'],
        ['Haruki', 'Murakami', 'Japanese writer, famous for surreal narratives'],
        ['Paulo', 'Coelho', 'Brazilian author, wrote The Alchemist']
    ];

    foreach ($authors as $author) {
        $stmt = $conn->prepare("INSERT INTO Author (First_Name, Last_Name, Biography) VALUES (?, ?, ?)");
        $stmt->execute($author);
    }
    echo "✓ Authors inserted\n";

    // Insert Books
    $books = [
        ['978-0747532699', 'Harry Potter and the Philosopher\'s Stone', 1997, 15.99, 100, 1, 1, 1],
        ['978-0747538494', 'Harry Potter and the Chamber of Secrets', 1998, 16.99, 85, 1, 1, 1],
        ['978-0552131414', 'A Game of Thrones', 1996, 18.99, 60, 2, 1, 2],
        ['978-0451524935', 'The Shining', 1977, 14.99, 45, 3, 1, 4],
        ['978-0547928227', 'The Hobbit', 1937, 12.99, 70, 4, 1, 3],
        ['978-0553293357', 'Foundation', 1951, 13.99, 50, 1, 3, 7],
        ['978-0385490818', 'The Handmaid\'s Tale', 1985, 16.99, 55, 2, 1, 5],
        ['978-0099322405', 'Norwegian Wood', 1987, 14.99, 40, 3, 1, 5],
        ['978-0061122415', 'The Alchemist', 1988, 13.99, 120, 4, 1, 1],
        ['978-1491946008', 'Web Development with Node and Express', 2014, 34.99, 30, 1, 7, 2],
        ['978-0201633610', 'Design Patterns', 1994, 54.99, 20, 2, 7, 2],
        ['978-0596007126', 'Head First Java', 2005, 44.99, 25, 3, 7, 2]
    ];

    foreach ($books as $book) {
        $stmt = $conn->prepare(
            "INSERT INTO Book (ISBN, Title, Pub_Year, Price, Stock_Qty, Publisher_ID, Category_ID) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute(array_slice($book, 0, 7));
    }
    echo "✓ Books inserted\n";

    // Link authors to books
    $book_authors = [
        ['978-0747532699', 1],
        ['978-0747538494', 1],
        ['978-0552131414', 2],
        ['978-0451524935', 3],
        ['978-0547928227', 4],
        ['978-0553293357', 5],
        ['978-0385490818', 6],
        ['978-0099322405', 7],
        ['978-0061122415', 8],
        ['978-1491946008', 5],
        ['978-0596007126', 5],
        ['978-0201633610', 5]
    ];

    foreach ($book_authors as $ba) {
        $stmt = $conn->prepare("INSERT INTO Book_Author (ISBN, Author_ID) VALUES (?, ?)");
        $stmt->execute($ba);
    }
    echo "✓ Book-Author relationships inserted\n";

    // Insert Sample Customers
    $customers = [
        ['John', 'Doe', 'john@example.com', '555-1234', '123 Main St, New York, NY'],
        ['Jane', 'Smith', 'jane@example.com', '555-5678', '456 Oak Ave, Los Angeles, CA'],
        ['Bob', 'Johnson', 'bob@example.com', '555-9012', '789 Pine Rd, Chicago, IL']
    ];

    foreach ($customers as $cust) {
        $stmt = $conn->prepare(
            "INSERT INTO Customer (First_Name, Last_Name, Email, Phone, Shipping_Address) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute($cust);
    }
    echo "✓ Sample customers inserted\n";

    echo "\n✅ All sample data inserted successfully!\n";

} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
