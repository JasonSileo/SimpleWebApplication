<?php

namespace PhoenixPizza\controllers;

class CheckoutController
{
    public function index()
    {
        $this->render('checkout');
    }

    public function submit()
    {
        session_start();

        // Validate input
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (!$name || !$phone || !$address) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: ?route=checkout');
            exit;
        }

        // Save order (in session for now)
        $_SESSION['order'] = [
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'cart' => $_SESSION['cart'] ?? [],
            'time' => date('Y-m-d H:i:s')
        ];

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect to confirmation
        header('Location: ?route=order_confirm');
        exit;
    }

    public function confirm()
    {
        session_start();

        $order = $_SESSION['order'] ?? null;

        if (!$order) {
            header('Location: ?route=menu');
            exit;
        }

        $this->render('order_confirm', ['order' => $order]);
    }

    private function render($view, $vars = [])
    {
        extract($vars);
        include __DIR__ . '/../../views/' . $view . '.php';
    }
}
