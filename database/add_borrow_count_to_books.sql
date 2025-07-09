-- Thêm trường lượt mượn vào bảng books
ALTER TABLE `books` ADD COLUMN `borrow_count` INT(11) NOT NULL DEFAULT 0 AFTER `price`;

-- Cập nhật lượt mượn dựa trên dữ liệu hiện có từ bảng borrowings
UPDATE books b 
SET borrow_count = (
    SELECT COALESCE(SUM(bd.quantity), 0) 
    FROM borrow_details bd 
    JOIN borrowings br ON bd.borrowing_id = br.id 
    WHERE bd.book_id = b.id 
    AND br.status IN ('completed', 'returned')
);

-- Thêm index để tối ưu hiệu suất truy vấn
CREATE INDEX `idx_books_borrow_count` ON `books` (`borrow_count` DESC); 