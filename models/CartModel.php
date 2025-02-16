<?php
// models/CartModel.php

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
}
?>