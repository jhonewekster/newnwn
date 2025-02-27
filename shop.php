<?php 
// Include necessary parts and classes
include 'parts/navbar.php'; 
require_once 'class/shop-class.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$shop = new Shop();

// Test database connection and table structure
$columns = $shop->testConnection();
if ($columns === false) {
    die("Failed to connect to the database or retrieve table structure.");
}

$filters = [
    'category' => isset($_GET['category']) ? $_GET['category'] : '',
    'format' => isset($_GET['format']) ? $_GET['format'] : '',
    'price' => isset($_GET['price']) ? $_GET['price'] : '',
    'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'featured'
];

// Clear empty filters
$filters = array_filter($filters, function($value) {
    return $value !== '';
});

// Debug filters
error_log("Applied Filters: " . print_r($filters, true));

$products = $shop->getProducts($filters);

// Debug products
error_log("Products Retrieved: " . count($products));
if (empty($products)) {
    error_log("No products found. Check your database and query.");
}

// Define available categories, formats, and price ranges
$categories = ['Adult shirts', 'Baby Bodysuits', 'T-shirts'];

$priceRanges = [
    'under-15' => 'Under $15',
    '15-30' => '$15 - $30',
    '30-50' => '$30 - $50',
    'over-50' => 'Over $50'
];

require_once __DIR__ . '/config/connexion.php';
$query = "SELECT `store_name`, `address_store`, `mail_business`, `phone`, `header_background` FROM websitesetting LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$store_name = $result ? $result['store_name'] : 'Default Store Name';
$storeName = $result['store_name'];
$storeUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$storeLogo = $result['header_background'];
$storePhone = $result['phone'];





$shop = new Shop();
$products = $shop->getProducts();

$cartItemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

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
    <script type="application/ld+json">
                {
              "@context": "https://schema.org/",
              "@type": "WebPage",
              "name": "<?php echo htmlspecialchars($storeName); ?> - Shop",
                "description": "Explore our collection of unique apparel and accessories. Find designs that represent your style and passion.",
                  "url": "<?php echo htmlspecialchars($storeUrl); ?>/shop",
                  "isPartOf": {
                    "@type": "WebSite",
                    "url": "<?php echo htmlspecialchars($storeUrl); ?>"
                  }
              }
            }
</script>
    <title><?php echo htmlspecialchars($store_name); ?> - Shop</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="style/shop.css">
    <meta name="robots" content="index, follow">
</head>
<body class="bg-gray-50">
    <div class="container-shop-page">
        <div class="shop-header">
            <h1 class="shop-title">Shop Now</h1>
            <p class="shop-subtitle">Explore our collection of unique apparel and accessories. Find designs that represent your style and passion.</p>
        </div>

        <div class="shop-layout">
            <!-- Filters Sidebar -->
            <aside class="filters-section">
                <h2 class="filters-title">
                    <i data-lucide="filter" class="w-5 h-5"></i>
                    Filters
                </h2>
                <form id="filtersForm" method="GET" action="/shop">
                    <!-- Categories Filter -->
                    <div class="filter-group">
                        <h3 class="filter-group-title">Categories</h3>
                        <div class="filter-list">
                            <label class="filter-option">
                                <input type="radio" name="category" value="" class="filter-checkbox" <?php echo empty($filters['category']) ? 'checked' : ''; ?>>
                                All Categories
                            </label>
                            <?php foreach ($categories as $category): ?>
                                <label class="filter-option">
                                    <input type="radio" name="category" value="<?php echo htmlspecialchars($category); ?>" 
                                           class="filter-checkbox" <?php echo isset($filters['category']) && $filters['category'] === $category ? 'checked' : ''; ?>>
                                    <?php echo htmlspecialchars($category); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="filter-group">
                        <h3 class="filter-group-title">Price Range</h3>
                        <div class="filter-list">
                            <label class="filter-option">
                                <input type="radio" name="price" value="" class="filter-checkbox" <?php echo empty($filters['price']) ? 'checked' : ''; ?>>
                                All Prices
                            </label>
                            <?php foreach ($priceRanges as $value => $label): ?>
                                <label class="filter-option">
                                    <input type="radio" name="price" value="<?php echo htmlspecialchars($value); ?>" 
                                           class="filter-checkbox" <?php echo isset($filters['price']) && $filters['price'] === $value ? 'checked' : ''; ?>>
                                    <?php echo htmlspecialchars($label); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                  
                </form>
            </aside>

            <!-- Products Section -->
            <section class="products-section">
                <div class="products-header">
                    <span class="products-count">Showing <?php echo count($products); ?> products</span>
                    <select class="sort-select" id="sortSelect">
                        <option value="featured" <?php echo $filters['sort'] === 'featured' ? 'selected' : ''; ?>>Featured</option>
                        <option value="newest" <?php echo $filters['sort'] === 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="price-asc" <?php echo $filters['sort'] === 'price-asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price-desc" <?php echo $filters['sort'] === 'price-desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>

                <div class="products-grid" id="productsGrid">
                    <?php if (empty($products)): ?>
                        <div class="no-products-message">
                            <p>No products found matching your criteria.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
<?php 
// Split the title into words and take the first three
$productWords = explode(' ', trim($product['title'])); 
$firstThreeWords = array_slice($productWords, 0, 3); // Get the first three words

// Join the words with hyphens, remove unwanted characters, and clean the result
$productUrl = htmlspecialchars(
    strtolower(implode('-', $firstThreeWords))  // Join the first three words with hyphens and convert to lowercase
);

// Optionally, remove any unwanted characters like special symbols or spaces (already handled in implode)
$productUrl = preg_replace('/[^a-z0-9\-]/', '', $productUrl); // Remove non-alphanumeric characters


?>



                        
                                <article class="product-card" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                                    <div class="floating-actions">
                                        
                                        <button style="background-color:white;color:black" class="action-button add-to-cart" data-product-id="<?php echo htmlspecialchars($product['id']); ?>" title="Add to Cart">
                                            <i data-lucide="shopping-cart" class="w-6 h-6 text-gray-700"></i>
                                        </button>
                                        <button class="action-button" title="Add to Wishlist">
                                            <i data-lucide="heart" class="w-5 h-5 text-gray-700"></i>
                                        </button>
                                    </div>
                                    <div class="product-image-container">
                                        <div class="product-image-wrapper">
                                            <img src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" 
                                                 alt="<?php echo htmlspecialchars($product['title']); ?>"
                                                 loading="lazy">
                                        </div>
                                        <div class="quick-view-overlay">
                                            <button class="quick-view-button" onclick="openModal(this.closest('.product-card'))">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                                Quick View
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product-content" >
                                        <a href="product/<?php echo urlencode($productUrl); ?>">
                                        <h2  class="product-title line-clamp-1"><?php echo htmlspecialchars($product['title']); ?></h2>
                                        <p  class="product-author">by <?php echo htmlspecialchars($product['author']); ?></p>
                                        <p  class="product-description line-clamp-2"><?php echo htmlspecialchars($product['description']); ?></p>
                                        <div class="product-meta" hidden>
                                            <p>Format: <?php echo htmlspecialchars($product['format'] ?? 'Paperback'); ?></p>
                                            <p>ISBN: <?php echo htmlspecialchars($product['isbn'] ?? 'N/A'); ?></p>
                                        </div>
                                        </a>
                                        <div class="product-footer">
                                            <span class="product-price">$<?php echo number_format($product['price'], 2); ?></span>
                                            <button class="add-to-cart" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="shopping-cart" class="lucide lucide-shopping-cart w-4 h-4"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path></svg>
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </article>
                            
                        <?php endforeach; ?>
                        <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div class="modal" id="quickViewModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="modal-body">
                <div class="modal-image">
                    <img id="modalImage" src="" alt="">
                </div>
                <div class="modal-info">
                    <h2 class="modal-title" id="modalTitle"></h2>
                    <p class="modal-price" id="modalPrice"></p>
                    <p class="modal-author" id="modalAuthor"></p>
                    <p class="modal-description line-clamp-4" id="modalDescription"></p>
                    <div class="modal-meta">
                        <p id="modalFormat"></p>
                    </div>
                    <div class="modal-actions">
                        <button class="add-to-cart" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Sidebar -->
    <div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity z-40 hidden"></div>
    <div id="cartSidebar" class="fixed top-0 right-0 h-full w-[400px] bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50">
        <div class="p-6" id="cartSidebarContent">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-2">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                    <h2 class="text-xl font-semibold">Shopping Cart</h2>
                    <span class="text-sm text-gray-500">(<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?> items)</span>
                </div>
                <button id="closeCart" class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
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
                        <button class="bg-black text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors">
                            Continue Shopping
                        </button>
                    </div>
                <?php else: ?>
                    <!-- Cart items -->
                    <div class="flex-1 overflow-y-auto">
                        <?php foreach ($cartProducts as $product): ?>
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-16 h-16">
                                    <img src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['title']); ?></h3>
                                    <p class="text-sm text-gray-500">by <?php echo htmlspecialchars($product['author']); ?></p>
                                    <p class="text-sm text-gray-900">$<?php echo number_format($product['price'], 2); ?></p>
                                </div>
                                <button class="text-gray-500 hover:text-gray-700 remove-from-cart" data-product-id="<?php echo $product['id']; ?>">
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
                        <button class="w-full bg-black text-white py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors">
                            Proceed to Checkout
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        function openModal(product) {
            const modal = document.getElementById('quickViewModal');
            
            try {
                // Get product data
                const image = product.querySelector('.product-image-wrapper img').src;
                const title = product.querySelector('.product-title').textContent;
                const author = product.querySelector('.product-author').textContent;
                const description = product.querySelector('.product-description').textContent;
                const meta = product.querySelector('.product-meta');
                const format = meta ? meta.children[0].textContent : 'Format: Paperback';
                const isbn = meta ? meta.children[1].textContent : 'ISBN: N/A';
                const price = product.querySelector('.product-price').textContent;
            
                // Update modal content
                document.getElementById('modalImage').src = image;
                document.getElementById('modalTitle').textContent = title;
                document.getElementById('modalPrice').textContent = price;
                document.getElementById('modalAuthor').textContent = author;
                document.getElementById('modalDescription').textContent = description;
                
            
                // Show modal
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';

                // Update icons in modal
                lucide.createIcons();
            } catch (error) {
                console.error('Error opening modal:', error);
            }
        }

        function closeModal() {
            const modal = document.getElementById('quickViewModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.getElementById('quickViewModal').addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Handle sort select changes
        document.getElementById('sortSelect').addEventListener('change', async function(e) {
            const sortValue = e.target.value;
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', sortValue);
            
            try {
                const response = await fetch(currentUrl.toString());
                if (!response.ok) throw new Error('Network response was not ok');
                
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Update products grid
                const newProductsGrid = doc.getElementById('productsGrid');
                const currentProductsGrid = document.getElementById('productsGrid');
                if (newProductsGrid && currentProductsGrid) {
                    currentProductsGrid.innerHTML = newProductsGrid.innerHTML;
                    // Reinitialize Lucide icons for new content
                    lucide.createIcons();
                }
                
                // Update product count
                const newCount = doc.querySelector('.products-count');
                const currentCount = document.querySelector('.products-count');
                if (newCount && currentCount) {
                    currentCount.textContent = newCount.textContent;
                }
                
                // Update URL without page reload
                window.history.pushState({}, '', currentUrl.toString());
            } catch (error) {
                console.error('Error updating products:', error);
            }
        });

        // Auto-submit form when radio buttons change
        document.querySelectorAll('#filtersForm input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', () => {
                document.getElementById('filtersForm').submit();
            });
        });

        // Cart functionality
        document.addEventListener('DOMContentLoaded', function() {
            initializeCartFunctionality();
        });

        function initializeCartFunctionality() {
            // Initialize Add to Cart buttons
            initializeAddToCart();

        }

        // Add to cart functionality
        function initializeAddToCart() {
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    
                    try {
                        const response = await fetch(`add-to-cart?action=add&id=${productId}`, {
                            method: 'GET',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'Cache-Control': 'no-cache'
                            }
                        });
                        
                        if (!response.ok) throw new Error('Network response was not ok');
                        
                        const result = await response.json();
                        if (result.success) {
                            // Update cart count in navbar
                            document.getElementById('cartItemCount').textContent = result.cartItemCount;
                            
                            // Update cart content
                            document.getElementById('cartSidebarContent').innerHTML = result.cartSidebarContent;
                            
                            // Reinitialize icons and event listeners
                            lucide.createIcons();
                            initializeRemoveFromCart();
                            
                            // Open cart sidebar
                            openCart();
                        } else {
                            console.error('Error:', result.message);
                        }
                    } catch (error) {
                        console.error('Error adding to cart:', error);
                    }
                });
            });
        }
        
        
          // Initialize Lucide icons
        lucide.createIcons();

        // Function to add product to cart
        function addToCart(productId) {
            fetch('shop.php?ajax=add_to_cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.cartItemCount !== undefined) {
                    document.getElementById('cartCount').textContent = data.cartItemCount;
                } else {
                    console.error('Error adding to cart:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

<?php 
require_once 'parts/Newsletter.php';
require_once 'parts/footer.php';
?>