-- ============================================
-- RUDRA STORIES - Complete Database Schema
-- ============================================
-- This SQL file contains all the database tables needed for Rudra Stories application
-- Import this file in phpMyAdmin after creating the database 'rudra_stories'
-- ============================================

-- Create Database (if not exists)
CREATE DATABASE IF NOT EXISTS `rudra_stories` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rudra_stories`;

-- ============================================
-- Table: usersignupinfo
-- Description: User registration and profile information
-- ============================================
CREATE TABLE IF NOT EXISTS `usersignupinfo` (
  `S_No` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `UserMobile` varchar(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'inactive',
  `uidenkk` varchar(255) NOT NULL,
  `images` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`S_No`),
  UNIQUE KEY `UserName` (`UserName`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: all_stories
-- Description: Main stories table
-- ============================================
CREATE TABLE IF NOT EXISTS `all_stories` (
  `story_id` int(11) NOT NULL AUTO_INCREMENT,
  `story_heading` varchar(255) NOT NULL,
  `story_type` int(11) NOT NULL,
  `story_desc` text NOT NULL,
  `written_by` varchar(255) NOT NULL,
  `main_story` longtext NOT NULL,
  `stry_likes` int(11) DEFAULT '0',
  `images` varchar(255) DEFAULT NULL,
  `view` int(11) DEFAULT '0',
  `story_identy` varchar(255) NOT NULL,
  `post_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`story_id`),
  UNIQUE KEY `story_identy` (`story_identy`),
  KEY `story_type` (`story_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: story_type
-- Description: Story categories/types
-- ============================================
CREATE TABLE IF NOT EXISTS `story_type` (
  `sno` int(11) NOT NULL AUTO_INCREMENT,
  `Story_type` varchar(255) NOT NULL,
  `type_iden` varchar(255) NOT NULL,
  `view` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`sno`),
  UNIQUE KEY `type_iden` (`type_iden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: comment_section
-- Description: Comments on main stories
-- ============================================
CREATE TABLE IF NOT EXISTS `comment_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_by` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `stry_iden` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stry_iden` (`stry_iden`),
  KEY `comment_by` (`comment_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: story_parts
-- Description: Parts/chapters of stories
-- ============================================
CREATE TABLE IF NOT EXISTS `story_parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_no` int(11) NOT NULL,
  `mainstry_id` int(11) NOT NULL,
  `story_heading` varchar(255) NOT NULL,
  `story_desc` text NOT NULL,
  `story_identy` varchar(255) NOT NULL,
  `story_type` int(11) DEFAULT NULL,
  `stry_likes` int(11) DEFAULT '0',
  `view` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `story_identy` (`story_identy`),
  KEY `mainstry_id` (`mainstry_id`),
  KEY `story_type` (`story_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: stry_part_comments
-- Description: Comments on story parts
-- ============================================
CREATE TABLE IF NOT EXISTS `stry_part_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_by` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `part_stry_identy` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `part_stry_identy` (`part_stry_identy`),
  KEY `comment_by` (`comment_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: thoughts
-- Description: Thoughts/quotes displayed on homepage
-- ============================================
CREATE TABLE IF NOT EXISTS `thoughts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Mainthought` text NOT NULL,
  `thought_iden` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `thought_iden` (`thought_iden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: subs
-- Description: Subscribers list
-- ============================================
CREATE TABLE IF NOT EXISTS `subs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Subscriber_Identy` varchar(255) NOT NULL,
  `Subscriber_Email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Subscriber_Identy` (`Subscriber_Identy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: helpquery
-- Description: Help/contact form queries
-- ============================================
CREATE TABLE IF NOT EXISTS `helpquery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Laravel Standard Tables (if needed)
-- ============================================

-- Password Resets Table
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Failed Jobs Table
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Personal Access Tokens Table
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Foreign Key Constraints (Optional)
-- ============================================
-- Note: Uncomment these if you want to enforce referential integrity

-- ALTER TABLE `all_stories`
--   ADD CONSTRAINT `fk_story_type` FOREIGN KEY (`story_type`) REFERENCES `story_type` (`sno`) ON DELETE CASCADE;

-- ALTER TABLE `comment_section`
--   ADD CONSTRAINT `fk_comment_story` FOREIGN KEY (`stry_iden`) REFERENCES `all_stories` (`story_identy`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`comment_by`) REFERENCES `usersignupinfo` (`UserName`) ON DELETE CASCADE;

-- ALTER TABLE `story_parts`
--   ADD CONSTRAINT `fk_part_story` FOREIGN KEY (`mainstry_id`) REFERENCES `all_stories` (`story_id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_part_type` FOREIGN KEY (`story_type`) REFERENCES `story_type` (`sno`) ON DELETE SET NULL;

-- ALTER TABLE `stry_part_comments`
--   ADD CONSTRAINT `fk_part_comment_story` FOREIGN KEY (`part_stry_identy`) REFERENCES `story_parts` (`story_identy`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_part_comment_user` FOREIGN KEY (`comment_by`) REFERENCES `usersignupinfo` (`UserName`) ON DELETE CASCADE;

-- ============================================
-- Sample Data (Optional - for testing)
-- ============================================
-- You can uncomment and modify these if you want sample data

-- INSERT INTO `story_type` (`Story_type`, `type_iden`, `view`) VALUES
-- ('Romance', 'ROMANCE001', 0),
-- ('Horror', 'HORROR001', 0),
-- ('Comedy', 'COMEDY001', 0),
-- ('Drama', 'DRAMA001', 0),
-- ('Mystery', 'MYSTERY001', 0);

-- ============================================
-- End of Database Schema
-- ============================================
