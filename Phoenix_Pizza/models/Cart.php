<?php

class Cart {
    public static function add($productId, $size, $toppingIds, $qty) {
        $cart = $_SESSION['cart'] ?? [];

        $product = Product::find($productId);
        if (!$product) return false;

        $basePrice = $product['price'];
        $sizePrice = self::getSizePrice($size);
        $toppings = Topping::findMany($toppingIds);
        $toppingPrice = array_sum(array_column($toppings, 'extra_price'));

        $unitPrice = $basePrice + $sizePrice + $toppingPrice;
        $lineTotal = $unitPrice * $qty;

        $key = md5($productId . $size . implode('-', $toppingIds));

        $cart[$key] = [
            'product_id' => $productId,
            'name' => $product['name'],
            'size' => $size,
            'toppings' => $toppings,
            'qty' => $qty,
            'unit_price' => round($unitPrice, 2),
            'line_total' => round($lineTotal, 2)
        ];

        $_SESSION['cart'] = $cart;
        return true;
    }

    public static function remove($key) {
        if (isset($_SESSION['cart'][$key])) {
            unset($_SESSION['cart'][$key]);
        }
    }

    public static function clear() {
        unset($_SESSION['cart']);
    }

    public static function all() {
        return $_SESSION['cart'] ?? [];
    }

    public static function getSizePrice($size) {
        return match(strtolower($size)) {
            'small' => 0.00,
            'medium' => 2.00,
            'large' => 4.00,
            default => 0.00,
        };
    }
}
