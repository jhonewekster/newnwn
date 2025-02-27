<?php

require_once 'class/shop-class.php';
session_start();

$shop = new Shop();
$product = null;

if (isset($_SERVER['REQUEST_URI'])) {
    // Get the last part of the URL
    $uriParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $urlTitle = end($uriParts);
    
    // Debug: Log the incoming URL title
    error_log("Incoming URL title: " . $urlTitle);
    
    // Basic URL decode
    $productName = urldecode($urlTitle);
    
    // Debug: Log after URL decode
    error_log("After URL decode: " . $productName);
    
    // Convert URL format to match database format
    $productName = str_replace(
        ['-', '%E2%80%93', '%7C', '%E2%80%99', '–'], 
        [' ', '–', '|', "'", '-'], 
        $productName
    );
    
    // Clean up any double spaces and trim
    $productName = trim(preg_replace('/\s+/', ' ', $productName));
    
    // Debug: Log the final processed title
    error_log("Final processed title: " . $productName);
    
    // Fetch product from database
    $product = $shop->getProductByTitle($productName);
    
    if (!$product) {
        header('HTTP/1.1 404 Not Found');
        echo 'Product not found. Debug info:<br>';
        echo 'Processed title: ' . htmlspecialchars($productName) . '<br>';
        exit;
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo 'Product not specified';
    exit;
}





// Prepare product details for JSON-LD
$productTitle = htmlspecialchars($product['title']);
$productImage = htmlspecialchars($product['image1']);
$productDescription = htmlspecialchars($product['description']);
$storeName = $result['store_name'];
$productUrl = 'https://texastreasures.shop' . $_SERVER['REQUEST_URI'];
$productPrice = number_format($product['price'], 2);
$productCurrency = 'USD';
$productAvailability = 'https://schema.org/InStock';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> - Texas Treasures</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon-us.svg">
       
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="/style/product.css"> 
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
     <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
     <meta name="robots" content="index, follow">
     <style>
     
.swiper {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
}
.product-image-container {
    position: relative;
}
.hover-icons {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 10;
}
.product-image-container:hover .hover-icons {
    opacity: 1;
}
.hover-icon {
    background: white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
    border: none;
}
.hover-icon:hover {
    transform: scale(1.1);
}
.review-stars {
    display: inline-flex;
    gap: 2px;
}
.swiper-slide {
    background-size: cover;
    background-position: center;
    background: #fff;
    border-radius: 0.5rem;
}
.mySwiper2 {
    width: 100%;
    height: 600px;
    margin-bottom: 1rem;
}
.mySwiper {
    height: 120px;
    box-sizing: border-box;
    padding: 0;
}
.mySwiper .swiper-slide {
    width: 120px;
    height: 100%;
    opacity: 0.4;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}
.mySwiper .swiper-slide-thumb-active {
        opacity: 1;
    border-color: #d9d9d9;
    ursor: grab;
}
.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 1rem;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 2rem;
}
.product-card {
    background: white;
    border-radius: 0.5rem;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Custom styles for Swiper navigation buttons */
.swiper-button-next,
.swiper-button-prev {
    color: #ffffff !important; /* Change this to your desired color */
    background-color: black !important; /* Optional: Add a background color */
    border-radius: 50%; /* Optional: Make the buttons circular */
    padding: 20px; /* Optional: Add some padding */
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    color: #ffffff !important; /* Change this to your desired hover color */
    background-color: black!important; /* Optional: Change background color on hover */
}

.review-stars {
     display: inline-flex;
     gap: 2px;
 }
 
 .tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
  </style>   
  <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "<?php echo $productTitle; ?>",
      "image": "<?php echo $productImage; ?>",
      "description": "<?php echo $productDescription; ?>",
      "brand": {
        "@type": "Brand",
        "name": "<?php echo $storeName; ?>"
      },
      "offers": {
        "@type": "Offer",
        "url": "<?php echo $productUrl; ?>",
        "priceCurrency": "<?php echo $productCurrency; ?>",
        "price": "<?php echo $productPrice; ?>",
        "availability": "<?php echo $productAvailability; ?>"
      }
    }
    </script>
</head>
<body class="bg-gray-50">
<?php include 'parts/navbar.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a href="/" class="text-gray-500 hover:text-gray-700">Home</a></li>
            <li class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="/shop" class="text-gray-500 hover:text-gray-700">Shop</a>
            </li>
            <li class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="text-gray-400"><?php echo htmlspecialchars($product['title']); ?></span>
            </li>
        </ol>
    </nav>

    <!-- Product Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <?php
        $image1 = htmlspecialchars($product['image1']);
        $image2 = !empty($product['image2']) ? htmlspecialchars($product['image2']) : null;
        $image3 = !empty($product['image3']) ? htmlspecialchars($product['image3']) : null;
        ?>

        <!-- Image Gallery -->
        <div>
            <div class="swiper mySwiper2">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="/product-image/<?php echo $image1; ?>" alt="Product Image 1" />
                    </div>
                    <?php if ($image2): ?>
                    <div class="swiper-slide">
                        <img src="/product-image/<?php echo $image2; ?>" alt="Product Image 2" />
                    </div>
                    <?php endif; ?>
                    <?php if ($image3): ?>
                    <div class="swiper-slide">
                        <img src="/product-image/<?php echo $image3; ?>" alt="Product Image 3" />
                    </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

            <!-- Thumbnail Swiper -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="/product-image/<?php echo $image1; ?>" alt="Thumbnail 1" />
                    </div>
                    <?php if ($image2): ?>
                    <div class="swiper-slide">
                        <img src="/product-image/<?php echo $image2; ?>" alt="Thumbnail 2" />
                    </div>
                    <?php endif; ?>
                    <?php if ($image3): ?>
                    <div class="swiper-slide">
                        <img src="/product-image/<?php echo $image3; ?>" alt="Thumbnail 3" />
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <h1 class="text-3xl font-bold text-gray-900"><?php echo htmlspecialchars($product['title']); ?></h1>
            <div class="text-2xl font-semibold text-gray-900">$<?php echo number_format($product['price'], 2); ?></div>
            
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="review-stars">
                        <?php
                        $reviews = $shop->getProductReviews($product['id']);
                        $averageRating = 0;
                        if (!empty($reviews)) {
                            $totalRating = 0;
                            foreach ($reviews as $review) {
                                $totalRating += $review['rating'];
                            }
                            $averageRating = round($totalRating / count($reviews));
                        }
                        ?>

                    <?php for ($i = 0; $i < 5; $i++): ?> 
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15 8 22 9 17 14 18 21 12 18 6 21 7 14 2 9 9 8 12 2"/>
                        </svg>
                    <?php endfor; ?>
                    </div>
                    <span class="text-sm text-gray-500 ml-2">(<?php echo count($reviews); ?> reviews)</span>
                </div>
                <div class="flex items-center text-green-600">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    <span>In Stock</span>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200">
                <div class="flex space-x-8">
                    <button class="tab-button py-4 text-sm font-medium text-gray-900 border-b-2 border-gray-900" data-tab="details">
                        Details
                    </button>
                    <button class="tab-button py-4 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="shipping">
                        Shipping
                    </button>
                    <button class="tab-button py-4 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="returns">
                        Returns
                    </button>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="space-y-4">
                <div id="details" class="tab-content active">
                    <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                <div id="shipping" class="tab-content">
                    <p class="text-gray-600">
                        Free shipping on orders over $50. Standard shipping within 5-7 business days. Express delivery available at checkout.
                    </p>
                </div>
                <div id="returns" class="tab-content">
                    <p class="text-gray-600">
                        Easy returns within 30 days of delivery. See our <a href="/refund-and-returns-policy">Return Policy</a> for more details.
                    </p>
                </div>
            </div>

            <!-- Add to Cart Section -->
            <div class="space-y-4">
                <div class="flex space-x-4">
                    <button class="flex-1 bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-900 transition-colors add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                        Add to Cart
                    </button>
                    <button class="flex-1 bg-gray-900 text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors buy-now" data-product-id="<?php echo $product['id']; ?>">
                        Buy Now
                    </button>
                </div>
                <button class="w-full flex items-center justify-center space-x-2 text-gray-900 border border-gray-300 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors add-to-wishlist" data-product-id="<?php echo $product['id']; ?>">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                    <span>Add to Wishlist</span>
                </button>
            </div>
        
        </div>
    </div>

   <!-- Reviews Section -->
<section class="mt-16">
    <!-- Reviews Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Customer Reviews</h2>
            <div class="flex items-center gap-4">
                <div class="flex items-center">
                    <div class="review-stars flex gap-1">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15 8 22 9 17 14 18 21 12 18 6 21 7 14 2 9 9 8 12 2"/>
                        </svg>
                    <?php endfor; ?>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-700"><?php echo number_format($averageRating, 1); ?> out of 5</span>
                </div>
                <span class="text-sm text-gray-500">Based on <?php echo count($reviews); ?> reviews</span>
            </div>
        </div>
        <a href="/login">
        <button  class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors duration-200 flex items-center gap-2">
            <i data-lucide="message-circle" class="w-4 h-4"></i>
            Write a Review
        </button>
        </a>
    </div>

    <!-- Rating Distribution -->
    <div class="mb-8 p-6 bg-gray-50 rounded-xl">
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-3">
                <?php
                $ratings = array_count_values(array_column($reviews, 'rating'));
                $totalReviews = count($reviews);
                for ($i = 5; $i >= 1; $i--):
                    $count = isset($ratings[$i]) ? $ratings[$i] : 0;
                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                ?>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 w-20">
                        <span class="text-sm font-medium"><?php echo $i; ?></span>
                        <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-yellow-400"></i>
                    </div>
                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-400 rounded-full transition-all duration-500" style="width: <?php echo $percentage; ?>%"></div>
                    </div>
                    <span class="text-sm text-gray-500 w-12"><?php echo $count; ?></span>
                </div>
                <?php endfor; ?>
            </div>
            <div class="pl-6 border-l border-gray-200">
                <h3 class="font-medium text-gray-900 mb-2">Review Highlights</h3>
                <div class="space-y-2">
                    <?php
                     // Calculate this based on your data
                    $avgDeliveryDays = "3-4"; // Calculate this based on your data
                    ?>
                   
                    <div class="text-sm text-gray-600">
                        Average delivery time: 2-4 days
                    </div>
                    <div class="text-sm text-gray-600">
                        Most mentioned: Quality, Design, Value
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="space-y-6">
        <?php if (empty($reviews)): ?>
            <div class="text-center py-16 bg-white rounded-xl">
                <div class="mb-4">
                    <i data-lucide="message-circle" class="w-16 h-16 mx-auto text-gray-300"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Reviews Yet</h3>
                <p class="text-gray-600 mb-6">Be the first to review this product</p>
                <a href="/login">
                <button  class="bg-black text-white px-8 py-3 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    Write a Review
                </button>
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="border-b border-gray-100 pb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                            <i data-lucide="user" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-medium text-gray-900"><?php echo htmlspecialchars($review['author']); ?></h3>
                                <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-full">
                                    Verified Purchase
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="flex gap-0.5">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <i data-lucide="star" class="w-4 h-4 <?php echo $i < $review['rating'] ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-sm text-gray-500"><?php echo htmlspecialchars($review['date']); ?></span>
                            </div>
                            <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($review['review_text']); ?></p>
                            <div class="flex items-center gap-6">
                                <button class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
                                    <i data-lucide="thumbs-up" class="w-4 h-4"></i>
                                    Helpful (<?php echo isset($review['helpful_count']) ? $review['helpful_count'] : '0'; ?>)
                                </button>
                                <button class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
                                    <i data-lucide="share-2" class="w-4 h-4"></i>
                                    Share
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Load More Button -->
            <?php if (count($reviews) >= 5): ?>
                <div class="mt-8 text-center">
                    <button class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Load More Reviews
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>

<?php
$currentProductCategory = $product['category'];
$shop = new Shop();
$similarProducts = $shop->getProductsByCategory($currentProductCategory, $product['id']);
?>
<?php $productUrl = str_replace(' ', '-', urlencode($product['title'])); ?>
<!-- You May Also Like Section -->

</div>
   
   
   

<script>
    
    
    
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

async function addToWishlist(productId) {
    try {
        const response = await fetch(`/wishlist?action=add&id=${productId}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const result = await response.json();

        if (result.redirect) {
            window.location.href = result.redirect;
        } else if (result.success) {
            alert('Product added to wishlist.');
        } else {
            alert('Failed to add product to wishlist.');
        }
    } catch (error) {
        console.error('Error adding to wishlist:', error);
    }
}

async function addToCart(productId) {
    try {
        const response = await fetch(`/add-to-cart?action=add&id=${productId}`, {
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
            document.getElementById('cartItemCount').textContent = result.cartItemCount;
            fetchCartItems();
            openCart();
        } else {
            console.error('Error adding to cart:', result.message);
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
    }
}

async function buyNow(productId) {
    await addToCart(productId);
    window.location.href = '/checkout';
}

async function fetchCartItems() {
    try {
        const response = await fetch('/fetch_cart.php', {
            method: 'GET',
            credentials: 'same-origin'
        });

        if (!response.ok) {
            const text = await response.text();
            throw new Error(text);
        }

        const result = await response.text();

        document.getElementById('cartSidebarContent').innerHTML = result;
        initializeEventListeners(); // Re-initialize event listeners
        lucide.createIcons();
    } catch (error) {
        console.error('Error fetching cart items:', error);
    }
}

function openCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    
    if (cartSidebar && cartOverlay) {
        cartSidebar.classList.remove('translate-x-full');
        cartOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        fetchCartItems();
    }
}

function closeCartSidebar() {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    
    if (cartSidebar && cartOverlay) {
        cartSidebar.classList.add('translate-x-full');
        cartOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function initializeEventListeners() {
    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.getAttribute('data-product-id');

            try {
                const response = await fetch(`/add-to-cart?action=remove&id=${productId}`, {
                    method: 'GET',
                    credentials: 'same-origin'
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const result = await response.json();

                if (result.success) {
                    document.getElementById('cartItemCount').textContent = result.cartItemCount;
                    fetchCartItems();
                } else {
                    console.error('Error removing from cart:', result.message);
                }
            } catch (error) {
                console.error('Error removing from cart:', error);
            }
        });
    });

    // Tab functionality
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const tab = this.getAttribute('data-tab');

            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('border-b-2', 'border-gray-900', 'text-gray-900');
                btn.classList.add('text-gray-500');
            });

            this.classList.remove('text-gray-500');
            this.classList.add('border-b-2', 'border-gray-900', 'text-gray-900');

            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            document.getElementById(tab).classList.add('active');
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
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

    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            addToWishlist(productId);
        });
    });

    const cartButton = document.getElementById('cartButton');
    const closeCart = document.getElementById('closeCart');
    const cartOverlay = document.getElementById('cartOverlay');

    if (cartButton) {
        cartButton.addEventListener('click', openCart);
    }

    if (closeCart) {
        closeCart.addEventListener('click', closeCartSidebar);
    }

    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeCartSidebar);
    }

    lucide.createIcons();
    initializeEventListeners();
});
</script>



<?php require_once 'parts/footer.php'; ?>
</body>
</html>
