<?php
// SpecialOfferModel.php
require_once '../conf/conf.php';

class SpecialOfferModel {
    public $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Fetch all active special offers from the database.
     *
     * @return array|null Array of special offers or null if no offers found.
     */
    public function getAllSpecialOffers() {
        $sql = "SELECT * FROM special_offers WHERE expiry_date >= CURDATE()"; // Fetch only active offers
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $offers = [];
            while ($row = $result->fetch_assoc()) {
                $offers[] = $row;
            }
            return $offers;
        } else {
            return null; // No offers found
        }
    }

    /**
     * Fetch a specific special offer by its ID.
     *
     * @param int $id The ID of the special offer.
     * @return array|null The special offer data or null if not found.
     */
    public function getSpecialOfferById($id) {
        $sql = "SELECT * FROM special_offers WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // Offer not found
        }
    }

    /**
     * Add a new special offer to the database.
     *
     * @param string $name The name of the offer.
     * @param string $description The description of the offer.
     * @param float $discount The discount percentage.
     * @param string $expiryDate The expiry date of the offer (YYYY-MM-DD).
     * @param string $applicableTo Whether the offer is applicable to 'all' or 'specific' items.
     * @return bool True if the offer was added successfully, false otherwise.
     */
    public function addSpecialOffer($name, $description, $discount, $expiryDate, $applicableTo) {
        $sql = "INSERT INTO special_offers (name, description, discount, expiry_date, applicable_to) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdss", $name, $description, $discount, $expiryDate, $applicableTo);

        return $stmt->execute();
    }

    /**
     * Update an existing special offer.
     *
     * @param int $id The ID of the offer to update.
     * @param string $name The updated name of the offer.
     * @param string $description The updated description of the offer.
     * @param float $discount The updated discount percentage.
     * @param string $expiryDate The updated expiry date of the offer (YYYY-MM-DD).
     * @param string $applicableTo Whether the offer is applicable to 'all' or 'specific' items.
     * @return bool True if the offer was updated successfully, false otherwise.
     */
    public function updateSpecialOffer($id, $name, $description, $discount, $expiryDate, $applicableTo) {
        $sql = "UPDATE special_offers 
                SET name = ?, description = ?, discount = ?, expiry_date = ?, applicable_to = ? 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdssi", $name, $description, $discount, $expiryDate, $applicableTo, $id);

        return $stmt->execute();
    }

    /**
     * Delete a special offer from the database.
     *
     * @param int $id The ID of the offer to delete.
     * @return bool True if the offer was deleted successfully, false otherwise.
     */
    public function deleteSpecialOffer($id) {
        $sql = "DELETE FROM special_offers WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
?>