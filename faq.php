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
              "@type": "<?php echo htmlspecialchars($store_name); ?> - FAQ Page",
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
    <title><?php echo htmlspecialchars($store_name); ?> - FAQ Page</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="style/faq.css">
    <meta name="robots" content="index, follow">
    <style>

    </style>
</head>
<body>
<?php include 'parts/navbar.php'; ?>
 
<div class="container mainos max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="header headros">
            <h1>Frequently Asked Questions</h1>
            <p class="text-gray-600">Find answers to common questions about our products and services</p>
        </div>

        <div class="shipping-card">
            <div class="shipping-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                    <path d="M3 9h18"></path>
                    <path d="M21 9V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3"></path>
                </svg>
            </div>
            <div class="shipping-content">
                <h3>Free US Shipping</h3>
                <p>We offer free standard shipping on all US orders:</p>
                <ul>
                   <li>Processing time: 2-4 business days</li>
                    <li>Standard shipping delivery time: 5-7 business days within the U.S.</li>
                    <li>Tracking number provided via email once your order has shipped</li>
                    <li>No minimum order value required for free shipping on orders over $50</li>
                </ul>
            </div>
        </div>

        <div class="category-tabs">
            <button class="tab active">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                    <path d="M3 9h18"></path>
                    <path d="M21 9V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3"></path>
                </svg>
                Shipping
            </button>
            <button class="tab">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M3 12h6"></path>
                </svg>
                Returns
            </button>
            <button class="tab">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 8a2 2 0 0 0-2-2h-6"></path>
                    <path d="M3 8a2 2 0 0 1 2-2h2"></path>
                    <path d="M21 12a2 2 0 0 1-2 2h-2"></path>
                    <path d="M3 12a2 2 0 0 0 2 2h6"></path>
                    <path d="M21 16a2 2 0 0 1-2 2h-2"></path>
                    <path d="M3 16a2 2 0 0 0 2 2h2"></path>
                </svg>
                Orders
            </button>
            <button class="tab">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                    <line x1="2" x2="22" y1="10" y2="10"></line>
                </svg>
                Payment
            </button>
            <button class="tab">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3"></path>
                    <path d="M21 16v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3"></path>
                    <path d="M4 12h16"></path>
                </svg>
                Products
            </button>
        </div>

        <div class="faq-list" id="shipping-faqs">
    <div class="faq-item">
        <button class="faq-question">
            Is shipping really free worldwide?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
           No, we currently offer free standard shipping on all U.S. orders only.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            Which countries do you ship to?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
            We currently offer shipping within the United States only.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            How long does shipping take?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
           For US orders: Processing time is 2-4 business days, with delivery taking 5-7 business days.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            Do you offer expedited shipping?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
          We currently do not offer expedited shipping. All orders are processed with standard shipping.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            How do I track my order?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
            You'll receive a tracking number via email once your order ships. You can track your order here: <a href='/track-order'>Track Order</a>
        </div>
    </div>
</div>

<div class="faq-list" id="returns-faqs" style="display: none;">
    <div class="faq-item">
        <button class="faq-question">
            What's your return policy?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
           We accept returns within 30 days of delivery for unused items in their original packaging. Returned items must be in new, resellable condition. Customers are responsible for return shipping costs. Please review our full return policy for additional details and exceptions.
        </div>
    </div>
    <div class="faq-item">
        <button class="faq-question">
            How do I initiate a return?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
            To initiate a return, please contact our customer support team for a return authorization and instructions on how to send back your item.
        </div>
    </div>
</div>

<div class="faq-list" id="orders-faqs" style="display: none;">
    <div class="faq-item">
        <button class="faq-question">
            How do I check my order status?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
            You can check the status of your order by logging into your account and viewing your order history. Alternatively, use the tracking number provided in the shipping confirmation email.
        </div>
    </div>
    <div class="faq-item">
        <button class="faq-question">
            Can I modify my order?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
            Orders can be modified within 1 hour of placement. If you need to make any changes, please contact customer support immediately.
        </div>
    </div>
</div>

<div class="faq-list" id="payment-faqs" style="display: none;">
    <div class="faq-item">
        <button class="faq-question">
            What payment methods do you accept?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
            We accept all major credit cards. Our payments are processed securely.
        </div>
    </div>
    <div class="faq-item">
        <button class="faq-question">
            Is my payment information secure?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
            Yes, we ensure that your payment information is securely processed using encrypted channels for your safety.
        </div>
    </div>
</div>

<div class="faq-list" id="products-faqs" style="display: none;">
    <div class="faq-item">
        <button class="faq-question">
            What's your warranty policy?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
           All products come with a manufacturer warranty against defects for a period of 6 month from the date of purchase.
        </div>
    </div>
    <div class="faq-item">
        <button class="faq-question">
            Do you offer bulk discounts?
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        <div class="faq-answer">
            Yes, we offer bulk discounts. Please contact our sales team for bulk order pricing and discounts.
        </div>
    </div>
</div>

<div class="mt-16 bg-gray-50 p-8 rounded-xl text-center">
    <h2 class="text-xl font-bold mb-4">Still have questions?</h2>
    <p>Our customer support team is here to help</p>
    <div class="contact-info">
        <p>Email: info@texastreasures.shop</p>
        <p>Phone: +1 (915) 202-4336</p>
        <p>Hours: Monday–Friday, 9:00 AM – 5:00 PM PST</p>
    </div>
</div>
</div>

    <script>
        // FAQ Accordion functionality
        const faqQuestions = document.querySelectorAll('.faq-question');
        function initializeFAQs() {
          faqQuestions.forEach(question => {
            question.addEventListener('click', () => {
              // Close other open items
              faqQuestions.forEach(otherQuestion => {
                if (otherQuestion !== question) {
                  otherQuestion.parentElement.classList.remove('active');
                }
              });
              // Toggle current item
              question.parentElement.classList.toggle('active');
            });
          });
        }

        // Initialize FAQ functionality
        initializeFAQs();

        // Category tabs functionality with content switching
        const faqSections = {
          'Shipping': 'shipping-faqs',
          'Returns': 'returns-faqs',
          'Orders': 'orders-faqs',
          'Payment': 'payment-faqs',
          'Products': 'products-faqs'
        };

        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => {
          tab.addEventListener('click', () => {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            tab.classList.add('active');

            // Hide all FAQ sections
            Object.values(faqSections).forEach(sectionId => {
              document.getElementById(sectionId).style.display = 'none';
            });

            // Show the selected section
            const category = tab.textContent.trim();
            const sectionId = faqSections[category];
            document.getElementById(sectionId).style.display = 'block';
          });
        });
    </script>

<?php require_once 'parts/footer.php'; ?>
</body>
</html>