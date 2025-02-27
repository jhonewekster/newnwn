<?php
session_start();
require_once __DIR__ . '/class/shop-class.php';
require_once __DIR__ . '/config/connexion.php';

$response = [
    'success' => false,
    'message' => ''
];

try {
    if (!isset($_GET['action'])) {
        throw new Exception('Missing action parameter');
    }

    if (!isset($_SESSION['user_id'])) {
        // User is not logged in, send a JSON response indicating the need to log in
        $response['message'] = 'User not logged in';
        $response['redirect'] = '/login';
        echo json_encode($response);
        exit();
    }

    $userId = $_SESSION['user_id'];
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($productId <= 0) {
        throw new Exception('Invalid product ID');
    }

    $shop = new Shop();
    $database = new Database();
    $pdo = $database->getConnection();

    switch ($_GET['action']) {
        case 'add':
            // Check if product is already in wishlist
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM wishlist WHERE user_id = :user_id AND product_id = :product_id');
            $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $response['message'] = 'Product already in wishlist';
            } else {
                // Add product to wishlist
                $stmt = $pdo->prepare('INSERT INTO wishlist (user_id, product_id) VALUES (:user_id, :product_id)');
                $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
                $response['success'] = true;
                $response['message'] = 'Product added to wishlist';
            }
            break;

        default:
            throw new Exception('Invalid action');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?>