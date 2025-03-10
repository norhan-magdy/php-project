<?php
// models/CartModel.php
require_once '../conf/conf.php';

class CartModel {
    // Add an item to the cart
    public static function addToCart($dish_id, $dish_name, $dish_price, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the dish is already in the cart
        $item_index = array_search($dish_id, array_column($_SESSION['cart'], 'dish_id'));

        if ($item_index !== false) {
            // Update quantity if the dish is already in the cart
            $_SESSION['cart'][$item_index]['quantity'] += $quantity;
        } else {
            // Add new item to the cart
            $_SESSION['cart'][] = [
                'dish_id' => $dish_id,
                'dish_name' => $dish_name,
                'dish_price' => $dish_price,
                'quantity' => $quantity
            ];
        }
    }

    // Get the cart items
    public static function getCart() {
        return $_SESSION['cart'] ?? [];
    }

    // Clear the cart
    public static function clearCart() {
        unset($_SESSION['cart']);
    }

    public static function updateCartItem($index, $quantity) {
        if (isset($_SESSION['cart'][$index])) {
            $_SESSION['cart'][$index]['quantity'] = $quantity;
        }
    }

    public static function removeCartItem($index) {
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
  

    public static function removeFromCart($dish_id)
    {
        // Check if the cart exists and is not empty
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            return;
        }

        // Find the index of the item with the given dish_id
        $item_index = array_search(
            $dish_id,
            array_column($_SESSION['cart'], 'dish_id')
        );

        // If the item exists in the cart
        if ($item_index !== false) {
            if ($_SESSION['cart'][$item_index]['quantity'] > 1) {
                // Decrease quantity by 1
                $_SESSION['cart'][$item_index]['quantity']--;
            } else {
                // Remove the item entirely
                unset($_SESSION['cart'][$item_index]);
                // Re-index the array to prevent gaps in keys
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
        }
    }
    
    
}

?>