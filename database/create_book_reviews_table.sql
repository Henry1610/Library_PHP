-- Tạo bảng book_reviews
CREATE TABLE IF NOT EXISTS `book_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrowing_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `comment` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_book_borrowing` (`user_id`, `book_id`, `borrowing_id`),
  KEY `fk_book_reviews_user` (`user_id`),
  KEY `fk_book_reviews_book` (`book_id`),
  KEY `fk_book_reviews_borrowing` (`borrowing_id`),
  CONSTRAINT `fk_book_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_book_reviews_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_book_reviews_borrowing` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm index để tối ưu hiệu suất truy vấn
CREATE INDEX `idx_book_reviews_book_rating` ON `book_reviews` (`book_id`, `rating`);
CREATE INDEX `idx_book_reviews_created_at` ON `book_reviews` (`created_at` DESC); 