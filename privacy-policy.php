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
              "@type": "<?php echo htmlspecialchars($store_name); ?> - Privacy Policy",
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
    <title><?php echo htmlspecialchars($store_name); ?> - Privacy Policy</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="style/privacy-policy.css">
    <meta name="robots" content="index, follow">
</head>

<body class="bg-gray-50">
    <?php include 'parts/navbar.php'; ?>



    <!-- start privacy policy content -->
    <?php
    $lastUpdated = "March 14, 2024";
    ?>

    <div class="privacy-policy">
        <h1>Privacy Policy</h1>
        <p class="last-updated">Last Updated: <?php echo $lastUpdated; ?></p>


        <?php
        require_once __DIR__ . '/class/entire-website-controle.php';

        $websiteEdite = new Entire_Website_Controle();
        $settings = $websiteEdite->Affcihe_Privacy();

        if ($settings) {
            $privacy_policy = $settings['privacy_policy'];

        } else {
            $privacy_policy = "Error , please load the page";

        }
        ?>




        <?php echo $privacy_policy; ?>




    </div>
    <!-- End privacy policy content -->










    <?php require_once 'parts/Newsletter.php'; ?>
    <?php require_once 'parts/footer.php'; ?>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>