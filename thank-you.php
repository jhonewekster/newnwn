<?php
session_start();

// Check if payment was successful
if (!isset($_SESSION['payment_success']) || $_SESSION['payment_success'] !== true) {
    header('Location: checkout.php');
    exit;
}

// Clear the payment success flag
unset($_SESSION['payment_success']);
$successMessage = $_SESSION['success_message'] ?? 'Thank you for your purchase!';
unset($_SESSION['success_message']);

include 'parts/navbar.php';
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
    <title><?php echo htmlspecialchars($store_name); ?> - Thank You</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style/checkout.css">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Thank You for Your Purchase!</h2>
                <p class="mt-2 text-gray-600"><?php echo htmlspecialchars($successMessage); ?></p>
                <p class="mt-2 text-sm text-gray-500">A confirmation email has been sent to your email address.</p>
                <div class="mt-6">
                    <a href="index.php" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>