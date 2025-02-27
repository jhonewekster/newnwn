<?php
require_once __DIR__ . '/../config/connexion.php';
require 'phpmailer/vendor/autoload.php'; // Assuming you're using Composer for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Contact {
    private $conn;
    private $table_name = "contact_messages";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function sendMail($name, $email, $subject, $message) {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'diixonduncan@gmail.com'; // Your Gmail address
            $mail->Password = 'wvtrwhfbxlvpznvz';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('diixonduncan@gmail.com', 'Dixon');
            $mail->addAddress('diixonduncan@gmail.com'); // Your Gmail address to receive the messages

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = "<p>Name: $name</p><p>Email: $email</p><p>Message: $message</p>";

            $mail->send();
            return ["status" => true, "message" => "Message has been sent"];
        } catch (Exception $e) {
            return ["status" => false, "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
        }
    }
}