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
