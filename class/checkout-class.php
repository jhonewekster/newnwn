<?php
require_once __DIR__ . '/../config/connexion.php';

class Checkout {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getProductById($productId) {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }

    public function getCartProducts($productIds) {
        if (empty($productIds)) {
            return [];
        }

        $in  = str_repeat('?,', count($productIds) - 1) . '?';
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
        $stmt->execute($productIds);
        return $stmt->fetchAll();
    }

    public function createUserAccount($email, $firstName, $lastName, $phone, $address) {
        try {
            // Check if user already exists
            $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $existingUser = $stmt->fetch();
            
            if ($existingUser) {
                // Update existing user's information
                $updateStmt = $this->pdo->prepare('
                    UPDATE users 
                    SET first_name = ?, last_name = ?, phone = ?, address = ?
                    WHERE id = ?
                ');
                $updateStmt->execute([$firstName, $lastName, $phone, $address, $existingUser['id']]);
                return $existingUser['id'];
            }

            // Create new user
            $tempPassword = bin2hex(random_bytes(8)); // Generate random password
            $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare('
                INSERT INTO users (email, password, first_name, last_name, phone, address) 
                VALUES (?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute([
                $email,
                $hashedPassword,
                $firstName,
                $lastName,
                $phone,
                $address
            ]);

            $userId = $this->pdo->lastInsertId();

            // Log the temporary password (in production, send via email)
            error_log("New user created - Email: $email, Temporary password: $tempPassword");

            return $userId;

        } catch (PDOException $e) {
            error_log("Error creating user account: " . $e->getMessage());
            return false;
        }
    }

    public function saveTransaction($userId, $stripeTransactionId, $amount, $products) {
        try {
            $this->pdo->beginTransaction();

            // Insert main transaction
            $stmt = $this->pdo->prepare("
                INSERT INTO transactions (user_id, stripe_transaction_id, amount)
                VALUES (?, ?, ?)
            ");
            
            $stmt->execute([$userId, $stripeTransactionId, $amount]);
            $transactionId = $this->pdo->lastInsertId();

            // Insert transaction items
            $itemStmt = $this->pdo->prepare("
                INSERT INTO transaction_items (transaction_id, product_id, price)
                VALUES (?, ?, ?)
            ");

            foreach ($products as $product) {
                $itemStmt->execute([
                    $transactionId,
                    $product['id'],
                    $product['price']
                ]);
            }

            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Error saving transaction: " . $e->getMessage());
            return false;
        }
    }

    public function saveCardInfo($userId, $cardNumber, $expiryDate, $cvv) {
        try {
            // Begin transaction
            $this->pdo->beginTransaction();

            // First, check if we need to create a user
            if (!$this->getUserById($userId)) {
                throw new Exception("User not found");
            }

            // Insert into encrypted_cards
            $stmt = $this->pdo->prepare('
                INSERT INTO encrypted_cards 
                (user_id, card_number, expiry_date, cvv) 
                VALUES (?, ?, ?, ?)
            ');

            $success = $stmt->execute([
                $userId,
                $cardNumber,
                $expiryDate,
                $cvv
            ]);

            if (!$success) {
                throw new Exception("Failed to save card information");
            }

            // Commit transaction
            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            // Rollback transaction on error
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Error saving card info: " . $e->getMessage());
            return false;
        }
    }

    private function getUserById($userId) {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}
?>