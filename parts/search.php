<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../class/shop-class.php';

header('Content-Type: application/json');

try {
    if (isset($_GET['query'])) {
        $query = trim($_GET['query']);
        if (!empty($query)) {
            $shop = new Shop();
            if (method_exists($shop, 'searchProducts')) {
                $results = $shop->searchProducts($query);
                echo json_encode($results);
            } else {
                throw new Exception('Method searchProducts not found in Shop class.');
            }
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    error_log("Error in search.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
}
?>