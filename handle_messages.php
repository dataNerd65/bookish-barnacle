<?php
    //Enable error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //Start a session
    session_start();

    //Generate a csrf token if one doesnt exist
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));
    }

    //Import PHPMailer classes into the global namespace
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // validate message
        if (empty($_POST["message"])) {
            die("Message is required");
        } else {
            $message = sanitize_input($_POST["message"]);
        }

        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('GMAIL_USERNAME');
        $mail->Password = getenv('GMAIL_PASSWORD');
        //Recipients
        $mail->setFrom('from@example.com', 'First Last');
        $mail->addAddress('whoto@example.com', 'John Doe');

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'PHPMailer GMail SMTP test';
        $mail->Body = $message;

        //Send the message
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }
    }

    //Function to sanitize user input
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>
