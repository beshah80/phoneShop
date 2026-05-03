# PhoneShop Setup Guide

Follow these steps to set up and run the PhoneShop project on your local machine.

## Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) (Recommended for Windows, macOS, and Linux)

## Step-by-Step Installation

### 1. Project Placement
Copy the entire `phoneShop` folder into your XAMPP's web root directory:
- **Windows**: `C:\xampp\htdocs\`
- **Linux**: `/opt/lampp/htdocs/`
- **macOS**: `/Applications/XAMPP/htdocs/`

### 2. Start Services
1. Open the **XAMPP Control Panel**.
2. Start the **Apache** and **MySQL** services.

### 3. Database Setup
1. Open your browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Create a new database named `shop_db`.
3. Click on the `shop_db` database in the left sidebar.
4. Go to the **Import** tab.
5. Choose the SQL files from the `sql/` directory in the following order:
   - `shop_db.sql` (Main schema)
   - `payment_methods.sql`
   - `payment_tables.sql`
6. Click **Go** to import.

### 4. Configuration
Ensure the database credentials in `includes/config.php` match your local setup. By default:
```php
$conn = mysqli_connect('localhost', 'root', '', 'shop_db');
```
*(If you have a password for your local MySQL root user, add it as the third argument.)*

### 5. Running the Application
Open your browser and navigate to:
[http://localhost/phoneShop/home.php](http://localhost/phoneShop/home.php)

---

## Folder Structure Overview
- `/admin`: Admin dashboard and management tools.
- `/seller`: Seller tools and application status.
- `/assets`: Images, CSS, and JS files.
- `/includes`: Shared logic and components.
- `/sql`: Database backup files.

## Troubleshooting
- **Image not showing**: Check if the `assets/uploads/` directory has write permissions.
- **Database Error**: Verify that the database name in `includes/config.php` matches what you created in phpMyAdmin.
