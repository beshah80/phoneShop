# 🚀 PhoneShop Live Deployment Guide

Follow these steps to move your project from your local computer to a live web server.

## 1. Get Your Live Files
All your files are ready in the `/home/beshah/ecommerce/phoneShop/` directory. 
- You will need to upload everything in this folder to your server's `public_html` directory via FTP.

## 2. Prepare the Database
1. Log in to your hosting panel (e.g., cPanel or Hostinger).
2. Go to **MySQL Databases** and create a new database (e.g., `phonesell_db`).
3. Create a **Database User** and **Password**, then assign the user to the database with "All Privileges".
4. Open **phpMyAdmin**, select your new database, and click **Import**.
5. Upload the file: `sql/MASTER_DEPLOY.sql` from your project.

## 3. Update Configuration
Open `includes/config.php` on your live server and update these lines:

```php
// 1. DATABASE SETTINGS
$db_host = 'localhost';   // Usually localhost
$db_user = 'your_live_user';
$db_pass = 'your_live_password';
$db_name = 'your_live_db_name';

// 2. SITE URL
// If your site is www.example.com, change this to '/'
define('BASE_URL', '/'); 
```

## 4. Final Check
- Ensure the `assets/uploads/` folder exists on the server and has "Write" permissions (CHMOD 755).
- Visit your domain in the browser.
- Try to **Login** and **Post an Ad** to ensure the database and uploads are working.

---
**Congratulations! Your PhoneSell Marketplace is now Live!** 📱✨
