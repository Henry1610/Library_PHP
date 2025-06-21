<?php
class Database {
    private $host = "localhost";
    private $db_name = "library";
    private $username = "root"; // hoặc user của bạn
    private $password = ""; // mật khẩu MySQL, nếu có
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }

        } catch (Exception $e) {
            die("Connection error: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>
