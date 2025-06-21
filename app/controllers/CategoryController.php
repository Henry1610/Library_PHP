<?php
require_once __DIR__ . '/../models/Category.php';
class CategoryController {
    public function list($error = null) {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        require __DIR__ . '/../views/admin/categories/list.php';
    }
    public function add() {
        $categoryModel = new Category();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description']
            ];
            $categoryModel->add($data);
            header('Location: index.php?action=categories');
            exit;
        }
        require __DIR__ . '/../views/admin/categories/add.php';
    }
    public function edit() {
        $categoryModel = new Category();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=categories');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description']
            ];
            $categoryModel->update($id, $data);
            header('Location: index.php?action=categories');
            exit;
        }
        $category = $categoryModel->getById($id);
        require __DIR__ . '/../views/admin/categories/edit.php';
    }
    public function delete() {
        $categoryModel = new Category();
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($categoryModel->hasBooks($id)) {
                $error = 'Không thể xóa: Danh mục này vẫn còn sách!';
                $this->list($error);
                return;
            }
            $categoryModel->delete($id);
        }
        header('Location: index.php?action=categories');
        exit;
    }
} 