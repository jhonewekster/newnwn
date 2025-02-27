<?php
session_start();
require_once __DIR__ . '/class/register-class.php';
if (isset($_SESSION['user_id'])) {
    header("Location: ./shop.php"); // Change this to your home page
    exit();
}
$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $register = new Register();
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];

    if ($password !== $confirm_password) {
        $error_message = "The passwords you entered don't match.";
    } else {
        $result = $register->register($first_name, $last_name, $email, $password);

        if ($result['status']) {
            $success_message = $result['message'];
            header("Location: login.php");
            exit();
        } else {
            $error_message = $result['message'];
        }
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
              "@type": "<?php echo htmlspecialchars($store_name); ?> - Sign Up",
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
    <title><?php echo htmlspecialchars($store_name); ?> - Sign Up</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <link rel="stylesheet" href="/style/index.css">
    <link rel="stylesheet" href="style/register.css">
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
            <h1 class="auth-title">Create an account</h1>
            <p class="auth-subtitle">Join Dekal today</p>
        </div>

        <form class="auth-form" action="register.php" method="POST">
            <div class="name-fields">
                <div class="form-group">
                    <label for="firstName" class="form-label">First name</label>
                    <input type="text" id="firstName" name="firstName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="lastName" class="form-label">Last name</label>
                    <input type="text" id="lastName" name="lastName" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email address</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="confirmPassword" class="form-label">Confirm password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" required>
            </div>

            <label class="terms-checkbox">
                <input type="checkbox" name="terms" class="w-4 h-4 rounded border-gray-300 text-blue-600" required>
                <span class="text-sm">I agree to the <a href="/terms-of-service">Terms of Service</a> and <a href="/privacy-policy">Privacy Policy</a></span>
            </label>

            <button type="submit" class="submit-button">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                Create account
            </button>
        </form>

        <div class="auth-divider">or</div>

        <div class="auth-footer">
            Already have an account? <a href="/login">Sign in</a>
        </div>
    </div>
</div>


<script>
    lucide.createIcons();

    document.addEventListener('DOMContentLoaded', function () {
        <?php if (!empty($error_message)): ?>
            document.getElementById('popupMessage').innerText = "<?php echo htmlspecialchars($error_message); ?>";
            document.getElementById('popupIcon').setAttribute('data-lucide', 'alert-circle');
            document.getElementById('popupTitle').innerText = "Registration Error";
            document.getElementById('popup').style.display = 'flex';
        <?php elseif (!empty($success_message)): ?>
            document.getElementById('popupMessage').innerText = "<?php echo htmlspecialchars($success_message); ?>";
            document.getElementById('popupIcon').setAttribute('data-lucide', 'check-circle');
            document.getElementById('popupTitle').innerText = "Registration Successful";
            document.getElementById('popup').style.display = 'flex';
        <?php endif; ?>
    });

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }
</script>

<div class="popup-overlay" id="popup" style="display: none;">
    <div class="popup">
        <i id="popupIcon" class="popup-icon"></i>
        <h2 id="popupTitle" class="popup-title"></h2>
        <p id="popupMessage" class="popup-message"></p>
        <button class="popup-close" onclick="closePopup()">Close</button>
    </div>
</div>












<?php require_once 'parts/footer.php'; ?>
</body>
</html>