<?php

namespace PhoenixPizza\controllers;

class CartController
{
    private $toppingList = [
        1 => 'Chicken',
        2 => 'Extra Cheese',
        3 => 'Jalapeños',
        4 => 'Mushrooms',
        5 => 'Onions',
        6 => 'Olives',
        7 => 'Bacon',
        8 => 'Pickles',
        9 => 'Ranch Drizzle',
        10 => 'Ghost Peppers',
        11 => 'Basil',
        12 => 'Pepperoni'
    ];

    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        $totals = $this->calculateTotals($cart);

        $this->render('cart', [
            'pageTitle' => 'Your Cart',
            'items' => $cart,
            'totals' => $totals
        ]);
    }

    public function add()
    {
        session_start();

        $id = $_POST['pizza_id'] ?? null;
        $name = $_POST['pizza_name'] ?? 'Unknown Pizza';
        $size = $_POST['size'] ?? 'medium';
        $toppingIDs = $_POST['toppings'] ?? [];
        $qty = (int)($_POST['qty'] ?? 1);
        $basePrice = (float)($_POST['base_price'] ?? 0.00);

        $toppingPrices = [
            1 => 2.25, 2 => 1.50, 3 => 1.10, 4 => 1.25, 5 => 0.95,
            6 => 1.10, 7 => 0.75, 8 => 0.95, 9 => 0.75, 10 => 1.25, 11 => 0.75, 12 => 1.75
        ];

        $sizePrice = 0;
        if ($size === 'medium') $sizePrice = 2.00;
        if ($size === 'large') $sizePrice = 4.00;

        $toppingTotal = 0;
        $toppingNames = [];

        foreach ($toppingIDs as $tid) {
            $tid = (int)$tid;
            $toppingTotal += $toppingPrices[$tid] ?? 0;
            $toppingNames[] = $this->toppingList[$tid] ?? "Unknown topping #$tid";
        }

        $unitPrice = round($basePrice + $sizePrice + $toppingTotal, 2);
        $key = $id . '-' . $size . '-' . implode(',', $toppingIDs);

        $_SESSION['cart'][$key] = [
            'product_id' => $id,
            'name' => $name,
            'size' => $size,
            // store names instead of IDs:
            'toppings' => $toppingNames,
            'qty' => $qty,
            'unit_price' => $unitPrice
        ];

        header('Location: ?route=cart');
        exit;
    }

    public function remove()
    {
        session_start();
        $key = $_GET['key'] ?? null;

        if ($key && isset($_SESSION['cart'][$key])) {
            unset($_SESSION['cart'][$key]);
        }

        header('Location: ?route=cart');
        exit;
    }

    public function update()
    {
        session_start();
        $key = $_POST['key'] ?? null;
        $qty = (int)($_POST['qty'] ?? 1);

        if ($key && isset($_SESSION['cart'][$key]) && $qty > 0) {
            $_SESSION['cart'][$key]['qty'] = $qty;
        }

        header('Location: ?route=cart');
        exit;
    }

    private function calculateTotals($cart)
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['unit_price'] * $item['qty'];
        }

        $tax = round($subtotal * 0.08, 2);
        $total = $subtotal + $tax;

        return [
            'subtotal' => number_format($subtotal, 2),
            'tax' => number_format($tax, 2),
            'total' => number_format($total, 2)
        ];
    }

    private function render($view, $vars = [])
    {
        extract($vars);
        include __DIR__ . '/../../views/' . $view . '.php';
    }
}
