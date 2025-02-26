<?php
require_once '../models/ReservationModel.php';
require_once '../helpers/emailHelper.php';

class ReservationController {
    private $reservationModel;

    public function __construct($conn) {
        $this->reservationModel = new ReservationModel($conn);
    }

    public function createReservation($user_id, $table_id, $reservation_date, $guests, $customer_email) {
        if (!$this->reservationModel->isTableAvailable($table_id, $reservation_date)) {
            return[
                'status' => 'error',
                'message' => 'This table is already reserved for the selected date and time.'
            ];
        }

        $reservationId = $this->reservationModel->createReservation($user_id, $table_id, $reservation_date, $guests);
        if (!$reservationId) {
            return[
                'status' => 'error',
                'message' => 'Failed to create reservation.'
            ];
        }

        // Send confirmation email
        $email_sent = sendEmail(
            $customer_email,
            "Reservation Confirmation",
            "<h1>Your Reservation is Confirmed!</h1><p>Thank you for reserving a table!</p>" .
            "<p>Reservation ID: $reservationId</p>" .
            "<p>Table ID: $table_id</p>" .
            "<p>Date and Time: $reservation_date</p>" .
            "<p>Number of Guests: $guests</p>"
            
        );

        return [
            'status' => 'success',
            'message' => $email_sent 
                ? 'Reservation created and confirmation email sent.' 
                : 'Reservation created, but there was an error sending the confirmation email.'
        ];
    }

    public function getAllTables() {
        return $this->reservationModel->getAllTables();
    }
}
?>