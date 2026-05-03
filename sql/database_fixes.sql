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
