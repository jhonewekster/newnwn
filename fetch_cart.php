<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/class/shop-class.php';

// Get the number of items in the cart
$cartItemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Fetch product details if there are items in the cart
$cartProducts = [];
if ($cartItemCount > 0) {
    $shop = new Shop();
    foreach ($_SESSION['cart'] as $productId) {
        $product = $shop->getProductById($productId);
        if ($product) {
            $cartProducts[] = $product;
        }
    }
}
?>

<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-2">
            <i data-lucide="shopping-cart" class="w-6 h-6"></i>
            <h2 class="text-xl font-semibold">Shopping Cart</h2>
            <span class="text-sm text-gray-500">(<?php echo $cartItemCount; ?> items)</span>
        </div>
    </div>

    <div class="flex flex-col h-[calc(100vh-200px)]">
        <?php if (empty($cartProducts)): ?>
            <!-- Empty cart state -->
            <div class="flex-1 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 mb-4 text-gray-300">
                    <i data-lucide="shopping-bag" class="w-full h-full"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-gray-500 mb-6">Looks like you haven't added any items yet</p>
                <a href="/shop.php" class="bg-black text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <!-- Cart items -->
            <div class="flex-1 overflow-y-auto">
                <?php foreach ($cartProducts as $product): ?>
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16">
                            <img style="object-fit: contain;" src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['title']); ?>" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['title']); ?></h3>
                            <p class="text-sm text-gray-500">by <?php echo htmlspecialchars($product['author']); ?></p>
                            <p class="text-sm text-gray-900">$<?php echo number_format($product['price'], 2); ?></p>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700 remove-from-cart" 
                                data-product-id="<?php echo $product['id']; ?>">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Cart footer -->
            <div class="border-t mt-auto pt-4">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">
                        $<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?>
                    </span>
                </div>
                <div class="flex justify-between mb-4">
                    <span class="text-gray-600">Shipping</span>
                    <span class="text-green-600">FREE</span>
                </div>
                <div class="flex justify-between mb-6">
                    <span class="text-lg font-semibold">Total</span>
                    <span class="text-lg font-semibold">
                        $<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?>
                    </span>
                </div>
                <a href="/checkout.php">
                    <button class="w-full bg-black text-white py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors">
                        Proceed to Checkout
                    </button>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>