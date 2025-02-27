<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
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
              "@type": "Organization",
              "name": "<?php echo htmlspecialchars($storeName); ?>",
              "url": "<?php echo htmlspecialchars($storeUrl); ?>",
              "logo": "<?php echo htmlspecialchars($storeUrl); ?>/images/favicon-us.svg",
              "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "<?php echo htmlspecialchars($storePhone); ?>",
                "contactType": "customer service"
              }
            }
    </script>
    <title><?php echo htmlspecialchars($store_name); ?> - Unique State-Themed Apparel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <meta name="robots" content="index, follow">
</head>
<body class="bg-gray-50">
<?php include 'parts/navbar.php'; ?>
<?php include 'parts/header.php'; ?>





<?php include 'parts/product.php'; ?>




<?php require_once 'parts/Newsletter.php'; ?>
<?php require_once 'parts/footer.php'; ?>



<style>
.cc-color-override-688238583 .cc-btn {
background-color:white !important
    
}
</style>
 <!-- Include Cookie Consent script -->
   <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />
    <script>
    window.addEventListener("load", function(){
      window.cookieconsent.initialise({
        "palette": {
          "popup": {
            "background": "#000"
          },
          "button": {
            "background": "#f1d600"
          }
        },
        "theme": "classic",
        "content": {
          "message": "This website uses cookies to ensure you get the best experience on our website.",
          "dismiss": "Got it!",
          "link": "Learn more",
          "href": "/cookie-policy"
        }
      });
    });
    </script>
<script>
    lucide.createIcons();
</script>
</body>
</html>