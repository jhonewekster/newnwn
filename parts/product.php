<?php
require_once __DIR__ . '/../config/connexion.php';

class Product {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getFeaturedProducts() {
        $stmt = $this->pdo->query('SELECT * FROM products LIMIT 2'); // Adjust the query as needed
        return $stmt->fetchAll();
    }
}

$product = new Product();
$featuredProducts = $product->getFeaturedProducts();
?>

<style>
@media (max-width: 768px) {
    .object-cover {
        object-fit: contain;
    }
}

/* Categories Section Styles */
.categories-section {
    padding: 3rem 0;
    background-color: #f8f9fa;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(200px, 1fr));
    gap: 2rem;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1rem;
}

.category-card {
    position: relative;
    border-radius: 1rem;
    overflow: hidden;
    transition: transform 0.3s ease;
    aspect-ratio: 3/4;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.category-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.1) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 1.5rem;
    color: white;
}

.category-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.category-count {
    font-size: 0.875rem;
    opacity: 0.9;
}

@media (max-width: 640px) {
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .category-card {
        aspect-ratio: 2/3;
    }
}
</style>










<!-- Categories Section -->
<section class="categories-section">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-2">Browse Categories</h2>
        <p class="text-gray-600 text-center mb-12">Discover products across various categories.</p>
        

        
        <div class="categories-grid">

            <div class="category-card">
                <a href="https://www.texastreasures.shop/shop?category=T-shirts">
                <img src="/../images/T-shirts.png" 
                     alt="Fiction Books" 
                     class="category-image">
                <div class="category-overlay">
                    <h3 class="category-title">T-shirts</h3>
                    <span class="category-count">1 item</span>
                    </a>
                </div>
            </div>

            <div class="category-card">
                <a href="https://www.texastreasures.shop/shop?category=Baby+Bodysuits">
                <img src="/../images/Baby Bodysuits.png" 
                     alt="Fiction Books" 
                     class="category-image">
                <div class="category-overlay">
                    <h3 class="category-title">Baby Bodysuits</h3>
                    <span class="category-count">1 item1 item</span>
                    </a>
                </div>
            </div>
           
            <div class="category-card" >
                <a href="https://www.texastreasures.shop/shop?category=Adult+shirts">
                <img src="/../images/Adult shirts.png" 
                     alt="Non-Fiction Books" 
                     class="category-image">
                <div class="category-overlay">
                    <h3 class="category-title">Adult shirts</h3>
                    <span class="category-count">1 item</span>
                    </a>
                </div>
            </div>
            
          
        </div>
    </div>
</section>









<section class="py-20 bg-white px-6">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center mb-2">Top-Rated Products</h1>
        <p class="text-gray-600 text-center mb-16">Handpicked selections for you</p>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 max-w-7xl mx-auto">
            <?php foreach ($featuredProducts as $product): ?>
                    <?php 
                    $productWords = explode(' ', trim($product['title'])); 
                    $firstThreeWords = array_slice($productWords, 0, 3);
                    $productUrl = htmlspecialchars(
                        strtolower(implode('-', $firstThreeWords))
                    );
                    $productUrl = preg_replace('/[^a-z0-9\-]/', '', $productUrl);
                    
                    
                    ?>
                <div class="flex flex-col md:flex-row gap-8 bg-white p-8 rounded-xl shadow-sm">
                    <img src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" 
                         alt="<?php echo htmlspecialchars($product['title']); ?>"
                         class="w-full md:w-48 h-64 object-cover bg-gray-50 rounded-lg">
                    <a href="product/<?php echo urlencode($productUrl); ?>">
                    <div class="flex-1 flex flex-col">
                        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($product['title']); ?></h2>
                        <p class="text-gray-600 mb-3">by <?php echo htmlspecialchars($product['author']); ?></p>
                        <p class="text-gray-700 mb-4 line-clamp-3"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="mt-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <span class="text-2xl font-bold">$<?php echo number_format($product['price'], 2); ?></span>
                            <button class="bg-black text-white px-6 py-2.5 rounded-full font-medium hover:bg-gray-800 transition-colors inline-flex items-center justify-center gap-2 add-to-cart" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-16">
            <a href="./shop.php" class="inline-flex items-center gap-2 bg-black text-white px-8 py-3 rounded-full font-medium hover:bg-gray-800 transition-colors">
                View All Products
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    </div>
</section>

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
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Swiper
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });

        var swiper2 = new Swiper(".mySwiper2", {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: swiper,
            },
        });

        // Show first tab by default
        const defaultTab = document.querySelector('.tab-button[data-tab="details"]');
        if (defaultTab) {
            defaultTab.click();
        }

        // Initialize all event listeners
        initializeEventListeners();
        
        // Initialize Lucide icons
        lucide.createIcons();
    });

    // Add to cart functionality
    async function addToCart(productId) {
        try {
            const response = await fetch(`/add-to-cart.php?action=add&id=${productId}`, {
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
                // Update cart count or any relevant UI component
                alert('Product added to cart.');
            } else {
                console.error('Error adding to cart:', result.message);
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
        }
    }

    // Buy now functionality
    async function buyNow(productId) {
        await addToCart(productId);
        window.location.href = '/checkout';
    }

    // Initialize event listeners
    function initializeEventListeners() {
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                addToCart(productId);
            });
        });

        document.querySelectorAll('.buy-now').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                buyNow(productId);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
    });
</script>
