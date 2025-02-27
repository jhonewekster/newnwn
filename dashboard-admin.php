<?php
require_once 'class/dashboard-admin-class.php';
session_start();
$dashboardAdmin = new DashboardAdmin();
$settings = $dashboardAdmin->getSettings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedData = [];

    foreach ($_POST as $key => $value) {
        if (isset($settings[$key]) && $settings[$key] !== $value) {
            $updatedData[$key] = $value;
        }
    }

    if (isset($_FILES['header_background']) && $_FILES['header_background']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/image-web/';
        $uploadFile = $uploadDir . basename($_FILES['header_background']['name']);
        if (move_uploaded_file($_FILES['header_background']['tmp_name'], $uploadFile)) {
            $updatedData['header_background'] = '/image-web/' . basename($_FILES['header_background']['name']);
        }
    } else {
        $updatedData['header_background'] = $settings['header_background']; // Retain the current image if no new image is uploaded
    }

    if (!empty($updatedData)) {
        $success = $dashboardAdmin->updateSettings($updatedData);
        if ($success) {
            header("Location: dashboard-admin.php");
            exit();
        } else {
            error_log("Error saving settings.");
            echo "Error saving settings.";
        }
    }
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$products = $dashboardAdmin->getProducts($page, $search, $category);
$totalProducts = $dashboardAdmin->getTotalProducts($search, $category);
$totalPages = ceil($totalProducts / 10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    require_once __DIR__ . '/config/connexion.php';
    $query = "SELECT store_name FROM websitesetting LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $store_name = $result ? $result['store_name'] : 'Default Store Name';
    ?>
    <title><?php echo htmlspecialchars($store_name); ?>Admin Dashboard</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="./style/dashboard-admin.css">

</head>
<body class="bg-gray-50">


    <div class="flex h-screen">
        <!-- Sidebar -->
        <!-- Sidebar -->
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center space-x-2">
            <a href="/index" class="flex items-center space-x-2">
                <i data-lucide="book" class="w-6 h-6"></i>
                <span class="text-xl font-semibold">TestBanky Panel</span>
            </a>
        </div>
    </div>
    <nav class="p-4 space-y-1 flex-grow">
        <a href="#products" class="sidebar-link active flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
            <i data-lucide="package" class="w-5 h-5"></i>
            <span>Products</span>
        </a>
        <a href="#orders" class="sidebar-link flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
            <span>Orders</span>
        </a>
        <a href="#settings" class="sidebar-link flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
            <i data-lucide="settings" class="w-5 h-5"></i>
            <span>Edit Entire Website</span>
        </a>
        <a href="#edit-pages" class="sidebar-link flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
        <i data-lucide="file-text" class="w-5 h-5"></i>
        <span>Edit Pages</span>
      </a>
    </nav>
    <div class="p-4">
        <a href="/parts/logout.php" class="sidebar-link flex items-center space-x-2 p-2 rounded-lg transition-colors logout-button">
            <i data-lucide="log-out" class="w-5 h-5"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white border-b border-gray-200 p-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Products Management</h2>
                    <button onclick="openAddProductModal()" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                        Add New Product
                    </button>
                </div>
            </div>

               <!-- Products Section -->
               <div id="products" class="p-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">All Products</h3>
                            <div class="flex space-x-2">
                                <input type="text" id="searchInput" placeholder="Search products..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">All Categories</option>
                                    <option value="Fiction">Fiction</option>
                                    <option value="Non-Fiction">Non-Fiction</option>
                                    <option value="Business">Business</option>
                                    <option value="Science">Science</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody" class="bg-white divide-y divide-gray-200">
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    <img class="product-image" src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['title']); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($product['author']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                <?php echo htmlspecialchars($product['category']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            $<?php echo number_format($product['price'], 2); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            In Stock
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button onclick="editProduct(<?php echo $product['id']; ?>)" class="text-indigo-600 hover:text-indigo-900">
                                                <i data-lucide="edit" class="w-5 h-5"></i>
                                            </button>
                                            <button onclick="deleteProduct(<?php echo $product['id']; ?>)" class="text-red-600 hover:text-red-900">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-700" id="paginationInfo">
                                Showing <?php echo ($page - 1) * 10 + 1; ?> to <?php echo min($page * 10, $totalProducts); ?> of <?php echo $totalProducts; ?> results
                            </div>
                            <div class="flex space-x-2" id="paginationControls">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($search); ?>&category=<?php echo htmlspecialchars($category); ?>" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">Previous</a>
                                <?php endif; ?>
                                <?php if ($page < $totalPages): ?>
                                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($search); ?>&category=<?php echo htmlspecialchars($category); ?>" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">Next</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





<!-- New sections for editing policy pages -->
<div id="edit-pages" class="hidden p-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Edit Pages</h3>
        </div>
        <div class="p-4">
            <form method="POST" id="editPagesForm">
                <div class="grid grid-cols-1 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Privacy Policy</label>
                        <textarea name="privacy_policy" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($settings['privacy_policy']); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Terms of Service</label>
                        <textarea name="terms_of_service" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($settings['terms_of_service']); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Shipping Policy</label>
                        <textarea name="shipping_policy" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($settings['shipping_policy']); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Policy</label>
                        <textarea name="payment_policy" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($settings['payment_policy']); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Refund and Returns Policy</label>
                        <textarea name="refund_returns_policy" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($settings['refund_returns_policy']); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">DMCA Policy</label>
                        <textarea name="dmca_policy" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($settings['dmca_policy']); ?></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Save Policies
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



            <!-- Orders Section (Hidden by default) -->
            <div id="orders" class="hidden p-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
                            <div class="flex space-x-2">
                                <input type="text" placeholder="Search orders..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#ORD-001</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">John Doe</div>
                                        <div class="text-sm text-gray-500">john@example.com</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-03-15</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$99.99</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-indigo-600 hover:text-indigo-900">View Details</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div id="settings" class="hidden p-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Edit Entire Website</h3>
                    </div>
                    <div class="p-4">
                        <form method="POST" enctype="multipart/form-data">
                            
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Store Name</label>
                                    <input type="text" name="store_name" value="<?php echo htmlspecialchars($settings['store_name']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Address Store</label>
                                    <input type="text" name="address_store" value="<?php echo htmlspecialchars($settings['address_store']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mail Business</label>
                                    <input type="email" name="mail_business" value="<?php echo htmlspecialchars($settings['mail_business']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="text" name="phone" value="<?php echo htmlspecialchars($settings['phone']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="upload-container">
                                    <label class="upload-label" for="header_background">Header Background</label>
                                    <div class="upload-input-container">
                                        <input 
                                            type="file" 
                                            name="header_background" 
                                            id="header_background" 
                                            class="upload-input" 
                                            accept="image/*"
                                        >
                                        <div class="upload-text">
                                            <div class="upload-icon">📁</div>
                                            <p>Drag and drop your image here or click to browse</p>
                                            <p class="upload-text">Supported formats: PNG, JPG, JPEG</p>
                                        </div>
                                    </div>
                                    <div class="preview-container">
                                        <div class="preview-placeholder">Image preview will appear here</div>
                                        <img id="header_background_preview" class="preview-img" src="/<?php echo htmlspecialchars($settings['header_background']); ?>" alt="Header Background Preview">
                                    </div>
                                    <div class="file-info"></div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

   <!-- Add/Edit Product Modal -->
<div id="productModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex min-h-full items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add New Product</h3>
                <button onclick="closeProductModal()" class="text-gray-400 hover:text-gray-500">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form id="productForm" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Author</label>
                        <input type="text" name="author" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Business">Business</option>
                            <option value="Science">Science</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image 1 URL</label>
                        <input type="url" name="image1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image 2 URL</label>
                        <input type="url" name="image2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image 3 URL</label>
                        <input type="url" name="image3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeProductModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
    


    document.addEventListener('DOMContentLoaded', (event) => {
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const tabs = {
        products: document.getElementById('products'),
        orders: document.getElementById('orders'),
        settings: document.getElementById('settings'),
        'edit-pages': document.getElementById('edit-pages')
    };

    sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href === '/parts/logout.php') {
                return;
            }

            e.preventDefault();
            const targetId = href.substring(1);
            
            // Ensure the target tab exists before manipulating its classList
            if (tabs[targetId]) {
                // Hide all tabs
                Object.values(tabs).forEach(tab => tab.classList.add('hidden'));
                
                // Show target tab
                tabs[targetId].classList.remove('hidden');
                
                // Update active state
                sidebarLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            }
        });
    });

    // Ensure icons are properly initialized
    lucide.createIcons();
});



    // Global state
    let currentPage = 1;
    let totalPages = 1;
    let currentSearch = '';
    let currentCategory = '';




    // Load products function
    async function loadProducts() {
        try {
            const response = await axios.get(`api/admin/products.php`, {
                params: {
                    page: currentPage,
                    search: currentSearch,
                    category: currentCategory
                }
            });

            if (response.data.success) {
                const { products, total, page, totalPages: pages } = response.data.data;
                totalPages = pages;

                // Update table
                const tbody = document.getElementById('productsTableBody');
                tbody.innerHTML = products.map(product => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img class="product-image" src="/product-image/${product.image1}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${product.title}</div>
                                    <div class="text-sm text-gray-500">${product.author}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                ${product.category}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            $${parseFloat(product.price).toFixed(2)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            In Stock
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="editProduct(${product.id})" class="text-indigo-600 hover:text-indigo-900">
                                <i data-lucide="edit" class="w-5 h-5"></i>
                            </button>
                            <button onclick="deleteProduct(${product.id})" class="text-red-600 hover:text-red-900">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');

                // Update pagination info
                const start = (page - 1) * 10 + 1;
                const end = Math.min(page * 10, total);
                document.getElementById('paginationInfo').textContent = 
                    `Showing ${start} to ${end} of ${total} results`;

                // Update pagination controls
                updatePaginationControls();

                // Reinitialize Lucide icons
                lucide.createIcons();
            }
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    // Update pagination controls
    function updatePaginationControls() {
        const controls = document.getElementById('paginationControls');
        controls.innerHTML = `
            <button onclick="changePage(${currentPage - 1})" 
                    class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50"
                    ${currentPage === 1 ? 'disabled' : ''}>
                Previous
            </button>
            <button onclick="changePage(${currentPage + 1})"
                    class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50"
                    ${currentPage === totalPages ? 'disabled' : ''}>
                Next
            </button>
        `;
    }

    // Change page
    function changePage(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            loadProducts();
        }
    }

    // Search and filter handlers
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentSearch = e.target.value;
            currentPage = 1;
            loadProducts();
        }, 300);
    });

    document.getElementById('categoryFilter').addEventListener('change', (e) => {
        currentCategory = e.target.value;
        currentPage = 1;
        loadProducts();
    });


    document.addEventListener('DOMContentLoaded', (event) => {
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const tabs = {
        products: document.getElementById('products'),
        orders: document.getElementById('orders'),
        settings: document.getElementById('settings')
    };

    sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href === '/parts/logout.php') {
                return;
            }

            e.preventDefault();
            const targetId = href.substring(1);
            
            // Ensure the target tab exists before manipulating its classList
            if (tabs[targetId]) {
                // Hide all tabs
                Object.values(tabs).forEach(tab => tab.classList.add('hidden'));
                
                // Show target tab
                tabs[targetId].classList.remove('hidden');
                
                // Update active state
                sidebarLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            }
        });
    });

    // Ensure icons are properly initialized
    lucide.createIcons();
});

        // Ensure icons are properly initialized
        document.addEventListener('DOMContentLoaded', (event) => {
            lucide.createIcons();
        });

    // Modal functions
    const productModal = document.getElementById('productModal');

    function openAddProductModal() {
        document.getElementById('modalTitle').textContent = 'Add New Product';
        productModal.classList.remove('hidden');
    }

    function editProduct(productId) {
        document.getElementById('modalTitle').textContent = 'Edit Product';
        productModal.classList.remove('hidden');
        
        // Fetch product data
        axios.get(`api/admin/products.php?id=${productId}`)
            .then(response => {
                if (response.data.success) {
                    const product = response.data.data;
                    const form = document.getElementById('productForm');
                    
                    // Populate form fields
                    Object.keys(product).forEach(key => {
                        const input = form.elements[key];
                        if (input) {
                            input.value = product[key];
                        }
                    });
                    
                    // Store product ID for update
                    form.dataset.productId = productId;
                }
            })
            .catch(error => console.error('Error fetching product:', error));
    }

    function closeProductModal() {
        productModal.classList.add('hidden');
        document.getElementById('productForm').reset();
        document.getElementById('productForm').dataset.productId = '';
    }

    function deleteProduct(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            axios.delete(`api/admin/products.php?id=${productId}`)
                .then(response => {
                    if (response.data.success) {
                        loadProducts();
                    } else {
                        alert('Error deleting product: ' + response.data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting product:', error);
                    alert('Error deleting product. Please try again.');
                });
        }
    }

    // Form submission
    document.getElementById('productForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const productId = e.target.dataset.productId;
            let response;
            
            if (productId) {
                // Update existing product
                data.id = productId;
                response = await axios.put('api/admin/products.php', data);
            } else {
                // Add new product
                response = await axios.post('api/admin/products.php', data);
            }
            
            if (response.data.success) {
                closeProductModal();
                loadProducts();
            } else {
                alert('Error saving product: ' + response.data.message);
            }
        } catch (error) {
            console.error('Error saving product:', error);
            alert('Error saving product. Please try again.');
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeProductModal();
        }
    });

    // Close modal when clicking outside
    productModal.addEventListener('click', (e) => {
        if (e.target === productModal) {
            closeProductModal();
        }
    });

    // Preview uploaded image
    document.getElementById('header_background').addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('header_background_preview').src = event.target.result;
                document.getElementById('header_background_preview').style.display = 'block';
                document.querySelector('.preview-placeholder').style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    // Initial load
    loadProducts();
</script>
</body>
</html>