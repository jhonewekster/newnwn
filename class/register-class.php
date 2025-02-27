<?php
require_once __DIR__ . '/../config/connexion.php';

class Register {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($first_name, $last_name, $email, $password) {
        // Check if email already exists
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ["status" => false, "message" => "Email address already exists."];
        }

        // Insert new user
        $query = "INSERT INTO " . $this->table_name . " (first_name, last_name, email, password, type) VALUES (:first_name, :last_name, :email, :password, :type)";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $first_name = htmlspecialchars(strip_tags($first_name));
        $last_name = htmlspecialchars(strip_tags($last_name));
        $email = htmlspecialchars(strip_tags($email));
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $type = 'user'; // Default type for new users

        // bind values
        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":type", $type);

        if ($stmt->execute()) {
            return ["status" => true, "message" => "User was successfully registered."];
        } else {
            return ["status" => false, "message" => "Unable to register the user."];
        }
    }
}
?>