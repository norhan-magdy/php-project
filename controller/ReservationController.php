<?php
// ReservationController.php

require_once '../conf/conf.php';
require_once '../models/ReservationModel.php';
require_once '../helpers/emailHelper.php';

class ReservationController {
    private $reservationModel;

    public function __construct() {
        $this->reservationModel = new ReservationModel();
    }

    public function createReservation() {
        // Retrieve form data
        $user_id          = $_POST['user_id']; 
        $table_id         = $_POST['table_id'];
        $reservation_date = $_POST['reservation_date'];
        $guests           = $_POST['guests'];
        $customer_email   = $_POST['email'];
    
        // Debug: Log reservation attempt
        error_log("Received reservation request: user_id=$user_id, table_id=$table_id, date=$reservation_date, guests=$guests, email=$customer_email");
    
        // Create the reservation in the database
        $reservationId = $this->reservationModel->createReservation($user_id, $table_id, $reservation_date, $guests);
    
        // Debug: Confirm reservation creation
        if (!$reservationId) {
            error_log("Failed to create reservation!");
            echo "Reservation creation failed.";
            return;
        }
        error_log("Reservation created successfully! ID: $reservationId");
    
        // Debug: Log before sending email
        error_log("Attempting to send reservation confirmation email to: " . $customer_email);
        echo "Attempting to send reservation confirmation email to: " . $customer_email . "<br>";
    
        // Send the confirmation email
        try {
            if (sendEmail($customer_email, "Reservation Confirmation", "<h1>Your Reservation is Confirmed!</h1><p>Thank you for reserving a table!</p>")) {
                error_log("Reservation email sent successfully to: " . $customer_email);
                echo "Reservation created and confirmation email sent.";
            } else {
                error_log("Error sending confirmation email to: " . $customer_email);
                echo "Reservation created, but there was an error sending the confirmation email.";
            }
        } catch (Exception $ex) {
            error_log("Exception while sending email: " . $ex->getMessage());
            echo "Reservation created, but there was an error sending the confirmation email.";
        }
    }
    
}

// If this file is accessed directly via a POST request, execute the creation method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ReservationController();
    $controller->createReservation();
}
