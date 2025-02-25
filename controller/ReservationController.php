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
        // Retrieve form data (ensure you validate and sanitize these in real usage)
        $user_id          = $_POST['user_id'];          // Usually from session data
        $table_id         = $_POST['table_id'];
        $reservation_date = $_POST['reservation_date'];
        $guests           = $_POST['guests'];
        $customer_email   = $_POST['email'];

        // Create the reservation in the database
        $reservationId = $this->reservationModel->createReservation($user_id, $table_id, $reservation_date, $guests);

        // Prepare the email content
        $subject = "Reservation Confirmation";
        $body = "
            <h1>Your Reservation is Confirmed!</h1>
            <p>Dear Customer,</p>
            <p>Thank you for reserving a table at our restaurant.</p>
            <p>Your reservation for {$guests} guest(s) on {$reservation_date} has been successfully created.</p>
            <p>We look forward to serving you!</p>
            <p>Best regards,<br>Your Restaurant Team</p>
        ";

        // Send the confirmation email
        if (sendEmail($customer_email, $subject, $body)) {
            echo "Reservation created and confirmation email sent.";
        } else {
            echo "Reservation created, but there was an error sending the confirmation email.";
        }
    }
}

// If this file is accessed directly via a POST request, execute the creation method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ReservationController();
    $controller->createReservation();
}
