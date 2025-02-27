<?php
class Database {
    private $host = "localhost"; // Change if needed (e.g., "mysql.hostinger.com")
    private $db_name = "u742764384_Treasu"; // Your real database name
    private $username = "u742764384_Treasu"; // Your database username
    private $password = "4555Oed@@54218"; // Your database password
    public $conn;

    // Constructor
    public function __construct() {
        $this->connectDB();
    }

    // Connect to database
    private function connectDB() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name;charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    // Get connection
    public function getConnection() {
        return $this->conn;
    }
}

// Create a database instance
$db = new Database();
$conn = $db->getConnection();
?>
