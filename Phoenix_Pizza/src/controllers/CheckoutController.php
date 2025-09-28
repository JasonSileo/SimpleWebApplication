<?php

namespace PhoenixPizza\controllers;

class CheckoutController
{
    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];

        $totals = $this->calculateTotals($cart);

        $this->render('checkout', [
            'items' => $cart,
            'totals' => $totals,
            'pageTitle' => 'Checkout'
        ]);
    }

    private function calculateTotals($cart)
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += 14.99 * $item['qty']; // example static price
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
