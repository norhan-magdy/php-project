<?php
// helpers/emailHelper.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader if you're using Composer
require_once __DIR__ . '/../vendor/autoload.php';

function sendEmail($to, $subject, $body) {
    $config = require __DIR__ . '/../conf/email.php';

    $mail = new PHPMailer(true);

    try {
        // Enable SMTP debugging and show output on screen
        $mail->SMTPDebug = 2; // 2 for detailed debugging
        $mail->Debugoutput = function($str, $level) { 
            echo "SMTP Debug [$level]: $str<br>"; // Show errors on screen
            error_log("SMTP Debug [$level]: $str"); // Log errors to terminal
        };

        // Server settings
        $mail->isSMTP();
        $mail->Host       = $config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port       = $config['port'];

        // Recipients
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Debugging log
        error_log("Attempting to send email to: " . $to);
        echo "Attempting to send email to: " . $to . "<br>"; // Show on screen

        $mail->send();

        // Log success
        error_log("Email successfully sent to: " . $to);
        echo "Email successfully sent to: " . $to . "<br>"; // Show on screen

        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: " . $mail->ErrorInfo);
        echo "Email could not be sent. Mailer Error: " . $mail->ErrorInfo . "<br>"; // Show on screen
        return false;
    }
}

