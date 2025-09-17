<?php
// src/Cart.php
namespace PhoenixPizza;

class Cart {
    public static function start(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['cart'] ??= [];
    }

    public static function items(): array {
        self::start();
        return $_SESSION['cart'];
    }

    public static function add(array $item): void {
        self::start();
        // item keys: product_id, name, size, topping_ids[], qty, unit_price, line_total
        // dedupe by (product_id, size, toppings signature)
        $signature = self::signature($item['product_id'], $item['size'], $item['topping_ids'] ?? []);
        if (isset($_SESSION['cart'][$signature])) {
            $_SESSION['cart'][$signature]['qty'] += (int)$item['qty'];
            // recompute line_total
            $_SESSION['cart'][$signature]['line_total'] = round($_SESSION['cart'][$signature]['unit_price'] * $_SESSION['cart'][$signature]['qty'], 2);
        } else {
            $_SESSION['cart'][$signature] = $item + ['signature' => $signature];
        }
    }

    public static function updateQuantity(string $signature, int $qty): void {
        self::start();
        if (!isset($_SESSION['cart'][$signature])) return;
        if ($qty < 1) { unset($_SESSION['cart'][$signature]); return; }
        $_SESSION['cart'][$signature]['qty'] = $qty;
        $_SESSION['cart'][$signature]['line_total'] = round($_SESSION['cart'][$signature]['unit_price'] * $qty, 2);
    }

    public static function remove(string $signature): void {
        self::start();
        unset($_SESSION['cart'][$signature]);
    }

    public static function clear(): void {
        self::start();
        $_SESSION['cart'] = [];
    }

    public static function totals(): array {
        self::start();
        $subtotal = 0.0;
        foreach ($_SESSION['cart'] as $it) {
            $subtotal += (float)$it['line_total'];
        }
        $tax = round($subtotal * 0.08, 2); // 8% example; replace with your tax logic
        $total = round($subtotal + $tax, 2);
        return compact('subtotal','tax','total');
    }

    private static function signature(int $productId, string $size, array $toppings): string {
        sort($toppings);
        return hash('sha256', $productId . '|' . strtolower($size) . '|' . implode(',', $toppings));
    }
}
