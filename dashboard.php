    <?php
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
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "<?php echo htmlspecialchars($store_name); ?> - User Dashboard",
              "mainEntity": {
                "@type": "Organization",
                "name": "<?php echo htmlspecialchars($storeName); ?>",
                "url": "<?php echo htmlspecialchars($storeUrl); ?>",
                "logo": "<?php echo htmlspecialchars($storeUrl); ?>/favicon.svg",
                "contactPoint": {
                  "@type": "ContactPoint",
                  "telephone": "<?php echo htmlspecialchars($storePhone); ?>",
                  "contactType": "customer service"
                }
              }
            }
        </script>
    <title><?php echo htmlspecialchars($store_name); ?> - User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="robots" content="index, follow">
    <style>
        .sidebar-link.active {
            background-color: #4338ca;
            color: white;
        }
        @media (max-width: 768px) {
            .dashboard-layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .order-grid {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="dashboard-layout flex min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar w-64 bg-white border-r border-gray-200 fixed h-full">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    <i data-lucide="book-open" class="w-8 h-8 text-indigo-600"></i>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">My Account</h1>
                        <p class="text-sm text-gray-500">Welcome back, John</p>
                    </div>
                </div>
            </div>
            <nav class="p-4 space-y-2">
                <a href="#dashboard" class="sidebar-link active flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#orders" class="sidebar-link flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>My Orders</span>
                </a>
                <a href="#library" class="sidebar-link flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
                    <i data-lucide="library" class="w-5 h-5"></i>
                    <span>My Library</span>
                </a>
                <a href="#profile" class="sidebar-link flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    <span>Profile</span>
                </a>
                <a href="#settings" class="sidebar-link flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-1 ml-64 p-6">
            <!-- Dashboard Overview -->
            <div id="dashboard" class="space-y-6">
                <div class="stats-grid grid grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-900">12</p>
                            </div>
                            <div class="p-3 bg-indigo-50 rounded-full">
                                <i data-lucide="shopping-cart" class="w-6 h-6 text-indigo-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Books Owned</p>
                                <p class="text-2xl font-bold text-gray-900">28</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded-full">
                                <i data-lucide="book" class="w-6 h-6 text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Reviews</p>
                                <p class="text-2xl font-bold text-gray-900">8</p>
                            </div>
                            <div class="p-3 bg-yellow-50 rounded-full">
                                <i data-lucide="star" class="w-6 h-6 text-yellow-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Wishlist</p>
                                <p class="text-2xl font-bold text-gray-900">15</p>
                            </div>
                            <div class="p-3 bg-red-50 rounded-full">
                                <i data-lucide="heart" class="w-6 h-6 text-red-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Recent Orders</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#ORD-2024-001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Mar 15, 2024</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Delivered
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$89.99</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-indigo-600 hover:text-indigo-900">View Details</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Profile Section -->
            <div id="profile" class="hidden">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
                    </div>
                    <div class="p-6">
                        <form class="space-y-6">
                            <div class="profile-grid grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" value="John" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" value="Doe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" value="john@example.com" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="tel" value="+1 234 567 890" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">123 Book Street, Reading City, RC 12345</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Library Section -->
            <div id="library" class="hidden">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">My Library</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Book Card -->
                            <div class="border rounded-lg overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=800" alt="Book cover" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="font-medium text-gray-900">The Art of Reading</h3>
                                    <p class="text-sm text-gray-500">by Jane Smith</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <button class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Read Now</button>
                                        <div class="flex items-center">
                                            <i data-lucide="star" class="w-4 h-4 text-yellow-400"></i>
                                            <span class="ml-1 text-sm text-gray-600">4.5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Tab switching
        const tabs = {
            dashboard: document.getElementById('dashboard'),
            orders: document.getElementById('orders'),
            library: document.getElementById('library'),
            profile: document.getElementById('profile'),
            settings: document.getElementById('settings')
        };

        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                
                // Hide all tabs
                Object.values(tabs).forEach(tab => {
                    if (tab) tab.classList.add('hidden');
                });
                
                // Show target tab
                const targetTab = tabs[targetId];
                if (targetTab) targetTab.classList.remove('hidden');
                
                // Update active state
                sidebarLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                // On mobile, scroll to top
                if (window.innerWidth < 768) {
                    window.scrollTo(0, 0);
                }
            });
        });

        // Handle responsive sidebar
        function handleResize() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth < 768) {
                sidebar.style.position = 'static';
                mainContent.style.marginLeft = '0';
            } else {
                sidebar.style.position = 'fixed';
                mainContent.style.marginLeft = '16rem';
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize(); // Initial call
    </script>
</body>
</html>