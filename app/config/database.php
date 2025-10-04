<?php
require_once __DIR__ . '/env.php';
class Database {
    private $host = "localhost";
    private $db_name = "library";
    private $username = "root"; 
    private $password = ""; 
    public $conn;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? $this->host;
        $this->db_name = $_ENV['DB_NAME'] ?? $this->db_name;
        $this->username = $_ENV['DB_USER'] ?? $this->username;
        $this->password = $_ENV['DB_PASS'] ?? $this->password;
    }

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }

            if (!$this->conn->set_charset('utf8mb4')) {
                mysqli_set_charset($this->conn, 'utf8mb4');
            }

        } catch (Exception $e) {
            die("Connection error: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>
