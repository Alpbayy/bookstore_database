<<<<<<< HEAD
# BookStore Website - PHP Edition

A beautiful, modern, and fully functional online bookstore website built with PHP and MySQL.

## Features

✨ **User Features:**
- Browse books by category
- Search for books by title, author, or publisher
- View detailed book information
- Sort by price and title
- Shopping cart with add/remove functionality
- Secure checkout process
- Order management

🎨 **Design:**
- Modern, responsive design
- Mobile-friendly interface
- Beautiful color scheme
- Smooth animations and transitions
- Professional UI/UX

⚙️ **Technical:**
- PDO (PHP Data Objects) for secure database access
- SQL prepared statements
- Session management
- Secure form validation
- RESTful URL structure

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Setup Steps

1. **Create the Database:**
   ```sql
   -- Run the provided Untitled.sql file in your MySQL client
   mysql -u your_user -p your_database < Untitled.sql
   ```

2. **Configure Database Connection:**
   Edit `config/db.php` and update the following:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_db_password');
   define('DB_NAME', 'bookstore_db');
   ```

3. **Place Files on Web Server:**
   Copy all files to your web server's document root (e.g., htdocs, www, or public_html)

4. **Access the Website:**
   Open your browser and navigate to `http://localhost/depo/` (or your configured path)

## File Structure

```
depo/
├── config/
│   └── db.php                 # Database connection
├── includes/
│   ├── header.php             # Header navigation
│   ├── footer.php             # Footer
│   ├── add-to-cart.php        # Add item to cart
│   ├── remove-from-cart.php   # Remove item from cart
│   └── update-cart.php        # Update cart quantities
├── assets/
│   ├── css/
│   │   └── style.css          # Complete styling
│   ├── js/
│   │   └── script.js          # JavaScript functionality
│   └── images/
│       └── placeholder-book.jpg
├── index.php                  # Homepage with book listing
├── book-detail.php            # Book detail page
├── cart.php                   # Shopping cart page
├── checkout.php               # Checkout page
└── order-success.php          # Order confirmation page
```

## Database Tables

- **Publisher** - Publisher information
- **Category** - Book categories
- **Book** - Book details
- **Author** - Author information
- **Customer** - Customer information
- **Employee** - Employee records
- **Shipper** - Shipping information
- **Order** - Customer orders
- **Book_Author** - Many-to-many relationship
- **Order_Details** - Order line items

## Usage

### Admin/Database Population

Add sample data to your database:

```sql
INSERT INTO Publisher (Name, Address, Website) 
VALUES ('Penguin Books', '123 Publisher St', 'www.penguin.com');

INSERT INTO Category (Name, Description) 
VALUES ('Fiction', 'Fictional novels and stories');

INSERT INTO Author (First_Name, Last_Name, Biography) 
VALUES ('John', 'Doe', 'Award-winning author...');

INSERT INTO Book (ISBN, Title, Pub_Year, Price, Stock_Qty, Publisher_ID, Category_ID) 
VALUES ('978-1234567890', 'Sample Book Title', 2023, 19.99, 50, 1, 1);
```

## Features in Detail

### Homepage
- Hero section with search functionality
- Category filtering
- Sorting options (price, title)
- Book grid display with book cards

### Book Detail Page
- Full book information
- Author details
- Stock availability
- Add to cart functionality
- Related books

### Shopping Cart
- View all items in cart
- Update quantities
- Remove items
- Order summary with totals
- Tax and shipping calculation

### Checkout
- Customer information form
- Order summary
- Real-time total calculation
- Order confirmation with details

## Customization

### Colors
Edit color variables in `assets/css/style.css`:
```css
:root {
    --primary-color: #1e40af;
    --secondary-color: #0f172a;
    --accent-color: #f59e0b;
    /* ... more colors ... */
}
```

### Add Book Images
Replace `placeholder-book.jpg` with actual book cover images in the `assets/images/` directory.

### Contact Information
Update footer contact details in `includes/footer.php`

## Security Considerations

✅ **Implemented Security Features:**
- SQL prepared statements to prevent SQL injection
- Session management for cart
- HTML entity encoding to prevent XSS
- Input validation on checkout form

⚠️ **Recommendations for Production:**
- Use HTTPS
- Add authentication system (login/register)
- Implement CSRF tokens
- Add SSL certificate
- Use environment variables for database credentials
- Implement proper payment gateway
- Add logging and monitoring

## Performance Tips

- Add book cover images to improve page loading
- Implement caching for categories
- Use database indexes on frequently queried fields
- Compress CSS and JavaScript files
- Optimize images

## Troubleshooting

**Database Connection Error:**
- Check database credentials in `config/db.php`
- Ensure MySQL is running
- Verify database name is correct

**No books displayed:**
- Insert sample books into the database
- Check database tables are created
- Review MySQL error logs

**Cart not working:**
- Ensure sessions are enabled in PHP
- Check session save path permissions
- Verify cookies are not disabled in browser

## Support

For issues or questions:
1. Check the error logs
2. Verify database connections
3. Test with sample data
4. Review PHP error reporting

## License

This project is available for personal and commercial use.

## Credits

Built with PHP, MySQL, and modern web technologies.
Responsive design using CSS Grid and Flexbox.

---

**Version:** 1.0
**Last Updated:** December 2025
=======
# bookstore_database
This project was conducted to provide a better understanding of how a sample database structure is used in real-world environments.
>>>>>>> 04c5db92c354a00de8dd22c7117dc7946a65baf5
