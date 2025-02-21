<?php
// SpecialOfferModel.php
require_once '../conf/conf.php'; // Include the database configuration

class SpecialOfferModel
{
    private $conn;
    private $tableSpecialOffers = 'special_offers';
    private $tableOfferMenuItems = 'offer_menu_items';
    private $tableMenuItems = 'menu_items';

    // Constructor to initialize the database connection
    public function __construct($conn)
    {
        $this->conn = $conn; // Use the connection passed from conf.php
    }

    /**
     * Fetch all active special offers from the database.
     *
     * @return array|null Array of special offers or null if no offers found.
     */
    public function getAllSpecialOffers(): ?array
    {
        $sql = "SELECT * FROM {$this->tableSpecialOffers} WHERE expiry_date >= CURDATE()";
        $result = $this->conn->query($sql);
        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return null;
        }
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
    public function getSpecialOfferById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->tableSpecialOffers} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return null;
        }
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
     * Fetch all eligible menu items for a specific special offer.
     *
     * @param int $offer_id The ID of the special offer.
     * @return array Array of eligible menu items or an empty array if none found.
     */
    public function getEligibleMenuItemsForOffer(int $offer_id): array
    {
        $sql = "
            SELECT mi.*
            FROM {$this->tableMenuItems} mi
            JOIN {$this->tableOfferMenuItems} omi ON mi.id = omi.menu_item_id
            WHERE omi.offer_id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("i", $offer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Filter eligible menu items to a specific subset.
     *
     * @param array $items Array of menu items.
     * @param array $subset_ids Array of IDs to filter by.
     * @return array Filtered array of menu items.
     */
    public function filterMenuItemsBySubset(array $items, array $subset_ids): array
    {
        return array_filter($items, function ($item) use ($subset_ids) {
            return in_array($item['id'], $subset_ids);
        });
    }

    /**
     * Check if a menu item is eligible for a specific offer.
     *
     * @param int $offer_id The ID of the special offer.
     * @param int $menu_item_id The ID of the menu item to check.
     * @return bool True if the menu item is eligible, false otherwise.
     */
    public function isMenuItemEligibleForOffer(int $offer_id, int $menu_item_id): bool
    {
        $sql = "
            SELECT COUNT(*) AS count
            FROM {$this->tableOfferMenuItems}
            WHERE offer_id = ? AND menu_item_id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ii", $offer_id, $menu_item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    /**
     * Fetch a single menu item by its ID.
     *
     * @param int $menu_item_id The ID of the menu item.
     * @return array|null The menu item data or null if not found.
     */
    public function getMenuItemById(int $menu_item_id): ?array
    {
        $sql = "SELECT * FROM {$this->tableMenuItems} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $menu_item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // Menu item not found
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
    public function addSpecialOffer(string $name, string $description, float $discount, string $expiryDate, string $applicableTo): bool
    {
        $sql = "INSERT INTO {$this->tableSpecialOffers} (name, description, discount, expiry_date, applicable_to) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
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
    public function updateSpecialOffer(int $id, string $name, string $description, float $discount, string $expiryDate, string $applicableTo): bool
    {
        $sql = "UPDATE {$this->tableSpecialOffers} 
                SET name = ?, description = ?, discount = ?, expiry_date = ?, applicable_to = ? 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssdssi", $name, $description, $discount, $expiryDate, $applicableTo, $id);
        return $stmt->execute();
    }

    /**
     * Delete a special offer from the database.
     *
     * @param int $id The ID of the offer to delete.
     * @return bool True if the offer was deleted successfully, false otherwise.
     */
    public function deleteSpecialOffer(int $id): bool
    {
        $sql = "DELETE FROM {$this->tableSpecialOffers} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
 * Fetch a specific special offer with its associated menu items.
 *
 * @param int $offer_id The ID of the special offer.
 * @return array|null The special offer data with associated items or null if not found.
 */
public function getSpecialOfferWithItems(int $offer_id): ?array
{
    $sql = "
        SELECT 
            so.*, 
            mi.id AS item_id, 
            mi.name AS item_name, 
            mi.description AS item_description, 
            mi.price AS item_price, 
            mi.image AS item_image 
        FROM {$this->tableSpecialOffers} so
        LEFT JOIN {$this->tableOfferMenuItems} omi ON so.id = omi.offer_id
        LEFT JOIN {$this->tableMenuItems} mi ON omi.menu_item_id = mi.id
        WHERE so.id = ? AND so.expiry_date >= CURDATE()
    ";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $this->conn->error);
        return null;
    }
    $stmt->bind_param("i", $offer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $offer = null;
        while ($row = $result->fetch_assoc()) {
            if (!$offer) {
                // Initialize the offer data
                $offer = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'discount' => $row['discount'],
                    'expiry_date' => $row['expiry_date'],
                    'applicable_to' => $row['applicable_to'],
                    'created_at' => $row['created_at'],
                    'items' => []
                ];
            }
            if ($row['item_id']) {
                // Add associated menu items
                $offer['items'][] = [
                    'id' => $row['item_id'],
                    'name' => $row['item_name'],
                    'description' => $row['item_description'],
                    'price' => $row['item_price'],
                    'image' => $row['item_image']
                ];
            }
        }
        return $offer;
    } else {
        return null; // Offer not found
    }
}

/**
 * Fetch the price of a menu item by its ID.
 *
 * @param int $menu_item_id The ID of the menu item.
 * @return float|null The price of the menu item or null if not found.
 */
public function getMenuItemPrice(int $menu_item_id): ?float
{
    $sql = "SELECT price FROM {$this->tableMenuItems} WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $this->conn->error);
        return null;
    }
    $stmt->bind_param("i", $menu_item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return (float)$row['price'];
    } else {
        return null; // Menu item not found
    }
}

/**
 * Fetch the name of a menu item by its ID.
 *
 * @param int $menu_item_id The ID of the menu item.
 * @return string|null The name of the menu item or null if not found.
 */
public function getMenuItemName(int $menu_item_id): ?string
{
    $sql = "SELECT name FROM {$this->tableMenuItems} WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $this->conn->error);
        return null;
    }
    $stmt->bind_param("i", $menu_item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return htmlspecialchars($row['name']);
    } else {
        return null; // Menu item not found
    }
}
}