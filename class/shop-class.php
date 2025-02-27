<?php
require_once __DIR__ . '/../config/connexion.php';

class Shop {
    private $conn;
    private $table_name = "products";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function testConnection() {
        try {
            $query = "DESCRIBE " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function getProducts($filters = []) {
        $query = "SELECT id, title, price, image1, image2, image3, author, category, description FROM " . $this->table_name;
        $conditions = [];
        $params = [];
        $orderBy = "";
    
        // Clean and validate filters
        $filters = array_map('trim', $filters);
        
        // Category filter
        if (!empty($filters['category'])) {
            $conditions[] = "category = :category";
            $params[':category'] = $filters['category'];
        }
    
        // Format filter
        if (!empty($filters['format'])) {
            $conditions[] = "description LIKE :format";
            $params[':format'] = '%' . $filters['format'] . '%';
        }
    
        // Price filter
        if (!empty($filters['price'])) {
            switch ($filters['price']) {
                case 'under-15':
                    $conditions[] = "price < 15";
                    break;
                case '15-30':
                    $conditions[] = "price >= 15 AND price <= 30";
                    break;
                case '30-50':
                    $conditions[] = "price > 30 AND price <= 50";
                    break;
                case 'over-50':
                    $conditions[] = "price > 50";
                    break;
            }
        }
    
        // Sort filter
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'newest':
                    $orderBy = " ORDER BY id DESC";
                    break;
                case 'price-asc':
                    $orderBy = " ORDER BY price ASC";
                    break;
                case 'price-desc':
                    $orderBy = " ORDER BY price DESC";
                    break;
                default:
                    $orderBy = " ORDER BY id ASC"; // Featured/default sorting
            }
        }
    
        // Add WHERE clause if there are conditions
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
    
        // Add ORDER BY clause
        $query .= $orderBy;
    
        // Debug query and parameters
        error_log("Query: " . $query);
        error_log("Parameters: " . print_r($params, true));
    
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error and return empty array
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    public function getProductById($id) {
        $query = "SELECT id, title, price, image1, image2, image3, author, category, description FROM " . $this->table_name . " WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

public function getProductByTitle($urlTitle) {
    // Normalize the search title
    $searchTitle = trim($urlTitle);
    
    $query = "SELECT id, title, price, image1, image2, image3, author, category, description 
              FROM " . $this->table_name . " 
              WHERE LOWER(title) LIKE LOWER(:title)";
              
    try {
        $stmt = $this->conn->prepare($query);
        // Make the search more flexible
        $titleParam = '%' . str_replace(['|', '–', "'"], ['%', '%', '%'], $searchTitle) . '%';
        $stmt->bindParam(':title', $titleParam, PDO::PARAM_STR);
        $stmt->execute();
        
        // Debug: Log the actual SQL query with parameter
        error_log("Search title parameter: " . $titleParam);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            error_log("Product not found for title: " . $searchTitle);
        }
        
        return $result;
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}
    
    
    
    
    
 public function searchProducts($query) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM products WHERE title LIKE :query");
            $stmt->execute(['query' => '%' . $query . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Search query error: ' . $e->getMessage());
            throw new Exception('Search query error');
        }
    }
    
    
    
    
    public function getProductsByCategory($category, $excludeProductId = null) {
        $query = "SELECT id, title, price, image1 FROM " . $this->table_name . " WHERE category = :category";
        $params = [':category' => $category];
    
        if ($excludeProductId) {
            $query .= " AND id != :excludeProductId";
            $params[':excludeProductId'] = $excludeProductId;
        }
    
        $query .= " ORDER BY RAND() LIMIT 4"; // Fetch 4 random products
    
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }
    public function getProductReviews($productId) {
        $query = "SELECT r.rating, r.review_text, CONCAT(u.first_name, ' ', u.last_name) AS author, r.created_at AS date FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = :product_id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
}
}