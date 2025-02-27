<?php
require_once __DIR__ . '/../config/connexion.php';

class Index_ugtri {
    private $conn;
    private $table_name = "newsletter";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

   public function addSubscriber($email) {
        $query = "INSERT INTO " . $this->table_name . " (email) VALUES (:email)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            return ["status" => true, "message" => "Thank you for subscribing!"];
        } else {
            return ["status" => false, "message" => "Error: " . $stmt->errorInfo()[2]];
        }
    }
}
?>