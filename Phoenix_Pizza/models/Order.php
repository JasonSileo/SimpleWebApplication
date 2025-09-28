<?php
class Order {
    public static function create($userData, $cart) {
        $pdo = DB::conn();
        $pdo->beginTransaction();

        try {
            // 1. Insert order
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, status, subtotal, tax, total, payment_ref)
                VALUES (?, 'pending', ?, ?, ?, ?)
            ");
            $userId = null; // assuming guest checkout
            $subtotal = $userData['subtotal'];
            $tax = $userData['tax'];
            $total = $userData['total'];
            $paymentRef = 'PHX' . time(); // or real gateway later

            $stmt->execute([$userId, $subtotal, $tax, $total, $paymentRef]);
            $orderId = $pdo->lastInsertId();

            // 2. Insert items
            $itemStmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, unit_price, selected_toppings)
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($cart as $item) {
                $productId = $item['product_id'];
                $qty = $item['qty'];
                $unitPrice = $item['unit_price'];
                $toppingIds = array_column($item['toppings'], 'id');
                $toppingsJson = json_encode($toppingIds);

                $itemStmt->execute([$orderId, $productId, $qty, $unitPrice, $toppingsJson]);
            }

            $pdo->commit();

            return $orderId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
