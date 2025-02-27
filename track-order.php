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
          "@type": "<?php echo htmlspecialchars($storeName); ?> - Track Order",
          "mainEntity": {
            "@type": "Organization",
            "name": "<?php echo htmlspecialchars($storeName); ?>",
            "url": "current url",
            "logo": "<?php echo htmlspecialchars($storeUrl); ?>/favicon-us.svg",
            "contactPoint": {
              "@type": "ContactPoint",
              "telephone": "<?php echo htmlspecialchars($storePhone); ?>",
              "contactType": "customer service"
            }
          }
        }
        </script>
    <title><?php echo htmlspecialchars($store_name); ?> - Track Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <meta name="robots" content="index, follow">
</head>
<body class="bg-gray-50">
<?php include 'parts/navbar.php'; ?>
    <div class="p-9 flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md">
            <h1 class="text-4xl font-bold text-center mb-4">Track Your Order</h1>
            <p class="text-gray-600 text-center mb-8">
                Enter your order number and email to track your package
            </p>

            <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <div class="space-y-2">
                    <label for="orderNumber" class="block text-sm font-medium text-gray-700">
                        Order Number
                    </label>
                    <input
                        type="text"
                        id="orderNumber"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="e.g., ORD123456"/>
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter the email used for the order"/>
                </div>

                <button 
                    type="submit"
                    class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                    <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                    Track Order
                </button>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
    <?php require_once 'parts/footer.php'; ?>
</body>
</html>