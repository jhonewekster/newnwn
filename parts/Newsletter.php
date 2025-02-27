
 <style>
/* Add these styles to your CSS file (e.g., subscribe.css) */

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
}

.popup {
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.popup-icon {
    width: 50px;
    height: 50px;
    color: #28a745; /* Green color for success */
}

.popup-icon.alert-circle {
    color: #dc3545; /* Red color for error */
}

.popup-title {
    font-size: 24px;
    margin: 10px 0;
}

.popup-message {
    font-size: 18px;
    margin: 10px 0;
}

.popup-close {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.popup-close:hover {
    background: #0056b3;
}
 </style>
<?php
require_once __DIR__ . '/../class/index-class.php';

$error_message = '';
$success_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email from the form submission
    $email = $_POST['email'];

    // Create an instance of the Subscribe class
    $subscribe = new Index_ugtri();

    // Add the subscriber
    $result = $subscribe->addSubscriber($email);

    // Provide feedback to the user
    if ($result['status']) {
        $success_message = "Thank you for subscribing with us!";
    } else {
        $error_message = $result['message'];
    }
}
?>

<section class="py-12 md:py-20 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6 text-center">
        <h2 class="text-xl md:text-2xl font-bold mb-2">Subscribe to Our Newsletter</h2>
        <p class="text-gray-600 text-sm mb-6 md:mb-8">Get the latest updates on new products and upcoming sales.</p>
        <div class="max-w-md mx-auto">
            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form class="flex flex-col sm:flex-row gap-2 sm:gap-0" method="POST" action="">
                <input 
                    type="email" 
                    name="email"
                    placeholder="Enter your email" 
                    class="flex-1 px-4 py-3 rounded-full sm:rounded-r-none border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-900 w-full"
                    required
                >
                <button type="submit" name="Subscribeme" class="w-full sm:w-auto bg-black text-white px-8 py-3 rounded-full sm:rounded-l-none font-semibold hover:bg-gray-800 transition-colors">
                    Subscribe
                </button>
            </form>
        </div>
    </div>
</section>

<?php if (!empty($error_message) || !empty($success_message)): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('subscribePopup').style.display = 'flex';
    });

    function closePopup() {
        document.getElementById('subscribePopup').style.display = 'none';
    }
</script>

<div class="popup-overlay" id="subscribePopup" style="display: flex;">
    <div class="popup">
        <i data-lucide="<?php echo !empty($error_message) ? 'alert-circle' : 'check-circle'; ?>" class="popup-icon"></i>
        <h2 class="popup-title"><?php echo !empty($error_message) ? 'Subscription Error' : 'Subscription Successful'; ?></h2>
        <p class="popup-message"><?php echo htmlspecialchars(!empty($error_message) ? $error_message : $success_message); ?></p>
        <button class="popup-close" onclick="closePopup()">Close</button>
    </div>
</div>
<?php endif; ?>

<script>
    lucide.createIcons();
</script>