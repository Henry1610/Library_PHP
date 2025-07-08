<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Category.php';
class BookController {
    public function list() {
        $bookModel = new Book();
        $categoryModel = new Category();
        $books = $bookModel->getAll();
        $categories = $categoryModel->getAll();
        require __DIR__ . '/../views/admin/books/list.php';
    }
    public function add() {
        $bookModel = new Book();
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cover_img = '';
            if (!empty($_FILES['cover_img']['name'])) {
                $targetDir = 'uploads/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . '_' . basename($_FILES['cover_img']['name']);
                $targetFile = $targetDir . $fileName;
                if (move_uploaded_file($_FILES['cover_img']['tmp_name'], $targetFile)) {
                    $cover_img = $targetFile;
                }
            } else if (!empty($_POST['cover_img_url'])) {
                $cover_img = $_POST['cover_img_url'];
            }
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'publisher' => $_POST['publisher'],
                'year' => $_POST['year'],
                'category_id' => $_POST['category_id'],
                'isbn' => $_POST['isbn'],
                'cover_img' => $cover_img,
                'quantity' => $_POST['quantity'],
                'available' => $_POST['available'] ?? 1,
                'price' => $_POST['price'] ?? 0
            ];
            $bookModel->add($data);
            header('Location: admin.php?action=books');
            exit;
        }
        require __DIR__ . '/../views/admin/books/add.php';
    }
    public function edit() {
        $bookModel = new Book();
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=books');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cover_img = $_POST['old_cover_img'] ?? '';
            if (!empty($_FILES['cover_img']['name'])) {
                $targetDir = 'uploads/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . '_' . basename($_FILES['cover_img']['name']);
                $targetFile = $targetDir . $fileName;
                if (move_uploaded_file($_FILES['cover_img']['tmp_name'], $targetFile)) {
                    $cover_img = $targetFile;
                }
            } else if (!empty($_POST['cover_img_url'])) {
                $cover_img = $_POST['cover_img_url'];
            }
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'publisher' => $_POST['publisher'],
                'year' => $_POST['year'],
                'category_id' => $_POST['category_id'],
                'isbn' => $_POST['isbn'],
                'cover_img' => $cover_img,
                'quantity' => $_POST['quantity'],
                'available' => $_POST['available'] ?? 1,
                'price' => $_POST['price'] ?? 0
            ];
            $bookModel->update($id, $data);
            header('Location: admin.php?action=books');
            exit;
        }
        $book = $bookModel->getById($id);
        require __DIR__ . '/../views/admin/books/edit.php';
    }
    public function delete() {
        $bookModel = new Book();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $bookModel->delete($id);
        }
        header('Location: admin.php?action=books');
        exit;
    }
} 