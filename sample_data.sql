-- ============================================
-- Sample Data for Rudra Stories
-- ============================================
-- This file contains sample data to populate the database
-- Import this AFTER importing database_schema.sql
-- ============================================

USE `rudra_stories`;

-- ============================================
-- Sample Thoughts (for homepage)
-- ============================================
INSERT INTO `thoughts` (`Mainthought`, `thought_iden`, `created_at`, `updated_at`) VALUES
('Every story has a beginning, but the best ones never end.', 'THOUGHT001', NOW(), NOW()),
('Stories are the way we preserve what matters most.', 'THOUGHT002', NOW(), NOW()),
('In every story, there is a piece of truth waiting to be discovered.', 'THOUGHT003', NOW(), NOW());

-- ============================================
-- Sample Story Types/Categories
-- ============================================
INSERT INTO `story_type` (`Story_type`, `type_iden`, `view`, `created_at`, `updated_at`) VALUES
('Romance', 'ROMANCE001', 0, NOW(), NOW()),
('Horror', 'HORROR001', 0, NOW(), NOW()),
('Comedy', 'COMEDY001', 0, NOW(), NOW()),
('Drama', 'DRAMA001', 0, NOW(), NOW()),
('Mystery', 'MYSTERY001', 0, NOW(), NOW()),
('Adventure', 'ADVENTURE001', 0, NOW(), NOW()),
('Fantasy', 'FANTASY001', 0, NOW(), NOW()),
('Thriller', 'THRILLER001', 0, NOW(), NOW());

-- ============================================
-- Note: You can add sample stories, users, etc. here
-- But for now, these are the minimum required to avoid errors
-- ============================================
