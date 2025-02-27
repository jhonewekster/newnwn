<?php
require_once __DIR__ . '/../config/connexion.php';

class DashboardAdmin {
    private $conn;
    private $table_name = "products";
    private $settings_table_name = "websitesetting";
    private $items_per_page = 10;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getSettings() {
        $query = "SELECT * FROM " . $this->settings_table_name . " LIMIT 1";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $settings = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($settings) {
                return $settings;
            } else {
                return [
                    'store_name' => '',
                    'address_store' => '',
                    'mail_business' => '',
                    'phone' => '',
                    'header_background' => '',
                    'privacy_policy' => '',
                    'terms_of_service' => '',
                    'shipping_policy' => '',
                    'payment_policy' => '',
                    'refund_returns_policy' => '',
                    'dmca_policy' => ''
                ];
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [
                'store_name' => '',
                'address_store' => '',
                'mail_business' => '',
                'phone' => '',
                'header_background' => '',
                'privacy_policy' => '',
                'terms_of_service' => '',
                'shipping_policy' => '',
                'payment_policy' => '',
                'refund_returns_policy' => '',
                'dmca_policy' => ''
            ];
        }
    }

    public function updateSettings($data) {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $query = "UPDATE " . $this->settings_table_name . " SET " . implode(", ", $fields) . " WHERE id = 1";
    
        try {
            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo "Error: " . $e->getMessage(); // Display error for debugging
            return false;
        }
    }

    public function getProducts($page = 1, $search = '', $category = '') {
        $offset = ($page - 1) * $this->items_per_page;
        
        $query = "SELECT id, title, price, image1, image2, image3, author, category, description 
                 FROM " . $this->table_name;
        
        $conditions = [];
        $params = [];
        
        if (!empty($search)) {
            $conditions[] = "(title LIKE :search OR author LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        if (!empty($category)) {
            $conditions[] = "category = :category";
            $params[':category'] = $category;
        }
        
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        
        // Add pagination
        $query .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = $this->items_per_page;
        $params[':offset'] = $offset;

        try {
            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                if ($key == ':limit' || $key == ':offset') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalProducts($search = '', $category = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        $conditions = [];
        $params = [];
        
        if (!empty($search)) {
            $conditions[] = "(title LIKE :search OR author LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        if (!empty($category)) {
            $conditions[] = "category = :category";
            $params[':category'] = $category;
        }
        
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return 0;
        }
    }

    public function addProduct($data) {
        $query = "INSERT INTO " . $this->table_name . "
                (title, author, price, category, description, image1, image2, image3)
                VALUES
                (:title, :author, :price, :category, :description, :image1, :image2, :image3)";

        try {
            $stmt = $this->conn->prepare($query);
            
            // Clean and validate data
            $data = $this->sanitizeProductData($data);
            
            $stmt->execute($data);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function updateProduct($id, $data) {
        $query = "UPDATE " . $this->table_name . "
                SET title = :title,
                    author = :author,
                    price = :price,
                    category = :category,
                    description = :description,
                    image1 = :image1,
                    image2 = :image2,
                    image3 = :image3
                WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            
            // Clean and validate data
            $data = $this->sanitizeProductData($data);
            $data[':id'] = $id;
            
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteProduct($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    private function sanitizeProductData($data) {
        return [
            ':title' => htmlspecialchars(strip_tags($data['title'])),
            ':author' => htmlspecialchars(strip_tags($data['author'])),
            ':price' => floatval($data['price']),
            ':category' => htmlspecialchars(strip_tags($data['category'])),
            ':description' => htmlspecialchars(strip_tags($data['description'])),
            ':image1' => filter_var($data['image1'], FILTER_SANITIZE_URL),
            ':image2' => filter_var($data['image2'], FILTER_SANITIZE_URL),
            ':image3' => filter_var($data['image3'], FILTER_SANITIZE_URL)
        ];
    }
}
?>