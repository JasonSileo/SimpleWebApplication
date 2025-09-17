<?php
// public/order_create.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/Cart.php';

use PhoenixPizza\DB\Database;
use PhoenixPizza\Cart;

header('Content-Type: application/json');

try {
    Cart::start();
    $items = Cart::items();
    if (empty($items)) {
        throw new RuntimeException('Cart is empty');
    }

    $pdo = Database::getConnection();
    $pdo->beginTransaction();

    $totals = Cart::totals();
    $insOrder = $pdo->prepare("INSERT INTO orders (user_id, status, subtotal, tax, total, payment_ref) VALUES (NULL, 'pending', ?, ?, ?, NULL)");
    $insOrder->execute([$totals['subtotal'], $totals['tax'], $totals['total']]);
    $orderId = (int)$pdo->lastInsertId();

    $insItem = $pdo->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, unit_price, selected_toppings)
         VALUES (?, ?, ?, ?, JSON_ARRAY())"
    );

    foreach ($items as $it) {
        $toppingsJson = json_encode($it['topping_ids'] ?? []);
        // We insert with empty JSON then update with the computed array to leverage prepared statements safely
        $insItem->execute([$orderId, (int)$it['product_id'], (int)$it['qty'], (float)$it['unit_price']]);
        $itemId = (int)$pdo->lastInsertId();
        $upd = $pdo->prepare("UPDATE order_items SET selected_toppings = CAST(? AS JSON) WHERE id = ?");
        $upd->execute([$toppingsJson, $itemId]);
    }

    $pdo->commit();

    // Generate an external order number (e.g., YYYYMMDD-<id>)
    $orderNumber = date('Ymd') . '-' . $orderId;

    // Clear cart after success
    Cart::clear();

    echo json_encode(['ok' => true, 'order_id' => $orderId, 'order_number' => $orderNumber, 'totals' => $totals]);
} catch (\Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
