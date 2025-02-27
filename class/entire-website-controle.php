<?php
require_once __DIR__ . '/../config/connexion.php';

class Entire_Website_Controle {

    private $pdo;

    // Constructor to initialize the database connection
    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();

        if ($this->pdo === null) {
            die("Connection failed: Unable to establish a database connection.");
        }
    }

    // Fetch website settings
    public function fetchWebsiteSettings() {
        $sql = "SELECT * FROM websitesetting WHERE id = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Output data of each row
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "0 results";
        }
    }

    // Fetch website settings
    public function Affiche_logo() {
        $sql = "SELECT `store_name` FROM `websitesetting` WHERE 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Output data of each row
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "0 results";
        }
    }



        // Fetch website privacy
        public function Affcihe_Privacy() {
            $sql = "SELECT `privacy_policy` FROM `websitesetting` WHERE 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                // Output data of each row
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "0 results";
            }
        }

        public function Affcihe_cookie() {
            $sql = "SELECT `cookie_policy` FROM `websitesetting` WHERE 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                // Output data of each row
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "0 results";
            }
        }

        public function Refund_and_Returns_Policy() {
            $sql = "SELECT `refund_returns_policy` FROM `websitesetting` WHERE 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                // Output data of each row
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "0 results";
            }
        }


        public function Terms_of_Service() {
            $sql = "SELECT `terms_of_service` FROM `websitesetting` WHERE 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                // Output data of each row
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "0 results";
            }
        }

        public function DMCA_Policy() {
            $sql = "SELECT `dmca_policy` FROM `websitesetting` WHERE 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                // Output data of each row
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "0 results";
            }
        }


        public function payment_policy() {
            $sql = "SELECT `payment_policy` FROM `websitesetting` WHERE 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                // Output data of each row
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "0 results";
            }
        }


        public function shipping_policy() {
            $sql = "SELECT `shipping_policy` FROM `websitesetting` WHERE 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                // Output data of each row
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "0 results";
            }
        }


        public function footer_propty() {
            $sql = "SELECT `id`, `store_name`, `address_store`, `mail_business`, `phone` FROM `websitesetting` WHERE 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                // Output data of each row
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "0 results";
            }
        }
}


?>