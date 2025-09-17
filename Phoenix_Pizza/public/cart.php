<?php
// public/cart.php?action=add|update|remove|list
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/PriceCalculator.php';
require_once __DIR__ . '/../src/Cart.php';
require_once __DIR__ . '/../src/dao/ProductDAO.php';
require_once __DIR__ . '/../src/dao/ToppingDAO.php';

use PhoenixPizza\Cart;
use PhoenixPizza\PriceCalculator;
use PhoenixPizza\DAO\ProductDAO;

header('Content-Type: application/json');

$action = $_GET['action'] ?? 'list';

try {
    if ($action === 'add') {
        $productId  = (int)($_POST['product_id'] ?? 0);
        $size       = (string)($_POST['size'] ?? 'medium');
        $toppingIds = array_map('intval', $_POST['topping_ids'] ?? []);
        $qty        = (int)($_POST['qty'] ?? 1);

        // Ensure topping belongs to product (server-side guard)
        $dao = new ProductDAO();
        $allowed = array_column($dao->getAllowedToppings($productId), 'id');
        foreach ($toppingIds as $tid) {
            if (!in_array($tid, $allowed, true)) {
                throw new RuntimeException("Invalid topping for product");
            }
        }

        $calc = new PriceCalculator();
        $pricing = $calc->calcItemTotal($productId, $size, $toppingIds, $qty);

        // enrich name for display
        $products = $dao->listActive();
        $pmap = [];
        foreach ($products as $p) $pmap[(int)$p['id']] = $p['name'] . '';
        $name = $pmap[$productId] ?? ('#' . $productId);

        Cart::add([
            'product_id' => $productId,
            'name' => $name,
            'size' => $size,
            'topping_ids' => $toppingIds,
            'qty' => $qty,
            'unit_price' => $pricing['unit_price'],
            'line_total' => $pricing['line_total']
        ]);

        echo json_encode(['ok' => true, 'cart' => Cart::items(), 'totals' => Cart::totals()]);
        exit;
    } elseif ($action === 'update') {
        $signature = (string)($_POST['signature'] ?? '');
        $qty = (int)($_POST['qty'] ?? 1);
        Cart::updateQuantity($signature, $qty);
        echo json_encode(['ok' => true, 'cart' => Cart::items(), 'totals' => Cart::totals()]);
        exit;
    } elseif ($action === 'remove') {
        $signature = (string)($_POST['signature'] ?? '');
        Cart::remove($signature);
        echo json_encode(['ok' => true, 'cart' => Cart::items(), 'totals' => Cart::totals()]);
        exit;
    } else { // list
        echo json_encode(['ok' => true, 'cart' => Cart::items(), 'totals' => Cart::totals()]);
        exit;
    }
} catch (\Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}
