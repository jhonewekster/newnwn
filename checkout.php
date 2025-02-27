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
              "@type": "<?php echo htmlspecialchars($store_name); ?> - Checkout",
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
    <title><?php echo htmlspecialchars($store_name); ?> - Checkout</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon-us.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="style/checkout.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-50">
<?php 
include 'parts/navbar.php'; 
require_once 'class/checkout-class.php';
require_once 'config/stripe.php';

$stripeConfig = include 'config/stripe.php';
$checkout = new Checkout();
$cartProducts = isset($_SESSION['cart']) ? $checkout->getCartProducts($_SESSION['cart']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['stripeToken'];
    $userId = $_SESSION['user_id']; // Assuming user_id is stored in session
    $cardNumber = $_POST['card_number'];
    $expiryDate = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Save card info in database
    if ($checkout->saveCardInfo($userId, $cardNumber, $expiryDate, $cvv)) {
    } else {
        echo '<p>Error saving card information.</p>';
    }
}
?>

<div class="checkout-container">
    <div class="form-section">
        <h2 class="section-title text-2xl font-bold mb-6">Contact Information</h2>
        <div class="form-group">
            <label>Email Address *</label>
            <input type="email" placeholder="Enter your email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" placeholder="e.g., +1234567890" pattern="^\+?[1-9]\d{1,14}$">
        </div>

        <h2 class="section-title text-2xl font-bold mb-6" style="margin-top: 2rem;">Shipping Address</h2>
        <div class="input-row">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" pattern="[A-Za-z]+" required>
            </div>
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" pattern="[A-Za-z]+" required>
            </div>
        </div>
        <div class="form-group">
            <label>Address *</label>
            <input type="text" required>
        </div>
        <div class="form-group">
            <label>Apartment (optional)</label>
            <input type="text">
        </div>
        <div class="input-row">
            <div class="form-group">
                <label>City *</label>
                <input type="text" pattern="[A-Za-z]+" required>
            </div>
            <div class="form-group">
                <label>State *</label>
                <input type="text" pattern="[A-Za-z]+" required>
            </div>
            <div class="form-group">
                <label>ZIP Code *</label>
                <input type="text" pattern="\d{5}(-\d{4})?" required>
            </div>
        </div>
        <div class="form-group">
            <label>Country</label>
            <select>
                <option>United States</option>
                <option>United Arab Emirates</option>
<option>United Kingdom</option>
                <option>Afghanistan</option>
<option>Albania</option>
<option>Algeria</option>
<option>Andorra</option>
<option>Angola</option>
<option>Antigua and Barbuda</option>
<option>Argentina</option>
<option>Armenia</option>
<option>Australia</option>
<option>Austria</option>
<option>Azerbaijan</option>
<option>Bahamas</option>
<option>Bahrain</option>
<option>Bangladesh</option>
<option>Barbados</option>
<option>Belarus</option>
<option>Belgium</option>
<option>Belize</option>
<option>Benin</option>
<option>Bhutan</option>
<option>Bolivia</option>
<option>Bosnia and Herzegovina</option>
<option>Botswana</option>
<option>Brazil</option>
<option>Brunei</option>
<option>Bulgaria</option>
<option>Burkina Faso</option>
<option>Burundi</option>
<option>Cabo Verde</option>
<option>Cambodia</option>
<option>Cameroon</option>
<option>Canada</option>
<option>Central African Republic</option>
<option>Chad</option>
<option>Chile</option>
<option>China</option>
<option>Colombia</option>
<option>Comoros</option>
<option>Congo (Congo-Brazzaville)</option>
<option>Costa Rica</option>
<option>Croatia</option>
<option>Cuba</option>
<option>Cyprus</option>
<option>Czechia</option>
<option>Democratic Republic of the Congo</option>
<option>Denmark</option>
<option>Djibouti</option>
<option>Dominica</option>
<option>Dominican Republic</option>
<option>Ecuador</option>
<option>Egypt</option>
<option>El Salvador</option>
<option>Equatorial Guinea</option>
<option>Eritrea</option>
<option>Estonia</option>
<option>Eswatini</option>
<option>Ethiopia</option>
<option>Fiji</option>
<option>Finland</option>
<option>France</option>
<option>Gabon</option>
<option>Gambia</option>
<option>Georgia</option>
<option>Germany</option>
<option>Ghana</option>
<option>Greece</option>
<option>Grenada</option>
<option>Guatemala</option>
<option>Guinea</option>
<option>Guinea-Bissau</option>
<option>Guyana</option>
<option>Haiti</option>
<option>Honduras</option>
<option>Hungary</option>
<option>Iceland</option>
<option>India</option>
<option>Indonesia</option>
<option>Iran</option>
<option>Iraq</option>
<option>Ireland</option>
<option>Israel</option>
<option>Italy</option>
<option>Jamaica</option>
<option>Japan</option>
<option>Jordan</option>
<option>Kazakhstan</option>
<option>Kenya</option>
<option>Kiribati</option>
<option>Kuwait</option>
<option>Kyrgyzstan</option>
<option>Laos</option>
<option>Latvia</option>
<option>Lebanon</option>
<option>Lesotho</option>
<option>Liberia</option>
<option>Libya</option>
<option>Liechtenstein</option>
<option>Lithuania</option>
<option>Luxembourg</option>
<option>Madagascar</option>
<option>Malawi</option>
<option>Malaysia</option>
<option>Maldives</option>
<option>Mali</option>
<option>Malta</option>
<option>Marshall Islands</option>
<option>Mauritania</option>
<option>Mauritius</option>
<option>Mexico</option>
<option>Micronesia</option>
<option>Moldova</option>
<option>Monaco</option>
<option>Mongolia</option>
<option>Montenegro</option>
<option>Morocco</option>
<option>Mozambique</option>
<option>Myanmar</option>
<option>Namibia</option>
<option>Nauru</option>
<option>Nepal</option>
<option>Netherlands</option>
<option>New Zealand</option>
<option>Nicaragua</option>
<option>Niger</option>
<option>Nigeria</option>
<option>North Korea</option>
<option>North Macedonia</option>
<option>Norway</option>
<option>Oman</option>
<option>Pakistan</option>
<option>Palau</option>
<option>Palestine</option>
<option>Panama</option>
<option>Papua New Guinea</option>
<option>Paraguay</option>
<option>Peru</option>
<option>Philippines</option>
<option>Poland</option>
<option>Portugal</option>
<option>Qatar</option>
<option>Romania</option>
<option>Russia</option>
<option>Rwanda</option>
<option>Saint Kitts and Nevis</option>
<option>Saint Lucia</option>
<option>Saint Vincent and the Grenadines</option>
<option>Samoa</option>
<option>San Marino</option>
<option>Sao Tome and Principe</option>
<option>Saudi Arabia</option>
<option>Senegal</option>
<option>Serbia</option>
<option>Seychelles</option>
<option>Sierra Leone</option>
<option>Singapore</option>
<option>Slovakia</option>
<option>Slovenia</option>
<option>Solomon Islands</option>
<option>Somalia</option>
<option>South Africa</option>
<option>South Korea</option>
<option>South Sudan</option>
<option>Spain</option>
<option>Sri Lanka</option>
<option>Sudan</option>
<option>Suriname</option>
<option>Sweden</option>
<option>Switzerland</option>
<option>Syria</option>
<option>Taiwan</option>
<option>Tajikistan</option>
<option>Tanzania</option>
<option>Thailand</option>
<option>Timor-Leste</option>
<option>Togo</option>
<option>Tonga</option>
<option>Trinidad and Tobago</option>
<option>Tunisia</option>
<option>Turkey</option>
<option>Turkmenistan</option>
<option>Tuvalu</option>
<option>Uganda</option>
<option>Ukraine</option>
<option>Uruguay</option>
<option>Uzbekistan</option>
<option>Vanuatu</option>
<option>Vatican City</option>
<option>Venezuela</option>
<option>Vietnam</option>
<option>Yemen</option>
<option>Zambia</option>
<option>Zimbabwe</option>
            </select>
        </div>

        <h2 class="section-title text-2xl font-bold mb-6" style="margin-top: 2rem;">Shipping Method</h2>
        <div class="shipping-method">
            <label>
                <input type="radio" name="shipping" checked>
                <span>
                    <strong>Standard Shipping</strong>
                    <div style="color: #666; font-size: 0.9rem;">3-5 business days</div>
                </span>
                <span style="margin-left: auto; color: #22c55e;">FREE</span>
            </label>
        </div>
        
        <div class="shipping-info-box">
            <p>• Enjoy free standard shipping on all orders over $50 within the U.S.</p>
            <p>• Standard shipping takes approximately 5-7 business days.</p>
            <p>• Receive tracking information via email once your order has been shipped.</p>

        </div>


        <h2 class="section-title text-2xl font-bold mb-6" style="margin-top: 2rem;">Payment</h2>
        <form id="payment-form" method="POST">
            <div class="form-group">
                <div id="card-element"></div>
            </div>
            <div id="card-errors" role="alert"></div>
            <input type="hidden" name="card_number" id="card_number">
            <input type="hidden" name="expiry_date" id="expiry_date">
            <input type="hidden" name="cvv" id="cvv">
            <button class="pay-button" id="submit">Pay $<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?></button>
        </form>
        <div style="text-align: center; margin-top: 1rem; font-size: 0.85rem; color: #666;">
            Your payment information is encrypted and secure. We never store your full card details.
        </div>
    </div>

    <div class="order-summary">
        <h2 class="section-title text-2xl font-bold mb-6">Order Summary</h2>
        <?php if (empty($cartProducts)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($cartProducts as $product): ?>
                <div class="product-card">
                    <img style="object-fit: contain;" src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" alt="Book" class="product-image">
                    <div class="product-details">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                        <div class="product-meta">Size: <?php echo htmlspecialchars($product['format'] ?? 'N/A'); ?></div>
                        <div class="product-meta">Qty: 1</div>
                    </div>
                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                </div>
            <?php endforeach; ?>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>$<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?></span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span style="color: #22c55e;">FREE</span>
            </div>
            <div class="total-row">
                <span>Total</span>
                <span>$<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?></span>
            </div>
        <?php endif; ?>

        <div class="shipping-info">
            ✓ Free US Shipping
            <div style="font-size: 0.85rem; margin-top: 0.25rem;">Delivery within 5-7 business days</div>
        </div>

        <div style="color: #666; font-size: 0.9rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Secure checkout
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Free returns within 30 days
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                 Enjoy free standard shipping on orders over $50 within the U.S
            </div>
        </div>
    </div>
</div>

<?php require_once 'parts/footer.php'; ?>
<script>
    lucide.createIcons();

    var stripe = Stripe('<?php echo $stripeConfig['publishable_key']; ?>');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                document.getElementById('card_number').value = result.token.card.last4;
                document.getElementById('expiry_date').value = result.token.card.exp_month + '/' + result.token.card.exp_year;
                document.getElementById('cvv').value = result.token.card.cvc_check;
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        form.submit();
    }
</script>
</body>
</html>