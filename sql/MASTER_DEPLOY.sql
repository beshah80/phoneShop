-- Fix database schema issues for phoneShop

-- Add missing seller_id column to products table
ALTER TABLE `products` ADD COLUMN `seller_id` int(100) DEFAULT NULL AFTER `image`;

-- Add missing phone_number column to users table  
ALTER TABLE `users` ADD COLUMN `phone_number` varchar(20) DEFAULT NULL AFTER `email`;

-- Add status column to products table for better Jiji-like functionality
ALTER TABLE `products` ADD COLUMN `status` enum('available','sold') DEFAULT 'available' AFTER `details`;

-- Add is_premium column for premium listings
ALTER TABLE `products` ADD COLUMN `is_premium` tinyint(1) DEFAULT 0 AFTER `status`;

-- Update existing products with seller_id (assign to admin user)
UPDATE `products` SET `seller_id` = 18 WHERE `seller_id` IS NULL;

-- Update image paths to remove ../uploaded_img/ prefix
UPDATE `products` SET `image` = REPLACE(`image`, '../uploaded_img/', '') WHERE `image` LIKE '../uploaded_img/%';

-- Add phone numbers to existing users
UPDATE `users` SET `phone_number` = '+251 911 123 456' WHERE `id` = 10;
UPDATE `users` SET `phone_number` = '+251 912 234 567' WHERE `id` = 14;
UPDATE `users` SET `phone_number` = '+251 913 345 678' WHERE `id` = 15;
UPDATE `users` SET `phone_number` = '+251 914 456 789' WHERE `id` = 16;
UPDATE `users` SET `phone_number` = '+251 915 567 890' WHERE `id` = 17;
UPDATE `users` SET `phone_number` = '+251 916 678 901' WHERE `id` = 18;

-- Set some products as premium for demonstration
UPDATE `products` SET `is_premium` = 1 WHERE `id` IN (1, 2, 6);
-- Fix remaining database schema issues for phoneShop

-- Add missing phone_number column to users table (if not exists)
ALTER TABLE `users` ADD COLUMN `phone_number` varchar(20) DEFAULT NULL AFTER `email`;

-- Add status column to products table (if not exists)
ALTER TABLE `products` ADD COLUMN `status` enum('available','sold') DEFAULT 'available' AFTER `details`;

-- Add is_premium column for premium listings (if not exists)
ALTER TABLE `products` ADD COLUMN `is_premium` tinyint(1) DEFAULT 0 AFTER `status`;

-- Update image paths to remove ../uploaded_img/ prefix
UPDATE `products` SET `image` = REPLACE(`image`, '../uploaded_img/', '') WHERE `image` LIKE '../uploaded_img/%';

-- Add phone numbers to existing users (if not already set)
UPDATE `users` SET `phone_number` = '+251 911 123 456' WHERE `id` = 10 AND `phone_number` IS NULL;
UPDATE `users` SET `phone_number` = '+251 912 234 567' WHERE `id` = 14 AND `phone_number` IS NULL;
UPDATE `users` SET `phone_number` = '+251 913 345 678' WHERE `id` = 15 AND `phone_number` IS NULL;
UPDATE `users` SET `phone_number` = '+251 914 456 789' WHERE `id` = 16 AND `phone_number` IS NULL;
UPDATE `users` SET `phone_number` = '+251 915 567 890' WHERE `id` = 17 AND `phone_number` IS NULL;
UPDATE `users` SET `phone_number` = '+251 916 678 901' WHERE `id` = 18 AND `phone_number` IS NULL;

-- Set some products as premium for demonstration
UPDATE `products` SET `is_premium` = 1 WHERE `id` IN (1, 2, 6) AND `is_premium` = 0;
-- Final database fixes - only update data, don't alter schema

-- Update image paths to remove ../uploaded_img/ prefix
UPDATE `products` SET `image` = REPLACE(`image`, '../uploaded_img/', '') WHERE `image` LIKE '../uploaded_img/%';

-- Add phone numbers to existing users (if not already set)
UPDATE `users` SET `phone_number` = '+251 911 123 456' WHERE `id` = 10 AND (`phone_number` IS NULL OR `phone_number` = '');
UPDATE `users` SET `phone_number` = '+251 912 234 567' WHERE `id` = 14 AND (`phone_number` IS NULL OR `phone_number` = '');
UPDATE `users` SET `phone_number` = '+251 913 345 678' WHERE `id` = 15 AND (`phone_number` IS NULL OR `phone_number` = '');
UPDATE `users` SET `phone_number` = '+251 914 456 789' WHERE `id` = 16 AND (`phone_number` IS NULL OR `phone_number` = '');
UPDATE `users` SET `phone_number` = '+251 915 567 890' WHERE `id` = 17 AND (`phone_number` IS NULL OR `phone_number` = '');
UPDATE `users` SET `phone_number` = '+251 916 678 901' WHERE `id` = 18 AND (`phone_number` IS NULL OR `phone_number` = '');

-- Set some products as premium for demonstration
UPDATE `products` SET `is_premium` = 1 WHERE `id` IN (1, 2, 6) AND (`is_premium` IS NULL OR `is_premium` = 0);
-- Create payment_methods table
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert payment methods
INSERT INTO `payment_methods` (`name`, `code`, `icon`, `is_active`) VALUES
('Telebirr', 'telebirr', 'fab fa-telegram', 1),
('Amole', 'amole', 'fas fa-wallet', 1),
('CBE Birr', 'cbe_birr', 'fas fa-university', 1),
('Dashen Bank', 'dashen', 'fas fa-university', 1),
('Cash on Delivery', 'cod', 'fas fa-money-bill-wave', 1),
('Credit Card', 'credit_card', 'far fa-credit-card', 1);

-- Create payment_transactions table
CREATE TABLE IF NOT EXISTS `payment_transactions` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `order_id` int(100) NOT NULL,
  `payment_method_id` int(100) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'ETB',
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `payment_method_id` (`payment_method_id`),
  CONSTRAINT `payment_transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_transactions_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create order_items table
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add payment_method_id column to orders table if it doesn't exist
ALTER TABLE `orders` 
ADD COLUMN IF NOT EXISTS `payment_method_id` int(100) NULL,
ADD COLUMN IF NOT EXISTS `currency` varchar(3) NOT NULL DEFAULT 'ETB',
ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`); USE `shop_db`;

-- Drop tables if they exist (in correct order due to foreign key constraints)
DROP TABLE IF EXISTS `payment_transactions`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `payment_methods`;

-- Create payment_methods table
CREATE TABLE `payment_methods` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert payment methods
INSERT INTO `payment_methods` (`name`, `code`, `icon`, `is_active`) VALUES
('Telebirr', 'telebirr', 'fab fa-telegram', 1),
('Amole', 'amole', 'fas fa-wallet', 1),
('CBE Birr', 'cbe_birr', 'fas fa-university', 1),
('Dashen Bank', 'dashen', 'fas fa-university', 1),
('Cash on Delivery', 'cod', 'fas fa-money-bill-wave', 1),
('Credit Card', 'credit_card', 'far fa-credit-card', 1);

-- Create payment_transactions table
CREATE TABLE `payment_transactions` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `order_id` int(100) NOT NULL,
  `payment_method_id` int(100) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'ETB',
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `payment_method_id` (`payment_method_id`),
  CONSTRAINT `payment_transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_transactions_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create order_items table
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add payment_method_id column to orders table if it doesn't exist
ALTER TABLE `orders` 
ADD COLUMN IF NOT EXISTS `payment_method_id` int(100) NULL,
ADD COLUMN IF NOT EXISTS `currency` varchar(3) NOT NULL DEFAULT 'ETB',
ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`); -- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 02:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(129, 14, 16, 'lavendor rose', 13, 1, 'lavendor rose.jpg'),
(130, 14, 18, 'red tulipa', 11, 1, 'red tulipa.jpg'),
(131, 14, 15, 'cottage rose', 15, 1, 'cottage rose.jpg'),
(132, 15, 13, 'pink rose', 10, 1, 'pink roses.jpg'),
(133, 15, 15, 'cottage rose', 15, 1, 'cottage rose.jpg'),
(134, 15, 16, 'lavendor rose', 13, 3, 'lavendor rose.jpg'),
(135, 17, 3, 'Google Pixel 7a', 499, 1, '../uploaded_img/google_pixel_7a.png'),
(136, 0, 1, 'Apple iPhone 14', 799, 1, 'uploaded_img/../uploaded_img/apple_iphone_14.png'),
(138, 0, 3, 'Google Pixel 7a', 499, 1, 'uploaded_img/../uploaded_img/google_pixel_7a.png'),
(139, 17, 6, 'Huawei P60 Pro', 899, 1, '../uploaded_img/huawei_p60_pro.png'),
(140, 16, 2, 'Apple iPhone 15 Pro', 999, 1, '../uploaded_img/apple_iphone_15_pro.png');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(13, 14, 'shaikh anas', 'shaikh@gmail.com', '0987654321', 'hi, how are you?');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(17, 14, 'shaikh anas', '0987654321', 'shaikh@gmail.com', 'credit card', 'flat no. 321, jogeshwari, mumbai, india - 654321', ', cottage rose (3) , pink bouquet (1) , yellow queen rose (1) ', 80, '11-Mar-2022', 'pending'),
(18, 14, 'shaikh anas', '1234567899', 'shaikh@gmail.com', 'paypal', 'flat no. 321, jogeshwari, mumbai, india - 654321', ', yellow queen rose (1) , pink rose (2) ', 40, '11-Mar-2022', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `image`) VALUES
(1, 'Apple iPhone 14', '6.1-inch Super Retina XDR display, A15 Bionic chip, dual-camera system', 799, '../uploaded_img/apple_iphone_14.png'),
(2, 'Apple iPhone 15 Pro', '6.1-inch ProMotion display, A17 Pro chip, triple-camera system with LiDAR', 999, '../uploaded_img/apple_iphone_15_pro.png'),
(3, 'Google Pixel 7a', '6.1-inch OLED display, Google Tensor G2 chip, dual-camera system', 499, '../uploaded_img/google_pixel_7a.png'),
(4, 'Google Pixel 8', '6.2-inch Actua display, Google Tensor G3 chip, advanced AI camera', 699, '../uploaded_img/google_pixel_8.png'),
(5, 'Huawei Mate 50', '6.7-inch OLED display, Snapdragon 8+ Gen 1, 50MP ultra-aperture camera', 799, '../uploaded_img/huawei_mate_50.png'),
(6, 'Huawei P60 Pro', '6.67-inch LTPO OLED, Snapdragon 8+ Gen 1, 48MP RYYB camera', 899, '../uploaded_img/huawei_p60_pro.png'),
(7, 'OnePlus 11', '6.7-inch AMOLED 120Hz display, Snapdragon 8 Gen 2, 50MP triple-camera', 699, '../uploaded_img/oneplus_11.png'),
(8, 'Samsung Galaxy A03', '6.5-inch HD+ display, Unisoc T606, 48MP dual-camera', 129, '../uploaded_img/Samsung_Galaxy_A03.jpg'),
(9, 'Samsung Galaxy S23', '6.1-inch Dynamic AMOLED 2X, Snapdragon 8 Gen 2, 50MP camera', 799, '../uploaded_img/samsung_galaxy_s23.png'),
(10, 'Samsung Galaxy Z Fold', '7.6-inch foldable AMOLED, Snapdragon 8+ Gen 1, multi-camera system', 1799, '../uploaded_img/samsung_galaxy_z_fold.png'),
(11, 'Xiaomi 14', '6.36-inch AMOLED 120Hz, Snapdragon 8 Gen 3, 50MP Leica camera', 799, '../uploaded_img/xiaomi_14.png'),
(12, 'Xiaomi Redmi Note 12', '6.67-inch AMOLED 120Hz, Snapdragon 685, 50MP triple-camera', 249, '../uploaded_img/xiaomi_redmi_note_12.png');

-- --------------------------------------------------------

--
-- Table structure for table `seller_applications`
--

CREATE TABLE `seller_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `business_address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(10, 'admin A', 'admin01@gmail.com', '698d51a19d8a121ce581499d7b701668', 'admin'),
(14, 'user A', 'user01@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user'),
(15, 'user B', 'user02@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user'),
(16, 'frezer', 'fre@gmail.com', '$2y$10$tbT7buJBR8SsjmUJyBIwn..M80/CTtwueffvu3fz8DPxn4yAAscbS', 'user'),
(17, 'kenbon ', 'kenbon@gmail.com', '$2y$10$A4tiJzPfFTwYVxFS74e.neXO.l9hisSLW9yeobBgKQYVqbhxt4qE6', 'user'),
(18, 'beshah', 'besh@gmail.com', '$2y$10$EXVvBwis4X/SkpOh4s1rCe1zL9vXcdohd6L78LPiEV8t7mUzDr8Sm', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `pid`, `name`, `price`, `image`) VALUES
(60, 14, 19, 'pink bouquet', 15, 'pink bouquet.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_applications`
--
ALTER TABLE `seller_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `seller_applications`
--
ALTER TABLE `seller_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `seller_applications`
--
ALTER TABLE `seller_applications`
  ADD CONSTRAINT `seller_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
