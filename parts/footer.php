
<?php
$currentYear = date('Y');
?>
        <?php
        require_once __DIR__ . '/../class/entire-website-controle.php';

        $websiteEdite = new Entire_Website_Controle();
        $settings = $websiteEdite->footer_propty();

        if ($settings) {
            $store_name = $settings['store_name'];
            $address_store = $settings['address_store'];
            $mail_business = $settings['mail_business'];
            $phone = $settings['phone'];

        } else {
            $store_name = "";
            $address_store = "";
            $mail_business = "";
            $phone = "";

        }
        ?>
        <link rel="stylesheet" href="/../style/footer.css">
        <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Company Info -->
                <div>
                    <a href="../" class="footer-brand">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="shirt" class="lucide lucide-shirt w-6 h-6"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                        <span class="footer-brand-text"><?php echo $store_name; ?></span>
                    </a>
                    <div class="footer-contact space-y-2">
                        <p class="flex items-center space-x-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 2L11 13"></path>
                                <path d="M22 2L15 22L11 13L2 9L22 2Z"></path>
                            </svg>
                            <span><?php echo $mail_business; ?></span>
                        </p>
                        <p class="flex items-center space-x-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <span><?php echo $phone; ?></span>
                        </p>
                        <p class="flex items-center space-x-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span><?php echo $address_store; ?></span>
                        </p>
                    </div>
                </div>

                <!-- Useful Links -->
                <div>
                    <h3 class="footer-heading">Useful Links</h3>
                    <ul class="footer-links">
                        <li><a href="/../index.php" class="footer-link">Home</a></li>
                        <li><a href="/../shop.php" class="footer-link">Shop</a></li>
                        <li><a href="/../faq.php" class="footer-link">FAQs</a></li>
                        <li><a href="/../about-us" class="footer-link">About Us</a></li>
                        <li><a href="/../contact-us" class="footer-link">Contact Us</a></li>
                        <li><a href="/../track-order" class="footer-link">Track Order</a></li>
                    </ul>
                </div>

                <!-- Information -->
                <div>
                    <h3 class="footer-heading">Information</h3>
                    <ul class="footer-links">
                        <li><a href="/../privacy-policy" class="footer-link">Privacy Policy</a></li>
                        <li><a href="/../terms-of-service" class="footer-link">Terms of Service</a></li>
                        <li><a href="/../shipping-policy" class="footer-link">Shipping Policy</a></li>
                        <li><a href="/../payment-policy" class="footer-link">Payment Policy</a></li>
                        <li><a href="/../refund-and-returns-policy" class="footer-link">Refund and Returns Policy</a></li>
                        <li><a href="/../dmca-policy" class="footer-link">DMCA Policy</a></li>
                         <li><a href="/../cookie-policy" class="footer-link">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div>© <?php echo $currentYear; ?> Texastreasures. All rights reserved.</div>
                <div class="footer-bottom-right">
                    <div class="footer-bottom-text">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        Secure payments by Stripe
                    </div>
                    <div class="footer-bottom-text">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        Privacy and security guaranteed
                    </div>
                </div>
            </div>
        </div>
    </footer>