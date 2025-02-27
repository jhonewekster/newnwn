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
          "@type": "<?php echo htmlspecialchars($storeName); ?> - About Us",
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
    <title><?php echo htmlspecialchars($store_name); ?> - Our Story</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="style/our-story.css">
    <meta name="robots" content="index, follow">
   <style>
   .mission-container {
    display: flex;
    gap: 20px; /* Adjust spacing between items */
    align-items: flex-start; /* Align items at the top */
}

.mission-item {
    flex: 1; /* Equal width for both sections */
    max-width: 50%; /* Ensures it doesn’t stretch too much */
}
 </style>  
</head>
<body class="bg-gray-50">
<?php include 'parts/navbar.php'; ?>





<div class="container-our-story">
        <!-- Mission Section -->
        <section class="mission-section">
            <i data-lucide="store" class="mission-icon"></i>
            <div class="mission-container">
    <div class="mission-item">
        <h1 class="mission-title">Our Story</h1>
        <p class="mission-text">
           Welcome to Texas Treasures, where we combine our love of distinctive clothing with a dedication to client pleasure.  We started out with the goal of providing premium designs that capture the essence of Texas and beyond, and we have since grown to become a reliable source for people looking for outstanding, customized fashion.
        </p>
    </div>
    
    <div class="mission-item">
        <h1 class="mission-title">Our Mission</h1>
        <p class="mission-text">
            Every article of clothing, in our opinion at Texas Treasures, has the capacity to inspire pride, celebrate culture, and express individuality.  Our goal is to provide clients with distinctive, superior designs and an outstanding purchasing experience.  We take great care in selecting our selection to make sure that each piece satisfies our exacting requirements for quality and affordability.
        </p>
    </div>
</div>

        </section>

        <!-- Core Values -->
        <div class="values-grid">
            <div class="value-card">
                <i data-lucide="users" class="value-icon community-icon"></i>
                <h3 class="value-title">Community First</h3>
                <p class="value-description">Building connections through a shared love of unique, state-inspired apparel.</p>
            </div>

            <div class="value-card">
                <i data-lucide="heart" class="value-icon care-icon"></i>
                <h3 class="value-title">Customer Care</h3>
                <p class="value-description">Dedicated to providing the best shopping experience and support for every customer.</p>
            </div>

            <div class="value-card">
                <i data-lucide="badge-check" class="value-icon quality-icon"></i>
                <h3 class="value-title">Quality First</h3>
                <p class="value-description">Ensuring excellence in every design and product we offer.</p>
            </div>
        </div>

        <!-- Values Section -->
        <section class="values-section">
            <h2 class="values-title">Our Values</h2>
            <p class="values-subtitle">The principles that guide everything we do</p>

            <div class="service-columns">
                <div class="service-column">
                    <h3 class="service-title">Quality Assurance</h3>
                    <p class="service-description">
                       Each piece in our collection is put through a thorough quality check to make sure it satisfies our exacting requirements for craftsmanship, durability, and design.
                    </p>
                    <ul class="service-list">
                        <li><i data-lucide="check-circle"></i>Thorough inspection process</li>
                        <li><i data-lucide="check-circle"></i>Authentic and unique designs</li>
                        <li><i data-lucide="check-circle"></i>Satisfaction guaranteed</li>
                    </ul>
                </div>

                <div class="service-column">
                    <h3 class="service-title">Customer Service</h3>
                    <p class="service-description">
                        If you have any questions or issues, our committed staff is available to help.  We take pleasure in offering prompt, friendly customer service to make your shopping experience even better.
                    </p>
                    <ul class="service-list">
                        <li><i data-lucide="check-circle"></i>24/7 customer support</li>
                        <li><i data-lucide="check-circle"></i>Expert product recommendations</li>
                        <li><i data-lucide="check-circle"></i>Personalized assistance</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section">
            <h2 class="contact-title">Get in Touch</h2>
            <p class="contact-subtitle">Have questions or suggestions? We'd love to hear from you!</p>

            <div class="contact-methods">
                <div class="contact-method visit">
                    <i data-lucide="map-pin" class="contact-method-icon"></i>
                    <h3 class="contact-method-title">Visit Us</h3>
                    <p class="contact-method-text">
                        6662, 13350 Dallas Pkwy #3610, Dallas, TX 75240,<br>
                        United States
                    </p>
                </div>

                <div class="contact-method call">
                    <i data-lucide="phone" class="contact-method-icon"></i>
                    <h3 class="contact-method-title">Call Us</h3>
                    <p class="contact-method-text">+1 (915) 202-4336</p>
                </div>

                <div class="contact-method email">
                    <i data-lucide="mail" class="contact-method-icon"></i>
                    <h3 class="contact-method-title">Email Us</h3>
                    <p class="contact-method-text">info@texastreasures.shop</p>
                </div>
            </div>
        </section>
    </div>










    <?php require_once 'parts/Newsletter.php'; ?>
    <?php require_once 'parts/footer.php'; ?>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>