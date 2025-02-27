<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/class/shop-class.php';

// Initialize the Shop class
$shop = new Shop();

// Handle adding and removing products by URL
$productId = $_GET['id'] ?? '';
$action = $_GET['action'] ?? 'add'; // Default action to 'add'

$response = ['success' => false];

if (!empty($productId)) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($action === 'add') {
        // Check if the product exists
        $product = $shop->getProductById($productId);
        if ($product && !in_array($productId, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $productId;
            $response['success'] = true;
        }
    } elseif ($action === 'remove') {
        if (($key = array_search($productId, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
            $response['success'] = true;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Get the number of items in the cart
$cartItemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Fetch product details if there are items in the cart
$cartProducts = [];
if ($cartItemCount > 0) {
    foreach ($_SESSION['cart'] as $productId) {
        $product = $shop->getProductById($productId);
        if ($product) {
            $cartProducts[] = $product;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .quantity-input {
            width: 80px;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.5rem;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.2s;
        }

        .quantity-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        }

        .cart-item {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .checkout-button {
            background: linear-gradient(to right, #4f46e5, #6366f1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .checkout-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .remove-button {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .cart-item:hover .remove-button {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .remove-button {
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include 'parts/navbar.php'; ?>
    
    <div class="">
        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="flex-grow">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="space-y-6">
                                <?php if (empty($cartProducts)): ?>
                                    <div class="text-center py-6">
                                        <div class="text-gray-300">
                                            <i data-lucide="shopping-bag" class="w-16 h-16 mx-auto"></i>
                                        </div>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                                        <p class="mt-1 text-sm text-gray-500">Looks like you haven't added any items yet</p>
                                        <a href="/shop" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800">
                                            Continue Shopping
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($cartProducts as $product): ?>
                                        <div class="cart-item flex items-center gap-6 p-4 bg-white rounded-lg border border-gray-100" data-product-id="<?php echo $product['id']; ?>">
                                            <img src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" 
                                                 alt="<?php echo htmlspecialchars($product['title']); ?>" 
                                                 class="w-24 h-24 object-cover rounded-lg">
                                            <div class="flex-grow">
                                                <h3 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($product['title']); ?></h3>
                                                <p class="text-sm text-gray-500 mb-2">By <?php echo htmlspecialchars($product['author']); ?></p>
                                                <div class="flex items-center gap-4">
                                                    <div class="flex items-center gap-2">
                                                        <button class="text-gray-500 hover:text-gray-700 p-1" onclick="updateQuantity('decrease', <?php echo $product['id']; ?>)">
                                                            <i data-lucide="minus-circle" class="w-5 h-5"></i>
                                                        </button>
                                                        <input type="number" value="1" min="1" class="quantity-input" onchange="updateQuantity('input', <?php echo $product['id']; ?>)">
                                                        <button class="text-gray-500 hover:text-gray-700 p-1" onclick="updateQuantity('increase', <?php echo $product['id']; ?>)">
                                                            <i data-lucide="plus-circle" class="w-5 h-5"></i>
                                                        </button>
                                                    </div>
                                                    <span class="text-lg font-semibold">$<?php echo number_format($product['price'], 2); ?></span>
                                                </div>
                                            </div>
                                            <button class="remove-button text-gray-400 hover:text-red-500 p-2" onclick="removeFromCart(<?php echo $product['id']; ?>)">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-[380px]">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-4">
                        <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal (<?php echo $cartItemCount; ?> items)</span>
                                <span>$<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="text-green-600">$5.99</span>
                            </div>
                           
                            <div class="pt-3 border-t">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold">Total</span>
                                    <span class="text-lg font-semibold">$<?php echo number_format(array_sum(array_column($cartProducts, 'price')) + 5.99, 2); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Promo Code -->
                        <div class="mb-6">
                            <div class="flex gap-2">
                                <input type="text" 
                                       placeholder="Enter promo code" 
                                       class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <button class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                                    Apply
                                </button>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <a href="/checkout.php" class="checkout-button w-full py-3 px-4 text-white rounded-lg font-medium flex items-center justify-center gap-2">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Quantity update function
        function updateQuantity(action, itemId) {
            const input = event.target.closest('.flex').querySelector('input');
            let value = parseInt(input.value);

            switch(action) {
                case 'increase':
                    input.value = value + 1;
                    break;
                case 'decrease':
                    if (value > 1) {
                        input.value = value - 1;
                    }
                    break;
                case 'input':
                    // Ensure the value is at least 1
                    if (value < 1) {
                        input.value = 1;
                    }
                    break;
            }
        }

        // Remove item functionality
        async function removeFromCart(productId) {
            const cartItem = document.querySelector(`.cart-item[data-product-id='${productId}']`);
            cartItem.style.opacity = '0';
            cartItem.style.transform = 'translateX(100px)';
            setTimeout(async () => {
                cartItem.remove();
                try {
                    const response = await fetch(`/cart.php?action=remove&id=${productId}`, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'Cache-Control': 'no-cache'
                        }
                    });

                    if (!response.ok) {
                        const text = await response.text();
                        throw new Error(text);
                    }

                    const result = await response.json();

                    if (result.success) {
                        // Update order summary or any relevant UI component
                        alert('Product removed from cart.');
                        location.reload();
                    } else {
                        console.error('Error removing from cart:', result.message);
                    }
                } catch (error) {
                    console.error('Error removing from cart:', error);
                }
            }, 300);
        }
    </script>
    <?php require_once 'parts/Newsletter.php'; ?>
    <?php require_once 'parts/footer.php'; ?>
</body>
</html>