<?php
require_once __DIR__ . '/../class/entire-website-controle.php';

$websiteEdite = new Entire_Website_Controle();
$settings = $websiteEdite->fetchWebsiteSettings();

if ($settings) {
    $store_name = $settings['store_name'];
    $header_background = $settings['header_background'];
} else {
    $store_name = "Default Store Name";
    $header_background = "./images/default-header.jpg"; // Default image in case of no results
}
?>

<link rel="stylesheet" href="style/header.css">
<link rel="icon" type="image/svg+xml" href="./images/favicon.svg">
<section class="hero-section-btt">
    <div class="hero-background-btt">
        <img src="<?php echo $header_background; ?>" alt="Library">
    </div>
    <div class="container-btt">
        <div class="hero-content-btt">
            <h1 class="hero-title-btt"><?php echo $store_name; ?> - Unique State-Themed Apparel</h1>
            <p class="hero-description-btt">Explore our exclusive line of state-inspired t-shirts, which honor Texas, the states of the United States, and American culture with their striking patterns and recognizable emblems.</p>
            <button class="hero-button-btt">
                <a href="./shop.php" class="flex items-center space-x-2">
                    <span>Explore Collection</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </a>
            </button>
        </div>
    </div>
</section>