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
