<?php
// Load PHPMailer classes (adjust the paths as necessary)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                        // Use SMTP
    $mail->Host       = 'smtp.gmail.com';                   // Your SMTP server (change if not using Gmail)
    $mail->SMTPAuth   = true;                               // Enable SMTP authentication
    $mail->Username   = 'sditalia76@gmail.com';  // Your new restaurant email
    $mail->Password   = 'fivt bcmr yntb bjeu';              // Your app-specific password (after enabling 2FA)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption
    $mail->Port       = 587;                                // TCP port to connect to

    // Recipients
    $mail->setFrom('sditalia76@gmail.com', "Sapori D'Italia");
    $mail->addAddress('rdiamond482@gmail.com');              // Add a recipient for testing

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from Your Restaurant App';
    $mail->Body    = 'This is a <b>test email</b> sent from your restaurant application.';
    $mail->AltBody = 'This is a test email sent from your restaurant application.';

    $mail->send();
    echo 'Test email sent successfully!';
} catch (Exception $e) {
    echo "Test email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
