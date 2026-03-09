-- Run in phpMyAdmin with rudra_stories database selected.
-- This script supports Razorpay checkout, free/paid books, PDF library access, and resale market.

CREATE TABLE IF NOT EXISTS `books_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int(11) NOT NULL DEFAULT '0',
  `access_type` enum('free','paid') NOT NULL DEFAULT 'paid',
  `book_type` enum('digital','physical','both') NOT NULL DEFAULT 'physical',
  `cover_image` varchar(255) DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `allow_resale` tinyint(1) NOT NULL DEFAULT '1',
  `resale_price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `books_store` ADD COLUMN IF NOT EXISTS `access_type` enum('free','paid') NOT NULL DEFAULT 'paid' AFTER `stock`;
ALTER TABLE `books_store` ADD COLUMN IF NOT EXISTS `book_type` enum('digital','physical','both') NOT NULL DEFAULT 'physical' AFTER `access_type`;
ALTER TABLE `books_store` ADD COLUMN IF NOT EXISTS `pdf_file` varchar(255) DEFAULT NULL AFTER `cover_image`;
ALTER TABLE `books_store` ADD COLUMN IF NOT EXISTS `allow_resale` tinyint(1) NOT NULL DEFAULT '1' AFTER `pdf_file`;
ALTER TABLE `books_store` ADD COLUMN IF NOT EXISTS `resale_price` decimal(10,2) DEFAULT NULL AFTER `allow_resale`;

CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_identity` varchar(255) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_user_identity_idx` (`user_identity`),
  KEY `cart_book_id_idx` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `book_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `user_identity` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `city` varchar(120) NOT NULL,
  `state` varchar(120) NOT NULL,
  `pincode` varchar(12) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(100) DEFAULT NULL,
  `order_status` varchar(20) NOT NULL DEFAULT 'processing',
  `subtotal_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number_unique` (`order_number`),
  KEY `orders_user_identity_idx` (`user_identity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `book_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `line_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `book_type_snapshot` varchar(20) DEFAULT NULL,
  `access_type_snapshot` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_idx` (`order_id`),
  KEY `order_items_book_id_idx` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `book_order_items` ADD COLUMN IF NOT EXISTS `book_type_snapshot` varchar(20) DEFAULT NULL AFTER `line_total`;
ALTER TABLE `book_order_items` ADD COLUMN IF NOT EXISTS `access_type_snapshot` varchar(20) DEFAULT NULL AFTER `book_type_snapshot`;

CREATE TABLE IF NOT EXISTS `user_book_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_identity` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `book_id` int(11) NOT NULL,
  `source` enum('free','purchase','resale','admin') NOT NULL DEFAULT 'purchase',
  `order_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_book_access_unique` (`user_identity`,`book_id`),
  KEY `user_book_access_user_idx` (`user_identity`),
  KEY `user_book_access_book_idx` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `book_resale_listings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `source_order_item_id` int(11) NOT NULL,
  `seller_identity` varchar(255) NOT NULL,
  `seller_username` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','sold','cancelled') NOT NULL DEFAULT 'active',
  `buyer_identity` varchar(255) DEFAULT NULL,
  `sold_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `book_resale_seller_idx` (`seller_identity`),
  KEY `book_resale_status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `books_store`
(`title`, `author`, `description`, `price`, `stock`, `access_type`, `book_type`, `cover_image`, `pdf_file`, `allow_resale`, `resale_price`, `is_active`, `created_at`, `updated_at`)
VALUES
('Gujarati Story Basics', 'Rudra Stories', 'Beginner guide to Gujarati storytelling.', 0.00, 1000, 'free', 'digital', NULL, 'gujarati-story-basics.pdf', 0, NULL, 1, NOW(), NOW()),
('Micro Tales Vol 1', 'Rudra Stories', 'Short micro-tales for quick reading.', 0.00, 1000, 'free', 'digital', NULL, 'micro-tales-vol-1.pdf', 0, NULL, 1, NOW(), NOW()),
('Hindi Plot Builder', 'Rudra Stories', 'Create story plots with practical templates.', 0.00, 1000, 'free', 'digital', NULL, 'hindi-plot-builder.pdf', 0, NULL, 1, NOW(), NOW()),
('Character Craft Lite', 'Rudra Stories', 'Simple character creation workbook.', 0.00, 1000, 'free', 'digital', NULL, 'character-craft-lite.pdf', 0, NULL, 1, NOW(), NOW()),
('Free Fiction Sampler', 'Rudra Stories', 'Sample chapters from popular fiction.', 0.00, 1000, 'free', 'digital', NULL, 'free-fiction-sampler.pdf', 0, NULL, 1, NOW(), NOW()),
('Weekend Story Prompts', 'Rudra Stories', '52 writing prompts for weekends.', 0.00, 1000, 'free', 'digital', NULL, 'weekend-story-prompts.pdf', 0, NULL, 1, NOW(), NOW()),
('Dialogue Drill Pack', 'Rudra Stories', 'Exercises to improve story dialogue.', 0.00, 1000, 'free', 'digital', NULL, 'dialogue-drill-pack.pdf', 0, NULL, 1, NOW(), NOW()),
('Mystery Mini Guide', 'Rudra Stories', 'How to write clean mystery arcs.', 0.00, 1000, 'free', 'digital', NULL, 'mystery-mini-guide.pdf', 0, NULL, 1, NOW(), NOW()),
('Fantasy World Seeds', 'Rudra Stories', 'Starter ideas for fantasy worlds.', 0.00, 1000, 'free', 'digital', NULL, 'fantasy-world-seeds.pdf', 0, NULL, 1, NOW(), NOW()),
('Self Editing Checklist', 'Rudra Stories', 'Polish your drafts before publishing.', 0.00, 1000, 'free', 'digital', NULL, 'self-editing-checklist.pdf', 0, NULL, 1, NOW(), NOW()),
('The Writer''s Mind Pro', 'Rudra', 'Advanced writing system with practical frameworks.', 299.00, 40, 'paid', 'both', NULL, 'writers-mind-pro.pdf', 1, 179.00, 1, NOW(), NOW()),
('Shadows Of Time Premium', 'Manish Pandey', 'Thriller anthology with bonus ending chapters.', 399.00, 28, 'paid', 'both', NULL, 'shadows-of-time-premium.pdf', 1, 249.00, 1, NOW(), NOW()),
('Story Craft Gujarati Pro', 'Dev Team', 'Complete Gujarati storytelling master course.', 249.00, 50, 'paid', 'both', NULL, 'story-craft-gujarati-pro.pdf', 1, 149.00, 1, NOW(), NOW()),
('Epic Tales Collector''s', 'Rudra Stories', 'Collectors edition with signed physical print.', 499.00, 18, 'paid', 'physical', NULL, NULL, 1, 299.00, 1, NOW(), NOW()),
('Myth To Modern', 'Rudra Stories', 'Mythology inspired modern fiction toolkit.', 349.00, 25, 'paid', 'both', NULL, 'myth-to-modern.pdf', 1, 209.00, 1, NOW(), NOW()),
('Crime Arc Blueprint', 'Rudra Stories', 'Design crime stories with high tension beats.', 289.00, 32, 'paid', 'digital', NULL, 'crime-arc-blueprint.pdf', 0, NULL, 1, NOW(), NOW()),
('Romance Story Engine', 'Rudra Stories', 'Framework for romantic fiction writing.', 269.00, 34, 'paid', 'digital', NULL, 'romance-story-engine.pdf', 0, NULL, 1, NOW(), NOW()),
('Sci-Fi Plot Engine', 'Rudra Stories', 'Build believable science fiction narratives.', 379.00, 22, 'paid', 'both', NULL, 'sci-fi-plot-engine.pdf', 1, 229.00, 1, NOW(), NOW()),
('Suspense Design Manual', 'Rudra Stories', 'Control pacing and cliffhangers effectively.', 319.00, 29, 'paid', 'both', NULL, 'suspense-design-manual.pdf', 1, 189.00, 1, NOW(), NOW()),
('Screen Adaptation Guide', 'Rudra Stories', 'Convert stories into screenplay format.', 459.00, 20, 'paid', 'both', NULL, 'screen-adaptation-guide.pdf', 1, 279.00, 1, NOW(), NOW());
