<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/class/shop-class.php';

$action = $_GET['action'] ?? '';
$productId = $_GET['id'] ?? '';

if (empty($productId)) {
    echo json_encode(['success' => false, 'message' => 'Product ID is missing.']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':
        if (in_array($productId, $_SESSION['cart'])) {
            echo json_encode(['success' => false, 'message' => 'Product already in cart.']);
        } else {
            $_SESSION['cart'][] = $productId;
            echo json_encode(['success' => true, 'cartItemCount' => count($_SESSION['cart'])]);
        }
        break;

    case 'remove':
        if (($key = array_search($productId, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
            echo json_encode(['success' => true, 'cartItemCount' => count($_SESSION['cart'])]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found in cart.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        break;
}
?>