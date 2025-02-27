<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../class/shop-class.php';

$cartItemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

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

require_once __DIR__ . '/../class/entire-website-controle.php';
$websiteEdite = new Entire_Website_Controle();
$settings = $websiteEdite->Affiche_logo();

if ($settings) {
    $store_name = $settings['store_name'];
} else {
    $store_name = "Default Store Name";
}

// Handle AJAX request for search functionality
if (isset($_GET['ajax']) && $_GET['ajax'] == 'search') {
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
    exit;
}

// Handle AJAX request to add product to cart
if (isset($_GET['ajax']) && $_GET['ajax'] == 'add_to_cart') {
    header('Content-Type: application/json');

    try {
        if (isset($_POST['product_id'])) {
            $productId = intval($_POST['product_id']);
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $_SESSION['cart'][] = $productId;
            $cartItemCount = count($_SESSION['cart']);
            echo json_encode(['cartItemCount' => $cartItemCount]);
        } else {
            echo json_encode(['error' => 'Product ID not provided']);
        }
    } catch (Exception $e) {
        error_log("Error in add_to_cart.php: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Mobile menu styles */
        @media (max-width: 768px) {
            #mobileMenu { display: none; }
            #mobileMenu.open { display: block; }
        }

        /* Search styles */
        .search-input {
            transition: width 0.3s ease-in-out;
            width: 0;
            padding: 0;
            border: none;
            outline: none;
        }

        .search-input.active {
            width: 200px;
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
        }

        .search-container {
            position: relative;
        }

        .search-results {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            width: 350px;
            max-height: 400px;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 50;
            display: none;
            border: 1px solid #e5e7eb;
        }

        .search-result-item {
            padding: 0.75rem 1rem;
            transition: background-color 0.2s;
            cursor: pointer;
            border-bottom: 1px solid #e5e7eb;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background-color: #f9fafb;
        }

        .search-result-image {
            background-color: #f3f4f6;
            border-radius: 0.375rem;
            padding: 0.25rem;
        }

        .search-no-results {
            padding: 2rem;
            text-align: center;
            color: #6b7280;
        }

        /* Cart popup styles */
        .cart-popup {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            width: 300px;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 50;
            display: none;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .cart-popup-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
        }

        .cart-popup-item:last-child {
            border-bottom: none;
        }

        .cart-popup-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.375rem;
            margin-right: 0.75rem;
        }

        .cart-popup-item-details {
            flex-grow: 1;
        }

        .view-cart-button {
            display: block;
            text-align: center;
            padding: 0.75rem;
            background: #4f46e5;
            color: white;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .view-cart-button:hover {
            background: #6366f1;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: black;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-white border-b">
        <div class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <a href="/" class="flex items-center space-x-2">
                        <i data-lucide="book-open" class="w-6 h-6"></i>
                        <strong><?php echo $store_name; ?></strong>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="/" class="text-gray-600 hover:text-gray-900 font-semibold">Home</a>
                    <a href="/shop" class="text-gray-600 hover:text-gray-900 font-semibold">Shop</a>
                    <a href="/about" class="text-gray-600 hover:text-gray-900 font-semibold">About</a>
                    <a href="/contact" class="text-gray-600 hover:text-gray-900 font-semibold">Contact</a>
                </div>

                <!-- Right Side Icons -->
                <div class="flex items-center space-x-6">
                    <!-- Search -->
                    <div class="relative flex items-center search-container">
                        <input type="text" 
                               id="searchInput" 
                               class="search-input bg-gray-50 text-gray-900 text-sm rounded-lg absolute right-0" 
                               placeholder="Search product...">
                        <button id="searchButton" class="text-gray-600 hover:text-gray-900 relative p-2">
                            <i data-lucide="search" class="w-6 h-6"></i>
                        </button>
                        <div id="searchResults" class="search-results"></div>
                    </div>

                    <!-- Cart -->
                    <div class="relative">
                        <button id="cartButton" class="text-gray-600 hover:text-gray-900 relative p-2">
                            <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                            <span id="cartCount" class="absolute -top-1 -right-1 bg-black text-white text-xs font-medium px-2 py-0.5 rounded-full"><?php echo $cartItemCount; ?></span>
                        </button>
                        <div id="cartPopup" class="cart-popup">
                            <div id="cartItems">
                                <?php if (!empty($cartProducts)): ?>
                                    <?php foreach ($cartProducts as $product): ?>
                                        <div class="cart-popup-item">
                                            <img src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                            <div class="cart-popup-item-details">
                                                <div class="font-medium"><?php echo htmlspecialchars($product['title']); ?></div>
                                                <div class="text-gray-600">$<?php echo number_format($product['price'], 2); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="cart-popup-item">Your cart is empty.</div>
                                <?php endif; ?>
                            </div>
                            <a href="/cart" class="view-cart-button">View Cart</a>
                        </div>
                    </div>

                    <!-- User -->
                    <button id="userButton" class="text-gray-600 hover:text-gray-900 p-2">
                        <i data-lucide="user" class="w-6 h-6"></i>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuButton" class="md:hidden text-gray-600 hover:text-gray-900 p-2">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden">
            <a href="/" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Home</a>
            <a href="/shop" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Shop</a>
            <a href="/about" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">About</a>
            <a href="/contact" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Contact</a>
        </div>
    </nav>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Mock data for demonstration
        const mockProducts = [
            {
                id: 1,
                title: "Texas Themed Toddler and Youth T-Shirt | Western Kid’s Shirt",
                author: "by Texas Treasures",
                price: "18.99",
                image1: "Texas%20Texas%20Texas%20Toddler%20and%20Youth%20TShirt.png",
            },
            {
                id: 2,
                title: "Texas Baby Bodysuit | Western Baby Clothes | Baby Shower Gift",
                author: "by Texas Treasures",
                price: "14.95",
                image1: "Texas%20Baby%20Bodysuit%20Texas%20Treasures.png",
            },
            {
                id: 3,
                title: "Grandpa Shirt Custom Comfort Colors Tee | Grandpa Est. 2025 | Father’s Day Gift",
                author: "by Texas Treasures",
                price: "12.99",
                image1: "Grandpa%20Shirt%20Texas%20Treasures.png",
            }
        ];

        // Search functionality
        const searchButton = document.getElementById('searchButton');
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let isSearchActive = false;

        searchButton.addEventListener('click', () => {
            isSearchActive = !isSearchActive;
            searchInput.classList.toggle('active');
            if (isSearchActive) {
                searchInput.focus();
            } else {
                searchResults.style.display = 'none';
            }
        });

        searchInput.addEventListener('input', async () => {
            const query = searchInput.value.trim();
            if (query !== '') {
                // Filter mock products based on search query
                const results = mockProducts.filter(product => 
                    product.title.toLowerCase().includes(query.toLowerCase()) ||
                    product.author.toLowerCase().includes(query.toLowerCase())
                );
                displaySearchResults(results);
            } else {
                searchResults.style.display = 'none';
            }
        });

        function displaySearchResults(results) {
            searchResults.innerHTML = '';
            if (results.length > 0) {
                results.forEach(product => {
                    const item = document.createElement('div');
                    item.className = 'search-result-item';
                    item.innerHTML = `
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 search-result-image flex items-center justify-center">
                                <img src="/product-image/${product.image1}" 
                                     alt="${product.title}" 
                                     class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 truncate mb-1">${product.title}</h3>
                                <p class="text-xs text-gray-500 mb-1">by ${product.author}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-900">$${Number(product.price).toFixed(2)}</span>
                                    <a href="/product/${encodeURIComponent(product.title.split(' ').slice(0, 3).join('-').toLowerCase().replace(/[^a-z0-9\-]/g, ''))}" class="text-xs text-white bg-black px-2 py-1 rounded-full">View Details</a>
                                </div>
                            </div>
                        </div>
                    `;
                    searchResults.appendChild(item);
                });
            } else {
                searchResults.innerHTML = '<div class="search-no-results">No results found</div>';
            }
            searchResults.style.display = 'block';
        }

        // Cart functionality
        const cartButton = document.getElementById('cartButton');
        const cartPopup = document.getElementById('cartPopup');
        const cartItems = document.getElementById('cartItems');
        const cartCount = document.getElementById('cartCount');
        let cart = [];

        cartButton.addEventListener('click', () => {
            cartPopup.style.display = cartPopup.style.display === 'block' ? 'none' : 'block';
        });

        function addToCart(productId) {
            const product = mockProducts.find(p => p.id === productId);
            if (product) {
                cart.push(product);
                updateCart();
                cartPopup.style.display = 'block';
            }
        }

        function updateCart() {
            cartCount.textContent = cart.length;
            cartItems.innerHTML = cart.length ? cart.map(item => `
                <div class="cart-popup-item">
                    <img src="${item.image}" alt="${item.title}">
                    <div class="cart-popup-item-details">
                        <div class="font-medium truncate">${item.title}</div>
                        <div class="text-xs text-gray-500">by ${item.author}</div>
                        <div class="text-gray-600">$${Number(item.price).toFixed(2)}</div>
                    </div>
                </div>
            `).join('') : '<div class="cart-popup-item">Your cart is empty</div>';
        }

        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.search-container')) {
                searchInput.classList.remove('active');
                searchResults.style.display = 'none';
            }
            if (!event.target.closest('.cart-popup') && !event.target.closest('#cartButton')) {
                cartPopup.style.display = 'none';
            }
        });
    </script>
</body>
</html>