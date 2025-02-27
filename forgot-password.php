<?php

session_start();
require_once __DIR__ . "/class/login-class.php";
if (isset($_SESSION['user_id'])) {
    header("Location: ./shop");
    exit();
}
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = new Login();
    $email = $_POST['email'];
    $password = $_POST['password'];
    $result = $login->login($email, $password);

    if ($result['status']) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['first_name'] = $result['first_name'];
        $_SESSION['last_name'] = $result['last_name'];
        $_SESSION['type'] = $result['type'];
        
        if ($result['type'] == 'admin') {
            header("Location: ./dashboard-admin");
        } else {
            header("Location: ./dashboard");
        }
        exit();
    } else {
        $error_message = $result['message'];
    }
}
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
              "@type": "<?php echo htmlspecialchars($store_name); ?> - Forget Password",
              "mainEntity": {
                "@type": "Organization",
                "name": "<?php echo htmlspecialchars($storeName); ?>",
                "url": "<?php echo htmlspecialchars($storeUrl); ?>",
                "logo": "<?php echo htmlspecialchars($storeUrl); ?>/favicon-us.svg",
                "contactPoint": {
                  "@type": "ContactPoint",
                  "telephone": "<?php echo htmlspecialchars($storePhone); ?>",
                  "contactType": "customer service"
                }
              }
            }
        </script>
    <title><?php echo htmlspecialchars($store_name); ?> - Forget Password</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <link rel="stylesheet" href="style/login.css">
    <link rel="stylesheet" href="style/popup.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
<meta name="robots" content="index, follow">
</head>
<body class="bg-gray-50">
<?php include 'parts/navbar.php'; ?>




















<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <a href="/" class="auth-logo">
                <i data-lucide="book" class="w-6 h-6"></i>
                <span class="text-xl font-semibold">Texas Treasures</span>
            </a>
            <h1 class="auth-title">Welcome</h1>
            <p class="auth-subtitle">Retrieve your password</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form class="auth-form" action="/login" method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Email address</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>

            <div class="form-footer">
                <label class="remember-me">
                    
                </label>
            </div>

            <button type="submit" class="submit-button">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                Send Email Instruction
            </button>
        </form>

        <div class="auth-divider">or</div>

        <div class="auth-footer">
            Don't have an account? <a href="/register">Create an account</a>
        </div>
    </div>
</div>


<script>
    lucide.createIcons();

    document.addEventListener('DOMContentLoaded', function () {
        const protectedButton = document.getElementById('protectedButton');

        if (protectedButton) {
            protectedButton.addEventListener('click', function (event) {
                <?php if (!isset($_SESSION['user_id'])): ?>
                    event.preventDefault();
                    document.getElementById('loginPopup').style.display = 'flex';
                <?php endif; ?>
            });
        }

        <?php if (!empty($error_message)): ?>
            document.getElementById('loginPopup').style.display = 'flex';
        <?php endif; ?>
    });

    function closePopup() {
        document.getElementById('loginPopup').style.display = 'none';
    }
</script>

<div class="popup-overlay" id="loginPopup" style="display: none;">
    <div class="popup">
        <i data-lucide="alert-circle" class="popup-icon"></i>
        <h2 class="popup-title">Login Error</h2>
        <p class="popup-message"><?php echo htmlspecialchars($error_message); ?></p>
        <button class="popup-close" onclick="closePopup()">Close</button>
    </div>
</div>











<?php require_once 'parts/footer.php'; ?>
</body>
</html>